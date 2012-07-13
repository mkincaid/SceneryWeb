<?php
    require_once ('../../inc/functions.inc.php');
    $page_title = "Automated Models Submission Form";
    include '../../inc/header.php';
?>
<script type="text/javascript" src="/inc/js/check_form.js"></script>
<script type="text/javascript" src="http://code.jquery.com/jquery.min.js"></script>
<script type="text/javascript" src="../../inc/js/jquery.multifile.js"></script>

<h1>Models Automated Submission Form</h1>
<p class="center">
<b>Foreword:</b> This automated form goal is to ease the submission of static and shared 3D models into FG Scenery database.
There are currently <?php count_models(); ?> models in <a href="http://scenemodels.flightgear.org/models.php">our database</a>.
Help us to make it more!
Please, read the following:
<ul class="warning">

    <li>Choose the correct family for your model: static if the model exists only once in the world (eg: Eiffel Tower) or a logical shared family if it can be used elsewhere).</li>
    <li>Choose the author for the model. Please contact us if you're not listed here. If you are building a new model based on another one, put the original creator's name here, and yours in the contributor field.</li>
    <li>An email address is important: the server will keep you up to date about your submission: submitted, rejected, added, you'll know all of it in real time.</li>
    <li>The country is the one where the model you're adding is located, not yours!</li>
    <li>The description is very important. Has to be short and complete, it will appear in the "name" field for instance <a href="http://scenemodels.flightgear.org/modeledit.php?id=2551">here</a> as well as on the maps.</li>
    <li>Use the terrain shipped with FlightGear/Terrasync, and not any custom elevation model you may have installed/compiled, or model will be sunk/floating.</li>
    <li>The comment is important too, you can be a bit more talkative on your model (not pages!). It appears as "Comment" <a href="http://scenemodels.flightgear.org/modeledit.php?id=2319">here</a>, so don't just say: please commit!</li>
    <li>You HAVE TO add at least 3 files: an AC3D file of your model, a XML file, and a JPEG thumbnail (PNG texture(s) if any. Files have to share a common name, for instance Rochester_Castle_Keep.ac, Rochester_Castle_Keep.xml, Rochester_Castle_Keep_thumbnail.jpg (thumbnail written as is)). If you have multiple textures, name them Rochester_Castle_Keep1.png, with the number incrementing.</li>
    <li>JPEG has to be 320*240 exciting thumbnail.</li>
    <li>PNG size must be a power of 2 in width and height.</li>
    <li>XML file must start with a classic XML header, such as: &lt;?xml version="1.0" encoding="UTF-8" ?&gt;</li>
    <li>Please also read <a href="http://scenemodels.flightgear.org/contribute.php">this page</a> in order to understand what recommandations this script is looking for.</li>
    <li>Please note that all fields are now mandatory. An XML file is mandatory, as the name of your submission is automagically derived from it and as it should
    be useful for future work. See <a href="TheNameOfYourACFile.xml">here</a> for a quick example.</li>
    <li>Do not try to import/update an already existing model: there will be an update script [sometimes].</li>
    <li>I think that's all, folks ;-) Ah yes, be patient, there are human beings with real life constraints behind, and don't feel blamed if your models are rejected, but try to understand why.</li>
