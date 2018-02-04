<?php
require_once(dirname(__FILE__) . '/../config.php');
require_once(dirname(__FILE__) . '/renderer.php');
require_once(dirname(__FILE__) . '/helpers.php');
require_once('class.datagateway.php');
require_once('class.config.php');
header('Content-type: application/json');

if($USER->profile[Config::FIELD_USER_TYPE_NAME] !== Config::USER_TYPE_KAFEDRA){
    echo json_encode(array('status' => "You are not a representative of the department"));
    exit();
}

if(!isset($_POST['file_id']) || intval($_POST['file_id']) == 0){
    echo json_encode(array('status' => "Validation error"));
    exit();
}

$file_id = $_POST['file_id'];

$file = DataGateway::get_file_by_id($file_id);

if(!$file){
    echo json_encode(array('status' => "Work does not exist"));
    exit();
}

$update_record = new stdClass();
$update_record->id = $file_id;
$update_record->is_sign_kaf = 1;

$message = "Документ одобрен и подписан.";

$DB->update_record('nir_files',$update_record);

$record = new stdClass();
$record->user_id = $USER->id;
$record->nir_id = $file->nir_id;
$record->nir_type = $file->type;
$record->text = $message;

$DB->insert_record('nir_messages', $record, false);

$last_date = NULL;
if (isset($_POST['last_date_message']))
    $last_date = $_POST['last_date_message'];

$messages_data = get_messages_for_kaf($file->nir_id, $file->type, $last_date);

echo json_encode(array('status' => "Ok", 'messages' => $messages_data, 'alert' => $message));
?>