<?php

if (!defined("__DIR__")) {
    define("__DIR__", getcwd());
}

require_once "config.php";

/// Global variables
$thumb_start = '';
$thumb_top1 = '<div class=photo_div><a href="#show(\'';
$thumb_top2 = '\');"><span>&nbsp;</span><img class=background src=".images/polaroid.png"><img class=thumb src="';
$thumb_middle = '"><em>';
$thumb_bottom = "</em></a></div>\n";
$thumb_end = "";

///TEMP GLOBAL VAR
$_REQUEST['arrange'] = 'date';

function write_header()
{
    ?>
<!DOCTYPE html>
<html><head>
<title>Photos</title>
<style type="text/css">

html {
    font-family: PisanNormal, sans;
}
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
    clip-path: url(.images/resources.xml#c1);
    cursor: pointer;
    cursor: hand;
}

a {
    text-decoration: none;
    color: #444;
}


/* Start of Picture Pile Style */

.pic_pile {
    position: absolute;
    border: #FFF 2px solid;
    -moz-box-shadow: 0px 0px 5px #333;
    box-shadow: 0px 0px 5px #333;
    -webkit-box-shadow: 0px 0px 5px #333;
    background: #FFF;
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
    height: 250px;
}

.label {
    position: relative;
    padding-top: 105px;
    width: 80%;
    z-index:999;
    text-align: center;
    color: #444;
    -moz-transform: rotate(-6deg);
    -webkit-transform: rotate(-6deg);
    font-size: 25px;
}
/* End of Picture Pile Styles */


/* Start of Pictures Styles */

body {
    margin: 20px auto;
    padding: 0;
    background: url(.images/cork-bg.png);
}

.gallery {
    margin: 0 4px 0 4px;
}
.gallery .photo_div {
    height: 250px;
    width: 200px;
    float: left;
    text-align: center;
}
.gallery .photo_div a {
    padding-bottom: 8px;
    /*background: url(.images/polaroid.png) no-repeat;
    background-size: 100%;*/
    float: left;
    position: relative;
    text-align: center;
    margin: 10px;
    /*background: url(.images/loader.gif) center no-repeat;*/

}
.gallery .background {
    position: absolute;
    z-index: -1;
    width: 100%;
    height: 100%;
}
.gallery .thumb {
    padding: 7px;
    /*opacity:.7;*/
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
    font-size: 20px;
    color: #444;
    font-style: normal;
}
@font-face {
	font-family: 'PisanNormal';
	src: url('.fonts/PISAN.eot');
	src: local('Pisan'), url('.fonts/PISAN.TTF.woff') format('woff'), url('.fonts/PISAN.ttf') format('truetype'), url('.fonts/PISAN.svg#webfont') format('svg');
	font-weight: normal;
	font-style: normal;
}

/* End of picture stlyes */
</style>
<script src=main.js></script>
</head><body>
    <?php
}

function escape_single_quotes($str)
{
    if (substr(PHP_OS, 0, 3) === 'WIN') {
        ///FIXME: How to do this on Windows?
        return $str;
    } else {
        return str_replace("\\'", "'\\''", $str);
    }
}

function beautify_name($name)
{
    $name = str_replace("_", " ", $name);
    
    $tmp_name = $name;
    /// Reorder dates and add slashes.
    $name = preg_replace('/^([1-2]\d\d\d)([01]\d)([0-3]\d)?$/', '$2/$3/$1', $name);
    /// If the day is left out, it will produce two consecutive slashes, so one must be removed.
    if ($name !== $tmp_name) {
        $name = str_replace("//", "/", $name);
    }
    
    return wordwrap(htmlentities(title_case($name)), 22, "<br>\n", true);
}

function show_back($starting_dir)
{
    create_picture_pile(dirname($starting_dir), '<- Go back&nbsp;&nbsp;&nbsp;<br><small>(' . beautify_name(basename(dirname($starting_dir))) . ')</small>', false);
}

