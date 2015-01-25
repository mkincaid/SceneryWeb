<?php
require_once '../../autoload.php';
$modelDaoRO = DAOFactory::getInstance()->getModelDaoRO();
$objectDaoRO = DAOFactory::getInstance()->getObjectDaoRO();
$authorDaoRO = DAOFactory::getInstance()->getAuthorDaoRO();
$requestDaoRW = DAOFactory::getInstance()->getRequestDaoRW();

# Inserting libs
require_once '../../inc/captcha/recaptchalib.php';
require_once '../../inc/functions.inc.php';

$fatalerror = false;
$errormsg   = "";

// Private key is needed for the server-to-Google auth.
$privatekey = "6Len6skSAAAAACnlhKXCda8vzn01y6P9VbpA5iqi";

if (isset($_POST['recaptcha_challenge_field']) && isset($_POST['recaptcha_response_field'])) {
    $resp = recaptcha_check_answer ($privatekey,
            $_SERVER["REMOTE_ADDR"],
            $_POST["recaptcha_challenge_field"],
            $_POST["recaptcha_response_field"]);
}

// What happens when the CAPTCHA was entered incorrectly
if (!isset($resp) || !$resp->is_valid) {
    $page_title = "Automated Models Submission Form";
    $error_text = "<br/>Sorry but the reCAPTCHA wasn't entered correctly. <a href='javascript:history.go(-1)'>Go back and try it again</a>.<br />";
    if (isset($resp)) {
        $error_text .= "(reCAPTCHA complained: " . $resp->error . ")<br />";
    }
    $error_text .= "Don't forget to feed the Captcha, it's a mandatory item as well. Don't know what a Captcha is or what its goal is? Learn more <a href=\"http://en.wikipedia.org/wiki/Captcha\">here</a>.";
    include '../../inc/error_page.php';
    exit;
}

$modelChecker = new ModelChecker();

$page_title = "Automated Models Submission Form";
require '../../inc/header.php';

################################################
################################################
#                                              #
# STEP 1 : CHECK IF ALL FILES WERE RECEIVED    #
#                                              #
################################################
################################################

if ($_FILES["mo_thumbfile"]['name'] != "" && ($_FILES["ac3d_file"]['name'] != "" || $_FILES["xml_file"]['name'] != "")) {
    $thumbName = $_FILES["mo_thumbfile"]['name'];
    $ac3dName  = $_FILES["ac3d_file"]['name'];
    $xmlName   = $_FILES["xml_file"]['name'];
}
else {
    if ($_FILES["mo_thumbfile"]["name"] == "") {
        $fatalerror = true;
        $errormsg .= "<li>You must provide a thumbnail.</li>";
    }
    if ($_FILES["ac3d_file"]['name'] == "" && $_FILES["xml_file"]['name'] == "") {
        $fatalerror = true;
        $errormsg .= "<li>You must provide at least one model (AC or XML) file.</li>";
    }
}

###########################################################
###########################################################
#                                                         #
# STEP 2 : CHECK IF ALL FILES MATCH THE NAMING CONVENTION #
#                                                         #
###########################################################
###########################################################
$exceptions = $modelChecker->checkFilesNames($ac3dName, $xmlName, $thumbName, $_FILES["png_file"]["name"]);
if (count($exceptions) > 0) {
    $fatalerror = true;
    foreach ($exceptions as $ex) {
        $errormsg .= "<li>".$ex->getMessage()."</li>";
    }
}

// Open working directory and set paths
try {
    $tmp_dir = sys_get_temp_dir();
    $targetPath = $modelChecker->openWorkingDirectory($tmp_dir);
    
    if ($xmlName != "") {
        $xmlPath = $targetPath.$xmlName;
    }
    $thumbPath = $targetPath.$thumbName;
    $ac3dPath  = $targetPath.$ac3dName;
} catch (Exception $ex) {
    $fatalerror = true;
    $errormsg .= "<li>".$ex->getMessage()."</li>";
}


###############################################
###############################################
#                                             #
# STEP 3 : UPLOAD ALL FILES IN TMP DIRECTORY  #
#                                             #
###############################################
###############################################

# STEP 3.1 : UPLOAD THUMBNAIL, AC3D, PNG AND XML FILES IN TMP DIRECTORY (Will be removed later on)
##############################################################################

$exceptions = $modelChecker->checkAC3DFileArray($_FILES['ac3d_file']) +
        $modelChecker->checkXMLFileArray($_FILES['xml_file']) +
        $modelChecker->checkThumbFileArray($_FILES['mo_thumbfile']);

