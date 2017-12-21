<?php
require_once(dirname(__FILE__) . '/../config.php');
require_once(dirname(__FILE__) . '/renderer.php');
header('Content-type: application/json');

if(!isset($_POST['work_id'])){
    echo json_encode(array('status' => "Validation error"));
    exit();
}

$work_id = $_POST['work_id'];

$sql_nir = "SELECT mdl_nir.id FROM mdl_nir WHERE (mdl_nir.user_id=".$USER->id." OR mdl_nir.teacher_id=".$USER->id.") AND
                mdl_nir.id=".$work_id." AND mdl_nir.is_closed=0";
$rs = $DB->get_records_sql($sql_nir);

if(count($rs) == 0){
    echo json_encode(array('status' => "Work does not exist"));
    exit();
}

$sql_work_plan = "SELECT mdl_nir_work_plans.id FROM mdl_nir_work_plans WHERE mdl_nir_work_plans.nir_id=".$work_id;
$rs = $DB->get_records_sql($sql_work_plan);

if(count($rs) === 0){
    echo json_encode(array('status' => "Work plan does not exist"));
    exit();
}

echo json_encode(array('status' => "Ok", 'data' => render_work_plan_view($work_id)));
?>