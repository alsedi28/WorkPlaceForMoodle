<?php
require_once(dirname(__FILE__) . '/../config.php');
require_once('class.helper.php');
require_once('class.config.php');
require_once('class.datagateway.php');
header('Content-type: application/json');

if(!isset($_POST['nir']) || intval($_POST['nir']) == 0 || !isset($_POST['type']) || !isset($_POST['text'])){
    echo json_encode(array('status' => "Validation error"));
    exit();
}

$work_id = $_POST['nir'];
$type = $_POST['type'];
$text = $_POST['text'];

if($type !== "Z" && $type !== "O" && $type !== "P"){
    echo json_encode(array('status' => "Incorrect type"));
    exit();
}

$is_kaf = false;
if($USER->profile[Config::FIELD_USER_TYPE_NAME] === Config::USER_TYPE_KAFEDRA)
    $is_kaf = true;

$work = DataGateway::get_nir_by_id($work_id);

if(!$work || ($work->user_id != $USER->id && $work->teacher_id != $USER->id && !$is_kaf)){
    echo json_encode(array('status' => "Validation error"));
    exit();
}
    
$record = new stdClass();
$record->user_id = $USER->id ;
$record->nir_id = $work_id;
$record->nir_type = $type;
$record->text = htmlspecialchars($text);

$DB->insert_record('nir_messages', $record, false);

$last_date = NULL;
if (isset($_POST['last_date_message']))
    $last_date = $_POST['last_date_message'];

$messages_data = "";

if($is_kaf){
    $messages_data = Helper::get_messages_for_kaf($work_id, $type, $last_date);
}
else{
    $messages_data = Helper::get_messages($work_id, $type, $last_date);
}

echo json_encode(array('status' => "Ok", 'messages' => $messages_data));
?>