<?php
require_once(dirname(__FILE__) . '/../config.php');
header('Content-type: application/json');

if(!isset($_POST['work_id']) || intval($_POST['work_id']) == 0 || !isset($_POST['type']) || !isset($_POST['file'])){
    echo json_encode(array('status' => "Validation error"));
    exit();
}

$work_id = $_POST['work_id'];
$type = $_POST['type'];
$filename = 'uploads/' . $_POST['file'];

$sql_file= "SELECT * FROM {nir_files} WHERE mdl_nir_files.user_id = ? AND mdl_nir_files.nir_id = ? AND 
              mdl_nir_files.type = ? AND mdl_nir_files.filename = ?";

$file = $DB->get_record_sql($sql_file, array($USER->id, $work_id, $type, $filename));

if(!$file){
    echo json_encode(array('status' => "File does not exist"));
    exit();
}

if(file_exists($filename)){
    unlink($filename);
}

$DB->delete_records('nir_files', array('id' => $file->id));

?>
