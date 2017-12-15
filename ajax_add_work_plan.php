<?php
require_once(dirname(__FILE__) . '/../config.php');
header('Content-type: application/json');

if(isset($_POST['work_id']) && isset($_POST['ex_surname']) && isset($_POST['ex_name'])&& isset($_POST['ex_patronymic'])&& isset($_POST['ex_phone_number']) && isset($_POST['ex_email']) &&
        isset($_POST['th_surname']) && isset($_POST['th_name']) && isset($_POST['th_patronymic']) && isset($_POST['th_phone_number']) && isset($_POST['th_email']) &&
        isset($_POST['th_place_work']) && isset($_POST['th_position_work']) && isset($_POST['th_academic_title']) && isset($_POST['th_academic_degree']) && isset($_POST['work_theme']) &&
        isset($_POST['work_goal'])){
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
    while(true){
        if(isset($_POST['work_content['.$i.']'])){
            array_push($work_content_items, $_POST['work_content['.$i.']']);
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
        if(isset($_POST['work_result['.$i.']'])){
            array_push($work_result_items, $_POST['work_result['.$i.']']);
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
        if(isset($_POST['info_source['.$i.']'])){
            array_push($info_source_items, $_POST['info_source['.$i.']']);
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
}
else {
    echo json_encode(array('status' => "Validation error"));
    exit();
}


?>