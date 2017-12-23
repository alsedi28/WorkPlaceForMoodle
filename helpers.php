<?php

function update_work_plan_items($items, $work_plan_id, $collection_name, $item_type){
    global $DB;

    $work_items_records = array();
    $need_add_items = false;

    $i = 0;
    while(true){
        if(isset($_POST[$collection_name][$i])){
            if(count($items) >= ($i + 1)){
                if($_POST[$collection_name][$i] !== $items[$i]->text){
                    $update_work_item = new stdClass();
                    $update_work_item->id=$items[$i]->id;
                    $update_work_item->text=$_POST[$collection_name][$i];
                    $DB->update_record('nir_work_plan_items',$update_work_item);
                }
            }
            else{
                $record = new stdClass();
                $record->text = $_POST[$collection_name][$i];
                $record->type = $item_type;
                $record->work_plan_id = $work_plan_id;
                $record->order_number = ($i + 1);

                array_push($work_items_records, $record);
                $need_add_items = true;
            }
        }
        else{
            break;
        }

        $i++;
    }

    if(count($items) == 4 && !isset($_POST[$collection_name][4])){
        $DB->delete_records('nir_work_plan_items', array('work_plan_id' => $work_plan_id, 'type' => $item_type, 'order_number' => 4));
    }

    if(count($items) == 5 && !isset($_POST[$collection_name][5])){
        $DB->delete_records('nir_work_plan_items', array('work_plan_id' => $work_plan_id, 'type' => $item_type, 'order_number' => 5));
    }

    if($need_add_items){
        $DB->insert_records('nir_work_plan_items', $work_items_records);
    }
}

function update_teacher_info($teacher_info, $data){
    global $DB;

    $need_update_teacher_info = false;

    $update_teacher_info = new stdClass();
    $update_teacher_info->id=$teacher_info->id;

    if($data['patronymic'] !== $teacher_info->patronymic){
        $update_teacher_info->patronymic=$data['patronymic'];
        $need_update_teacher_info = true;
    }

    if($data['phone_number'] !== $teacher_info->phone_number){
        $update_teacher_info->phone_number=$data['phone_number'];
        $need_update_teacher_info = true;
    }

    if($data['email'] !== $teacher_info->email){
        $update_teacher_info->email=$data['email'];
        $need_update_teacher_info = true;
    }

    if($data['place_work'] !== $teacher_info->place_work){
        $update_teacher_info->place_work=$data['place_work'];
        $need_update_teacher_info = true;
    }

    if($data['position_work'] !== $teacher_info->position_work){
        $update_teacher_info->position_work=$data['position_work'];
        $need_update_teacher_info = true;
    }

    if($data['academic_title'] !== $teacher_info->academic_title){
        $update_teacher_info->academic_title=$data['academic_title'];
        $need_update_teacher_info = true;
    }

    if($data['academic_degree'] !== $teacher_info->academic_degree){
        $update_teacher_info->academic_degree=$data['academic_degree'];
        $need_update_teacher_info = true;
    }

    if($need_update_teacher_info)
        $DB->update_record('nir_teacher_info',$update_teacher_info);
}

function sort_items($a, $b)
{
    return $a->order_number > $b->order_number;
}

?>