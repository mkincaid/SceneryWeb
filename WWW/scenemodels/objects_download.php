<?php
require_once "classes/DAOFactory.php";
$modelDaoRO = DAOFactory::getInstance()->getModelDaoRO();

require 'inc/header.php';
?>
<h1>Scenery Object Downloads</h1>
<p>
  Click on any of the 10x10 degree chunks in the image below to download the
  objects for that area.<br/>
  An area with no corresponding link means that no objects are available in that
  area, or if a rebuild is in progress, that chunk may not yet be generated.
</p>
<p>
  Currently a global file is also available <a href="/download/GlobalObjects.tgz">here</a>.
</p>
<p>
  A collection of shared models can be downloaded
  <a href="/download/SharedModels.tgz">here</a>.<br/>
  (This file is <strong>*REQUIRED*</strong> if you want to see all the objects,
  and should be unpacked in your FGROOT directory)
</p>


<div class="center">
<img src="http://scenery.flightgear.org/img/download.png" width="720"
     height="450" usemap="#map" alt="Map"/>
<map name="map" id="map">
<area shape="rect" coords="360,200,380,225" href="/download/GlobalObjects.tgz" alt="GlobalObjects.tgz  3.97 Mb  10/15/2005"/>
<area shape="rect" coords="360,200,380,225" href="/download/SharedModels.tgz" alt="SharedModels.tgz  0.71 Mb  10/15/2005"/>
<area shape="rect" coords="360,200,380,225" href="/download/e000n00.tgz" alt="e000n00.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="360,175,380,200" href="/download/e000n10.tgz" alt="e000n10.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="360,150,380,175" href="/download/e000n20.tgz" alt="e000n20.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="360,125,380,150" href="/download/e000n30.tgz" alt="e000n30.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="360,100,380,125" href="/download/e000n40.tgz" alt="e000n40.tgz  0.52 Mb  10/15/2005"/>
<area shape="rect" coords="360,75,380,100" href="/download/e000n50.tgz" alt="e000n50.tgz  0.05 Mb  10/15/2005"/>
<area shape="rect" coords="360,50,380,75" href="/download/e000n60.tgz" alt="e000n60.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="360,225,380,250" href="/download/e000s10.tgz" alt="e000s10.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="380,200,400,225" href="/download/e010n00.tgz" alt="e010n00.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="380,175,400,200" href="/download/e010n10.tgz" alt="e010n10.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="380,150,400,175" href="/download/e010n20.tgz" alt="e010n20.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="380,125,400,150" href="/download/e010n30.tgz" alt="e010n30.tgz  0.31 Mb  10/15/2005"/>
<area shape="rect" coords="380,100,400,125" href="/download/e010n40.tgz" alt="e010n40.tgz  0.12 Mb  10/15/2005"/>
<area shape="rect" coords="380,75,400,100" href="/download/e010n50.tgz" alt="e010n50.tgz  0.07 Mb  10/15/2005"/>
<area shape="rect" coords="380,50,400,75" href="/download/e010n60.tgz" alt="e010n60.tgz  0.01 Mb  10/15/2005"/>
<area shape="rect" coords="380,25,400,50" href="/download/e010n70.tgz" alt="e010n70.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="380,225,400,250" href="/download/e010s10.tgz" alt="e010s10.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="380,250,400,275" href="/download/e010s20.tgz" alt="e010s20.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="380,275,400,300" href="/download/e010s30.tgz" alt="e010s30.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="380,300,400,325" href="/download/e010s40.tgz" alt="e010s40.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="400,200,420,225" href="/download/e020n00.tgz" alt="e020n00.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="400,175,420,200" href="/download/e020n10.tgz" alt="e020n10.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="400,150,420,175" href="/download/e020n20.tgz" alt="e020n20.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="400,125,420,150" href="/download/e020n30.tgz" alt="e020n30.tgz  0.02 Mb  10/15/2005"/>
<area shape="rect" coords="400,100,420,125" href="/download/e020n40.tgz" alt="e020n40.tgz  0.01 Mb  10/15/2005"/>
<area shape="rect" coords="400,75,420,100" href="/download/e020n50.tgz" alt="e020n50.tgz  0.05 Mb  10/15/2005"/>
<area shape="rect" coords="400,50,420,75" href="/download/e020n60.tgz" alt="e020n60.tgz  0.01 Mb  10/15/2005"/>
<area shape="rect" coords="400,25,420,50" href="/download/e020n70.tgz" alt="e020n70.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="400,225,420,250" href="/download/e020s10.tgz" alt="e020s10.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="400,250,420,275" href="/download/e020s20.tgz" alt="e020s20.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="400,275,420,300" href="/download/e020s30.tgz" alt="e020s30.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="400,300,420,325" href="/download/e020s40.tgz" alt="e020s40.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="420,200,440,225" href="/download/e030n00.tgz" alt="e030n00.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="420,175,440,200" href="/download/e030n10.tgz" alt="e030n10.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="420,150,440,175" href="/download/e030n20.tgz" alt="e030n20.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="420,125,440,150" href="/download/e030n30.tgz" alt="e030n30.tgz  0.01 Mb  10/15/2005"/>
<area shape="rect" coords="420,100,440,125" href="/download/e030n40.tgz" alt="e030n40.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="420,75,440,100" href="/download/e030n50.tgz" alt="e030n50.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="420,50,440,75" href="/download/e030n60.tgz" alt="e030n60.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="420,25,440,50" href="/download/e030n70.tgz" alt="e030n70.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="420,225,440,250" href="/download/e030s10.tgz" alt="e030s10.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="420,250,440,275" href="/download/e030s20.tgz" alt="e030s20.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="420,275,440,300" href="/download/e030s30.tgz" alt="e030s30.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="420,300,440,325" href="/download/e030s40.tgz" alt="e030s40.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="420,400,440,425" href="/download/e030s80.tgz" alt="e030s80.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="440,200,460,225" href="/download/e040n00.tgz" alt="e040n00.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="440,175,460,200" href="/download/e040n10.tgz" alt="e040n10.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="440,150,460,175" href="/download/e040n20.tgz" alt="e040n20.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="440,125,460,150" href="/download/e040n30.tgz" alt="e040n30.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="440,100,460,125" href="/download/e040n40.tgz" alt="e040n40.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="440,75,460,100" href="/download/e040n50.tgz" alt="e040n50.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="440,50,460,75" href="/download/e040n60.tgz" alt="e040n60.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="440,25,460,50" href="/download/e040n70.tgz" alt="e040n70.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="440,225,460,250" href="/download/e040s10.tgz" alt="e040s10.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="440,250,460,275" href="/download/e040s20.tgz" alt="e040s20.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="440,275,460,300" href="/download/e040s30.tgz" alt="e040s30.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="460,175,480,200" href="/download/e050n10.tgz" alt="e050n10.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="460,150,480,175" href="/download/e050n20.tgz" alt="e050n20.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="460,125,480,150" href="/download/e050n30.tgz" alt="e050n30.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="460,100,480,125" href="/download/e050n40.tgz" alt="e050n40.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="460,75,480,100" href="/download/e050n50.tgz" alt="e050n50.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="460,50,480,75" href="/download/e050n60.tgz" alt="e050n60.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="460,225,480,250" href="/download/e050s10.tgz" alt="e050s10.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="460,250,480,275" href="/download/e050s20.tgz" alt="e050s20.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="460,275,480,300" href="/download/e050s30.tgz" alt="e050s30.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="480,150,500,175" href="/download/e060n20.tgz" alt="e060n20.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="480,125,500,150" href="/download/e060n30.tgz" alt="e060n30.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="480,100,500,125" href="/download/e060n40.tgz" alt="e060n40.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="480,75,500,100" href="/download/e060n50.tgz" alt="e060n50.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="480,50,500,75" href="/download/e060n60.tgz" alt="e060n60.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="480,25,500,50" href="/download/e060n70.tgz" alt="e060n70.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="480,250,500,275" href="/download/e060s20.tgz" alt="e060s20.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="500,200,520,225" href="/download/e070n00.tgz" alt="e070n00.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="500,175,520,200" href="/download/e070n10.tgz" alt="e070n10.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="500,150,520,175" href="/download/e070n20.tgz" alt="e070n20.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="500,125,520,150" href="/download/e070n30.tgz" alt="e070n30.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="500,100,520,125" href="/download/e070n40.tgz" alt="e070n40.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="500,75,520,100" href="/download/e070n50.tgz" alt="e070n50.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="500,50,520,75" href="/download/e070n60.tgz" alt="e070n60.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="500,225,520,250" href="/download/e070s10.tgz" alt="e070s10.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="520,200,540,225" href="/download/e080n00.tgz" alt="e080n00.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="520,175,540,200" href="/download/e080n10.tgz" alt="e080n10.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="520,150,540,175" href="/download/e080n20.tgz" alt="e080n20.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="520,100,540,125" href="/download/e080n40.tgz" alt="e080n40.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="520,75,540,100" href="/download/e080n50.tgz" alt="e080n50.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="520,50,540,75" href="/download/e080n60.tgz" alt="e080n60.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="520,25,540,50" href="/download/e080n70.tgz" alt="e080n70.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="540,200,560,225" href="/download/e090n00.tgz" alt="e090n00.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="540,175,560,200" href="/download/e090n10.tgz" alt="e090n10.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="540,150,560,175" href="/download/e090n20.tgz" alt="e090n20.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="540,125,560,150" href="/download/e090n30.tgz" alt="e090n30.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="540,100,560,125" href="/download/e090n40.tgz" alt="e090n40.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="540,75,560,100" href="/download/e090n50.tgz" alt="e090n50.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="540,50,560,75" href="/download/e090n60.tgz" alt="e090n60.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="540,225,560,250" href="/download/e090s10.tgz" alt="e090s10.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="540,250,560,275" href="/download/e090s20.tgz" alt="e090s20.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="560,200,580,225" href="/download/e100n00.tgz" alt="e100n00.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="560,175,580,200" href="/download/e100n10.tgz" alt="e100n10.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="560,150,580,175" href="/download/e100n20.tgz" alt="e100n20.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="560,125,580,150" href="/download/e100n30.tgz" alt="e100n30.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="560,100,580,125" href="/download/e100n40.tgz" alt="e100n40.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="560,75,580,100" href="/download/e100n50.tgz" alt="e100n50.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="560,50,580,75" href="/download/e100n60.tgz" alt="e100n60.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="560,25,580,50" href="/download/e100n70.tgz" alt="e100n70.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="560,225,580,250" href="/download/e100s10.tgz" alt="e100s10.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="560,250,580,275" href="/download/e100s20.tgz" alt="e100s20.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="580,200,600,225" href="/download/e110n00.tgz" alt="e110n00.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="580,175,600,200" href="/download/e110n10.tgz" alt="e110n10.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="580,150,600,175" href="/download/e110n20.tgz" alt="e110n20.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="580,125,600,150" href="/download/e110n30.tgz" alt="e110n30.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="580,100,600,125" href="/download/e110n40.tgz" alt="e110n40.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="580,75,600,100" href="/download/e110n50.tgz" alt="e110n50.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="580,50,600,75" href="/download/e110n60.tgz" alt="e110n60.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="580,225,600,250" href="/download/e110s10.tgz" alt="e110s10.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="580,275,600,300" href="/download/e110s30.tgz" alt="e110s30.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="580,300,600,325" href="/download/e110s40.tgz" alt="e110s40.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="600,200,620,225" href="/download/e120n00.tgz" alt="e120n00.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="600,175,620,200" href="/download/e120n10.tgz" alt="e120n10.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="600,150,620,175" href="/download/e120n20.tgz" alt="e120n20.tgz  0.21 Mb  10/15/2005"/>
<area shape="rect" coords="600,125,620,150" href="/download/e120n30.tgz" alt="e120n30.tgz  0.01 Mb  10/15/2005"/>
<area shape="rect" coords="600,100,620,125" href="/download/e120n40.tgz" alt="e120n40.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="600,75,620,100" href="/download/e120n50.tgz" alt="e120n50.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="600,50,620,75" href="/download/e120n60.tgz" alt="e120n60.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="600,25,620,50" href="/download/e120n70.tgz" alt="e120n70.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="600,225,620,250" href="/download/e120s10.tgz" alt="e120s10.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="600,250,620,275" href="/download/e120s20.tgz" alt="e120s20.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="600,275,620,300" href="/download/e120s30.tgz" alt="e120s30.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="600,300,620,325" href="/download/e120s40.tgz" alt="e120s40.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="620,200,640,225" href="/download/e130n00.tgz" alt="e130n00.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="620,150,640,175" href="/download/e130n20.tgz" alt="e130n20.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="620,125,640,150" href="/download/e130n30.tgz" alt="e130n30.tgz  0.01 Mb  10/15/2005"/>
<area shape="rect" coords="620,100,640,125" href="/download/e130n40.tgz" alt="e130n40.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="620,75,640,100" href="/download/e130n50.tgz" alt="e130n50.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="620,50,640,75" href="/download/e130n60.tgz" alt="e130n60.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="620,225,640,250" href="/download/e130s10.tgz" alt="e130s10.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="620,250,640,275" href="/download/e130s20.tgz" alt="e130s20.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="620,275,640,300" href="/download/e130s30.tgz" alt="e130s30.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="620,300,640,325" href="/download/e130s40.tgz" alt="e130s40.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="640,175,660,200" href="/download/e140n10.tgz" alt="e140n10.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="640,150,660,175" href="/download/e140n20.tgz" alt="e140n20.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="640,125,660,150" href="/download/e140n30.tgz" alt="e140n30.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="640,100,660,125" href="/download/e140n40.tgz" alt="e140n40.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="640,75,660,100" href="/download/e140n50.tgz" alt="e140n50.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="640,25,660,50" href="/download/e140n70.tgz" alt="e140n70.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="640,225,660,250" href="/download/e140s10.tgz" alt="e140s10.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="640,250,660,275" href="/download/e140s20.tgz" alt="e140s20.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="640,275,660,300" href="/download/e140s30.tgz" alt="e140s30.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="640,300,660,325" href="/download/e140s40.tgz" alt="e140s40.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="640,325,660,350" href="/download/e140s50.tgz" alt="e140s50.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="660,200,680,225" href="/download/e150n00.tgz" alt="e150n00.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="660,150,680,175" href="/download/e150n20.tgz" alt="e150n20.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="660,75,680,100" href="/download/e150n50.tgz" alt="e150n50.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="660,50,680,75" href="/download/e150n60.tgz" alt="e150n60.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="660,225,680,250" href="/download/e150s10.tgz" alt="e150s10.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="660,250,680,275" href="/download/e150s20.tgz" alt="e150s20.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="660,275,680,300" href="/download/e150s30.tgz" alt="e150s30.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="660,300,680,325" href="/download/e150s40.tgz" alt="e150s40.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="680,200,700,225" href="/download/e160n00.tgz" alt="e160n00.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="680,175,700,200" href="/download/e160n10.tgz" alt="e160n10.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="680,75,700,100" href="/download/e160n50.tgz" alt="e160n50.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="680,50,700,75" href="/download/e160n60.tgz" alt="e160n60.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="680,225,700,250" href="/download/e160s10.tgz" alt="e160s10.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="680,250,700,275" href="/download/e160s20.tgz" alt="e160s20.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="680,275,700,300" href="/download/e160s30.tgz" alt="e160s30.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="680,325,700,350" href="/download/e160s50.tgz" alt="e160s50.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="680,400,700,425" href="/download/e160s80.tgz" alt="e160s80.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="700,200,720,225" href="/download/e170n00.tgz" alt="e170n00.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="700,75,720,100" href="/download/e170n50.tgz" alt="e170n50.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="700,50,720,75" href="/download/e170n60.tgz" alt="e170n60.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="700,225,720,250" href="/download/e170s10.tgz" alt="e170s10.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="700,250,720,275" href="/download/e170s20.tgz" alt="e170s20.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="700,300,720,325" href="/download/e170s40.tgz" alt="e170s40.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="700,325,720,350" href="/download/e170s50.tgz" alt="e170s50.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="340,200,360,225" href="/download/w010n00.tgz" alt="w010n00.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="340,175,360,200" href="/download/w010n10.tgz" alt="w010n10.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="340,150,360,175" href="/download/w010n20.tgz" alt="w010n20.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="340,125,360,150" href="/download/w010n30.tgz" alt="w010n30.tgz  0.01 Mb  10/15/2005"/>
<area shape="rect" coords="340,100,360,125" href="/download/w010n40.tgz" alt="w010n40.tgz  0.01 Mb  10/15/2005"/>
<area shape="rect" coords="340,75,360,100" href="/download/w010n50.tgz" alt="w010n50.tgz  0.54 Mb  10/15/2005"/>
<area shape="rect" coords="340,50,360,75" href="/download/w010n60.tgz" alt="w010n60.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="340,25,360,50" href="/download/w010n70.tgz" alt="w010n70.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="320,200,340,225" href="/download/w020n00.tgz" alt="w020n00.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="320,175,340,200" href="/download/w020n10.tgz" alt="w020n10.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="320,150,340,175" href="/download/w020n20.tgz" alt="w020n20.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="320,125,340,150" href="/download/w020n30.tgz" alt="w020n30.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="320,50,340,75" href="/download/w020n60.tgz" alt="w020n60.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="320,225,340,250" href="/download/w020s10.tgz" alt="w020s10.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="300,175,320,200" href="/download/w030n10.tgz" alt="w030n10.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="300,125,320,150" href="/download/w030n30.tgz" alt="w030n30.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="300,50,320,75" href="/download/w030n60.tgz" alt="w030n60.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="300,25,320,50" href="/download/w030n70.tgz" alt="w030n70.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="280,125,300,150" href="/download/w040n30.tgz" alt="w040n30.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="280,50,300,75" href="/download/w040n60.tgz" alt="w040n60.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="280,225,300,250" href="/download/w040s10.tgz" alt="w040s10.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="280,250,300,275" href="/download/w040s20.tgz" alt="w040s20.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="260,50,280,75" href="/download/w050n60.tgz" alt="w050n60.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="260,225,280,250" href="/download/w050s10.tgz" alt="w050s10.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="260,250,280,275" href="/download/w050s20.tgz" alt="w050s20.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="260,275,280,300" href="/download/w050s30.tgz" alt="w050s30.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="260,375,280,400" href="/download/w050s70.tgz" alt="w050s70.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="240,200,260,225" href="/download/w060n00.tgz" alt="w060n00.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="240,175,260,200" href="/download/w060n10.tgz" alt="w060n10.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="240,100,260,125" href="/download/w060n40.tgz" alt="w060n40.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="240,75,260,100" href="/download/w060n50.tgz" alt="w060n50.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="240,50,260,75" href="/download/w060n60.tgz" alt="w060n60.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="240,25,260,50" href="/download/w060n70.tgz" alt="w060n70.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="240,225,260,250" href="/download/w060s10.tgz" alt="w060s10.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="240,250,260,275" href="/download/w060s20.tgz" alt="w060s20.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="240,275,260,300" href="/download/w060s30.tgz" alt="w060s30.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="240,300,260,325" href="/download/w060s40.tgz" alt="w060s40.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="240,350,260,375" href="/download/w060s60.tgz" alt="w060s60.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="240,375,260,400" href="/download/w060s70.tgz" alt="w060s70.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="220,200,240,225" href="/download/w070n00.tgz" alt="w070n00.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="220,175,240,200" href="/download/w070n10.tgz" alt="w070n10.tgz  0.01 Mb  10/15/2005"/>
<area shape="rect" coords="220,125,240,150" href="/download/w070n30.tgz" alt="w070n30.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="220,100,240,125" href="/download/w070n40.tgz" alt="w070n40.tgz  0.02 Mb  10/15/2005"/>
<area shape="rect" coords="220,75,240,100" href="/download/w070n50.tgz" alt="w070n50.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="220,50,240,75" href="/download/w070n60.tgz" alt="w070n60.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="220,25,240,50" href="/download/w070n70.tgz" alt="w070n70.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="220,0,240,25" href="/download/w070n80.tgz" alt="w070n80.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="220,225,240,250" href="/download/w070s10.tgz" alt="w070s10.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="220,250,240,275" href="/download/w070s20.tgz" alt="w070s20.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="220,275,240,300" href="/download/w070s30.tgz" alt="w070s30.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="220,300,240,325" href="/download/w070s40.tgz" alt="w070s40.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="220,325,240,350" href="/download/w070s50.tgz" alt="w070s50.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="220,350,240,375" href="/download/w070s60.tgz" alt="w070s60.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="220,375,240,400" href="/download/w070s70.tgz" alt="w070s70.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="200,200,220,225" href="/download/w080n00.tgz" alt="w080n00.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="200,175,220,200" href="/download/w080n10.tgz" alt="w080n10.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="200,150,220,175" href="/download/w080n20.tgz" alt="w080n20.tgz  0.01 Mb  10/15/2005"/>
<area shape="rect" coords="200,125,220,150" href="/download/w080n30.tgz" alt="w080n30.tgz  0.10 Mb  10/15/2005"/>
<area shape="rect" coords="200,100,220,125" href="/download/w080n40.tgz" alt="w080n40.tgz  0.15 Mb  10/15/2005"/>
<area shape="rect" coords="200,75,220,100" href="/download/w080n50.tgz" alt="w080n50.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="200,50,220,75" href="/download/w080n60.tgz" alt="w080n60.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="200,25,220,50" href="/download/w080n70.tgz" alt="w080n70.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="200,225,220,250" href="/download/w080s10.tgz" alt="w080s10.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="200,250,220,275" href="/download/w080s20.tgz" alt="w080s20.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="200,275,220,300" href="/download/w080s30.tgz" alt="w080s30.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="200,300,220,325" href="/download/w080s40.tgz" alt="w080s40.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="200,325,220,350" href="/download/w080s50.tgz" alt="w080s50.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="200,350,220,375" href="/download/w080s60.tgz" alt="w080s60.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="180,200,200,225" href="/download/w090n00.tgz" alt="w090n00.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="180,175,200,200" href="/download/w090n10.tgz" alt="w090n10.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="180,150,200,175" href="/download/w090n20.tgz" alt="w090n20.tgz  0.06 Mb  10/15/2005"/>
<area shape="rect" coords="180,125,200,150" href="/download/w090n30.tgz" alt="w090n30.tgz  0.33 Mb  10/15/2005"/>
<area shape="rect" coords="180,100,200,125" href="/download/w090n40.tgz" alt="w090n40.tgz  0.22 Mb  10/15/2005"/>
<area shape="rect" coords="180,75,200,100" href="/download/w090n50.tgz" alt="w090n50.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="180,50,200,75" href="/download/w090n60.tgz" alt="w090n60.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="180,25,200,50" href="/download/w090n70.tgz" alt="w090n70.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="180,225,200,250" href="/download/w090s10.tgz" alt="w090s10.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="160,175,180,200" href="/download/w100n10.tgz" alt="w100n10.tgz  0.01 Mb  10/15/2005"/>
<area shape="rect" coords="160,150,180,175" href="/download/w100n20.tgz" alt="w100n20.tgz  0.07 Mb  10/15/2005"/>
<area shape="rect" coords="160,125,180,150" href="/download/w100n30.tgz" alt="w100n30.tgz  0.28 Mb  10/15/2005"/>
<area shape="rect" coords="160,100,180,125" href="/download/w100n40.tgz" alt="w100n40.tgz  0.14 Mb  10/15/2005"/>
<area shape="rect" coords="160,75,180,100" href="/download/w100n50.tgz" alt="w100n50.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="160,50,180,75" href="/download/w100n60.tgz" alt="w100n60.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="160,25,180,50" href="/download/w100n70.tgz" alt="w100n70.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="160,225,180,250" href="/download/w100s10.tgz" alt="w100s10.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="140,175,160,200" href="/download/w110n10.tgz" alt="w110n10.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="140,150,160,175" href="/download/w110n20.tgz" alt="w110n20.tgz  0.01 Mb  10/15/2005"/>
<area shape="rect" coords="140,125,160,150" href="/download/w110n30.tgz" alt="w110n30.tgz  0.10 Mb  10/15/2005"/>
<area shape="rect" coords="140,100,160,125" href="/download/w110n40.tgz" alt="w110n40.tgz  0.04 Mb  10/15/2005"/>
<area shape="rect" coords="140,75,160,100" href="/download/w110n50.tgz" alt="w110n50.tgz  0.01 Mb  10/15/2005"/>
<area shape="rect" coords="140,50,160,75" href="/download/w110n60.tgz" alt="w110n60.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="140,275,160,300" href="/download/w110s30.tgz" alt="w110s30.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="120,150,140,175" href="/download/w120n20.tgz" alt="w120n20.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="120,125,140,150" href="/download/w120n30.tgz" alt="w120n30.tgz  0.09 Mb  10/15/2005"/>
<area shape="rect" coords="120,100,140,125" href="/download/w120n40.tgz" alt="w120n40.tgz  0.04 Mb  10/15/2005"/>
<area shape="rect" coords="120,75,140,100" href="/download/w120n50.tgz" alt="w120n50.tgz  0.01 Mb  10/15/2005"/>
<area shape="rect" coords="120,50,140,75" href="/download/w120n60.tgz" alt="w120n60.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="120,25,140,50" href="/download/w120n70.tgz" alt="w120n70.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="100,125,120,150" href="/download/w130n30.tgz" alt="w130n30.tgz  0.04 Mb  10/15/2005"/>
<area shape="rect" coords="100,100,120,125" href="/download/w130n40.tgz" alt="w130n40.tgz  0.04 Mb  10/15/2005"/>
<area shape="rect" coords="100,75,120,100" href="/download/w130n50.tgz" alt="w130n50.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="100,50,120,75" href="/download/w130n60.tgz" alt="w130n60.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="100,25,120,50" href="/download/w130n70.tgz" alt="w130n70.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="80,75,100,100" href="/download/w140n50.tgz" alt="w140n50.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="80,50,100,75" href="/download/w140n60.tgz" alt="w140n60.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="80,225,100,250" href="/download/w140s10.tgz" alt="w140s10.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="80,250,100,275" href="/download/w140s20.tgz" alt="w140s20.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="80,275,100,300" href="/download/w140s30.tgz" alt="w140s30.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="60,75,80,100" href="/download/w150n50.tgz" alt="w150n50.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="60,50,80,75" href="/download/w150n60.tgz" alt="w150n60.tgz  0.01 Mb  10/15/2005"/>
<area shape="rect" coords="60,25,80,50" href="/download/w150n70.tgz" alt="w150n70.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="60,225,80,250" href="/download/w150s10.tgz" alt="w150s10.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="60,250,80,275" href="/download/w150s20.tgz" alt="w150s20.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="60,275,80,300" href="/download/w150s30.tgz" alt="w150s30.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="40,200,60,225" href="/download/w160n00.tgz" alt="w160n00.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="40,175,60,200" href="/download/w160n10.tgz" alt="w160n10.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="40,150,60,175" href="/download/w160n20.tgz" alt="w160n20.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="40,75,60,100" href="/download/w160n50.tgz" alt="w160n50.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="40,50,60,75" href="/download/w160n60.tgz" alt="w160n60.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="40,25,60,50" href="/download/w160n70.tgz" alt="w160n70.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="40,225,60,250" href="/download/w160s10.tgz" alt="w160s10.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="40,250,60,275" href="/download/w160s20.tgz" alt="w160s20.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="40,275,60,300" href="/download/w160s30.tgz" alt="w160s30.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="20,75,40,100" href="/download/w170n50.tgz" alt="w170n50.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="20,50,40,75" href="/download/w170n60.tgz" alt="w170n60.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="20,25,40,50" href="/download/w170n70.tgz" alt="w170n70.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="20,250,40,275" href="/download/w170s20.tgz" alt="w170s20.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="0,150,20,175" href="/download/w180n20.tgz" alt="w180n20.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="0,75,20,100" href="/download/w180n50.tgz" alt="w180n50.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="0,50,20,75" href="/download/w180n60.tgz" alt="w180n60.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="0,225,20,250" href="/download/w180s10.tgz" alt="w180s10.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="0,250,20,275" href="/download/w180s20.tgz" alt="w180s20.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="0,275,20,300" href="/download/w180s30.tgz" alt="w180s30.tgz  0.00 Mb  10/15/2005"/>
<area shape="rect" coords="0,325,20,350" href="/download/w180s50.tgz" alt="w180s50.tgz  0.00 Mb  10/15/2005"/>
</map>
</div>
<p>
  In your scenery directory create directories called Objects and Terrain.
</p>
<p>
  Unpack the files from this site in the Objects directory, and the 
  <a href="scenery_download.php">FlightGear scenery</a> files into the Terrain 
  directory.
</p>
<?php require 'inc/footer.php'; ?>
