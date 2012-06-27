<?php

// Inserting libs
require_once('../../inc/functions.inc.php');

// Checking DB availability before all
$ok=check_availability();

if(!$ok) {
    $page_title = "Automated Shared Models Positions Mass Import Submission Form";
    include '../../inc/header.php';
    ?>
    <br />
    <center><font color="red">Sorry, but the database is currently unavailable. We are doing the best to put it back up online. Please come back again soon.</font></center>
    <br /><center>The FlightGear team.</center>
    <?php include '../../inc/footer.php';
}
else {
    $page_title = "Automated Shared Models Positions Mass Import Submission Form";
    include '../../inc/header.php';
    ?>
<script type='text/javascript'>
function validField(fld) {
    if (fld == '') return false;
        return true;
}
</script>
<p>
<h1 align="center">Positions Automated Mass Import Submission Form</h1>
<p>
</p><b>Foreword:</b> This automated mass import form goal is to ease the submission when submitter want to add a lot of shared models positions into FG Scenery database. <br />There are currently <?php count_objects(); ?>
 objects in the database. Help us to make it more! Simply copy/paste the content of your STG files below.<br /></p>
 <b>WARNING: please only add NEW objects or you will encounter errors!!</b>

<p></p>Please read <a href="http://scenemodels.flightgear.org/contribute.php">this page</a> in order to understand what recommandations this script is looking for. <br />
Also note that all fields are now mandatory. Do not insert models not existing in the scenery objects database, nor OBJECT_SIGN, nor static objects. Finally, please <b>do not</b> add forests or items linked to the landcover. Those have to be generated by the landmass layers! Will only be accepted the trees or equivalent natural boundaries within an airport.
100 lines maximum!</p>
<form name="positions" method="post" action="check_mass_import.php">
<table width="400">
    <tr>
        <td><span title="This is the content of the STG file you want to add."><a style="cursor: help;">Content to add</a></span></td>
        <td><textarea name="stg" rows="30" cols="100" onblur="if (!validField(this.value)) alert('Please enter a value in STG field!');"></textarea></td>
    </tr>
    <tr>
        <td><span title="Please add a short (max 100 letters) statement why you are inserting this data. This will help the maintainers understand what you are doing. eg: I have placed a couple of aircraft shelters and static F16's at EHVK, please commit"><a style="cursor: help">Comment</a></span></td>
        <td>
            <input type="text" name="comment" maxlength="100" size="40" value="Comment" />
            <input name="IPAddr" type="hidden" value="<?php echo $_SERVER[REMOTE_ADDR]?>" />
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <center>
            <?php
            // Google Captcha stuff
            require_once('../../captcha/recaptchalib.php');
            $publickey = "6Len6skSAAAAAB1mCVkP3H8sfqqDiWbgjxOmYm_4";
            echo recaptcha_get_html($publickey);
            ?>
            <br />
            <input type="submit" value="Submit mass import" />
            </center>
        </td>
    </tr>
</table>
</form>
</p>
<?php include '../../inc/footer.php';
}
?>