// PNG Files
for ($i=0; $i<count($_FILES['png_file']['name']); $i++) {
    if (isset($_FILES['png_file']['name'][$i]) && ($_FILES['png_file']['name'][$i] != '')) {
        $arrayPNG = array();
        $arrayPNG['name'] = $_FILES['png_file']['name'][$i];
        $arrayPNG['type'] = $_FILES['png_file']['type'][$i];
        $arrayPNG['size'] = $_FILES['png_file']['size'][$i];
        $arrayPNG['error'] = $_FILES['png_file']['error'][$i];
        $arrayPNG['tmp_name'] = $_FILES['png_file']['tmp_name'][$i];

        $exceptionsPNG = $modelChecker->checkPNGArray($arrayPNG);
        
        // check uploaded file
        if (count($exceptionsPNG) == 0) {
            if (!move_uploaded_file($arrayPNG['tmp_name'], $targetPath.$arrayPNG['name'])) {
                $fatalerror = true;
                $errormsg .= "<li>There has been an error while moving the file \"".$pngName."\" on the server.</li>"; 
            }
        } else {
            $exceptions += $exceptionsPNG;
        }
    }
}

if (count($exceptions) == 0) {
    // check uploaded file
    if (isset($xmlPath) && !move_uploaded_file($_FILES['xml_file']['tmp_name'], $xmlPath)) {
        $fatalerror = true;
        $errormsg = "<li>There has been an error while moving the file \"".$xmlName."\" on the server.</li>";
    }
    
    // check upload file
    if (!move_uploaded_file($_FILES['ac3d_file']['tmp_name'], $ac3dPath)) {
        $fatalerror = true;
        $errormsg .= "<li>There has been an error while moving the file \"".$ac3dName."\" on the server.</li>";
    }
    
    // check uploaded file
    if (!move_uploaded_file($_FILES['mo_thumbfile']['tmp_name'], $thumbPath)) {
        $fatalerror = true;
        $errormsg .= "<li>There has been an error while moving the file \"".$thumbName."\" on the server.</li>";
    }
} else {
    $fatalerror = true;
    foreach ($exceptions as $ex) {
        $errormsg .= "<li>".$ex->getMessage()."</li>";
    }
}

######################################################
# IF ERRORS ARE DETECTED : STOP NOW AND PRINT ERRORS #
######################################################

if ($fatalerror) {
    echo "<h2>Oops, something went wrong</h2>" .
         "Error message(s)  : <br/>" .
         "<ul>".$errormsg."</ul><br/>" .
         "<a href='javascript:history.go(-1)'>Go back and correct your mistakes</a>.<br/><br/>" .
         "You can also ask the <a href=\"http://sourceforge.net/mailarchive/forum.php?forum_name=flightgear-devel\">mailing list</a> " .
         "or the <a href=\"http://www.flightgear.org/forums/viewtopic.php?f=5&t=14671\">forum</a> for help!";
    if (isset($targetPath)) {
        clear_dir($targetPath);
    }
    include '../../inc/footer.php';
    exit;
}

###############################################
###############################################
#                                             #
# STEP 4 : CHECK FILES                        #
#                                             #
###############################################
###############################################

$validatorsSet = new ValidatorsSet();
if ($xmlName != "") {
    $modelFilesValidator = ModelFilesValidator::instanceWithXML($targetPath, $xmlName, $ac3dName, $_FILES["png_file"]["name"]);
} else {
    $modelFilesValidator = ModelFilesValidator::instanceWithAC3DOnly($targetPath, $ac3dName, $_FILES["png_file"]["name"]);
}
$thumbValidator = new ThumbValidator($thumbPath);
$validatorsSet->addValidator($modelFilesValidator);
$validatorsSet->addValidator($thumbValidator);

$exceptions = $validatorsSet->validate();


$path_to_use = $ac3dName;
if (isset($xmlPath) && file_exists($xmlPath)) {
    # If an XML file is used for the model, the mo_path has to point to it, or
    # FG will not render it correctly. Else the .ac file will be used as mo_path.
    $path_to_use = $xmlName;
}

// Check if path is already used
if (path_exists($path_to_use)) {
    $exceptions[] = new Exception("Filename \"".$path_to_use."\" is already used");
} else {
    echo "<p class=\"center\">Your model named ".$path_to_use;
}

####################################################
# IF ERRORS ARE DETECTED : STOP NOW AND PRINT ERRORS
####################################################

