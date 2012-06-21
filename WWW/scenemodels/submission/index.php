<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" 
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
  <head>
    <title>Automated Scenery Objects Submission Forms</title>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
    <link rel="stylesheet" href="../css/style.css" type="text/css"></link>
  </head>
  <body>
  <?php include '../header.php'; ?>

  <br/><br/>

  <h1 align="center">FG scenery objects submission forms.</h1>
  <b>Foreword: </b>The goal of those automated forms is to make the submission of positions and models into FG Scenery database faster, easier and better, in:
  <ul>
    <li>helping the developer submitting his positions and objects (time and process gain) nearly directly into Terrasync;</li>
    <li>helping the maintainers through an automated process (no human interactions, automated consistency checks).</li>
  </ul>
  Please read <a href="http://scenemodels.flightgear.org/contribute.php">this page</a> in order to understand what items those forms are looking for. All submissions are being followed and logged, so <b>DO NOT TAKE THIS</b> as a sandbox.

  <br/><br/>

  <table>
    <tr align="left">
      <td align="left">
        Now select the operation you would like to perform:<br />
        - on shared objects (eg windturbines, pylons, generic buildings...):
        <ul>
          <li><a href="shared/index.php">adding shared objects positions.</a></li>
          <li><a href="shared/index_delete.php">deleting shared objects positions</a> (delete an existing shared object).</li>
          <li><a href="shared/index_update.php">editing shared objects</a> (editing position, model, offset of an object...).</li>
          <li><a href="shared/index_mass_import.php">mass import tool</a> (adding tens of lines of positions in one click). Work in progress... (not in production yet).</li>
        </ul>
        - on static models (objects designed for a specific location):
        <ul>
          <li><a href="static/index.php">adding static models positions and objects</a> (add a specific static or shared 3D model to the database). Work In Progress.... (not in production yet).</li>
        </ul>
        Comments or contributions propositions are always welcome through the usual channels (<a href="http://sourceforge.net/mailarchive/forum.php?forum_name=flightgear-devel">devel list</a>, <a href="http://www.flightgear.org/forums/viewtopic.php?f=5&t=14671">forum</a>).
        <br/><br/>
      </td>
    </tr>
  </table>
<?php include 'inc/footer.php'; ?>
