<?php

/*
 * Copyright (C) 2015 FlightGear Team
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

namespace controller;

/**
 * Controller for model addition form
 *
 * @author Julien Nguyen
 */
class AddModelController extends ModelRequestController {
    private $objectDaoRO;
    private $authorDaoRO;
    
    public function __construct() {
        parent::__construct();
        $this->objectDaoRO = \dao\DAOFactory::getInstance()->getObjectDaoRO();
        $this->authorDaoRO = \dao\DAOFactory::getInstance()->getAuthorDaoRO();
    }
    
    /**
     * Display form action
     */
    public function formAction() {
        parent::menu();
        
        // Show all the families other than the static family
        $modelsGroups = $this->getModelsGroups();
        $countries = $this->objectDaoRO->getCountries();
        $nbModels = $this->getModelDaoRO()->countTotalModels();
        $authors = $this->authorDaoRO->getAllAuthors(0, "ALL");

        include 'view/submission/model/add_model_form.php';
    }
    
    /**
     * Check and add new model addition request 
     */
    public function addRequestAction() {
        $requestDaoRW = \dao\DAOFactory::getInstance()->getRequestDaoRW();
        
        $resp = $this->checkCaptcha();
        if (!$resp->is_valid) {
            $this->displayCaptchaError($resp);
            return;
        }
        
        /** STEP 1 : CHECK IF ALL FILES WERE RECEIVED */
        $exceptions = $this->checkFilesArray();
        
        /** STEP 2 : MOVE THUMBNAIL, AC3D, PNG AND XML FILES IN TMP DIRECTORY (Will be removed later on) */
        $thumbName = $_FILES['mo_thumbfile']['name'];
        $ac3dName  = $_FILES['ac3d_file']['name'];
        $xmlName   = $_FILES['xml_file']['name'];
        
        if (empty($exceptions)) {
            try {
                // Open working directory
                $targetPath = $this->getModelChecker()->openWorkingDirectory(sys_get_temp_dir());
                $exceptions = $this->moveFilesToTMPDir($targetPath, $xmlName, $ac3dName, $thumbName);
            } catch (\Exception $ex) {
                $exceptions[] = $ex;
            }
        }
        
        /** IF ERRORS ARE DETECTED : STOP NOW AND PRINT ERRORS */
        if (!empty($exceptions)) {
            if (isset($targetPath)) {
                \FileSystemUtils::clearDir($targetPath);
            }
            
            $this->displayModelErrors($exceptions);
            return;
        }
        
        /** STEP 4 : CHECK FILES */
        $exceptions = $this->checkFiles($targetPath, $xmlName, $ac3dName, $thumbName);
        
        // If an XML file is used for the model, the mo_path has to point to it, otherwise use AC3D
        $pathToUse = $ac3dName;
        if (!empty($xmlName)) {
            $pathToUse = $xmlName;
        }

        // Check if path is already used
        if ($this->pathExists($pathToUse)) {
            $exceptions[] = new \Exception("Filename \"".$pathToUse."\" is already used");
        }
        
        /** STEP 9 : CHECK MODEL INFORMATION */
        $name    = addslashes(htmlentities(strip_tags($this->getVar('mo_name')), ENT_QUOTES));
        $notes   = addslashes(htmlentities(strip_tags($this->getVar('notes')), ENT_QUOTES));
        $authorId  = $this->getVar('mo_author');
        $moGroupId = $this->getVar('model_group_id');
            
        if (empty($notes)) {
            $notes = "";
        }

        $modelMDValidator = \submission\ModelMetadataValidator::getModelMDValidator($name, $notes, $authorId, $moGroupId);

        /** STEP 10 : CHECK GEOGRAPHICAL INFORMATION */
        $longitude = strip_tags($this->getVar('longitude'));
        $latitude  = strip_tags($this->getVar('latitude'));
        $offset    = strip_tags($this->getVar('offset'));
        $heading   = strip_tags($this->getVar('heading'));
        $country   = $this->getVar('ob_country');
        $objectValidator = \submission\ObjectValidator::getPositionValidator($longitude, $latitude, $country, $offset, $heading);

        $validatorsSet = new \submission\ValidatorsSet();
        $validatorsSet->addValidator($modelMDValidator);
        $validatorsSet->addValidator($objectValidator);
 
        // If the author was unknown in the DB
        if ($authorId == 1) {
            $auEmail = $this->getVar('au_email');
            $auName = $this->getVar('au_name');
            $authorValidator = \submission\AuthorValidator::getAuthorValidator($auName, $auEmail);
            $validatorsSet->addValidator($authorValidator);
        }
        
        $exceptions = array_merge($exceptions, $validatorsSet->validate());

        if (empty($this->getVar('gpl'))) {
            $exceptions[] = new \Exception("You did not accept the GNU GENERAL PUBLIC LICENSE Version 2, June 1991. As all the models shipped with FlightGear must wear this license, your contribution can't be accepted in our database. Please try to find GPLed textures and/or data.");
        }

        // Display errors if exist
        if (!empty($exceptions)) {
            \FileSystemUtils::clearDir($targetPath);
            $this->displayModelErrors($exceptions);
            return;
        }
        
        /** STEP 8 : ARCHIVE AND COMPRESS FILES */
        $thumbFile = $this->prepareThumbFile($targetPath.$thumbName);
        // Has to be deleted, because it's not put into the .tar.gz
        unlink($targetPath.$thumbName);
        
        $modelFile = $this->prepareModelFile($targetPath, $xmlName, $ac3dName);
        // Delete temporary model directory
        \FileSystemUtils::clearDir($targetPath);
        
        $modelFactory = new \ModelFactory($this->getModelDaoRO(), $this->authorDaoRO);
        $objectFactory = new \ObjectFactory($this->objectDaoRO);
        $newModel = new \model\Model();
        $newModelMD = $modelFactory->createModelMetadata(-1, $authorId, $pathToUse, $name, $notes, $moGroupId);
        if ($authorId != 1) {
            $auEmail = $newModelMD->getAuthor()->getEmail();
        } else {
            $author = $newModelMD->getAuthor();
            $author->setName($auName);
            $author->setEmail($auEmail);
            $newModelMD->setAuthor($author);
        }

        $newModel->setMetadata($newModelMD);
        $newModel->setModelFiles($modelFile);
        $newModel->setThumbnail($thumbFile);

        $newObject = $objectFactory->createObject(-1, -1, $longitude, $latitude, $country, 
                $offset, \ObjectUtils::headingSTG2True($heading), 1, $name);

        $request = new \model\RequestModelAdd();
        $request->setNewModel($newModel);
        $request->setNewObject($newObject);
        $request->setContributorEmail($auEmail);

        try {
            $updatedReq = $requestDaoRW->saveRequest($request);
            
            $this->sendEmailsRequestPending($auEmail, $updatedReq);
            include 'view/submission/model/check_model_add.php';
        } catch (\Exception $ex) {
            $pageTitle = "Automated Models Submission Form";
            $errorText = "Sorry, but the query could not be processed. Please ask for help on the <a href='http://www.flightgear.org/forums/viewforum.php?f=5'>Scenery forum</a> or on the devel list.";
            include 'view/error_page.php';
            return;
        }
    }
    
    private function sendEmailsRequestPending($auEmail, $updatedReq) {
        // Sending mail if there is no false and SQL was correctly inserted.
        // Sets the time to UTC.
        date_default_timezone_set('UTC');
        $dtg = date('l jS \of F Y h:i:s A');
        // Retrieving the IP address of the submitter (takes some time to resolve the IP address though).
        $ipaddr = htmlentities(stripslashes($_SERVER["REMOTE_ADDR"]));
        $host = gethostbyaddr($ipaddr);

        $emailSubmit = \EmailContentFactory::getAddModelRequestPendingEmailContent($dtg, $ipaddr, $host, $updatedReq);
        $emailSubmit->sendEmail("", true);

        // Mailing the submitter to tell him that his submission has been sent for validation
        $emailSubmitContr = \EmailContentFactory::getAddModelRequestSentForValidationEmailContent($dtg, $ipaddr, $host, $updatedReq);
        $emailSubmitContr->sendEmail($auEmail, false);
    }
}
