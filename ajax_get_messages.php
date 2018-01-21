<?php
require_once(dirname(__FILE__) . '/../config.php');
require_once(dirname(__FILE__) . '/renderer.php');
require_once(dirname(__FILE__) . '/helpers.php');
header('Content-type: application/json');


if(!isset($_GET['work_id']) || intval($_GET['work_id']) == 0 || !isset($_GET['date']) || !isset($_GET['type'])){
    echo json_encode(array('status' => "Validation error"));
    exit();
}

$work_id = $_GET['work_id'];
$date = $_GET['date'];
$type = $_GET['type'];

$sql_nir = "SELECT mdl_nir.id FROM {nir} WHERE (mdl_nir.user_id = ? OR mdl_nir.teacher_id = ?) AND
                mdl_nir.id = ? AND mdl_nir.is_closed = 0";

$nir = $DB->get_record_sql($sql_nir, array($USER->id, $USER->id, $work_id));

if(!$nir){
    echo json_encode(array('status' => "Work does not exist"));
    exit();
}

$sql_messages = "SELECT * FROM (SELECT mdl_nir_messages.id as message_id, mdl_nir_messages.text, mdl_nir_messages.date, mdl_user.firstname, mdl_user.lastname, mdl_user.id FROM 
                                {nir_messages}, {user} WHERE mdl_nir_messages.nir_id = ? AND mdl_user.id = mdl_nir_messages.user_id AND mdl_nir_messages.nir_type = ? 
                                AND mdl_nir_messages.date < ? ORDER BY mdl_nir_messages.id DESC LIMIT 6) as tmp ORDER BY tmp.date";

$messages = $DB->get_records_sql($sql_messages, array($work_id, $type, $date));
$count_messages = count($messages);

if($count_messages > 5)
    array_shift($messages);

$messages_data = render_messages($messages);

echo json_encode(array('status' => "Ok", 'messages' => $messages_data, 'count' => $count_messages));
?>