<?php
require_once(dirname(__FILE__) . '/../config.php');
require_once(dirname(__FILE__) . '/renderer.php');
header('Content-type: application/json');

if(!isset($_POST['work_id'])){
    echo json_encode(array('status' => "Validation error"));
    exit();
}

$work_id = $_POST['work_id'];

$sql_work_plan_info = "SELECT * FROM mdl_nir_work_plans WHERE mdl_nir_work_plans.nir_id=".$work_id;
$work_plan_info = $DB->get_record_sql($sql_work_plan_info);

if(!$work_plan_info){
    echo json_encode(array('status' => "Work plan does not exist"));
    exit();
}

$sql_user_info = "SELECT * FROM mdl_nir_user_info WHERE mdl_nir_user_info.work_plan_id=".$work_plan_info->id." AND mdl_nir_user_info.user_id=".$USER->id;
$user_info = $DB->get_record_sql($sql_user_info);

$sql_teacher_info = "SELECT * FROM mdl_nir_teacher_info WHERE mdl_nir_teacher_info.work_plan_id=".$work_plan_info->id." AND mdl_nir_teacher_info.type='T'";
$teacher_info = $DB->get_record_sql($sql_teacher_info);

$sql_consultant_info = "SELECT * FROM mdl_nir_teacher_info WHERE mdl_nir_teacher_info.work_plan_id=".$work_plan_info->id." AND mdl_nir_teacher_info.type='C'";
$consultant_info = $DB->get_record_sql($sql_consultant_info);

$sql_work_plan_items = "SELECT * FROM mdl_nir_work_plan_items WHERE mdl_nir_work_plan_items.work_plan_id=".$work_plan_info->id;
$work_plan_items = $DB->get_records_sql($sql_work_plan_items);

if(isset($_POST['work_id']) && isset($_POST['ex_surname']) && isset($_POST['ex_name'])&& isset($_POST['ex_patronymic'])&& isset($_POST['ex_phone_number']) && isset($_POST['ex_email']) &&
    isset($_POST['th_surname']) && isset($_POST['th_name']) && isset($_POST['th_patronymic']) && isset($_POST['th_phone_number']) && isset($_POST['th_email']) &&
    isset($_POST['th_place_work']) && isset($_POST['th_position_work']) && isset($_POST['th_academic_title']) && isset($_POST['th_academic_degree']) && isset($_POST['work_theme']) &&
    isset($_POST['work_goal'])) {
    $work_id = $_POST['work_id'];
    $ex_surname = $_POST['ex_surname'];
    $ex_name = $_POST['ex_name'];
    $ex_patronymic = $_POST['ex_patronymic'];
    $ex_phone_number = $_POST['ex_phone_number'];
    $ex_email = $_POST['ex_email'];
    $th_surname = $_POST['th_surname'];
    $th_name = $_POST['th_name'];
    $th_patronymic = $_POST['th_patronymic'];
    $th_phone_number = $_POST['th_phone_number'];
    $th_email = $_POST['th_email'];
    $th_place_work = $_POST['th_place_work'];
    $th_position_work = $_POST['th_position_work'];
    $th_academic_title = $_POST['th_academic_title'];
    $th_academic_degree = $_POST['th_academic_degree'];
    $work_theme = $_POST['work_theme'];
    $work_goal = $_POST['work_goal'];

    $work_content_items = array();
    $i = 0;
    while (true) {
        if (isset($_POST['work_content'][$i])) {
            array_push($work_content_items, $_POST['work_content'][$i]);
            $i++;
        } else if ($i < 3) {
            echo json_encode(array('status' => "Validation error"));
            exit();
        } else {
            break;
        }
    }

    $work_result_items = array();
    $i = 0;
    while (true) {
        if (isset($_POST['work_result'][$i])) {
            array_push($work_result_items, $_POST['work_result'][$i]);
            $i++;
        } else if ($i < 3) {
            echo json_encode(array('status' => "Validation error"));
            exit();
        } else {
            break;
        }
    }

    $info_source_items = array();
    $i = 0;
    while (true) {
        if (isset($_POST['info_source'][$i])) {
            array_push($info_source_items, $_POST['info_source'][$i]);
            $i++;
        } else if ($i < 3) {
            echo json_encode(array('status' => "Validation error"));
            exit();
        } else {
            break;
        }
    }

    $need_update = false;
    $update_work_plan = new stdClass();
    $update_work_plan->id=$work_plan_info->id;

    if($work_theme !== $work_plan_info->theme){
        $update_work_plan->theme=$work_plan_info->theme;
        $need_update = true;
    }

    if($work_goal !== $work_plan_info->goal){
        $update_work_plan->goal=$work_plan_info->goal;
        $need_update = true;
    }

    if($need_update)
        $DB->update_record('nir_files',$update_work_plan);

    $need_update = false;
}
else{
    echo json_encode(array('status' => "Validation error"));
}
?>