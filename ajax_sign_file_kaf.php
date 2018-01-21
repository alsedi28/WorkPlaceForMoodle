<?php
require_once(dirname(__FILE__) . '/../config.php');
require_once(dirname(__FILE__) . '/renderer.php');
require_once(dirname(__FILE__) . '/helpers.php');
header('Content-type: application/json');

if($USER->profile['isTeacher'] !== "666"){
    echo json_encode(array('status' => "You are not a representative of the department"));
    exit();
}

if(!isset($_POST['file_id']) || intval($_POST['file_id']) == 0){
    echo json_encode(array('status' => "Validation error"));
    exit();
}

$file_id = $_POST['file_id'];

$sql_work = "SELECT nir_id, type FROM {nir_files} WHERE id = ?";

$work_result = $DB->get_record_sql($sql_work, array($file_id));

if(!$work_result){
    echo json_encode(array('status' => "Work does not exist"));
    exit();
}

$update_record = new stdClass();
$update_record->id=$file_id;
$update_record->is_sign_kaf=1;

$message = "Документ одобрен и подписан.";

$DB->update_record('nir_files',$update_record);

$record = new stdClass();
$record->user_id = $USER->id;
$record->nir_id = $work_result->nir_id;
$record->nir_type = $work_result->type;
$record->text = $message;

$DB->insert_record('nir_messages', $record, false);

$last_date = NULL;
if (isset($_POST['last_date_message']))
    $last_date = $_POST['last_date_message'];

$messages_data = get_messages_for_kaf($work_result->nir_id, $work_result->type, $last_date);

echo json_encode(array('status' => "Ok", 'messages' => $messages_data, 'alert' => $message));
?>