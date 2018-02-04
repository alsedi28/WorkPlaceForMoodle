<?php
require_once(dirname(__FILE__) . '/../config.php');
require_once(dirname(__FILE__) . '/renderer.php');
require_once('class.datagateway.php');
header('Content-type: application/json');

if(isset($_POST['work_id']) && isset($_POST['ex_surname']) && isset($_POST['ex_name'])&& isset($_POST['ex_patronymic'])&& isset($_POST['ex_phone_number']) && isset($_POST['ex_email']) &&
        isset($_POST['th_surname']) && isset($_POST['th_name']) && isset($_POST['th_patronymic']) && isset($_POST['th_phone_number']) && isset($_POST['th_email']) &&
        isset($_POST['th_place_work']) && isset($_POST['th_position_work']) && isset($_POST['th_academic_title']) && isset($_POST['th_academic_degree']) && isset($_POST['work_theme']) &&
        isset($_POST['work_goal'])){
    $work_id = intval($_POST['work_id']);
    $ex_surname = htmlspecialchars($_POST['ex_surname']);
    $ex_name = htmlspecialchars($_POST['ex_name']);
    $ex_patronymic = htmlspecialchars($_POST['ex_patronymic']);
    $ex_phone_number = htmlspecialchars($_POST['ex_phone_number']);
    $ex_email = htmlspecialchars($_POST['ex_email']);
    $th_surname = htmlspecialchars($_POST['th_surname']);
    $th_name = htmlspecialchars($_POST['th_name']);
    $th_patronymic = htmlspecialchars($_POST['th_patronymic']);
    $th_phone_number = htmlspecialchars($_POST['th_phone_number']);
    $th_email = htmlspecialchars($_POST['th_email']);
    $th_place_work = htmlspecialchars($_POST['th_place_work']);
    $th_position_work = htmlspecialchars($_POST['th_position_work']);
    $th_academic_title = htmlspecialchars($_POST['th_academic_title']);
    $th_academic_degree = htmlspecialchars($_POST['th_academic_degree']);
    $work_theme = htmlspecialchars($_POST['work_theme']);
    $work_goal = htmlspecialchars($_POST['work_goal']);

    if($work_id === 0){
        echo json_encode(array('status' => "Validation error"));
        exit();
    }

    $work_content_items = array();
    $i = 0;
    while(true){
        if(isset($_POST['work_content'][$i])){
            array_push($work_content_items, htmlspecialchars($_POST['work_content'][$i]));
            $i++;
        }
        else if($i < 3){
            echo json_encode(array('status' => "Validation error"));
            exit();
        }
        else{
            break;
        }
    }

    $work_result_items = array();
    $i = 0;
    while(true){
        if(isset($_POST['work_result'][$i])){
            array_push($work_result_items, htmlspecialchars($_POST['work_result'][$i]));
            $i++;
        }
        else if($i < 3){
            echo json_encode(array('status' => "Validation error"));
            exit();
        }
        else{
            break;
        }
    }

    $info_source_items = array();
    $i = 0;
    while(true){
        if(isset($_POST['info_source'][$i])){
            array_push($info_source_items, htmlspecialchars($_POST['info_source'][$i]));
            $i++;
        }
        else if($i < 3){
            echo json_encode(array('status' => "Validation error"));
            exit();
        }
        else{
            break;
        }
    }

    $nir = DataGateway::get_nir_by_user($USER->id, $work_id);

    if(!$nir){
        echo json_encode(array('status' => "Validation error"));
        exit();
    }

    $work_plan = DataGateway::get_work_plan_by_nir($work_id);

    if($work_plan){
        echo json_encode(array('status' => "Validation error"));
        exit();
    }

    $record = new stdClass();
    $record->theme = $work_theme;
    $record->goal = $work_goal;
    $record->nir_id = $work_id;
    $record->is_sign_user = 1;

    $work_plan_id = $DB->insert_record('nir_work_plans', $record, true);

    $work_content_items_records = array();
    $order = 1;
    foreach ($work_content_items as $item){
        $record = new stdClass();
        $record->text = $item;
        $record->type = 'C';
        $record->work_plan_id = $work_plan_id;
        $record->order_number = $order;

        array_push($work_content_items_records, $record);
        $order++;
    }

    $DB->insert_records('nir_work_plan_items', $work_content_items_records);

    $work_result_items_records = array();
    $order = 1;
    foreach ($work_result_items as $item){
        $record = new stdClass();
        $record->text = $item;
        $record->type = 'R';
        $record->work_plan_id = $work_plan_id;
        $record->order_number = $order;

        array_push($work_result_items_records, $record);
        $order++;
    }

    $DB->insert_records('nir_work_plan_items', $work_result_items_records);

    $info_source_items_records = array();
    $order = 1;
    foreach ($info_source_items as $item){
        $record = new stdClass();
        $record->text = $item;
        $record->type = 'I';
        $record->work_plan_id = $work_plan_id;
        $record->order_number = $order;

        array_push($info_source_items_records, $record);
        $order++;
    }

    $DB->insert_records('nir_work_plan_items', $info_source_items_records);

    $record_user_info = new stdClass();
    $record_user_info->user_id = $USER->id;
    $record_user_info->work_plan_id = $work_plan_id;
    $record_user_info->name = $ex_name;
    $record_user_info->surname = $ex_surname;
    $record_user_info->patronymic = $ex_patronymic;
    $record_user_info->phone_number = $ex_phone_number;
    $record_user_info->email = $ex_email;
    $DB->insert_record('nir_user_info', $record_user_info, false);

    $record_teacher_info = new stdClass();
    $record_teacher_info->work_plan_id = $work_plan_id;
    $record_teacher_info->type = 'T';
    $record_teacher_info->name = $th_name;
    $record_teacher_info->surname = $th_surname;
    $record_teacher_info->patronymic = $th_patronymic;
    $record_teacher_info->phone_number = $th_phone_number;
    $record_teacher_info->email = $th_email;
    $record_teacher_info->place_work = $th_place_work;
    $record_teacher_info->position_work = $th_position_work;
    $record_teacher_info->academic_title = $th_academic_title;
    $record_teacher_info->academic_degree = $th_academic_degree;
    $DB->insert_record('nir_teacher_info', $record_teacher_info, false);

    if(isset($_POST['cn_surname']) && isset($_POST['cn_name']) && isset($_POST['cn_patronymic']) && isset($_POST['cn_phone_number']) &&
            isset($_POST['cn_email']) && isset($_POST['cn_place_work']) && isset($_POST['cn_position_work']) &&
            isset($_POST['cn_academic_title']) && isset($_POST['cn_academic_degree'])){

        $record_consultant_info = new stdClass();
        $record_consultant_info->work_plan_id = $work_plan_id;
        $record_consultant_info->type = 'C';
        $record_consultant_info->name = htmlspecialchars($_POST['cn_name']);
        $record_consultant_info->surname = htmlspecialchars($_POST['cn_surname']);
        $record_consultant_info->patronymic = htmlspecialchars($_POST['cn_patronymic']);
        $record_consultant_info->phone_number = htmlspecialchars($_POST['cn_phone_number']);
        $record_consultant_info->email = htmlspecialchars($_POST['cn_email']);
        $record_consultant_info->place_work = htmlspecialchars($_POST['cn_place_work']);
        $record_consultant_info->position_work = htmlspecialchars($_POST['cn_position_work']);
        $record_consultant_info->academic_title = htmlspecialchars($_POST['cn_academic_title']);
        $record_consultant_info->academic_degree = htmlspecialchars($_POST['cn_academic_degree']);
        $DB->insert_record('nir_teacher_info', $record_consultant_info, false);
    }

    $message = "Создано задание на НИР и отправлено научному руководителю.";
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

    echo json_encode(array('status' => "Ok", 'data' => render_work_plan_view($work_id), 'messages' => $messages_data,
        'alert' => "Задание на НИР создано и отправлено научному руководителю."));
}
else {
    echo json_encode(array('status' => "Validation error"));
}

?>