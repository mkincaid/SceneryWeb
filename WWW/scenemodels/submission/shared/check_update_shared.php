<?php

// Inserting libs
require_once('../../inc/functions.inc.php');

// Final step to edition
if((isset($_POST['new_long'])) && (isset($_POST['new_lat'])) && (isset($_POST['new_gndelev'])) && (isset($_POST['new_offset'])) && (isset($_POST['new_orientation']))) {

    // Captcha stuff
    require_once('../../inc/captcha/recaptchalib.php');

    // Private key is needed for the server-to-Google auth.
    $privatekey = "6Len6skSAAAAACnlhKXCda8vzn01y6P9VbpA5iqi";
    $resp = recaptcha_check_answer ($privatekey,
                                    $_SERVER["REMOTE_ADDR"],
                                    $_POST["recaptcha_challenge_field"],
                                    $_POST["recaptcha_response_field"]);

    // What happens when the CAPTCHA was entered incorrectly
    if (!$resp->is_valid) {
        $page_title = "Automated Shared Models Positions Update Form";
        include '../../inc/header.php';
        echo "<br />";
        die ("<center>Sorry but the reCAPTCHA wasn't entered correctly. <a href='http://scenemodels.flightgear.org/submission/shared/index_update.php'>Go back and try it again</a>." .
             "<br />(reCAPTCHA complained: " . $resp->error . ")</center>\n");
    }
    else {
        // Talking back to submitter.
        $page_title = "Automated Shared Models Positions Update Form";
        include '../../inc/header.php';

        // Checking that email is valid (if it exists).
        //(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))
        $failed_mail = 0;
        if((isset($_POST['email'])) && ((strlen($_POST['email'])) > 0) && ((strlen($_POST['email']) <= 50))) {
            $safe_email = pg_escape_string(stripslashes($_POST['email']));
            echo "<font color=\"green\">Email: ".$safe_email."</font><br />";
        }
        else {
            echo "<font color=\"red\">No email was given (not mandatory) or email mismatch!</font><br />";
            $failed_mail = 1;
        }

        // Preparing the update request
        $query_update="UPDATE fgs_objects ".
                      "SET ob_text='".object_name($_POST['model_name'])."', wkb_geometry=ST_PointFromText('POINT(".$_POST['new_long']." ".$_POST['new_lat'].")', 4326), ob_gndelev=".$_POST['new_gndelev'].", ob_elevoffset=".$_POST['new_offset'].", ob_heading=".heading_stg_to_true($_POST['new_orientation']).", ob_model=".$_POST['model_name'].", ob_group=1 ".
                      "WHERE ob_id=".$_POST['id_to_update'].";";

        // Generating the SHA-256 hash based on the data we've received + microtime (ms) + IP + request. Should hopefully be enough ;-)
        $sha_to_compute = "<".microtime()."><".$_POST['IPAddr']."><".$query_update.">";
        $sha_hash = hash('sha256', $sha_to_compute);

        // Zipping the Base64'd request.
        $zipped_base64_update_query = gzcompress($query_update,8);

        // Coding in Base64.
        $base64_update_query = base64_encode($zipped_base64_update_query);

        // Opening database connection...
        $resource_rw = connect_sphere_rw();

        // Sending the request...
        $query_rw_pending_request = "INSERT INTO fgs_position_requests (spr_hash, spr_base64_sqlz) VALUES ('".$sha_hash."', '".$base64_update_query."');";
        $resultrw = @pg_query($resource_rw, $query_rw_pending_request);

        // Closing the connection.
        @pg_close($resource_rw);

?>
    <br /><br />
<?php
    if(!$resultrw) {
        echo "<center>Sorry, but the query could not be processed. Please ask for help on the <a href='http://www.flightgear.org/forums/viewforum.php?f=5'>Scenery forum</a> or on the devel list.<br /></center>";
        include '../../inc/footer.php';
        exit;
    }
    else {
        echo "<center>Your update request has been successfully queued into the FG scenery database update requests!<br />";
        echo "Unless it's rejected, the object should be updated in Terrasync within a few days.<br />";
        echo "The FG community would like to thank you for your contribution!<br />";
        echo "Want to update, delete or submit another position?<br /> <a href=\"http://scenemodels.flightgear.org/submission/\">Click here to go back to the submission page.</a></center>";

        // Sending mail if there is no false and SQL was correctly inserted.
        // Sets the time to UTC.
        date_default_timezone_set('UTC');
        $dtg = date('l jS \of F Y h:i:s A');

        // Retrieving the IP address of the submitter (takes some time to resolve the IP address though).
        $ipaddr = pg_escape_string(stripslashes($_POST['IPAddr']));
        $host = gethostbyaddr($ipaddr);

        // OK, let's start with the mail redaction.
        // Who will receive it ?
        $to = "\"Olivier JACQ\" <olivier.jacq@free.fr>" . ", ";
        $to .= "\"Martin SPOTT\" <martin.spott@mgras.net>";

        // What is the subject ?
        $subject = "[FG Scenery Submission forms] Automatic shared model update request: needs validation.";

        // Correctly format the data for the mail.
        $object_url = "http://scenemodels.flightgear.org/modeledit.php?id=".$_POST['model_name'];
        $html_object_url = htmlspecialchars($object_url);

        // Generating the message and wrapping it to 77 signs per HTML line (asked by Martin). But warning, this must NOT cut an URL, or this will not work.
        if($failed_mail != 1) {
            $message0 = "Hi," . "\r\n" .
                        "This is the automated FG scenery update PHP form at:" . "\r\n" .
                        "http://scenemodels.flightgear.org/submission/check_update_shared.php" . "\r\n" .
                        "I just wanted to let you know that a new shared object position update request is pending." . "\r\n" .
                        "On ".$dtg." UTC, user with the IP address ".$ipaddr." (".$host.") and with email address ".$safe_email."\r\n" .
                        "issued the following request:" . "\r\n";
        }
        else {
            $message0 = "Hi," . "\r\n" .
                        "This is the automated FG scenery update PHP form at:" . "\r\n" .
                        "http://scenemodels.flightgear.org/submission/check_update_shared.php" . "\r\n" .
                        "I just wanted to let you know that a new shared object position update request is pending." . "\r\n" .
                    "On ".$dtg." UTC, user with the IP address ".$ipaddr." (".$host.") issued the following request:" . "\r\n";
        }
        $message077 = wordwrap($message0, 77, "\r\n");

        // There is no possibility to wrap the URL or it will not work, nor the rest of the message (short lines), or it will not work.
        $message1 = "Object #: ".$_POST['id_to_update']."\r\n" .
                    "Family: ". get_object_family_from_id($_POST['id_to_update']) ." => ".family_name($_POST['family_name'])."\r\n" .
                    "Object: ". object_name(get_object_model_from_id($_POST['id_to_update'])) ." => ".object_name($_POST['model_name'])."\r\n" .
                    "[ ".$html_object_url." ]" . "\r\n" .
                    "Latitude: ". get_object_latitude_from_id($_POST['id_to_update']) . "  => ".$_POST['new_lat']."\r\n" .
                    "Longitude: ". get_object_longitude_from_id($_POST['id_to_update']) . " => ".$_POST['new_long']."\r\n" .
                    "Ground elevation: ". get_object_elevation_from_id($_POST['id_to_update']) . " => ".$_POST['new_gndelev']."\r\n" .
                    "Elevation offset: ". get_object_offset_from_id($_POST['id_to_update']) . " => ".$_POST['new_offset']."\r\n" .
                    "True (DB) orientation: ". get_object_true_orientation_from_id($_POST['id_to_update']) . " => ".heading_stg_to_true($_POST['new_orientation'])."\r\n" .
                    "Comment: ". strip_tags($_POST['comment']) ."\r\n" .
                    "Please click:" . "\r\n" .
                    "http://mapserver.flightgear.org/map/?lon=". $_POST['new_long'] ."&lat=". $_POST['new_lat'] ."&zoom=14&layers=000B0000TFFFTFFFTFTFTFFF" . "\r\n" .
                    "to locate the object on the map (eventually new position)." ;

        $message2 = "\r\n".
                    "Now please click:" . "\r\n" .
                    "http://scenemodels.flightgear.org/submission/shared/submission.php?action=confirm&sig=". $sha_hash ."&email=". $safe_email."\r\n" .
                    "to confirm the update" . "\r\n" .
                    "or" . "\r\n" .
                    "http://scenemodels.flightgear.org/submission/shared/submission.php?action=reject&sig=". $sha_hash ."&email=". $safe_email."\r\n" .
                    "to reject the update." . "\r\n" . "\r\n" .
                    "Thanks!" ;

        // Preparing the headers.
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "From: \"FG Scenery Update forms\" <martin.spott@mgras.net>" . "\r\n";
        $headers .= "X-Mailer: PHP-" . phpversion() . "\r\n";

        // Let's send it ! No management of mail() errors to avoid being too talkative...
        $message = $message077.$message1.$message2;
        @mail($to, $subject, $message, $headers);

        // Mailing the submitter
        if($failed_mail != 1) {

            // Tell the submitter that its submission has been sent for validation.
            $to = $safe_email;

            // What is the subject ?
            $subject = "[FG Scenery Submission forms] Automatic shared model position update request.";

            // Correctly set the object URL.
            $family_url = "http://scenemodels.flightgear.org/modelbrowser.php?shared=".$family_id;
            $object_url = "http://scenemodels.flightgear.org/modeledit.php?id=".$model_id;
            $html_family_url = htmlspecialchars($family_url);
            $html_object_url = htmlspecialchars($object_url);

            // Generating the message and wrapping it to 77 signs per HTML line (asked by Martin). But warning, this must NOT cut an URL, or this will not work.
            $message3 = "Hi," . "\r\n" .
                        "This is the automated FG scenery submission PHP form at:" . "\r\n" .
                        "http://scenemodels.flightgear.org/submission/check_update_shared.php" . "\r\n" .
                        "On ".$dtg." UTC, user with the IP address ".$ipaddr." (".$host."), which is thought to be you, issued the following request." . "\r\n" .
                        "This new shared object position update request has been sent for validation." . "\r\n" .
                        "The first part of the unique of this request is ".substr($sha_hash,0,10). "..." . "\r\n" .
                        "If you have not asked for anything, or think this is a spam, please read the last part of this email." ."\r\n";
            $message077 = wordwrap($message3, 77, "\r\n");

            // There is no possibility to wrap the URL or it will not work, nor the rest of the message (short lines), or it will not work.
            $message4 = "Object #: ".$_POST['id_to_update']."\r\n" .
                        "Family: ". get_object_family_from_id($_POST['id_to_update']) ." => ".family_name($_POST['family_name'])."\r\n" .
                        "[ ".$html_family_url." ]" . "\r\n" .
                        "Object: ". object_name(get_object_model_from_id($_POST['id_to_update'])) ." => ".object_name($_POST['model_name'])."\r\n" .
                        "[ ".$html_object_url." ]" . "\r\n" .
                        "Latitude: ". get_object_latitude_from_id($_POST['id_to_update']) . "  => ".$_POST['new_lat']."\r\n" .
                        "Longitude: ". get_object_longitude_from_id($_POST['id_to_update']) . " => ".$_POST['new_long']."\r\n" .
                        "Ground elevation: ". get_object_elevation_from_id($_POST['id_to_update']) . " => ".$_POST['new_gndelev']."\r\n" .
                        "Elevation offset: ". get_object_offset_from_id($_POST['id_to_update']) . " => ".$_POST['new_offset']."\r\n" .
                        "True (DB) orientation: ". get_object_true_orientation_from_id($_POST['id_to_update']) . " => ".heading_stg_to_true($_POST['new_orientation'])."\r\n" .
                        "Comment: ". strip_tags($_POST['comment']) ."\r\n" .
                        "Please click:" . "\r\n" .
                        "http://mapserver.flightgear.org/map/?lon=". $_POST['new_long'] ."&lat=". $_POST['new_lat'] ."&zoom=14&layers=000B0000TFFFTFFFTFTFTFFF" . "\r\n" .
                        "to locate the object on the map (eventually new position)." . "\r\n" .
                        "This process has been going through antispam measures. However, if this email is not sollicited, please excuse-us and report at http://www.flightgear.org/forums/viewtopic.php?f=5&t=14671";

            // Preparing the headers.
            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "From: \"FG Scenery Submission forms\" <martin.spott@mgras.net>" . "\r\n";
            $headers .= "X-Mailer: PHP-" . phpversion() . "\r\n";

            // Let's send it ! No management of mail() errors to avoid being too talkative...
            $message = $message077.$message4;
            @mail($to, $subject, $message, $headers);
        }
    include '../../inc/footer.php';
    exit;
    }
}
}

