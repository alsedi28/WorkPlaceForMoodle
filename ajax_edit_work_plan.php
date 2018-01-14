<?php
require_once(dirname(__FILE__) . '/../config.php');
require_once(dirname(__FILE__) . '/renderer.php');
require_once(dirname(__FILE__) . '/helpers.php');
require_once(dirname(__FILE__) . '/constants.php');
header('Content-type: application/json');

if(!isset($_POST['work_id'])){
    echo json_encode(array('status' => "Validation error"));
    exit();
}

$work_id = $_POST['work_id'];

$sql_work_plan_info = "SELECT mdl_nir_work_plans.id, mdl_nir_work_plans.theme, mdl_nir_work_plans.goal, mdl_nir.teacher_id  FROM mdl_nir_work_plans, mdl_nir WHERE 
                        mdl_nir_work_plans.nir_id=".$work_id." AND mdl_nir.id=mdl_nir_work_plans.nir_id AND 
                        (mdl_nir.teacher_id=".$USER->id." OR mdl_nir.user_id=".$USER->id.")";

$work_plan_info = $DB->get_record_sql($sql_work_plan_info);

if(!$work_plan_info){
    echo json_encode(array('status' => "Work plan does not exist"));
    exit();
}

$sql_user_info = "SELECT * FROM mdl_nir_user_info WHERE mdl_nir_user_info.work_plan_id=".$work_plan_info->id;
$user_info = $DB->get_record_sql($sql_user_info);

$sql_teacher_info = "SELECT * FROM mdl_nir_teacher_info WHERE mdl_nir_teacher_info.work_plan_id=".$work_plan_info->id." AND mdl_nir_teacher_info.type='T'";
$teacher_info = $DB->get_record_sql($sql_teacher_info);

$sql_consultant_info = "SELECT * FROM mdl_nir_teacher_info WHERE mdl_nir_teacher_info.work_plan_id=".$work_plan_info->id." AND mdl_nir_teacher_info.type='C'";
$consultant_info = $DB->get_record_sql($sql_consultant_info);

$sql_work_plan_items = "SELECT * FROM mdl_nir_work_plan_items WHERE mdl_nir_work_plan_items.work_plan_id=".$work_plan_info->id;
$work_plan_items = $DB->get_records_sql($sql_work_plan_items);

