<?php
require_once(dirname(__FILE__) . '/../config.php');
header('Content-type: application/json');

if(!isset($_POST['id']) || intval($_POST['id']) == 0){
    echo json_encode(array('status' => "Validation error"));
    exit();
}

$file_id = $_POST['id'];

$sql_file = "SELECT * FROM {nir_files} WHERE id = ?";
$file = $DB->get_record_sql($sql_file, array($file_id));

if($file && $file->user_id !== $USER->id){
    $update_record = new stdClass();
    $update_record->id = $file_id;
    $update_record->is_new = 0;
   
    $DB->update_record('nir_files',$update_record);
    echo json_encode(array('status' => "Ok"));
}
else{
    echo json_encode(array('status' => "Validation error"));
}
?>