// Getting back the update_choice
if(((isset($_POST['update_choice'])) && ($_POST['update_choice']>'0')) || ((isset($_GET['update_choice'])) && ($_GET['update_choice']>'0'))) {
    $page_title = "Automated Shared Models Positions Update Form";
    $body_onload = "update_objects();";
    include '../../inc/header.php';

    if(isset($_POST['update_choice'])) {
        $update_choice = $_POST['update_choice'];
    }
    else $update_choice = $_GET['update_choice'];
?>
<script src="/inc/js/update_objects.js" type ="text/javascript"></script>
<script src="/inc/js/check_form.js" type="text/javascript"></script>
<br /><br />
<?php
    $id_to_update = pg_escape_string(stripslashes($update_choice));
    echo "<center>You have asked to update object #".$id_to_update.".</center><br /><br />\n";
?>
        <form name="update" method="post" action="check_update_shared.php">
        <table>
        <tr>
        <td></td>
        <td><center>Actual value</center></td>
        <td><center>New value</center></td>
        <input type="hidden" name="id_to_update" value="<?php echo $_POST['update_choice']?>" />
        </tr>
        <tr>
        <td>
        <span title="This is the family name of the object you want to update."><a style="cursor: help;">Object's family</a></span>
        </td>
        <td>
        <?php $actual_family = get_object_family_from_id($id_to_update); echo $actual_family; ?>
        </td>
        <td>
        <?php

        $resource_r = connect_sphere_r();

        // If connection is OK
        if($resource_r != '0')
        {
            // Show all the families other than the static family
            $result = @pg_query("select mg_id,mg_name from fgs_modelgroups where mg_id!='0' order by mg_name;");

            // Start the select form
            echo "<select id=\"family_name\" name=\"family_name\" onchange=\"update_objects();\">\n";
            echo "<option selected value=\"0\">Please select a family</option>\n";
            while ($row = @pg_fetch_assoc($result))
            {
                $name=preg_replace('/ /',"&nbsp;",$row["mg_name"]);
                echo "<option value=\"".$row["mg_id"]."\">".$name."</option>\n";
            };
            echo "</select>";

            // Close the database resource
            @pg_close($resource_r);
        }

        // Else, write message.
        else {
            echo "<br /><center><font color='red'>Sorry but the database is currently unavailable, please come again soon.</font></center>";
        }
        ?>
            </td>
            </tr>
            <tr>
            <td>
            <span title="This is the name of the object you want to update, ie the name as it's supposed to appear in the .stg file.">
            <a style="cursor: help; ">Model name</a></span>
            </td>
            <td>
            <?php $actual_model_name = object_name(get_object_model_from_id($id_to_update));  echo $actual_model_name; ?>
            </td>
            <td>
            <?php
            // Now everything is done via the Ajax stuff, and the results inserted here.
            echo "<div id=\"form_objects\"></div>";
            ?>
            </td>
            </tr>
            <tr>
            <td>
            <span title="This is the WGS84 longitude of the object you want to update. Has to be between -180.000000 and +180.000000.">
            <a style="cursor: help; ">Longitude</a></span>
            </td>
            <td>
            <?php $actual_long = get_object_longitude_from_id($id_to_update); echo $actual_long; ?>
            </td>
            <td>
            <input type="text" name="new_long" maxlength="13" value="<?php echo $actual_long; ?>" onblur="checkNumeric(this,-180,180);" />
            </td>
            </tr>
            <tr>
            <td>
            <span title="This is the WGS84 latitude of the object you want to update. Has to be between -90.000000 and +90.000000.">
            <a style="cursor: help; ">Latitude</a></span>
            </td>
            <td>
            <?php $actual_lat = get_object_latitude_from_id($id_to_update); echo $actual_lat; ?>
            </td>
            <td>
            <input type="text" name="new_lat" maxlength="13" value="<?php echo $actual_lat; ?>" onblur="checkNumeric(this,-90,90);" />
            </td>
            </tr>
            <tr>
            <td>
            <span title="This is the ground elevation (in meters) of the position where the object you want to update is located. Warning : if your model is sunk into the ground, the Elevation offset field is set below.">
            <a style="cursor: help; ">Elevation</a></span>
            </td>
            <td>
            <?php $actual_elevation = get_object_elevation_from_id($id_to_update); echo $actual_elevation; ?>
            </td>
            <td>
            <input type="text" name="new_gndelev" maxlength="10" value="<?php echo $actual_elevation; ?>" onblur="checkNumeric(this,-10000,10000);" />
            </td>
            </tr>
            <tr>
            <td>
            <span title="This is the offset (in meters) between your model 'zero' and the elevation at the considered place (ie if it is sunk into the ground).">
            <a style="cursor: help; ">Elevation Offset</a></span>
            </td>
            <td>
            <?php $actual_offset = get_object_offset_from_id($id_to_update); echo $actual_offset; ?>
            </td>
            <td>
            <input type="text" name="new_offset" maxlength="10" value="<?php echo $actual_offset; ?>" onblur="checkNumeric(this,-10000,10000);" />
            </td>
            </tr>
            <tr>
            <td>
            <span title="The orientation of the object you want to update - as it appears in the STG file (this is NOT the true heading). Let 0 if there is no specific orientation."><a style="cursor: help; ">Orientation</a></span>
            </td>
            <td>
            <?php $actual_orientation = heading_true_to_stg(get_object_true_orientation_from_id($id_to_update)); echo $actual_orientation; ?>
            </td>
            <td>
            <input type="text" name="new_orientation" maxlength="7" value="<?php echo $actual_orientation; ?>" onblur="checkNumeric(this,0,359.999);" />
            </td>
            </tr>
            <tr>
            <td><span title="Please add a short (max 100 letters) statement why you are updating this data. This will help the maintainers understand what you are doing. eg: this model was misplaced, so I'm updating it">
            <a style="cursor: help">Comment</a></span></td>
            <td colspan="2">
            <center><input type="text" name="comment" maxlength="100" size="40" value="" /></center>
            </td>
            </tr>
            <tr>
            <td><span title="Please leave YOUR VALID email address over here. This will help you be informed of your submission process. EXPERIMENTAL">
            <a style="cursor:help">Email address (EXPERIMENTAL and not mandatory)</a></span></td>
            <td colspan="2">
            <center></center><input type="text" name="email" maxlength="50" size="40" value="" onblur="checkEmail(this);"/></center>
            </td>
            </tr>
            <tr>
            <td colspan="4">
            <center>
            <?php
                // Google Captcha stuff
                require_once('../../inc/captcha/recaptchalib.php');
                $publickey = "6Len6skSAAAAAB1mCVkP3H8sfqqDiWbgjxOmYm_4";
                echo recaptcha_get_html($publickey);
            ?>
            <input name="IPAddr" type="hidden" value="<?php echo $_SERVER[REMOTE_ADDR]?>" />
            <input type="submit" name="submit" value="Update this object!" />
            <input type="button" name="cancel" value="Cancel - Do not update!" onclick="history.go(-1)"/>
            </center>
            </td>
            </tr>
        </table>
        </form>
        <?php include '../../inc/footer.php';
}
else
{

// Checking DB availability before all
$ok=check_availability();

if(!$ok) {
    $page_title = "Automated Shared Models Positions Update Form";
    include '../../inc/header.php';
?>
<br /><br />
<center><font color="red">Sorry, but the database is currently unavailable. We are doing the best to put it back up online. Please come back again soon.</font></center>
<br /><center>The FlightGear team.</center>
<?php
include '../../inc/footer.php';
}
else {
    $page_title = "Automated Shared Models Positions Update Form";
    include '../../inc/header.php';
?>
<br />
<?php
global $false;
$false = '0';

// Checking that latitude exists and is containing only digits, - or ., is >=-90 and <=90 and with correct decimal format.
// (preg_match('/^[0-9\-\.]+$/u',$_POST['latitude']))
if((isset($_POST['latitude'])) && ((strlen($_POST['latitude'])) <= 13) && ($_POST['latitude'] <= 90) && ($_POST['latitude'] >= -90)) {
    $lat = number_format(pg_escape_string(stripslashes($_POST['latitude'])),7,'.','');
}
else {
    echo "<font color=\"red\">Latitude mismatch!</font><br />";
    $false='1';
}

// Checking that longitude exists and is containing only digits, - or ., is >=-180 and <=180 and with correct decimal format.
// (preg_match('/^[0-9\-\.]+$/u',$_POST['longitude']))
if((isset($_POST['longitude'])) && ((strlen($_POST['longitude'])) <= 13) && ($_POST['longitude'] >= -180) && ($_POST['longitude'] <= 180)) {
    $long = number_format(pg_escape_string(stripslashes($_POST['longitude'])),7,'.','');
}
else {
    echo "<font color=\"red\">Longitude mismatch!</font><br />";
    $false = '1';
}

// If there is no false, generating SQL to check for object.
if ($false == 0) {

    // Opening database connection...
    $resource_r_update = connect_sphere_r();

    // Let's see in the database if something exists at this position
    $query_pos = "SELECT ob_id, ob_modified, ob_gndelev, ob_elevoffset, ob_heading, ob_model FROM fgs_objects WHERE wkb_geometry = ST_PointFromText('POINT(".$long." ".$lat.")', 4326);";
    $result = @pg_query($resource_r_update, $query_pos);
    $returned_rows = pg_num_rows($result);

    if ($returned_rows == '0') {
        echo "<br /><font color=\"red\">Sorry, but no object was found at position longitude: ".$long.", latitude: ".$lat.". Please <a href=\"index_update.php\">go back and check your position</a> (see in the relevant STG file).</font><br/>";
        exit;
    }
    else {
        if($returned_rows == '1') { // If we have just an answer...
            while ($row = pg_fetch_row($result)) {
                echo "<br /><center>One object (#".$row[0].") with WGS84 coordinates longitude: ".$long.", latitude: ".$lat." has been found in the database.</center><br /><br />";
        ?>
                <form name="update_position" method="post" action="http://scenemodels.flightgear.org/submission/shared/check_update_shared.php">
                <table>
                    <tr>
                        <td><span title="This is the family name of the object you want to update."><a style="cursor: help;">Object's family</a></span></td>
                        <td colspan="4"><?php $family_name = get_object_family_from_id($row[0]); echo $family_name; ?></td>
                    </tr>
                    <tr>
                        <td><span title="This is the name of the object you want to update, ie the name as it's supposed to appear in the .stg file."><a style="cursor: help; ">Model name</a></span></td>
                        <td colspan="4"><?php $real_name = object_name($row[5]); echo $real_name; ?></td>
                        <input name="model_id" type="hidden" value="<?php echo $row[5]; ?>" />
                    </tr>
                    <tr>
                        <td><span title="This is the last update or submission date/time of the corresponding object.">
                        <a style="cursor: help; ">Date/Time of last update</a></span></td>
                        <td colspan="4"><?php echo $row[1]; ?></td>
                    </tr>
                    <tr>
                        <td><span title="This is the ground elevation (in meters) of the position where the object you want to update is located. Warning : if your model is sunk into the ground, the Elevation offset field is set below."><a style="cursor: help; ">Elevation</a></span></td>
                        <td colspan="4"><?php $actual_elevation = get_object_elevation_from_id($row[0]); echo $actual_elevation; ?></td>
                    </tr>
                    <tr>
                        <td><span title="This is the offset (in meters) between your model 'zero' and the elevation at the considered place (ie if it is sunk into the ground)."><a style="cursor: help; ">Elevation Offset</a></span></td>
                        <td colspan="4"><?php $actual_offset = get_object_offset_from_id($row[0]); echo $actual_offset; ?></td>
                    </tr>
                    <tr>
                        <td><span title="The orientation of the object you want to update - as it appears in the STG file (this is NOT the true heading). Let 0 if there is no specific orientation."><a style="cursor: help; ">Orientation</a></span></td>
                        <td colspan="4"><?php $actual_orientation = heading_true_to_stg(get_object_true_orientation_from_id($row[0])); echo $actual_orientation; ?></td>
                    </tr>
                    <tr>
                        <td><span title="This is the picture of the object you want to update"><a style="cursor: help; ">Picture</a></span></td>
                        <td><a href="http://scenemodels.flightgear.org/modeledit.php?id=<?php echo $row[5]; ?>"><img src="http://scenemodels.flightgear.org/modelthumb.php?id=<?php echo $row[5]; ?>"></a></td>
                        <td><span title="This is the map around the object you want to update"><a style="cursor: help; ">Map</a></span></td>
                        <td><iframe src="http://mapserver.flightgear.org/map/?lon=<? echo $long; ?>&lat=<? echo $lat; ?>&zoom=14&layers=000B0000TFFFTFFFTFTFTFFF" width="300" height="225" scrolling="auto" marginwidth="2" marginheight="2" frameborder="0">
                            </iframe>
                        </td>
                    </tr>
                    <input name="update_choice" type="hidden" value="<?php echo $row[0]; ?>" />
                    <input name="IPAddr" type="hidden" value="<?php echo $_SERVER[REMOTE_ADDR]; ?>" />
                    <input name="comment" type="hidden" value="<?php echo $_POST['comment']; ?>" />
                    <tr>
                        <td colspan="4">
                        <center>
                        <br />
                        <input type="submit" name="submit" value="I want to update this object!" />
                        <input type="button" name="cancel" value="Cancel, I made a mistake!" onclick="history.go(-1)"/>
                        </center>
                        </td>
                    </tr>
                </table>
                </form>
                <?php include '../../inc/footer.php';
            }
            exit;
        }
        else if($returned_rows > '1') {// If we have more than one, the user has to choose...
            echo "<br /><center>".$returned_rows." objects with WGS84 coordinates longitude: ".$long.", latitude: ".$lat." have been found in the database.<br />Please select with the left radio button the one you want to update.</center><br /><br />";

            // Starting multi-solutions form
            echo "<form name=\"update_position\" method=\"post\" action=\"http://scenemodels.flightgear.org/submission/shared/check_update_shared.php\"\">";
            echo "<table>";

            $i = 1; // Just used to put the selected button on the first entry
            while($row = pg_fetch_row($result)) {
?>
                    <tr>
                        <td colspan="5" background="white"><center><b>Object number #<?php echo $row[0]; ?></b></center>
                        </td>
                    </tr>
                    <tr>
                        <th rowspan="6">
                        <?php
                        if ($i == 1) {
                            echo "<input type=\"radio\" name=\"update_choice\" value=\"".$row[0]."\" checked />";
                            }
                            else echo "<input type=\"radio\" name=\"update_choice\" value=\"".$row[0]."\" />";
                            ?>
                        </th>
                        <td><span title="This is the family name of the object you want to update."><a style="cursor: help;">Object's family</a></span></td>
                        <td colspan="4"><?php $family_name = get_object_family_from_id($row[0]); echo $family_name; ?></td>
                    </tr>
                    <tr>
                        <td><span title="This is the name of the object you want to update, ie the name as it's supposed to appear in the .stg file.">
                        <a style="cursor: help; ">Model name</a></span></td>
                        <td colspan="4"><?php $real_name = object_name($row[5]); echo $real_name; ?></td>
                    </tr>
                    <tr>
                        <td><span title="This is the last update or submission date/time of the corresponding object.">
                        <a style="cursor: help; ">Date/Time of last update</a></span></td>
                        <td colspan="4"><?php echo $row[1]; ?></td>
                    </tr>
                    <tr>
                        <td><span title="This is the ground elevation (in meters) of the position where the object you want to update is located. Warning : if your model is sunk into the ground, the Elevation offset field is set below.">
                        <a style="cursor: help; ">Elevation</a></span></td>
                        <td colspan="4"><?php $actual_elevation = get_object_elevation_from_id($row[0]); echo $actual_elevation; ?></td>
                    </tr>
                    <tr>
                        <td><span title="This is the offset (in meters) between your model 'zero' and the elevation at the considered place (ie if it is sunk into the ground)."><a style="cursor: help; ">Elevation Offset</a></span></td>
                        <td colspan="4"><?php $actual_offset = get_object_offset_from_id($row[0]); echo $actual_offset; ?></td>
                    </tr>
                    <tr>
                        <td><span title="The orientation of the object you want to update - as it appears in the STG file (this is NOT the true heading). Let 0 if there is no specific orientation."><a style="cursor: help; ">Orientation</a></span></td>
                        <td colspan="4">?php $actual_orientation = heading_true_to_stg(get_object_true_orientation_from_id($row[0])); echo $actual_orientation; ?></td>
                    </tr>
                    <tr>
                        <td><span title="This is the picture of the object you want to update"><a style="cursor: help; ">Picture</a></span></td>
                        <td><a href="http://scenemodels.flightgear.org/modeledit.php?id=<?php echo $row[5]; ?>"><img src="http://scenemodels.flightgear.org/modelthumb.php?id=<?php echo $row[5]; ?>"></a></td>
                        <td><span title="This is the map around the object you want to update"><a style="cursor: help; ">Map</a></span></td>
                        <td><iframe src="http://mapserver.flightgear.org/map/?lon=<? echo $long; ?>&lat=<? echo $lat; ?>&zoom=14&layers=000B0000TFFFTFFFTFTFTFFF" width="300" height="225" scrolling="no" marginwidth="2" marginheight="2" frameborder="0">
                            </iframe>
                        </td>
                    </tr>
                <?php
                $i++;
            }
                ?>
                    <tr>
                        <td colspan="5">
                        <center>
                        <input name="IPAddr" type="hidden" value="<?php echo $_SERVER[REMOTE_ADDR]; ?>" />
                        <input name="comment" type="hidden" value="<?php echo $_POST['comment']; ?>" />
                        <br />
                        <input type="submit" name="submit" value="I want to update the selected object!" />
                        <input type="button" name="cancel" value="Cancel - I made a mistake!" onclick="history.go(-1)"/>
                        </center>
                        </td>
                    </tr>
                </table>
                </form>
<?php
            exit();
        }
    }
    }
include '../../inc/footer.php';
}
}
?>
