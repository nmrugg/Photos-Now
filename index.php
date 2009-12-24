<?php

require 'functions.php';

write_header();

if (!isset($_REQUEST['dir']) || $_REQUEST['dir'] == "" || substr($_REQUEST['dir'], 0, 1) == "." || !is_dir($_REQUEST['dir'])) $_REQUEST['dir'] = "";

if ($_REQUEST['dir'] != "") show_back(PHOTOS_PATH . $_REQUEST['dir']);

echo '<div class=gallery>';
list_dirs(glob(PHOTOS_PATH . $_REQUEST['dir'] . '*', GLOB_ONLYDIR));

list_photos(get_images(PHOTOS_PATH . $_REQUEST['dir']));
echo '</div>';