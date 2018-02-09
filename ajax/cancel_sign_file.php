<?php
require_once(dirname(__FILE__) . '/../../config.php');
require_once('../class.helper.php');
require_once('../class.config.php');
require_once('../class.datagateway.php');
header('Content-type: application/json');

if(!isset($_POST['file_id']) || intval($_POST['file_id']) == 0){
    echo json_encode(array('status' => "Validation error"));
    exit();
}

$file_id = $_POST['file_id'];

$file = DataGateway::get_file_by_id($file_id);

$message = "";
$status = "Ok cancel sign document";

if($file && $USER->profile[Config::FIELD_USER_TYPE_NAME] === Config::USER_TYPE_KAFEDRA){
    
    if($file->is_sign_kaf == 1){
        $update_record = new stdClass();
        $update_record->id = $file_id;
        $update_record->is_sign_kaf = 0;
        
        $DB->update_record('nir_files', $update_record);
        
        $message = "Подпись отменена.";
    }
    else{
        
        $update_record = new stdClass();
        $update_record->id = $file_id;
        $update_record->is_sign_teacher = 0;
        
        $DB->update_record('nir_files', $update_record);
        
        $message = "Документ отклонён.";

        $status = "Ok cancel document";
    }
    
    $user_id = $USER->id;
        
    $record = new stdClass();
    $record->user_id = $USER->id;
    $record->nir_id = $file->nir_id;
    $record->nir_type = $file->type;
    $record->text = $message;
    
    $DB->insert_record('nir_messages', $record, false);

    $last_date = NULL;
    if (isset($_POST['last_date_message']))
        $last_date = $_POST['last_date_message'];

    $messages_data = Helper::get_messages_for_kaf($file->nir_id, $file->type, $last_date);

    echo json_encode(array('status' => $status, 'messages' => $messages_data));
}
else{
    echo json_encode(array('status' => "Error"));
}
?>