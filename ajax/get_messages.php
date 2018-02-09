<?php
require_once(dirname(__FILE__) . '/../../config.php');
require_once('../class.helper.php');
require_once('../class.datagateway.php');
header('Content-type: application/json');


if(!isset($_GET['work_id']) || intval($_GET['work_id']) == 0 || !isset($_GET['date']) || !isset($_GET['type'])){
    echo json_encode(array('status' => "Validation error"));
    exit();
}

$work_id = $_GET['work_id'];
$date = $_GET['date'];
$type = $_GET['type'];

$nir = DataGateway::get_nir_by_user($USER->id, $work_id, false);

if(!$nir){
    echo json_encode(array('status' => "Work does not exist"));
    exit();
}

$messages = DataGateway::get_comments_limit_by_date($work_id, $type, $date);
$count_messages = count($messages);

if($count_messages > 5)
    array_shift($messages);

$messages_data = Helper::render_messages($messages);

echo json_encode(array('status' => "Ok", 'messages' => $messages_data, 'count' => $count_messages));
?>