<?php
require_once(dirname(__FILE__) . '/../config.php');
require_once('class.datagateway.php');
header('Content-type: application/json');

if(!isset($_POST['work_id']) || intval($_POST['work_id']) == 0 || !isset($_POST['type']) || !isset($_POST['file'])){
    echo json_encode(array('status' => "Validation error"));
    exit();
}

$work_id = $_POST['work_id'];
$type = $_POST['type'];
$filename = 'uploads/' . $_POST['file'];

$file = DataGateway::get_file($USER->id, $work_id, $type, $filename);

if(!$file){
    echo json_encode(array('status' => "File does not exist"));
    exit();
}

if(file_exists($filename)){
    unlink($filename);
}

$DB->delete_records('nir_files', array('id' => $file->id));

?>
