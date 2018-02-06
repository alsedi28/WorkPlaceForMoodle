<?php
require_once(dirname(__FILE__) . '/../config.php');
require_once('class.helper.php');
require_once('class.config.php');
require_once('class.datagateway.php');
header('Content-type: application/json');

if(!isset($_POST['id']) || intval($_POST['id']) == 0){
    echo json_encode(array('status' => "Validation error"));
    exit();
}

$file_id = $_POST['id'];

$file = DataGateway::get_file_by_id($file_id);

if($file && $USER->profile[Config::FIELD_USER_TYPE_NAME] === Config::USER_TYPE_TEACHER){
    $update_record = new stdClass();
    $update_record->id = $file_id;
    $update_record->is_sign_teacher = 1;

    $DB->update_record('nir_files',$update_record);

    $record = new stdClass();
    $record->user_id = $USER->id;
    $record->nir_id = $file->nir_id;
    $record->nir_type = $file->type;
    $record->text = "Документ одобрен и подписан научным руководителем.";

    $DB->insert_record('nir_messages', $record, false);

    $last_date = NULL;
    if (isset($_POST['last_date_message']))
        $last_date = $_POST['last_date_message'];

    $messages_data = "";

    $messages_data = Helper::get_messages($file->nir_id, $file->type, $last_date);

    echo json_encode(array('status' => "Ok", 'messages' => $messages_data));
}
else{
    echo json_encode(array('status' => "Validation error"));
}
?>