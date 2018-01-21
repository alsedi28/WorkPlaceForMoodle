<?php
require_once(dirname(__FILE__) . '/../config.php');
require_once(dirname(__FILE__) . '/renderer.php');
require_once(dirname(__FILE__) . '/helpers.php');
header('Content-type: application/json');

if(!isset($_POST['work_id']) || intval($_POST['work_id']) == 0){
    echo json_encode(array('status' => "Validation error"));
    exit();
}

$work_id = $_POST['work_id'];

$sql_work_plan_info = "SELECT mdl_nir_work_plans.id FROM {nir_work_plans}, {nir} WHERE 
                        mdl_nir_work_plans.nir_id = ? AND mdl_nir.id = mdl_nir_work_plans.nir_id";

$work_plan_info = $DB->get_record_sql($sql_work_plan_info, array($work_id));

if(!$work_plan_info){
    echo json_encode(array('status' => "Work plan does not exist"));
    exit();
}

if($USER->profile['isTeacher'] !== "666"){
    echo json_encode(array('status' => "You are not a representative of the department"));
    exit();
}

$update_work_plan = new stdClass();
$update_work_plan->id=$work_plan_info->id;
$update_work_plan->is_sign_user = 0;
$update_work_plan->is_sign_teacher = 0;
$update_work_plan->is_sign_kaf = 0;

$DB->update_record('nir_work_plans',$update_work_plan);

$message = 'Задание на НИР отклонено и отправлено на доработку.';

$record = new stdClass();
$record->user_id = $USER->id;
$record->nir_id = $work_id;
$record->nir_type = 'Z';
$record->text = $message;

$DB->insert_record('nir_messages', $record, false);

$last_date = NULL;
if (isset($_POST['last_date_message']))
    $last_date = $_POST['last_date_message'];

$messages_data = get_messages_for_kaf($work_id, 'Z', $last_date);

echo json_encode(array('status' => "Ok", 'messages' => $messages_data, 'alert' => $message));
?>