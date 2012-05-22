<?php
  header('content-type: text/css');
  header('HTTP/1.0 304 Not Modified');
  header('Cache-Control: max-age=3600, must-revalidate');
?>

/*###################################################################*/
/*                                MENU                               */
/*###################################################################*/

#menu{
  float: left;
  width: 230px;
  height: 530px;
}

#menu h4{
  -moz-border-bottom-colors: none;
  -moz-border-image: none;
  -moz-border-left-colors: none;
  -moz-border-right-colors: none;
  -moz-border-top-colors: none;
  background-color: #FBFBFB;
  border-color: #CCCCCC;
  border-style: solid;
  border-width: 1px 0;
  margin: 10px -14px;
}

#menu h4 span{
  background-color: #EBEBEB;
  display: block;
  font-family: Verdana, Geneva, sans-serif;
  font-size: 16px;
  margin: 1px 0;
  padding: 6px 15px;
}

#menu div.t{
  background: url("<?php echo $_SERVER['HTTP_HOST']; ?>/template/sidebars.png") no-repeat scroll -500px 0 transparent;
  height: 170px;
  margin-right: 15px;
}

#menu div.t div{
  background: url("<?php echo $_SERVER['HTTP_HOST']; ?>/template/sidebars.png") no-repeat scroll -985px 0 transparent;
  float: right;
  height: 170px;
  margin-right: -15px;
  width: 15px;
}

#menu div.i{
  background: url("<?php echo $_SERVER['HTTP_HOST']; ?>/template/sidebars.png") repeat-y scroll -1000px 0 transparent;
  margin-right: 15px;
}

#menu div.i div.i2{
  background: url("<?php echo $_SERVER['HTTP_HOST']; ?>/template/sidebars.png") repeat-y scroll right 0 transparent;
  margin: 0 -15px 0 15px;
  padding: 1px 0;
}

#menu div.i div.i2 div.c{
  position: relative;
  margin: -155px 15px 0 0;
}

#menu ul{
  list-style: none outside none;
}

#menu ul li{
  background: url("<?php echo $_SERVER['HTTP_HOST']; ?>/template/sidebars.png") no-repeat scroll -500px -195px transparent;
  font-family: Verdana, Geneva, sans-serif;
  font-size: 12px;
  line-height: 18px;
  margin-bottom: 1px;
  padding: 0 0 0 20px;
}

#menu div.b{
  background: url("<?php echo $_SERVER['HTTP_HOST']; ?>/template/sidebars.png") no-repeat scroll -500px -170px transparent;
  height: 15px;
  margin-right: 15px;
}

#menu div.b div{
  background: url("<?php echo $_SERVER['HTTP_HOST']; ?>/template/sidebars.png") no-repeat scroll -985px -170px transparent;
  float: right;
  height: 15px;
  margin-right: -15px;
  width: 15px;
}
