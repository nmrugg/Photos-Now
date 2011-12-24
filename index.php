<?php

require 'functions.php';

write_header();

if (!isset($_REQUEST['dir']) || $_REQUEST['dir'] == "" || substr($_REQUEST['dir'], 0, 1) == "." || !is_dir(__DIR__ . '/' . PHOTOS_PATH . $_REQUEST['dir'])) {
    $_REQUEST['dir'] = "";
}

echo '<div class=gallery>';

if ($_REQUEST['dir'] !== "") {
    show_back(PHOTOS_PATH . $_REQUEST['dir']);
}

list_dirs(get_dirs($_REQUEST['dir']));

list_photos(get_images(PHOTOS_PATH . $_REQUEST['dir']));

echo '</div>';