if (count($exceptions) > 0) {
    foreach ($exceptions as $ex) {
        $errormsg .= "<li>".$ex->getMessage()."</li>";
    }
    
    echo "<h2>Oops, something went wrong</h2>" .
         "Error message(s)  : <br/>" .
         "<ul>".$errormsg."</ul><br/>" .
         "<a href='javascript:history.go(-1)'>Go back and correct your mistakes</a>.<br/><br/>" .
         "You can also ask the <a href=\"http://sourceforge.net/mailarchive/forum.php?forum_name=flightgear-devel\">mailing list</a> " .
         "or the <a href=\"http://www.flightgear.org/forums/viewtopic.php?f=5&t=14671\">forum</a> for help!";

    include '../../inc/footer.php';
    clear_dir($targetPath);
    exit;
}

###############################################
###############################################
#                                             #
# STEP 8 : ARCHIVE AND COMPRESS FILES         #
#                                             #
###############################################
###############################################

if (file_exists($targetPath) && is_dir($targetPath)) {
    $handle    = fopen($thumbPath, "r");
    $contents  = fread($handle, filesize($thumbPath));
    fclose($handle);
    $thumbFile = base64_encode($contents);             // Dump & encode the file
    unlink($thumbPath);                                // Has to be deleted, because it's not put into the .tar.gz

    // Dos2unix on XML
    if (isset($xmlPath)) {
        $d2u_xml_command  = 'dos2unix '.$xmlPath;
        system($d2u_xml_command);
    }

    // Dos2Unix on AC3D
    $d2u_ac3d_command = 'dos2unix '.$ac3dPath;
    system($d2u_ac3d_command);

    $phar = new PharData($tmp_dir . '/static.tar');                // Create archive file
    $phar->buildFromDirectory($targetPath);                        // Fills archive file
    $phar->compress(Phar::GZ);                                     // Convert archive file to compress file
    unlink($tmp_dir . '/static.tar');                              // Delete archive file
    rename($tmp_dir . '/static.tar.gz', $tmp_dir.'/static.tgz');   // Rename compress file

    $handle    = fopen($tmp_dir."/static.tgz", "r");
    $contents  = fread($handle, filesize($tmp_dir."/static.tgz"));
    fclose($handle);
    $modelFile = base64_encode($contents);                    // Dump & encode the file

    unlink($tmp_dir . '/static.tgz');                         // Delete compress file
    clear_dir($targetPath);                                   // Delete temporary static directory
}

###############################################
###############################################
#                                             #
# STEP 9 : CHECK MODEL INFORMATION            #
#                                             #
###############################################
###############################################

if (isset($_POST["model_group_id"]) && isset($_POST["mo_author"])
        && isset($_POST["mo_name"]) && isset($_POST['notes'])) {

    $name        = addslashes(htmlentities(strip_tags($_POST["mo_name"]), ENT_QUOTES));
    $notes       = addslashes(htmlentities(strip_tags($_POST["notes"]), ENT_QUOTES));
    $authorId    = $_POST["mo_author"];
    $moGroupId   = $_POST["model_group_id"];
    
    // If the author was unknown in the DB
    if ($authorId == 1) {
        if (isset($_POST["au_email"]) && FormChecker::isEmail($_POST["au_email"])) {
            $auEmail = $_POST["au_email"];
        } else {
            $fatalerror = true;
            $errormsg .= "<li>Please check email.</li>";
        }
        
        if (isset($_POST["au_name"]) && FormChecker::isComment($_POST["au_name"])) {
            $auName = $_POST["au_name"];
        } else {
            $fatalerror = true;
            $errormsg .= "<li>Please check your name.</li>";
        }
    }

    if (!FormChecker::isModelName($name)) {
        $fatalerror = true;
        $errormsg .= "<li>Please check the model name.</li>";
    }
    
    if (!FormChecker::isModelGroupId($moGroupId)) {
        $fatalerror = true;
        $errormsg .= "<li>Please check the model group.</li>";
    }
    
    if (!FormChecker::isAuthorId($authorId)) {
        $fatalerror = true;
        $errormsg .= "<li>Please check the author value.</li>";
    }
}
else {
    $fatalerror = true;
    $errormsg .= "<li>Please fill in all required fields.</li>";
}

if (!isset($_POST["gpl"])) {
    $fatalerror = true;
    $errormsg .= "<li>You did not accept the GNU GENERAL PUBLIC LICENSE Version 2, June 1991. As all the models shipped with FlightGear must wear this license, your contribution can't be accepted in our database. Please try to find GPLed textures and/or data.</li>";
}

###############################################
###############################################
#                                             #
# STEP 10 : CHECK GEOGRAPHICAL INFORMATION    #
#                                             #
###############################################
###############################################

