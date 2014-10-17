<?php

/*
 * Copyright (C) 2014 Flightgear Team
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 */

require_once 'IRequestDAO.php';
require_once 'Request.php';
require_once 'RequestMassiveObjectsAdd.php';
require_once 'RequestModelAdd.php';
require_once 'RequestModelUpdate.php';
require_once 'RequestObjectAdd.php';
require_once 'RequestObjectDelete.php';
require_once 'RequestObjectUpdate.php';
require_once 'ObjectDAO.php';
require_once 'ObjectFactory.php';
require_once 'ModelFactory.php';
require_once 'ModelFilesTar.php';

require_once 'RequestNotFoundException.php';

/**
 * Request DAO
 *
 * @author Julien Nguyen <julien.nguyen3@gmail.com>
 */
class RequestDAO extends PgSqlDAO implements IRequestDAO {
    private $objectDao;
    private $modelDao;
    private $authorDao;
    
    public function __construct(PGDatabase $database, ObjectDAO $objectDao,
            ModelDAO $modelDAO, AuthorDAO $authorDAO) {
        parent::__construct($database);
        $this->objectDao = $objectDao;
        $this->modelDao = $modelDAO;
        $this->authorDao = $authorDAO;
    }
    
    public function getRequest($sig) {
        $result = $this->database->query("SELECT spr_id, spr_hash, spr_base64_sqlz ".
                                         "FROM fgs_position_requests ".
                                         "WHERE spr_hash = '". $sig ."';");
        
        $row = pg_fetch_assoc($result);
        
        if (!$row) {
            throw new RequestNotFoundException('No request with sig '. $sig. ' was found!');
        }
        
        return $this->getRequestFromRow($row);
    }
    
    public function saveRequest($request) {
        $reqStr = $this->serializeRequest($request);
        
        $zippedQuery = gzcompress($reqStr,8);
        $encodedReqStr = base64_encode($zippedQuery);
        
        $shaToCompute = "<".microtime()."><".$_SERVER["REMOTE_ADDR"]."><".$encodedReqStr.">";
        $sig = hash('sha256', $shaToCompute);
        
        $query = "INSERT INTO fgs_position_requests (spr_id, spr_hash, spr_base64_sqlz) VALUES (DEFAULT, '".$sig."', '".$encodedReqStr."') RETURNING spr_id;";
        
        $result = $this->database->query($query);
        
        if (!$result) {
            throw new Exception("Adding object failed!");
        }
        
        $returnRow = pg_fetch_row($result);
        $request->setId($returnRow[0]);
        $request->setSig($sig);
        return $request;
    }
    
    private function serializeRequest($request) {
        switch (get_class($request)) {
        case "RequestObjectAdd":
            $reqStr = $this->serializeRequestObjectAdd($request);
            break;
        
        case "RequestObjectUpdate":
            $reqStr = $this->serializeRequestObjectUpdate($request);
            break;
        
        case "RequestObjectDelete":
            $reqStr = $this->serializeRequestObjectDelete($request);
            break;
        
        case "RequestMassiveObjectsAdd":
            $reqStr = $this->serializeRequestMassiveObjectsAdd($request);
            break;
        
        case "RequestModelAdd":
            $reqStr = $this->serializeRequestModelAdd($request);
            break;
        
        case "RequestModelUpdate":
            $reqStr = $this->serializeRequestModelUpdate($request);
            break;
        
        default:
            throw new Exception("Not a request!");
        }
        
        return $reqStr;
    }
    
    private function serializeRequestObjectAdd($request) {
        $newObj = $request->getNewObject();
        $offset = $newObj->getElevationOffset();
        
        $reqStr = "INSERT INTO fgs_objects (ob_text, wkb_geometry, ob_gndelev, ob_elevoffset, ob_heading, ob_country, ob_model, ob_group) ".
                  "VALUES ('".pg_escape_string($newObj->getDescription())."', ST_PointFromText('POINT(".$newObj->getLongitude()." ".$newObj->getLatitude().")', 4326), -9999, ".
                  (empty($offset)?"NULL":$offset) .", ".
                  $newObj->getOrientation().", '".$newObj->getCountry()->getCode()."', ".$newObj->getModelId().", 1);";

        return $reqStr;
    }
    
