<?php
require_once(dirname(__FILE__) . '/../config.php');
require_once(dirname(__FILE__) . '/renderer.php');
header('Content-type: application/json');

if(!isset($_POST['work_id']) || intval($_POST['work_id']) == 0){
    echo json_encode(array('status' => "Validation error"));
    exit();
}

$work_id = $_POST['work_id'];

$sql_nir = "SELECT mdl_nir.id FROM {nir} WHERE (mdl_nir.user_id = ? OR mdl_nir.teacher_id = ?) AND
                mdl_nir.id = ? AND mdl_nir.is_closed = 0";
$nir = $DB->get_record_sql($sql_nir, array($USER->id, $USER->id, $work_id));

if(!$nir){
    echo json_encode(array('status' => "Work does not exist"));
    exit();
}

$sql_work_plan = "SELECT mdl_nir_work_plans.id FROM {nir_work_plans} WHERE mdl_nir_work_plans.nir_id = ?";
$work_plan = $DB->get_record_sql($sql_work_plan, array($work_id));

if(!$work_plan){
    echo json_encode(array('status' => "Work plan does not exist"));
    exit();
}

echo json_encode(array('status' => "Ok", 'data' => render_work_plan_edit($work_id), 'alert' => 'Можете начать редактирование задания на НИР.'));

?>