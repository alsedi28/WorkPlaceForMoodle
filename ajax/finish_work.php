<?php
require_once(dirname(__FILE__) . '/../../config.php');
require_once('../class.config.php');
require_once('../class.datagateway.php');

if(!isset($_POST['id']) || intval($_POST['id']) == 0 || !isset($_POST['mark']) || intval($_POST['mark']) == 0 || !isset($_POST['comment']) || !isset($_POST['status'])){
    header("Location: ".$_SERVER['HTTP_REFERER']);
}

$id = $_POST['id'];
$mark = intval($_POST['mark']);
$status = $_POST['status'];
$comment = htmlspecialchars($_POST['comment']);

$work = DataGateway::get_nir_by_id($id);

if($work && $USER->profile[Config::FIELD_USER_TYPE_NAME] === Config::USER_TYPE_KAFEDRA){
    $update_record = new stdClass();
    $update_record->id = $id;
    $update_record->is_closed = 1;
    $update_record->final_mark = $mark;
    $update_record->status_completion = $status;
    $update_record->final_comment = $comment;

    $DB->update_record('nir', $update_record);

    header("Location: ".$_SERVER['HTTP_REFERER']);
}
?>