    private function serializeRequestObjectUpdate($request) {
        $newObj = $request->getNewObject();
        
        $reqStr = "UPDATE fgs_objects ".
                  "SET ob_text=$$".$newObj->getDescription()."$$, wkb_geometry=ST_PointFromText('POINT(".$newObj->getLongitude()." ".$newObj->getLatitude().")', 4326), ob_gndelev=-9999, ob_elevoffset=".$newObj->getElevationOffset().", ob_heading=".$newObj->getOrientation().", ob_model=".$newObj->getModelId().", ob_group=1 ".
                  "WHERE ob_id=".$newObj->getId().";";
                
        return $reqStr;
    }
    
    private function serializeRequestObjectDelete($request) {
        $objToDel = $request->getObjectToDelete();
        
        $reqStr = "DELETE FROM fgs_objects WHERE ob_id=".$objToDel->getId().";";
                
        return $reqStr;
    }
    
    private function serializeRequestMassiveObjectsAdd($request) {
        $newObjects = $request->getNewObjects();
        
        // Proceed on with the request generation
        $reqStr = "INSERT INTO fgs_objects (ob_text, wkb_geometry, ob_gndelev, ob_elevoffset, ob_heading, ob_model, ob_country, ob_group) VALUES ";

        $comma = "";
        // For each line, add the data content to the request
        foreach ($newObjects as $newObj) {
            $reqStr .= $comma."('".pg_escape_string($newObj->getDescription()).
                    "', ST_PointFromText('POINT(".$newObj->getLongitude()." ".$newObj->getLatitude().")', 4326), -9999, ".
                    $newObj->getElevationOffset().", ".$newObj->getOrientation().", ".$newObj->getModelId().", '".$newObj->getCountry()->getCode()."', 1)";

            $comma = ", ";
        }
                
        return $reqStr.";";
    }
    
    private function serializeRequestModelAdd($request) {
        $newModel = $request->getNewModel();
        $newModelMD = $newModel->getMetadata();
        $newObject = $request->getNewObject();
        
        $moQuery  = "MODEL_ADD||";
        $moQuery .= $newModelMD->getFilename()."||";        // mo_path
        $moQuery .= $newModelMD->getAuthor()->getId()."||"; // mo_author
        $moQuery .= $newModelMD->getName()."||";            // mo_name
        $moQuery .= $newModelMD->getDescription()."||";     // mo_notes
        $moQuery .= $newModel->getThumbnail()."||";         // mo_thumbfile
        $moQuery .= $newModel->getModelFiles()."||";        // mo_modelfile
        $moQuery .= $newModelMD->getModelsGroup()->getId(); // mo_shared

        $obQuery  = "INSERT INTO fgs_objects ";
        $obQuery .= "(wkb_geometry, ob_gndelev, ob_elevoffset, ob_heading, ob_country, ob_model, ob_text, ob_group) ";
        $obQuery .= "VALUES (";
        $obQuery .= "ST_PointFromText('POINT(".$newObject->getLongitude()." ".$newObject->getLatitude().")', 4326), ";        // wkb_geometry
        $obQuery .= "-9999, ";                                                                // ob_gndelev
        $obQuery .= $newObject->getElevationOffset().", ";                                                             // ob_elevoffset
        $obQuery .= $newObject->getOrientation().", ";                                       // ob_heading
        $obQuery .= "'".$newObject->getCountry()->getCode()."', ";                                                       // ob_country
        $obQuery .= "Thisisthevalueformo_id, ";                                                           // ob_model
        $obQuery .= "'".$newObject->getDescription()."', ";                                                          // ob_text
        $obQuery .= "1";                                                                      // ob_group
        $obQuery .= ")";

        $reqStr = $moQuery.";".$obQuery;
                
        return $reqStr;
    }
    
