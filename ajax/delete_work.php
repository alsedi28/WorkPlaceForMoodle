<?php
require_once(dirname(__FILE__) . '/../../config.php');
require_once('../class.datagateway.php');
require_once('../class.config.php');

if($USER->profile[Config::FIELD_USER_TYPE_NAME] === Config::USER_TYPE_STUDENT && isset($_POST['work_id']) && intval($_POST['work_id']) != 0){
    $work_id = $_POST['work_id'];

    $work = DataGateway::get_nir_by_user($USER->id, $work_id);

    if($work){
        $work_plan = DataGateway::get_work_plan_by_nir_and_user($work_id, $USER->id);

        if(!$work_plan){
            $count_files_in_nir = DataGateway::get_number_files_by_nir($work_id);

            if($count_files_in_nir->count == 0){
                $DB->delete_records('nir', array('id' => $work_id));
            }
        }
    }
}
?>