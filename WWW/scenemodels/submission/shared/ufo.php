<?php

    // Inserting libs
    require_once('../../inc/functions.inc.php');

    // Checking DB availability before all
    $ok = check_availability();

    if (!$ok) {
        $page_title = "Automated Objects Massive Import Submission Form";
        $error_text = "Sorry, but the database is currently unavailable. We are doing the best to put it back up online. Please come back again soon.";
        include '../../inc/error_page.php';
        exit;
    }

    $page_title = "Automated Objects Massive Import Submission Form";
    include '../../inc/header.php';
?>
<script src="/inc/js/check_form.js" type="text/javascript"></script>
<script type="text/javascript">
/*<![CDATA[*/
function validateForm()
{
    var form = document.getElementById("positions");

    if (!checkStringNotDefault(form["stg"], "") || !checkSTG(form["stg"]) ||
        (form['email'].value!="" && !checkEmail(form['email'])) ||
        !checkStringNotDefault(form["comment"], "") || !checkComment(form["comment"]))
        return false;
}
/*]]>*/
</script>

<h1>Automated Objects Massive Import Submission Form</h1>

<p>
  <b>Foreword:</b> This automated massive import form goal is to ease the submission
  when submitter wants to add a lot of objects positions into FG Scenery database.
  <br />There are currently <?php $objects = count_objects(); echo number_format($objects, '0', '', ' ');?> objects in the database.
  Help us to make it more! Simply copy/paste the NEW content of your STG files below.
</p>
<p>
  Please read <a href="http://<?php echo $_SERVER['SERVER_NAME']; ?>/contribute.php">this page</a>
  in order to understand what recommandations this script is looking for.
</p>
<p>Please note that:</p>

<ul class="warning">
    <li>Only add NEW objects or you will encounter errors!!</li>
    <li>
      Do not insert models not existing in the scenery objects database,
      nor OBJECT_SIGN, nor OBJECT_STATIC lines.
    </li>
    <li>
      Do <strong>NOT</strong> add forests or items linked to the landcover.
      Those have to be generated by the landmass layers! Will only be accepted
      the trees or equivalent natural boundaries within an airport.
    </li>
    <li>
      Also, please use the terrain shipped with FlightGear/Terrasync, and not
      any custom elevation model you may have installed/compiled.
    </li>
    <li>
      Pitch or roll information issued by UFO in the
      latest version, can't - yet - been taken into consideration. It's on
      our TODO list.
    </li>
    <li>
        Line without offset, pitch and roll: OBJECT_SHARED Models/Airport/localizer.xml 121.337467 31.179872 2.47 267.03<br />
        Line with offset: OBJECT_SHARED Models/Airport/localizer.xml 121.337467 31.179872 2.47 267.03 -20.0<br />
        Line with pitch and roll (ignored), but without offset: OBJECT_SHARED Models/Airport/localizer.xml 121.337467 31.179872 2.47 267.03 0.0 0.0
    </li>
    <li>Finally, add 100 lines maximum per submission!</li>
    <li>
      You can copy/paste objects positions coming from different STG files,
      it's not necessary to do as many submissions as STG files you're working on.
    </li>
    <li>
      Don't forget to feed the Captcha, it's a mandatory item as well. Don't
      know what a Captcha is or what its goal is? Learn more
      <a href="http://en.wikipedia.org/wiki/Captcha">here</a>.
    </li>
</ul>
<?php
$file = "tmp/data".$_SERVER['REMOTE_ADDR'].".xml";
$time_start = microtime(true);
$time = 0;
while (!file_exists($file) and $time < 5) {
    sleep(1);
    flush();
    $time_end = microtime(true);
    $time = $time_end - $time_start;
}
if (file_exists($file)) {
    $xml = simplexml_load_file($file);

    $stg = "";
    foreach($xml->children() as $child)
    {
    $stg .= "OBJECT_SHARED " .
         $child->{'path'} . " " .
         $child->{'longitude-deg'} . " " .
         $child->{'latitude-deg'} . " " .
         $child->{'elevation-ft'} . " " .
         $child->{'heading-deg'} . "\n";
    }
    unlink($file);  
} else {
    $stg = "Sorry, your objects did not make their way to our server. Please try again.";
}
?>
<form id="positions" method="post" action="confirm_mass_import.php" onsubmit="return validateForm();">
<table>
    <tr>
        <td style="vertical-align: top;"><label for="stg">Content to add<em>*</em><span>This is the content of the STG file you want to add.</span></label></td>
        <td><textarea name="stg" id="stg" rows="30" cols="100" onchange="checkSTG(this);"><?php echo $stg; ?></textarea></td>
    </tr>
    <tr>
        <td><label for="email">Email address<span>Please leave YOUR VALID email address over here. This will help you be informed of your submission process.</span></label></td>
        <td>
            <input type="text" name="email" id="email" maxlength="50" size="50" value="" onkeyup="checkEmail(this);" />
        </td>
    </tr>
    <tr>
        <td><label for="comment">Comment<em>*</em><span>Please add a short (max 100 letters) statement why you are inserting this data. This will help the maintainers understand what you are doing. eg: 'I have placed a couple of aircraft shelters and static F16's at EHVK, please commit.' Only alphanumerical, colon, semi colon, question and exclamation mark, arobace, minus, underscore, antislash and point are granted.</span></label></td>
        <td>
            <input type="text" name="comment" id="comment" maxlength="100" size="100" value="" onkeyup="checkComment(this);" />
            <input name="IPAddr" type="hidden" value="<?php echo $_SERVER['REMOTE_ADDR']?>" />
        </td>
    </tr>
    <tr>
        <td colspan="2" class="submit">
<?php
            // Google Captcha stuff
            require_once('../../inc/captcha/recaptchalib.php');
            $publickey = "6Len6skSAAAAAB1mCVkP3H8sfqqDiWbgjxOmYm_4";
            echo recaptcha_get_html($publickey);
?>
            <br />
            <input type="submit" value="Submit mass import" />
        </td>
    </tr>
</table>
</form>

<?php include '../../inc/footer.php';
?>