    private function serializeRequestModelUpdate($request) {
        $newModel = $request->getNewModel();
        $newModelMD = $newModel->getMetadata();
        
        $reqStr  = "MODEL_UPDATE||";
        $reqStr .= $newModelMD->getFilename()."||";             // mo_path
        $reqStr .= $newModelMD->getAuthor()->getId()."||";      // mo_author
        $reqStr .= $newModelMD->getName()."||";                 // mo_name
        $reqStr .= $newModelMD->getDescription()."||";          // mo_notes
        $reqStr .= $newModel->getThumbnail()."||";              // mo_thumbfile
        $reqStr .= $newModel->getModelFiles()."||";             // mo_modelfile
        $reqStr .= $newModelMD->getModelsGroup()->getId()."||"; // mo_shared
        $reqStr .= $newModelMD->getId();                        // mo_id
                
        return $reqStr;
    }
    
    public function deleteRequest($sig) {
        // Checking the presence of sig into the database
        $result = $this->database->query("SELECT 1 FROM fgs_position_requests WHERE spr_hash = '". $sig ."';");
        $row = pg_fetch_assoc($result);
        // If not ok...
        if (!$row) {
            throw new RequestNotFoundException('No request with sig '. $sig. ' was found!');
        }
        
        // Delete the entry from the pending query table.
        $resultdel = $this->database->query("DELETE FROM fgs_position_requests WHERE spr_hash = '". $sig ."';");

        return $resultdel != FALSE;
    }
    
    public function getPendingRequests() {
        $result = $this->database->query("SELECT spr_id, spr_hash, spr_base64_sqlz ".
                                         "FROM fgs_position_requests ".
                                         "ORDER BY spr_id ASC;");
        $resultArray = array();
                           
        while ($row = pg_fetch_assoc($result)) {
            try {
                $resultArray[] = $this->getRequestFromRow($row);
            } catch (Exception $ex) {
                error_log("Error with request ".$row['spr_id'].": ". $ex->getMessage());
            }
        }
        
        return $resultArray;
    }
    
    private function getRequestFromRow($requestRow) {
        // Decoding in Base64. Dezipping the Base64'd request.
        $requestQuery = gzuncompress(base64_decode($requestRow["spr_base64_sqlz"]));
        
        // Delete object request
        if (substr_count($requestQuery,"DELETE FROM fgs_objects") == 1) {
            $request = $this->getRequestObjectDeleteFromRow($requestQuery);
        }
        
        // Update object request
        if (substr_count($requestQuery,"UPDATE fgs_objects") == 1) {
            $request = $this->getRequestObjectUpdateFromRow($requestQuery);
        }
        
        // Add object(s) request
        if (substr_count($requestQuery,"INSERT INTO fgs_objects") == 1 && substr_count($requestQuery,"Thisisthevalueformo_id") == 0) {
            if (substr_count($requestQuery,"INSERT INTO fgs_objects (ob_text, wkb_geometry, ob_gndelev, ob_elevoffset, ob_heading, ob_country, ob_model, ob_group)") == 1) {
                $request = $this->getRequestObjectAddFromRow($requestQuery);
            }
            // Else, it is a mass insertion
            else {
                $request = $this->getRequestMassiveObjectsAddFromRow($requestQuery);
            }
        }
        
        // Add model request
        if (substr_count($requestQuery,"MODEL_ADD") == 1) {
            $request = $this->getRequestModelAddFromRow($requestQuery);
        }
        
        // Update model request
        if (substr_count($requestQuery,"MODEL_UPDATE") == 1) {
            $request = $this->getRequestModelUpdateFromRow($requestQuery);
        }
        
        $request->setId($requestRow["spr_id"]);
        $request->setSig($requestRow["spr_hash"]);
        
        return $request;
    }
    