</ul>
  </p>
  <p class="center">
  Note this page is under HEAVY DEVELOPMENT and links to nowhere. Please do NOT use it unless we ask you for. It'll be for a bright future.
  </p>
     <form name="positions" method="post" action="check_static.php" enctype="multipart/form-data">
    <table>
        <tr>
            <td>
            <span title="This is the family name of the object you want to add. If your 3D model is going to be shared, use the proper family. If it's going to be a static one, then choose the static family."><a style="cursor: help;">Model's family</a></span>
            </td>
            <td colspan="2">
            <select name="mo_shared">
            <?php
            $resource_r = connect_sphere_r();
            $result = pg_query("SELECT mg_id, mg_name FROM fgs_modelgroups ORDER BY mg_name;");

            while ($row = pg_fetch_assoc($result)) {
                $name = preg_replace('/ /',"&nbsp;", $row["mg_name"]);
                // Selecting static family by default
                if(($row["mg_id"]) == 0) echo "<option value=\"".$row["mg_id"]."\" selected=\"selected\">".$name."</option>\n";
                else echo "<option value=\"".$row["mg_id"]."\">".$name."</option>\n";
            }
            pg_close ($resource_r);
            ?>
            </select>
            </td>
        </tr>
        <tr>
            <td>
            <span title="This is the name of the author. If the author does not exist, please ask the scenery maintainers to add it. This name is the author of the true creator of the model, if you just converted a model and were granted to do so, then use the line below.">
            <a style="cursor: help; ">Author</a>
            </span>
            </td>
            <td>
            <select name="mo_author">
            <?php list_authors(); ?>
            </select>
        </td>
        </tr>
        <tr>
            <td>
            <span title="If you did not make the 3D model yourself, but were granted to do so, then you're a contributor. Please enter your name here.">
            <a style="cursor: help; ">Contributor</a>
            </span>
            </td>
            <td>
            <input type="text" name="contributor" maxlength="100" size="40" value="Your name" onblur="checkStringNotDefault(this,'Your name');checkName(this)"/>
            </td>
        </tr>
        <tr>
            <td>
            <span title="Please leave YOUR VALID email address over here. This will help you be informed of your submission process. EXPERIMENTAL">
            <a style="cursor: help; ">Email address</a>
            </span>
            </td>
            <td>
            <input type="text" name="email" maxlength="100" size="40" value="Your email" onblur="checkEmail(this);" />
            </td>
        </tr>
        <tr>
            <td>
            <span title="This is the country code where the model is located (for static models only).">
            <a style="cursor: help; ">Country</a>
            </span>
            </td>
            <td>
            <select name="ob_country">
            <?php list_countries(); ?>
            </select>
            </td>
        </tr>
        <tr>
            <td>
            <span title="Please add a short (max 100 letters) name of your model (eg : Cornet antenna radome - Brittany - France">
            <a style="cursor: help">Description</a>
            </span>
            </td>
            <td>
            <input type="text" name="mo_name" maxlength="100" size="40" value="Tell us more about your model." onblur="checkComment(this);"/>
            </td>
        </tr>
        <tr>
            <td>
            <span title="This is the WGS84 longitude of the model you want to add. Has to be between -180.000000 and +180.000000.">
            <a style="cursor: help; ">Longitude</a>
            </span>
            </td>
            <td>
            <input type="text" name="longitude" maxlength="11" value="" onblur="checkNumeric(this,-180,180);" />
            </td>
        </tr>
        <tr>
            <td>
            <span title="This is the WGS84 latitude of the model you want to add. Has to be between -90.000000 and +90.000000.">
            <a style="cursor: help; ">Latitude</a>
            </span>
            </td>
            <td>
            <input type="text" name="latitude" maxlength="10" value="" onblur="checkNumeric(this,-90,90);" />
            </td>
        </tr>
        <tr>
            <td>
            <span title="This is the ground elevation (in meters) of the position where the model you want to add is located. Warning: if your model is sunk into the ground, use the elevation offset field below.">
            <a style="cursor: help; ">Elevation</a>
            </span>
            </td>
            <td>
            <input type="text" name="gndelev" maxlength="10" value="" onblur="checkNumeric(this,-10000,10000);" />
            </td>
        </tr>
        <tr>
            <td>
            <span title="This is the offset (in meters) between your model 'zero' and the elevation at the considered place (ie if it is sunk into the ground). Let 0 if there is no offset.">
            <a style="cursor: help; ">Elevation offset</a>
            </span>
            </td>
            <td>
            <input type="text" name="offset" maxlength="10" value="0" onblur="checkNumeric(this,-10000,10000);" />
            </td>
        </tr>
        <tr>
            <td>
            <span title="The orientation (in degrees) of the object you want to add - as it appears in the .stg file (this is NOT the true heading). Let 0 if there is no specific orientation.">
            <a style="cursor: help; ">Orientation</a>
            </span>
            </td>
            <td>
            <input type="text" name="heading" maxlength="7" value="" onblur="checkNumeric(this,0,359.999);" />
            </td>
        </tr>
        <tr>
            <td>
            <span title="Please add a short (max 100 letters) statement why you are inserting this data. This will help the maintainers understand what you are doing. eg: Hi, this is a new telecommunications model in Brittany, please commit.">
            <a style="cursor: help">Comment</a>
            </span>
            </td>
            <td>
            <input type="text" name="comment" maxlength="100" size="40" value="" onblur="checkComment(this);" />
            </td>
        </tr>
        <tr>
            <td>
            <span title="This is a nice picture representing your model in FG the best way (eg: tower_thumbnail.jpeg).">
            <a style="cursor: help; ">Corresponding 320x240 JPEG thumbnail</a>
            </span>
            </td>
            <td>
            <input type="file" name="mo_thumbfile" class="multi" maxlength="1" accept="image/jpg, image/jpeg" />
            </td>
        </tr>
        <tr>
            <td>
            <span title="This is the AC3D file of your model (eg: tower.ac).">
            <a style="cursor: help; ">Corresponding AC3D File</a>
            </span>
            </td>
            <td>
            <input type="file" name="ac3d_file" class="multi" maxlength="1" accept="ac"/>
            </td>
        </tr>
        <tr>
            <td>
            <span title="This is the XML file of your model (eg: tower.xml).">
            <a style="cursor: help; ">Corresponding XML File</a>
            </span>
            </td>
            <td>
            <input type="file" name="xml_file" class="multi" maxlength="1" accept="text/xml" />
            </td>
        </tr>
        <tr>
            <td>
            <span title="This (Those) is (are) the PNG texture(s) file(s) of your model. Has to be a factor of 2 in height and length.">
            <a style="cursor: help; ">Corresponding PNG Texture Files</a>
            </span>
            </td>
            <td>
            <input type="file" name="png_file[]" class="multi" maxlength="12" accept="image/png" />
            </td>
        </tr>
        <tr>
            <td colspan="2">
            <center>
            <input type="checkbox" name="gpl"/> I accept to release all my contribution under <a href="http://www.gnu.org/licenses/gpl-2.0.html">GNU GENERAL PUBLIC LICENSE Version 2, June 1991.</a><br/>
            <?php
            // Google Captcha stuff
            require_once('../../inc/captcha/recaptchalib.php');
            $publickey = "6Len6skSAAAAAB1mCVkP3H8sfqqDiWbgjxOmYm_4";
            //echo recaptcha_get_html($publickey);
            ?>
            <br />
            <input type="hidden" name="MAX_FILE_SITE" value="2000000" />
            <input name="IPAddr" type="hidden" value="<?php echo $_SERVER['REMOTE_ADDR']; ?>" />
            <input type="submit" value="Submit model" />
            </center>
            </td>
        </tr>
    </table>
    </form>

<script type="text/javascript">
$(document).ready(function(){
  // Checks if the GPL checkbox is checked
  $('input[type="submit"]').attr('disabled','disabled');

  $('input[name="gpl"]').change(function(){
    if($('input[name="gpl"]').is(':checked')){
      $('input[type="submit"]').removeAttr('disabled');
    }else{
      $('input[type="submit"]').attr('disabled','disabled');
    }
  });
});
</script>
<?php include '../../inc/footer.php'; ?>
