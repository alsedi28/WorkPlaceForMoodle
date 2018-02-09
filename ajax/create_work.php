<?php

// The number of lines in front of config file determine the // hierarchy of files. 
require_once(dirname(__FILE__) . '/../../config.php');

$teacher = isset($_POST['teacher']) ? $_POST['teacher'] : 0;
$title = isset($_POST['title']) ? $_POST['title'] : "";
$user = isset($_POST['user']) ? $_POST['user'] : 0;

$record = new stdClass();
$record->teacher_id = (int)$teacher;
$record->user_id = (int)$user;
$record->title = htmlspecialchars($title);

$DB->insert_record('nir', $record, false);

header( 'Location: /nir/index.php' );
?>