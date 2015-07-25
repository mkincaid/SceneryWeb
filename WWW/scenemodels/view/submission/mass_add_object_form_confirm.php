<?php
$page_title = "Automated Objects Massive Import Submission Form";
require 'view/header.php';
?>
<script src="/inc/js/check_form.js" type="text/javascript"></script>
<script type ="text/javascript">
function update_countries(code,n) {
    for(var i = 1; i <= n; i++) {
        if (document.getElementById("countryId"+i).value === "zz") {
            document.getElementById("countryId"+i).value=code;
        }
    }
}

function validateForm() {
    var form = document.getElementById("positions");
    var result = true;

    if (!checkStringNotDefault(form["comment"], "") || !checkComment(form['comment']) ||
        (form['email'].value!=="" && !checkEmail(form['email']))) {
        result = false;
    }

    var i = 1;
    while (form["modelId"+i]) {
        if (!checkStringNotDefault(form["long"+i], "") || !checkNumeric(form["long"+i],-180,180) ||
            !checkStringNotDefault(form["lat"+i], "") || !checkNumeric(form["lat"+i],-90,90) ||
            !checkNumeric(form['offset'+i],-999,999) ||
            !checkStringNotDefault(form["heading"+i], "") || !checkNumeric(form['heading'+i],0,359.999))
            result = false;
        
        i++;
    }
    
    return result;
}
</script>
<br />
<?php

if (!isset($sent_comment)) {
    echo "<p class=\"center warning\">Comment mismatch!</p>";
    include 'view/footer.php';
    exit;
}

if (isset($safe_email)) {
    echo "<p class=\"center ok\">Email: ".$safe_email."</p>";
} else {
    echo "<p class=\"center warning\">No email was given (not mandatory) or email mismatch!</p>";
}

echo '<p class=\"center\">Counted a number of '.$nb_lines.' lines submitted.</p>';
echo "Please check the table below carefully, and make sure that your submission was read correctly. We have proposed a country for each object, but this may be incorrect. You can only change the countries on this page. Please <a href='javascript:history.go(-1)'>go back and edit your lines</a> if you would like to edit other things.";

?>
<form id="positions" method="post" action="app.php?c=AddObjects&a=check" onsubmit="return validateForm();">
<?php

// Display result table
$unknownCountry = false;

echo "<table>";
echo "<tr><th>Line</th><th>Model</th><th>Longitude</th><th>Latitude</th><th>Elev. offset</th><th>Orientation</th><th>Country</th><th>Result</th></tr>";

// Validates - or no - the right to go further.
$global_ko = false;

foreach ($objectLinesRequests as $lineNb => $objectLineRequest) {
    echo '<tr><td>'.$lineNb.'</td>';

    if ($objectLineRequest->getObject() != null) {
        $object = $objectLineRequest->getObject();

        echo '<td><input type="hidden" name="modelId'.$lineNb.'" value="'.$object->getModelId().'"/>'.$modelMDs[$object->getModelId()]->getName().'</td>'.
             '<td><input type="text" size="10" name="long'.$lineNb.'" value="'.$object->getPosition()->getLongitude().'"/></td>'.
             '<td><input type="text" size="10" name="lat'.$lineNb.'" value="'.$object->getPosition()->getLatitude().'"/></td>'.
             '<td><input type="text" size="10" name="offset'.$lineNb.'" value="'.$object->getElevationOffset().'"/></td>'.
             '<td><input type="text" size="10" name="heading'.$lineNb.'" value="'.\ObjectUtils::headingTrue2STG($object->getOrientation()).'"/></td>';


        if ($object->getCountry()->getCode() == "zz") {
            $unknownCountry = true;
        }
        echo "<td><select name='countryId".$lineNb."' id='countryId".$lineNb."' style='width: 100%;'>";

        foreach($countries as $country) {
            echo "<option value=\"".$country->getCode()."\"";
            if ($country->getCode() == $object->getCountry()->getCode()) {
                echo " selected";
            }
            echo ">".$country->getName()."</option>";
        }

        echo "</select></td>";

    } else {
        echo '<td colspan=\'6\'>'.$objectLineRequest->getStgLine().'</td>';
    }

    
    if (count($objectLineRequest->getErrors()) > 0) {
        echo "<td style='background-color: rgb(200, 0, 0);'>";
        foreach ($objectLineRequest->getErrors() as $error) {
            echo $error->getMessage()."<br/>";
        }
        echo "</td>";

        $global_ko = true;
    } else if (count($objectLineRequest->getWarnings()) > 0) {
        echo "<td style='background-color: rgb(255, 200, 0);'>";
        foreach ($objectLineRequest->getWarnings() as $warning) {
            echo $warning->getMessage()."<br/>";
        }
        echo "</td>";
    } else {
        echo "<td style='background-color: rgb(0, 200, 0);'>OK</td>";
    }
    echo '</tr>';
}

if ($unknownCountry) {
    echo "<tr><td colspan=\"6\" align=\"right\">Set all 'unknown' countries to:</td><td>" .
         "<select name='global_country' id='global_country' style='width: 100%;' onchange='update_countries(this.value,".$nb_lines.")'>" .
         "<option value=\"\">----</option>";

    foreach($countries as $country) {
        echo "<option value=\"".$country->getCode()."\">".$country->getName()."</option>";
    }
    echo "</select></td><td></td></tr>";
}
echo "</table>";

echo "<b>Your comment:</b> ".$sent_comment."<br/>" .
     "<b>Your email:</b> ".$safe_email."<br/>" .
     "<input type='hidden' name='email' id='email' value='".$safe_email."'/>" .
     "<input type='hidden' name='comment' id='comment' value='".$sent_comment."'/>";

if ($global_ko) { // If errors have been found...
    echo "<p class=\"center warning\">Errors have been found in your submission. Please <a href='javascript:history.go(-1)'>go back</a> and correct or delete the corresponding lines from your submission before submitting again.</p>";

    include 'view/footer.php';
    exit;
}

// Else, allow submitter to proceed
echo "<p class=\"center ok\">No errors have been found in your submission, all fields have been checked and seem to be OK to be proceeded.<br/>".
     "Enter captcha and press the button below to finish your submission.</p>";

// Google Captcha stuff
require_once 'inc/captcha/recaptchalib.php';
$publickey = "6Len6skSAAAAAB1mCVkP3H8sfqqDiWbgjxOmYm_4";
echo "<div style=\"margin: auto;display: table;\">".recaptcha_get_html($publickey)."</div>";

echo "<p class=\"center ok\"><input name='submit' type='submit' value='Submit objects' /></p></form>";

require 'view/footer.php';
?>