    private function getRequestModelAddFromRow($requestQuery) {
        $queryModel = substr($requestQuery, 0, strpos($requestQuery, ";INSERT INTO fgs_objects"));
        $queryObj = strstr($requestQuery, "INSERT INTO fgs_objects");

        // Retrieve MODEL data from query
        // MODEL_ADD||mo_path||mo_author||mo_name||mo_notes||mo_thumbfile||mo_modelfile||mo_shared
        $modelArr = explode("||", $queryModel);
        $modelFactory = new ModelFactory($this->modelDao, $this->authorDao);
        $modelMD = $modelFactory->createModelMetadata(-1, $modelArr[2], $modelArr[1], $modelArr[3], $modelArr[4], $modelArr[7]);
        $newModel = new Model();
        $newModel->setMetadata($modelMD);
        $newModel->setModelFiles(new ModelFilesTar(base64_decode($modelArr[6])));
        $newModel->setThumbnail(base64_decode($modelArr[5]));

        // Retrieve OBJECT data from query
        $search = 'ob_elevoffset'; // We're searching for ob_elevoffset presence in the request to correctly preg it.
        $pos = strpos($queryObj, $search);

        $pattern  = "/INSERT INTO fgs_objects \(wkb_geometry, ob_gndelev, ob_elevoffset, ob_heading, ob_country, ob_model, ob_text, ob_group\) VALUES \(ST_PointFromText\('POINT\((?P<long>[0-9.-]+) (?P<lat>[0-9.-]+)\)', 4326\), (?P<gndelev>[0-9.-]+), (?P<elevoffset>[NULL0-9.-]+), (?P<orientation>[0-9.-]+), '(?P<country>[a-z-A-Z-]+)', (?P<model>[a-z-A-Z_0-9-]+), '(?P<notes>[^$]*)', 1\)/";
        preg_match($pattern, $queryObj, $matches);
        
        $objectFactory = new ObjectFactory($this->objectDao);
        $newObject = $objectFactory->createObject(-1, -1,
                $matches['long'], $matches['lat'], $matches['country'], 
                $matches['elevoffset'], $matches['orientation'], 1, $matches['notes']);
        
        $requestModelAdd = new RequestModelAdd();
        $requestModelAdd->setNewModel($newModel);
        $requestModelAdd->setNewObject($newObject);
        
        return $requestModelAdd;
    }
    
    private function getRequestModelUpdateFromRow($requestQuery) {
        // Retrieve data from query
        // MODEL_UPDATE||path||author||name||notes||thumbfile||modelfile||shared||modelid
        $modelArr = explode("||", $requestQuery);

        $modelFactory = new ModelFactory($this->modelDao, $this->authorDao);
        $modelMD = $modelFactory->createModelMetadata($modelArr[8],
                $modelArr[2], $modelArr[1], $modelArr[3],
                $modelArr[4], $modelArr[7]);
        
        $newModel = new Model();
        $newModel->setMetadata($modelMD);
        $newModel->setModelFiles(new ModelFilesTar(base64_decode($modelArr[6])));
        $newModel->setThumbnail(base64_decode($modelArr[5]));

        // Retrieve old model
        $oldModel = $this->modelDao->getModel($modelMD->getId());
        
        $requestModelUpd = new RequestModelUpdate();
        $requestModelUpd->setNewModel($newModel);
        $requestModelUpd->setOldModel($oldModel);
        
        return $requestModelUpd;
    }
    
    private function getRequestObjectAddFromRow($requestQuery) {
        // Removing the start of the query from the data
        $triggedQuery = strstr($requestQuery, 'VALUES');
        $pattern = "/VALUES \('(?P<desc>[0-9a-zA-Z_\-., \[\]()]+)', ST_PointFromText\('POINT\((?P<long>[0-9.-]+) (?P<lat>[0-9.-]+)\)', 4326\), (?P<elev>[0-9.-]+), (?P<elevoffset>(([0-9.-]+)|NULL)), (?P<orientation>[0-9.-]+), '(?P<country>[a-z]+)', (?P<model_id>[0-9]+), 1\)/";

        preg_match($pattern, $triggedQuery, $matches);
        $objectFactory = new ObjectFactory($this->objectDao);
        
        $newObject = $objectFactory->createObject(-1, $matches['model_id'],
                $matches['long'], $matches['lat'], $matches['country'], 
                $matches['elevoffset'], $matches['orientation'], 1, $matches['desc']);
            
        $requestObjAdd = new RequestObjectAdd();
        $requestObjAdd->setNewObject($newObject);
        
        return $requestObjAdd;
    }
    