function get_dirs($dir_path)
{
    ///DATE CODE
    $debug = false;
    if ($_REQUEST['arrange'] == 'date' && $debug) {
        die($dir_path);
    } else {
        $dirs = glob(__DIR__ . '/' . PHOTOS_PATH . $dir_path . '*', GLOB_ONLYDIR);
        
        foreach ($dirs as &$value) {
            $value = substr($value, strlen(__DIR__ . '/'));
        }
        
        return $dirs;
    }
}


function list_dirs($dirs)
{
    if (count((array)$dirs) == 0) return null;
    
    foreach ($dirs as $dir) {
        create_picture_pile($dir);
    }
}


function create_picture_pile($dir, $dir_name = "", $beautify = true)
{
    if ($dir_name == "") $dir_name = basename($dir);
    $dir_images = get_images($dir . '/');
    $dir_path = substr($dir, strlen(PHOTOS_PATH)) . '/';
    if ($dir_path == "/" || $dir_path == "") {
        $url = '';
    } else {
        $url = 'dir=' . urlencode($dir_path);
    }
    echo '<a href="?' . $url . '">';
    echo '<div class=dir>';
    echo '<img src=".images/folder-yellow-back.png" class="folder back">';
    echo '<img src=".images/folder-yellow-front.png" class="folder front">';
    for ($i = 0; $i < PHOTO_PILE_COUNT; ++$i) {
        $rand_key = array_rand($dir_images);
        //echo find_thumb($dir_images[$rand_key]);
        //echo '<img src="' . find_thumb($dir_images[$rand_key]) . '" class="pic_pile" style="-moz-transform: rotate(' . round(mt_rand(-6, 6)) . 'deg); left: ' . (($i) * PHOTO_SIZE * -1) . 'px">';
        if (isset($dir_images[$rand_key])) {
            $rotate = round(mt_rand(-6, 6));
            echo '<img src="' . htmlentities(find_thumb($dir_images[$rand_key])) . '" class="pic_pile" style="-moz-transform: rotate(' . $rotate . 'deg);-webkit-transform: rotate(' . $rotate . 'deg);">';
        }
    }
    echo '<div class=label>' . ($beautify ? beautify_name($dir_name) : $dir_name). '</div>';
    echo "</div>";
    echo "</a>\n";
}


function get_images($dir)
{
    $imgs = glob(__DIR__ . '/' . $dir . '*.{j,p,g,J,P,G}{p,n,i,P,N,I}{g,f,G,F}', GLOB_BRACE);
    foreach ($imgs as &$value) {
        $value = substr($value, strlen(__DIR__ . '/'));
    }
    
    return $imgs;
}


function list_photos($files)
{
    global $thumb_start, $thumb_top1, $thumb_top2, $thumb_middle, $thumb_bottom, $thumb_end;
    echo $thumb_start;
    foreach ($files as $file) {
        $thumb = find_thumb($file);
        $thumb_top = $thumb_top1 . addslashes(htmlentities($file)) . $thumb_top2;
        echo $thumb_top . htmlentities($thumb) . $thumb_middle . wordwrap(htmlentities(title_case(str_replace("_", " ", pathinfo_filename($file)))), 22, "<br>\n", true) . $thumb_bottom;
    }
    echo $thumb_end;
}


function find_thumb($file)
{
    $path = pathinfo(__DIR__ . '/' . $file, PATHINFO_DIRNAME) . '/' . THUMB_PATH . '/';
    $filename = pathinfo($file, PATHINFO_BASENAME);
    if (!is_dir($path)) {
        mkdir($path);
    }
    if (!file_exists($path . $filename)) {
        create_thumb($file, $path . $filename);
    }
    
    return substr($path, strlen(__DIR__ . '/')) . $filename;
}

