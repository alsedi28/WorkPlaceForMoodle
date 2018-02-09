<?php
require_once(dirname(__FILE__) . '/../../config.php');
require_once('../class.config.php');
require_once('../class.datagateway.php');
header('Content-type: application/json');

$id = isset($_POST['id']) ? intval($_POST['id']) : null;
$review = isset($_POST['review']) ? $_POST['review'] : "";
$mark = isset($_POST['mark']) ? intval($_POST['mark']) : null;

if($id === null || !$id || $review === "" || $mark === null ||
    !($mark >= 1 && $mark <= 5) || $USER->profile[Config::FIELD_USER_TYPE_NAME] !== Config::USER_TYPE_TEACHER){
    echo json_encode(array('status' => "Validation error"));
    exit();
}

$work = DataGateway::get_nir_by_id($id);

if($work && $work->teacher_id == $USER->id && $work->review === null && $work->mark === null){
    $update_record = new stdClass();
    $update_record->id = $id;
    $update_record->review = htmlspecialchars($review);
    $update_record->mark = $mark;
   
    $DB->update_record('nir',$update_record);

    echo json_encode(array('status' => "Ok"));
}
else{
    echo json_encode(array('status' => "Validation error"));
}
?>