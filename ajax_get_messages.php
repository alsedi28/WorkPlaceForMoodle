<?php
require_once(dirname(__FILE__) . '/../config.php');
require_once(dirname(__FILE__) . '/renderer.php');
require_once(dirname(__FILE__) . '/helpers.php');
header('Content-type: application/json');


if(!isset($_GET['work_id']) || !isset($_GET['date']) || !isset($_GET['type'])){
    echo json_encode(array('status' => "Validation error"));
    exit();
}

$work_id = $_GET['work_id'];
$date = $_GET['date'];
$type = $_GET['type'];

$sql_nir = "SELECT mdl_nir.id FROM mdl_nir WHERE (mdl_nir.user_id=".$USER->id." OR mdl_nir.teacher_id=".$USER->id.") AND
                mdl_nir.id=".$work_id." AND mdl_nir.is_closed=0";
$rs = $DB->get_records_sql($sql_nir);

if(count($rs) == 0){
    echo json_encode(array('status' => "Work does not exist"));
    exit();
}

$sql_messages = "SELECT * FROM (SELECT mdl_nir_messages.id as message_id, mdl_nir_messages.text, mdl_nir_messages.date, mdl_user.firstname, mdl_user.lastname, mdl_user.id FROM 
                                mdl_nir_messages, mdl_user WHERE mdl_nir_messages.nir_id=".$work_id." AND mdl_user.id=mdl_nir_messages.user_id AND mdl_nir_messages.nir_type='".$type."' 
                                AND mdl_nir_messages.date < '".$date."' ORDER BY mdl_nir_messages.id DESC LIMIT 6) as tmp ORDER BY tmp.date";

$messages = $DB->get_records_sql($sql_messages);
$count_messages = count($messages);

if($count_messages > 5)
    array_shift($messages);

$messages_data = render_messages($messages);

echo json_encode(array('status' => "Ok", 'messages' => $messages_data, 'count' => $count_messages));
?>