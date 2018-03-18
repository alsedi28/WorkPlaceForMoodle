<?php
require_once(dirname(__FILE__) . '/../../config.php');
require_once('../class.config.php');

$id = isset($_POST['id']) ? intval($_POST['id']) : null;
$status = isset($_POST['status']) ? $_POST['status'] : null;

if($id === null || $status === null || $USER->profile[Config::FIELD_USER_TYPE_NAME] !== Config::USER_TYPE_KAFEDRA){
    exit();
}

$update_record = new stdClass();
$update_record->id = $id;
$update_record->is_active = $status == "true" ? 1 : 0;

$DB->update_record('nir_groups',$update_record);

?>