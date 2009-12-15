<?php

require 'functions.php';

write_header();

if (!isset($_REQUEST['dir']) || $_REQUEST['dir'] == "" || substr($_REQUEST['dir'], 0, 1) == "." || !is_dir($_REQUEST['dir'])) $_REQUEST['dir'] = "";

if ($_REQUEST['dir'] != "") show_back($_REQUEST['dir']);

list_dirs(glob($_REQUEST['dir'] . '*', GLOB_ONLYDIR));

list_photos(glob($_REQUEST['dir'] . '*.{j,p,g,J,P,G}{p,n,i,P,N,I}{g,f,G,F}', GLOB_BRACE));