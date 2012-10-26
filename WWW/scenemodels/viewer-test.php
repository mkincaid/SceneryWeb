<!doctype html>
<html>
  <head>

    <style>

      body {

        color: #6b6b6b;
        font-family: ubuntu, helvetica, arial, sans, sans-serif;

      }

      a {

        color: #3194d5;

      }

      @media (max-device-width: 480px) {

          #wrapper #support h1 {
            font-weight: bold;
            font-size: 20px;
            margin: 10px 0px;
          }
          #wrapper #support .webgl-div div {
            margin: 10px 10px;    
          }
          #wrapper #support #logo-container {
            text-align: center;
          }
          #wrapper #support canvas {
            margin: 10px 0px 10px 0px;
          }
          #wrapper hr {
            margin: 10px 0px;
          }
          #wrapper #moreinfo {
            margin: 10px 0px 0px 0px;
          }

      }

      @media (min-device-width: 600px) {

          #wrapper #support h1 {
            font-weight: normal;
            font-size: 40px;
            margin: 40px 0px;
          }

          #wrapper #support {
            text-align: center;
          }
    
          #wrapper #support canvas {
            margin: 30px 0px 10px 0px;
          }
    
          #wrapper hr {
            margin: 40px 0px;
          }

          #wrapper {
            width: 600px;
          }

          #wrapper #moreinfo {
            width: 250px;
            margin: 0px 20px 0px 20px;
            float: left;
          }

          #wrapper #resources {
            width: 250px;
            height: 150px;
            margin: 0px 20px 0px 40px;
            float: left;
          }

          #wrapper #support .webgl-div div {
            margin: 20px 100px;    
          }
      }

      #wrapper {
        margin: auto;
      }

      #wrapper hr {
        border-top: solid #e3e3e3;
        border-width: 1px 0px 0px 0px;
        height: 0px;
      }

      #wrapper #support h1 {
        color: #33a933;
      }

      #wrapper #resources div {
        font-size: 13px;
      }

      #wrapper #moreinfo div {
        font-size: 13px;
      }

      .webgl-hidden {
          display: none;
      }

      #webgl-browser-list {
          white-space: nowrap;
      }

    </style>

<?php
if (isset($_REQUEST['id']) && (preg_match('/^[0-9]+$/u',$_GET['id']))) {
    $id = $_REQUEST['id'];
}
?>

<script type="text/javascript">
function $$(x) {
    return document.getElementById(x);
}

var Models = [
  { file: "get_ac3d_from_dir.php?id=<?php echo rawurlencode($id); ?>"}
];

var canvas, details, loading, viewer, current, gl;

function launchLogo() {
    details = document.getElementById("details");
    loading = document.getElementById("loading");
    viewer = new HG.Viewer(canvas);
    current = 0;

    resize();
    showModel(Models[current]);
}

function log(msg) {
    var d = document.createElement("div");
    d.appendChild(document.createTextNode(msg));
    document.body.appendChild(d);
}

function removeClass(element, clas) {
    // Does not work in IE var classes = element.getAttribute("class");
    var classes = element.className;
    if (classes) {
        var cs = classes.split(/\s+/);
        if (cs.indexOf(clas) != -1) {
            cs.splice(cs.indexOf(clas), 1);
        }
        // Does not work in IE element.setAttribute("class", cs.join(" "));
        element.className = cs.join(" ");
    }
}

function addClass(element, clas) {
    element.className = element.className + " " + clas;
}

function pageLoaded() {
    removeClass($$("have-javascript"), "webgl-hidden");
    addClass($$("no-javascript"), "webgl-hidden");
    canvas = document.getElementById("canvas");
    var ratio = (window.devicePixelRatio ? window.devicePixelRatio : 1);
    canvas.width = 140 * ratio;
    canvas.height = 150 * ratio;
    var experimental = false;
    try { gl = canvas.getContext("webgl"); }
    catch (x) { gl = null; }

    if (gl == null) {
        try { gl = canvas.getContext("experimental-webgl"); experimental = true; }
        catch (x) { gl = null; }
    }

    if (gl) {
        // hide/show phrase for webgl-experimental
        $$("webgl-experimental").style.display = experimental ? "auto" : "none";

        // show webgl supported div, and launch webgl demo
        removeClass($$("webgl-yes"), "webgl-hidden");
        launchLogo();
    } else if ("WebGLRenderingContext" in window) {
        // not a foolproof way to check if the browser
        // might actually support WebGL, but better than nothing
        removeClass($$("webgl-disabled"), "webgl-hidden");
    } else {
        // Show the no webgl message.
        removeClass($$("webgl-no"), "webgl-hidden");
    }
}

function resize(){
  canvas.width = window.innerWidth;
  canvas.height = window.innerHeight;

  window.addEventListener("resize",
    function(event){
      viewer.onResize(window.innerWidth, window.innerHeight);
    }, false);
};

function showModel(model){
  loading.style.display = "block";
  viewer.show(model.file, {callback:onLoaded, texturePath:"get_texture_by_filename.php?id=<?php echo rawurlencode($id); ?>&name="});
};

function onLoaded(){
  loading.style.display = "none";
};

// addEventListener does not work on IE7/8.
window.onload = pageLoaded;
</script>
  </head>
  <body>
    <div id="wrapper">
      <div id="support">

        <div class="webgl-hidden" id="have-javascript">
          <div class="webgl-hidden webgl-div" id="webgl-yes">
            <h1 class="good">Your browser supports WebGL</h1>

            <div id="webgl-experimental">However, it indicates that support is
            experimental; you might see issues with some content.</div>

            <div>You should see a spinning cube. If you do not, please
            <a id="support-link">visit the support site for your browser</a>.</div>

            <div id="logo-container">
            <canvas id="canvas" style="width: 140px; height: 150px;" /></canvas>
            </div>
          </div>

          <div class="webgl-hidden webgl-div" id="webgl-disabled">
            <p>Hmm.  While your browser seems to support WebGL, it is disabled or unavailable.  If possible, please ensure that you are running the latest drivers for your video card.</p>
            <p id="known-browser" class="webgl-hidden"><a id="troubleshooting-link" href="">For more help, please click this link</a>.</p>
            <p id="unknown-browser">For more help, please visit the support site for your browser.</p>
          </div>

          <div class="webgl-hidden webgl-div" id="webgl-no">
            <p>Oh no!  We are sorry, but your browser does not seem to support WebGL.</p>
            <div id="upgrade-browser">
            <p><a id="upgrade-link" href="">You can upgrade <span id="name"></span> by clicking this link.</a></p>
            </div>
            <div id="get-browser" class="webgl-hidden">
            <p>You may want to download one of the following browsers to view WebGL content.</p>

            <p>The following browsers support WebGL on <span id="platform"></span>:</p>

              <div id="webgl-browser-list">
              </div>
            </div>
          </div>

        </div>
        <div id="no-javascript">
          You must enable JavaScript to use WebGL.
        </div>

      </div>
      <hr />
      <div id="resources">

        <div>Check out some of the following links to learn
        more about WebGL and to find more web applications
        using WebGL.</div><br />

        <div><a href="http://www.khronos.org/webgl/wiki/Main_Page">WebGL Wiki</a></div>
 
      </div>
      <div id="moreinfo">
        <div>Want more information about WebGL?</div><br />

        <div><a href="http://khronos.org/webgl">khronos.org/webgl</a></div>
      </div>
    </div>
  </div>

  </body>
</html>
