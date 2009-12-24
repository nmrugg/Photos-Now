<?php

define('THUMB_PATH', '.thumb');
define('PHOTOS_PATH', 'photos/');

define('PHOTO_PILE_COUNT', 5);

define('PHOTO_SIZE', 175);

/// Global variables
$thumb_start = '';
$thumb_top1 = '<div class=photo_div><a href="#show(\'';
$thumb_top2 = '\');"><span>&nbsp;</span><img class=background src=".images/polaroid.png"><img class=thumb src="';
$thumb_middle = '"><em>';
$thumb_bottom = "</em></a></div>\n";
$thumb_end = "";

function write_header()
{
	?>
<!DOCTYPE html>
<html><head>
<style type="text/css">
img {
	border: none;
}
.photo {
	max-height: 90%;
	max-width: 90%;
	position: fixed;
	z-index: 2147483647;
    border-radius: 20px;
    -webkit-border-radius: 20px;
	-moz-box-shadow: 0px 0px 5px #000;
	box-shadow: 0px 0px 5px #000;
	-webkit-box-shadow: 0px 0px 5px #000;
	/* For FF 3.5 to do curves better on an image. */
    clip-path: url(.images/resources.svg#c1);
    cursor: pointer;
    cursor: hand;
}

a {
	text-decoration: none;
}


/* Start of Picture Pile Style */

.pic_pile {
	position: absolute;
	border: #FFF 2px solid;
	-moz-box-shadow: 0px 0px 5px #333;
	box-shadow: 0px 0px 5px #333;
	-webkit-box-shadow: 0px 0px 5px #333;
}
.folder {
	position: absolute;
	padding-top: 45px;
}
.front {
	z-index: 99;
}
.dir {
	float: left;
	width: 200px!important;
}

/* End of Picture Pile Styles */


/* Start of Pictures Styles */

body {
	margin: 20px auto;
	padding: 0;
	background: url(.images/cork-bg.png);
}


.gallery .photo_div {
	height: 250px;
	width: 200px;
	float: left;
}
.gallery .photo_div a {
	padding-bottom: 8px;
	/*background: url(.images/polaroid.png) no-repeat;
	background-size: 100%;*/
	float: left;
	position: relative;
	text-align: center;
	margin: 10px;
}
.gallery .background {
	position: absolute;
	z-index: -1;
	width: 100%;
	height: 100%;
}
.gallery .thumb {
	padding: 7px;
}

.gallery span {
	background: url(.images/tape.png) no-repeat center;
	display: block;
	position: absolute;
	top: -5px;
	width:100%;
}
.gallery em {
	display: block;
	text-align: center;
	font: 20px PisanNormal, sans;
	color: #444;
}
@font-face {
	font-family: 'PisanNormal';
	src: url('.fonts/PISAN.eot');
	src: local('Pisan Normal'), local('Pisan-Normal'), url('.fonts/PISAN.woff') format('woff'), url('.fonts/PISAN.TTF') format('truetype'), url('.fonts/PISAN.svg#Pisan-Normal') format('svg');
}

/* End of picture stlyes */
</style>
<script src=main.js></script>
</head><body>
	<?php
}

function show_back($starting_dir)
{
	
}


function list_dirs($dirs)
{
	if (count((array)$dirs) == 0) return null;
	
	foreach ($dirs as $dir) {
		$dir_images = get_images($dir . '/');
		echo '<div class=dir>&nbsp;';
		echo '<img src=".images/folder-yellow-back.png" class="folder back">';
		echo '<img src=".images/folder-yellow-front.png" class="folder front">';
		for ($i = 0; $i < PHOTO_PILE_COUNT; ++$i) {
			$rand_key = array_rand($dir_images);
			//echo find_thumb($dir_images[$rand_key]);
			//echo '<img src="' . find_thumb($dir_images[$rand_key]) . '" class="pic_pile" style="-moz-transform: rotate(' . round(mt_rand(-6, 6)) . 'deg); left: ' . (($i) * PHOTO_SIZE * -1) . 'px">';
			echo '<img src="' . find_thumb($dir_images[$rand_key]) . '" class="pic_pile" style="-moz-transform: rotate(' . round(mt_rand(-6, 6)) . 'deg); lef: ' . (($i) * PHOTO_SIZE * -1) . 'px">';
		}
		echo "</div>\n";
	}
}



function get_images($dir)
{
	return glob($dir . '*.{j,p,g,J,P,G}{p,n,i,P,N,I}{g,f,G,F}', GLOB_BRACE);
}


function list_photos($files)
{
	global $thumb_start, $thumb_top1, $thumb_top2, $thumb_middle, $thumb_bottom, $thumb_end;
	echo $thumb_start;
	foreach ($files as $file) {
		$thumb = find_thumb($file);
		$thumb_top = $thumb_top1 . htmlentities($file) . $thumb_top2;
		echo $thumb_top . htmlentities($thumb) . $thumb_middle . wordwrap(htmlentities(title_case(str_replace("_", " ", pathinfo($file, PATHINFO_FILENAME)))), 22, "<br>\n", true) . $thumb_bottom;
	}
	echo $thumb_end;
}


function find_thumb($file)
{
	$path = pathinfo($file, PATHINFO_DIRNAME) . '/' . THUMB_PATH . '/';
	$filename = pathinfo($file, PATHINFO_BASENAME);
	if (!is_dir($path)) {
		mkdir($path);
	}
	if (!file_exists($path . $filename)) {
		create_thumb($file, $path . $filename);
	}
	return $path . $filename;
}


function create_thumb($original, $new_filename)
{
	/// There are better ways to do this.
	$res = shell_exec("convert \"" . addslashes($original) . "\" -thumbnail " . PHOTO_SIZE . "x" . PHOTO_SIZE . " \"" . addslashes($new_filename) . "\" 2>&1");
	
	if (!file_exists($new_filename)) {
		/// Try gd.
	}
}


function title_case($title)
{
	$preps_articles_conjunctions = array('of','a','the','and','an','or','nor','but','is','if','then', 'else','when','at','from','by','on','off','for','in','out','over','to','into','with');
	$words = explode(' ', $title);
	foreach ($words as $key => $word) {
		if ($key == 0 || !in_array($word, $preps_articles_conjunctions))
		$words[$key] = ucwords(strtolower($word));
	}
	
	$newtitle = implode(' ', $words);
	return $newtitle;
}

