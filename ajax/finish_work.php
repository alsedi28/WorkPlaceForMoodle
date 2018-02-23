<?php
require_once(dirname(__FILE__) . '/../../config.php');
require_once('../class.config.php');
require_once('../class.datagateway.php');
header('Content-type: application/json');

if(!isset($_POST['id']) || intval($_POST['id']) == 0){
    echo json_encode(array('status' => "Validation error"));
    exit();
}

$id = $_POST['id'];

$work = DataGateway::get_nir_by_id($id);

if($work && $USER->profile[Config::FIELD_USER_TYPE_NAME] === Config::USER_TYPE_KAFEDRA){
    $update_record = new stdClass();
    $update_record->id = $id;
    $update_record->is_closed = 1;
   
    $DB->update_record('nir', $update_record);

    echo json_encode(array('status' => "Ok"));
}
else{
    echo json_encode(array('status' => "Error"));
}
?>