if ($_POST["longitude"] != "" && $_POST["latitude"] != "" && $_POST["offset"] != ""
        && $_POST["heading"] != "" && $_POST["ob_country"] != "") {
    $longitude = strip_tags($_POST["longitude"]);
    $latitude  = strip_tags($_POST["latitude"]);
    $offset    = strip_tags($_POST["offset"]);
    $heading   = strip_tags($_POST["heading"]);
    $country   = $_POST["ob_country"];

    if (!FormChecker::isLongitude($longitude)) {
        $fatalerror = true;
        $errormsg .= "<li>Please check the longitude value (-180 < longitude < 180) and not null.</li>";
    }

    if (!FormChecker::isLatitude($latitude)) {
        $fatalerror = true;
        $errormsg .= "<li>Please check the latitude value (-90 < latitude < 90) and not null.</li>";
    }

    if ($offset == '' || $offset == '0') {
        $offset = 0;
    } else if (!FormChecker::isOffset($offset)) {
        $fatalerror = true;
        $errormsg .= "<li>Please check the offset value (-10000 < offset < 10000).</li>";
    }

    if (!FormChecker::isHeading($heading)) {
        $fatalerror = true;
        $errormsg .= "<li>Please check the heading value (0 < heading < 359.999).</li>";
    }
    
    if (!FormChecker::isCountryId($country)) {
        $fatalerror = true;
        $errormsg .= "<li>Please check the country value.</li>";
    }
}
else {
    $fatalerror = true;
    $errormsg .= "<li>Please fill in all required fields.</li>";
}

###############################################
###############################################
#                                             #
# FINAL STEP : RESUME AND SUBMIT VALIDATION   #
#                                             #
###############################################
###############################################

if ($fatalerror) {
    echo "<h2>Oops, something went wrong</h2>" .
         "Error message(s)  : <br/>" .
         "<ul>".$errormsg."</ul><br/>" .
         "<a href='javascript:history.go(-1)'>Go back and correct your mistakes</a>.<br/><br/>" .
         "You can also ask the <a href=\"http://sourceforge.net/mailarchive/forum.php?forum_name=flightgear-devel\">mailing list</a> " .
         "or the <a href=\"http://www.flightgear.org/forums/viewtopic.php?f=5&t=14671\">forum</a> for help!";
    include '../../inc/footer.php';
    clear_dir($targetPath);
    exit;
}
else {
    $modelFactory = new ModelFactory($modelDaoRO, $authorDaoRO);
    $objectFactory = new ObjectFactory($objectDaoRO);
    $newModel = new Model();
    $newModelMD = $modelFactory->createModelMetadata(-1, $authorId, $path_to_use, $name, $notes, $moGroupId);
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
            $offset, heading_stg_to_true($heading), 1, $name);

    $request = new RequestModelAdd();
    $request->setNewModel($newModel);
    $request->setNewObject($newObject);
    $request->setContributorEmail($auEmail);
    
    try {
        $updatedReq = $requestDaoRW->saveRequest($request);
        
        echo "has been successfully queued into the FG scenery database insertion requests!<br />";
        echo "Unless it's rejected, it should appear in Terrasync within a few days.<br />";
        echo "The FG community would like to thank you for your contribution!<br />";
        echo "Want to submit another model or position?<br /> <a href=\"http://".$_SERVER['SERVER_NAME']."/submission/\">Click here to go back to the submission page.</a></p>";

        // Sending mail if there is no false and SQL was correctly inserted.
        // Sets the time to UTC.
        date_default_timezone_set('UTC');
        $dtg = date('l jS \of F Y h:i:s A');
        // Retrieving the IP address of the submitter (takes some time to resolve the IP address though).
        $ipaddr = htmlentities(stripslashes($_SERVER["REMOTE_ADDR"]));
        $host = gethostbyaddr($ipaddr);
        
        $emailSubmit = EmailContentFactory::getAddModelRequestPendingEmailContent($dtg, $ipaddr, $host, $updatedReq);
        $emailSubmit->sendEmail("", true);
        
        // Mailing the submitter to tell him that his submission has been sent for validation
        $emailSubmitContr = EmailContentFactory::getAddModelRequestSentForValidationEmailContent($dtg, $ipaddr, $host, $updatedReq);
        $emailSubmitContr->sendEmail($auEmail, false);
    } catch (Exception $ex) {
        echo "<p class=\"center\">Sorry, but the query could not be processed. Please ask for help on the <a href='http://www.flightgear.org/forums/viewforum.php?f=5'>Scenery forum</a> or on the devel list.</p><br />";
    }
}
require '../../inc/footer.php';
?>