if(isset($_POST['ex_surname']) && isset($_POST['ex_name'])&& isset($_POST['ex_patronymic'])&& isset($_POST['ex_phone_number']) && isset($_POST['ex_email']) &&
    isset($_POST['th_surname']) && isset($_POST['th_name']) && isset($_POST['th_patronymic']) && isset($_POST['th_phone_number']) && isset($_POST['th_email']) &&
    isset($_POST['th_place_work']) && isset($_POST['th_position_work']) && isset($_POST['th_academic_title']) && isset($_POST['th_academic_degree']) && isset($_POST['work_theme']) &&
    isset($_POST['work_goal']) && isset($_POST['work_content'][0]) && isset($_POST['work_content'][1]) && isset($_POST['work_content'][2]) &&
    isset($_POST['work_result'][0]) && isset($_POST['work_result'][1]) && isset($_POST['work_result'][2]) && isset($_POST['info_source'][0]) &&
    isset($_POST['info_source'][1]) && isset($_POST['info_source'][2]))
    {
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

    $alert_message = '';

    $update_work_plan = new stdClass();
    $update_work_plan->id=$work_plan_info->id;

    if(isset($_POST['action'])) {
        if ($work_plan_info->teacher_id == $USER->id) {
            if ($_POST['action'] == "send_to_kaf") {
                $update_work_plan->is_sign_teacher = 1;
                $alert_message = "Задание на НИР отредактировано и отправлено на кафедру.";
            } else if ($_POST['action'] == "send_to_user") {
                $update_work_plan->is_sign_teacher = 0;
                $update_work_plan->is_sign_user = 0;
                $alert_message = "Задание на НИР отредактировано и отправлено студенту для доработки.";
            }
        }
        else{
            $update_work_plan->is_sign_user = 1;
            $alert_message = "Задание на НИР отредактировано и отправлено научному руководителю.";
        }
    }

    $changed_common_fields = array();

    if($work_theme !== $work_plan_info->theme){
        $update_work_plan->theme=$work_theme;
        array_push($changed_common_fields, $local['work_theme']);
    }

    if($work_goal !== $work_plan_info->goal){
        $update_work_plan->goal=$work_goal;
        array_push($changed_common_fields, $local['work_goal']);
    }

    $changed_executor_fields = array();
    $need_update_user_info = false;

    $update_user_info = new stdClass();
    $update_user_info->id=$user_info->id;

    if($ex_patronymic !== $user_info->patronymic){
        $update_user_info->patronymic=$ex_patronymic;
        array_push($changed_executor_fields, $local['patronymic']);
        $need_update_user_info = true;
    }

    if($ex_phone_number !== $user_info->phone_number){
        $update_user_info->phone_number=$ex_phone_number;
        array_push($changed_executor_fields, $local['phone_number']);
        $need_update_user_info = true;
    }

    if($ex_email !== $user_info->email){
        $update_user_info->email=$ex_email;
        array_push($changed_executor_fields, $local['email']);
        $need_update_user_info = true;
    }

    $changed_teacher_fields = update_teacher_info($teacher_info, array('patronymic' => $th_patronymic, 'phone_number' => $th_phone_number,
        'email' => $th_email, 'place_work' => $th_place_work, 'position_work' => $th_position_work, 'academic_title' => $th_academic_title,
        'academic_degree' => $th_academic_degree));

    $changed_consultant_fields = array();
    if(isset($_POST['cn_surname']) && isset($_POST['cn_name']) && isset($_POST['cn_patronymic']) && isset($_POST['cn_phone_number']) &&
            isset($_POST['cn_email']) && isset($_POST['cn_place_work']) && isset($_POST['cn_position_work']) &&
            isset($_POST['cn_academic_title']) && isset($_POST['cn_academic_degree'])){

        if($consultant_info){
            $changed_consultant_fields = update_teacher_info($consultant_info, array('patronymic' => $_POST['cn_patronymic'], 'phone_number' => $_POST['cn_phone_number'],
                'email' => $_POST['cn_email'], 'place_work' => $_POST['cn_place_work'], 'position_work' => $_POST['cn_position_work'],
                'academic_title' => $_POST['cn_academic_title'], 'academic_degree' => $_POST['cn_academic_degree']));
        }
        else{
            $record_consultant_info = new stdClass();
            $record_consultant_info->work_plan_id = $work_plan_info->id;
            $record_consultant_info->type = 'C';
            $record_consultant_info->name = $_POST['cn_name'];
            $record_consultant_info->surname = $_POST['cn_surname'];
            $record_consultant_info->patronymic = $_POST['cn_patronymic'];
            $record_consultant_info->phone_number = $_POST['cn_phone_number'];
            $record_consultant_info->email = $_POST['cn_email'];
            $record_consultant_info->place_work = $_POST['cn_place_work'];
            $record_consultant_info->position_work = $_POST['cn_position_work'];
            $record_consultant_info->academic_title = $_POST['cn_academic_title'];
            $record_consultant_info->academic_degree = $_POST['cn_academic_degree'];
            $DB->insert_record('nir_teacher_info', $record_consultant_info, false);
        }
    }

    $work_content_items = array();
    $work_result_items = array();
    $info_source_items = array();
    $work_items_records = array();
    $need_add_items = false;

    foreach ($work_plan_items as $item) {
        switch($item->type){
            case 'C':
                array_push($work_content_items, $item);
                break;
            case 'R':
                array_push($work_result_items, $item);
                break;
            case 'I':
                array_push($info_source_items, $item);
                break;
        }
    }

    usort($work_content_items, 'sort_items');
    usort($work_result_items, 'sort_items');
    usort($info_source_items, 'sort_items');

    update_work_plan_items($work_content_items, $work_plan_info->id, 'work_content', 'C');
    update_work_plan_items($work_result_items, $work_plan_info->id, 'work_result', 'R');
    update_work_plan_items($info_source_items, $work_plan_info->id, 'info_source', 'I');

    $DB->update_record('nir_work_plans',$update_work_plan);

    if($need_update_user_info)
        $DB->update_record('nir_user_info',$update_user_info);

    $message = build_message_edit_work_plan($alert_message, $changed_common_fields, $changed_executor_fields, $changed_teacher_fields, $changed_consultant_fields);

    $record = new stdClass();
    $record->user_id = $USER->id;
    $record->nir_id = $work_id;
    $record->nir_type = 'Z';
    $record->text = $message;

    $DB->insert_record('nir_messages', $record, false);

    $last_date = NULL;
    if (isset($_POST['last_date_message']))
        $last_date = $_POST['last_date_message'];

    $messages_data = get_messages($work_id, 'Z', $last_date);

    echo json_encode(array('status' => "Ok", 'data' => render_work_plan_view($work_id), 'messages' => $messages_data, 'alert' => $alert_message));
}
else{
    echo json_encode(array('status' => "Validation error"));
}
?>