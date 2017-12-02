<?php

// The number of lines in front of config file determine the // hierarchy of files. 
require_once(dirname(__FILE__) . '/../config.php');

$teacher = isset($_POST['teacher']) ? $_POST['teacher'] : 0;
$title = isset($_POST['title']) ? $_POST['title'] : "";
$user = isset($_POST['user']) ? $_POST['user'] : 0;

$record = new stdClass();
$record->teacher_id = (int)$teacher;
$record->user_id = (int)$user;
$record->title = $title;

$DB->insert_record('nir', $record, false);

/*$sql_work = "SELECT mdl_nir.id, mdl_nir.title, mdl_user.firstname, mdl_user.lastname FROM mdl_nir, mdl_user where mdl_nir.user_id=".$USER->id." AND mdl_user.id=mdl_nir.teacher_id ORDER BY mdl_nir.id DESC LIMIT 1";
$work = $DB->get_record_sql($sql_work);

echo "<a href='/nir/index.php?id=".$work->id."'><div class='work_block'>";
echo "<p class='work_title'><span class='work_title_title'>Научный руководитель: </span>".$work->lastname." ".$work->firstname."</p>";
echo "<p class='work_teacher'><span class='work_teacher_title'>Тема: </span></br>".$work->title."</p>";
echo "</div></a>";*/

header( 'Location: /nir/index.php' );
?>