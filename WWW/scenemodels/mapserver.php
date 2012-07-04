<?php
$page_title = "TelaScience / OSGeo / FlightGear Landcover Database Mapserver";
$body_onload = "init()";
include 'inc/header.php';
?>
<h1>TelaScience / OSGeo / FlightGear Landcover Database Mapserver</h1>
<center>
    Please read the corresponding
    <a href="http://wiki.osgeo.org/wiki/Geodata_Repository#PostGIS_serving_vector_data">NOTES</a>
    on the fine <a href="http://www.osgeo.org/">OSGeo</a> Wiki -<br />
    and visit our sister projects at
    <a href="http://www.custom-scenery.org/">Custom Scenery Project</a> as well as
    <a href="http://scenemodels.flightgear.org/models.php">Scenery Model Repository</a>.
    <p>
    The latest reference to Custom Scenery land cover classes is available on
    <a href="http://wiki.osgeo.org/wiki/LandcoverDB_CS_Detail">this page</a>.
    </p>
</center>
    <hr />
<!-- include 'include/locateIp.php'; -->
    <table style="border-style: solid; border-width: 1px;" cellpadding="1" cellspacing="1" rules="rows">
      <tr>
      <td>
        <iframe
          src="http://mapserver.flightgear.org/lightmap/?lon=-117.12099&amp;lat=32.73356&amp;zoom=12"
          width="450" height="450"
          scrolling="no"
          marginwidth="2" marginheight="2"
          frameborder="0">
        </iframe>
      </td>
      <td bgcolor="#DDDDDD"><center>
        <p style="border:1px solid; padding: 5px; background-color: white; border-color:grey;">
        <a href="/shpdl/">Download Shapefiles</a>
        </p></center>
      </td>
      </tr>
    </table>
    <br />
    <a href="http://en.wikipedia.org/wiki/ICAO_airport_code">ICAO airport codes From Wikipedia</a>
    <br />
    <a href="http://worldaerodata.com/countries/">ICAO airport codes From WorldAeroData.com</a>
    <br />
    <hr width="42">
    WMS: <a href="http://mapserver.flightgear.org/ms?Service=WMS&amp;Version=1.1.1&amp;request=GetCapabilities">http://mapserver.flightgear.org/ms?Service=WMS&amp;Version=1.1.1&amp;request=GetCapabilities</a>
    <br />
    WFS: <a href="http://mapserver.flightgear.org/ms?Service=WFS&amp;Version=1.0.0&amp;request=GetCapabilities">http://mapserver.flightgear.org/ms?Service=WFS&amp;Version=1.0.0&amp;request=GetCapabilities</a>
    <br />
    TileCache: <a href="http://mapserver.flightgear.org/tc">http://mapserver.flightgear.org/tc</a>
    (EPSG:900913 !!)
    <hr />
    </center>
    <table style="border-style: solid; border-width: 1px;" cellpadding="1" cellspacing="1" rules="rows">

    <form method="post" action="http://mapserver.flightgear.org/icaolayers.php">
      <tr>
        <td>Airport code - OpenLayers:</td>
        <td></td>
        <td>  ICAO:</td><td> <input type="text" size="4" maxlength="4" name="icao"></td>
        <td>  <input type="submit" name="senden" value="ICAO"></td>
      </tr>
    </form>

    <form method="post" action="http://mapserver.flightgear.org/lonlatlayers.php">
      <tr>
        <td>  OpenLayers - Longitude:</td><td> <input type="text" size="8" maxlength="8" name="lon"></td>
        <td>  Latitude:</td><td> <input type="text" size="8" maxlength="8" name="lat"></td>
        <td>  <input type="submit" name="senden" value="LON/LAT"></td>
      </tr>
    </form>

    <tr><td></td></tr>

    <form method="post" action="http://mapserver.flightgear.org/icaomap.php">
      <tr>
        <td>Airport code - MS template view:</td>
        <td></td>
        <td>  ICAO:</td><td> <input type="text" size="4" maxlength="4" name="icao"/></td>
        <td>  <input type="submit" name="senden" value="ICAO"/></td>
      </tr>
    </form>

    <form method="post" action="http://mapserver.flightgear.org/lonlatmap.php">
      <tr>
        <td>  MS template view - Longitude:</td><td> <input type="text" size="8" maxlength="8" name="lon"/></td>
        <td>  Latitude:</td><td> <input type="text" size="8" maxlength="8" name="lat"/></td>
        <td>  <input type="submit" name="senden" value="LON/LAT"/></td>
      </tr>
    </form>

    <tr><td></td></tr>

    <form method="post" action="http://mapserver.flightgear.org/map/?lon=9.20438&amp;lat=47.63982&amp;zoom=11">
      <tr>
        <td>Bodensee <a href="http://www.custom-scenery.org/Satellite-Image.304.0.html">Custom Scenery</a> OpenLayers Demo</td>
        <td></td>
        <td></td>
        <td></td>
        <td>  <input type="submit" name="senden" value="OpenLayers EDNY"/></td>
      </tr>
    </form>

    <form method="post" action="http://mapserver.flightgear.org/map/?lon=-62.87648&amp;lat=17.69865&amp;zoom=10">
      <tr>
        <td>Caribbean <a href="http://www.custom-scenery.org/Satellite-Image.304.0.html">Custom Scenery</a> OpenLayers Demo</td>
        <td></td>
        <td></td>
        <td></td>
        <td>  <input type="submit" name="senden" value="OpenLayers TKPK"/></td>
      </tr>
    </form>

    <form method="post" action="http://mapserver.flightgear.org/map/?lon=-156.34932&amp;lat=20.76679&amp;zoom=11">
      <tr>
        <td>Kahului <a href="http://www.custom-scenery.org/Satellite-Image.304.0.html">Custom Scenery</a> OpenLayers Demo</td>
        <td></td>
        <td></td>
        <td></td>
        <td>  <input type="submit" name="senden" value="OpenLayers PHOG"/></td>
      </tr>
    </form>

    <form method="post" action="http://mapserver.flightgear.org/map/?lon=-0.35721&amp;lat=51.44879&amp;zoom=11&amp">
      <tr>
        <td>London partially <a href="http://www.custom-scenery.org/Satellite-Image.304.0.html">Custom Scenery</a> OpenLayers Demo</td>
        <td></td>
        <td></td>
        <td></td>
        <td>  <input type="submit" name="senden" value="OpenLayers EGLL"/></td>
      </tr>
    </form>

    <form method="post" action="http://mapserver.flightgear.org/map/?lon=-122.25243&amp;lat=37.63058&amp;zoom=11&amp">
      <tr>
        <td>San Francisco Bay partially <a href="http://www.custom-scenery.org/Satellite-Image.304.0.html">Custom Scenery</a> OpenLayers Demo</td>
        <td></td>
        <td></td>
        <td></td>
        <td>  <input type="submit" name="senden" value="OpenLayers SFO Bay"/></td>
      </tr>
    </form>

    <form method="post" action="http://mapserver.flightgear.org/map/?lon=-122.37563&amp;lat=37.61927&amp;zoom=15&amp">
      <tr>
        <td>Detailed KSFO <a href="http://www.mapability.com/info/vmap0_intro.html">VMap0</a> OpenLayers Demo</td>
        <td></td>
        <td></td>
        <td></td>
        <td>  <input type="submit" name="senden" value="OpenLayers KSFO"/></td>
      </tr>
    </form>

    <tr><td></td></tr>

    <form method="post" action="http://mapserver.flightgear.org/berlin.php">
      <tr>
        <td>Berlin - <a href="http://www.custom-scenery.org/Satellite-Image.304.0.html">Custom Scenery</a></td>
        <td></td>
        <td></td>
        <td></td>
        <td>  <input type="submit" name="senden" value="Berlin"/></td>
      </tr>
    </form>

    <form method="post" action="http://mapserver.flightgear.org/sandiego.php">
      <tr>
        <td>San Diego</td>
        <td></td>
        <td></td>
        <td></td>
        <td>  <input type="submit" name="senden" value="San Diego"/></td>
      </tr>
    </form>

    <form method="post" action="http://mapserver.flightgear.org/neworleans.php">
      <tr>
        <td>New Orleans / Mississippi Delta</td>
        <td></td>
        <td></td>
        <td></td>
        <td>  <input type="submit" name="senden" value="New Orleans"/></td>
      </tr>
    </form>

    <form method="post" action="http://mapserver.flightgear.org/defaultmap.php">
      <tr>
        <td>Bodensee</td>
        <td></td>
        <td></td>
        <td></td>
        <td>  <input type="submit" name="senden" value="Default"></td>
      </tr>
    </form>

    <tr><td></td></tr>

    <form method="get" action="http://mapserver.flightgear.org/dist.php">
    <tr>
      <td>  ICAO:</td><td> <input type="text" size="4" maxlength="4" name="icao1"></td>
      <td>  ICAO:</td><td> <input type="text" size="4" maxlength="4" name="icao2"></td>
      <td>  <input type="submit" value="Distance"/>  between two airfields</td>
    </tr>
    </form>
  </table>
  <hr />
  In order to retrieve airfield locations, please use this syntax:<br />
  http://mapserver.flightgear.org/loc.php?icao=&#60add your ICAO code here&#62
  </center>
  <hr />
  <center>
  World Custom Scenery - <a href="/git/">TerraGear GIT repository</a>
  </center>
  <hr />
  <center>
  <p>Read about the background of the whole effort. The pages are a bit
  outdated but the bottom line is still valid
  <br />
  <a href="http://www.custom-scenery.org/Landcover-DB.212.0.html">Custom-Scenery.org: Landcover DB</a>

  </center>
<?php
include('inc/footer.php');
?>