    private function getRequestMassiveObjectsAddFromRow($requestQuery) {
        // Removing the start of the query from the data;
        $triggedQuery = str_replace("INSERT INTO fgs_objects (ob_text, wkb_geometry, ob_gndelev, ob_elevoffset, ob_heading, ob_model, ob_country, ob_group) " .
                                        "VALUES (","",$requestQuery);
        // Separating the data based on the ST_PointFromText existence
        $tab_tags = explode(", (",$triggedQuery);
        $newObjects = array();
        
        $pattern = "/'(?P<notes>[a-zA-Z0-9 +,!_.;\(\)\[\]\/-]*)', ST_PointFromText\('POINT\((?P<long>[0-9.-]+) (?P<lat>[0-9.-]+)\)', 4326\), (?P<elev>[0-9.-]+), (?P<elevoffset>[0-9.-]+), (?P<orientation>[0-9.-]+), (?P<model_id>[0-9]+), '(?P<country>[a-z]+)', 1\)/";
        foreach ($tab_tags as $value_tag) {
            preg_match($pattern, $value_tag, $matches);

            $objectFactory = new ObjectFactory($this->objectDao);
        
            $newObject = $objectFactory->createObject(-1, $matches['model_id'],
                    $matches['long'], $matches['lat'], $matches['country'], 
                    $matches['elevoffset'], $matches['orientation'], 1, $matches['notes']);
            
            $newObjects[] = $newObject;
        }
        
        $requestMassObjAdd = new RequestMassiveObjectsAdd();
        $requestMassObjAdd->setNewObjects($newObjects);
        
        return $requestMassObjAdd;
    }
    
    private function getRequestObjectUpdateFromRow($requestQuery) {
        // Removing the start of the query from the data
        $triggedQuery = strstr($requestQuery, 'SET');

        $pattern = '/SET ob_text\=\$\$(?P<notes>[^\$]*)\$\$, wkb_geometry\=ST_PointFromText\(\'POINT\((?P<lon>[0-9.-]+) (?P<lat>[0-9.-]+)\)\', 4326\), ob_gndelev\=(?P<elev>[0-9.-]+), ob_elevoffset\=(?P<elevoffset>(([0-9.-]+)|NULL)), ob_heading\=(?P<orientation>[0-9.-]+), ob_model\=(?P<model_id>[0-9]+), ob_group\=1 WHERE ob_id\=(?P<object_id>[0-9]+)/';

        preg_match($pattern, $triggedQuery, $matches);
        //$country = $matches['country'];

        $objectFactory = new ObjectFactory($this->objectDao);
        $newObject = $objectFactory->createObject($matches['object_id'], $matches['model_id'],
                $matches['lon'], $matches['lat'], 0, $matches['elevoffset'],
                $matches['orientation'], 1, $matches['notes']);
        $newObject->setGroundElevation($matches['elev']);

        $requestObjUp = new RequestObjectUpdate();
        $requestObjUp->setContributorEmail("");
        $requestObjUp->setComment("");
        $requestObjUp->setNewObject($newObject);
        $requestObjUp->setOldObject($this->objectDao->getObject($matches['object_id']));
        
        return $requestObjUp;
    }
    
    private function getRequestObjectDeleteFromRow($requestQuery) {
        $triggedQuery = strstr($requestQuery, 'WHERE');
        
        $pattern = "/WHERE ob_id\=(?P<object_id>[0-9]+)/";

        preg_match($pattern, $triggedQuery, $matches);
        $objectToDel = $this->objectDao->getObject($matches['object_id']);

        $requestObjDel = new RequestObjectDelete();
        
        // Not available with actual DAO
        $requestObjDel->setContributorEmail("");
        $requestObjDel->setComment("");
        
        $requestObjDel->setObjectToDelete($objectToDel);

        return $requestObjDel;
    }
}
?>