function create_thumb($original, $new_filename)
{
    /// Attempt to create the thumbnail with ImageMagick.
    ///NOTE: It would be good to add a constant for the "convert" executable.
    $res = shell_exec("convert '" . escape_single_quotes(addslashes(__DIR__ . '/' . $original)) . "' -thumbnail " . PHOTO_SIZE . "x" . PHOTO_SIZE . " '" . escape_single_quotes(addslashes($new_filename)) . "' 2>&1");
    
    /// Did ImageMagick work?
    if (!file_exists($new_filename)) {
        /// Attempt to create the thumbnail with GD.
        
        list($orig_width, $orig_height, $imagetype) = getimagesize(__DIR__ . '/' . $original);
        
        $ratio_orig = $orig_width / $orig_height;
        
        /// Is the image is wider than it is tall?
        if ($orig_width > $orig_height) {
            $maxwidth = PHOTO_SIZE;
            $maxheight = round(PHOTO_SIZE / $ratio_orig);
        } else {
            $maxheight = PHOTO_SIZE;
            $maxwidth = round(PHOTO_SIZE * $ratio_orig);
        }
        
        /// Create blank image for the thumbnail.
        $thumb = imagecreatetruecolor($maxwidth, $maxheight);
        
        $type_unknown = false;
        
        if ($imagetype == 1) {
            $source = imagecreatefromgif(__DIR__ . '/' . $original);
            imagecopyresampled($thumb, $source, 0, 0, 0, 0, $maxwidth, $maxheight, $orig_width, $orig_height);
        } elseif ($imagetype == 2) {
            $source = imagecreatefromjpeg(__DIR__ . '/' . $original);
            imagecopyresampled($thumb, $source, 0, 0, 0, 0, $maxwidth, $maxheight, $orig_width, $orig_height);
        } elseif ($imagetype == 3) {
            $source = imagecreatefrompng(__DIR__ . '/' . $original);
            imagecopyresampled($thumb, $source, 0, 0, 0, 0, $maxwidth, $maxheight, $orig_width, $orig_height);
        } else {
            $type_unknown = true;
        }
        
        if (!$type_unknown) {
            imagejpeg($thumb, $new_filename, 85);
        }
    }
}

function title_case($title)
{
    $preps_articles_conjunctions = array('of', 'a', 'the', 'and', 'an', 'or', 'nor', 'but', 'is', 'if', 'then', 'else', 'when', 'at', 'from', 'by', 'on', 'off', 'for', 'in', 'out', 'over', 'to', 'into', 'with');
    $words = explode(' ', $title);
    $words_len = count($words);
    foreach ($words as $key => $word) {
        if ($key === 0 || $key === $words_len || !in_array($word, $preps_articles_conjunctions)) {
            $words[$key] = ucwords(strtolower($word));
        }
    }
    
    $newtitle = implode(' ', $words);
    return $newtitle;
}

function pathinfo_filename($path)
{
    /// PHP >= 5.2.0
    if (defined('PATHINFO_FILENAME')) {
        return pathinfo($path, PATHINFO_FILENAME);
    } else {
        $path_parts = pathinfo($path);
        return substr($path_parts['basename'], 0, strlen($path_parts['basename']) - strlen($path_parts['extension']) - 1);
    }
}


function get_file_date($filename)
{
    if (!file_exists($filename)) {
        return false;
    }
    $exif = exif_read_data($filename, 0, true);
    if (isset($exif['EXIF']) && isset($exif['EXIF']['DateTimeOriginal']) && $exif['EXIF']['DateTimeOriginal'] != "") {
        $filetime = strtotime($exif['EXIF']['DateTimeOriginal']);
    } elseif (isset($exif['EXIF']) && isset($exif['EXIF']['DateTimeDigitized']) && $exif['EXIF']['DateTimeDigitized'] != "") {
        $filetime = strtotime($exif['EXIF']['DateTimeDigitized']);
    } else {
        ///TODO: Try PEL if exif_read_data() fails.
        $filetime = filemtime($filename);
    }
    
    ///NOTE: month_str is the month as a string, i.e., "January" instead of "01".
    return Array('timestamp' => $filetime, 'year' => date('Y', $filetime), 'month' => date('m', $filetime), 'month_str' => date('F', $filetime));
}
