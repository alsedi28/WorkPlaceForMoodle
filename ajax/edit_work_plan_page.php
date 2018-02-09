<?php
require_once(dirname(__FILE__) . '/../../config.php');
require_once('../class.datagateway.php');
require_once('../class.render.php');
header('Content-type: application/json');

if(!isset($_POST['work_id']) || intval($_POST['work_id']) == 0){
    echo json_encode(array('status' => "Validation error"));
    exit();
}

$work_id = $_POST['work_id'];

$nir = DataGateway::get_nir_by_user($USER->id, $work_id);

if(!$nir){
    echo json_encode(array('status' => "Work does not exist"));
    exit();
}

$work_plan = DataGateway::get_work_plan_by_nir($work_id);

if(!$work_plan){
    echo json_encode(array('status' => "Work plan does not exist"));
    exit();
}

echo json_encode(array('status' => "Ok", 'data' => Render::render_work_plan_edit($work_id), 'alert' => 'Можете начать редактирование задания на НИР.'));

?>