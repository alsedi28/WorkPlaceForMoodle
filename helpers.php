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
    global $local;

    $changed_fields = array();
    $need_update_teacher_info = false;

    $update_teacher_info = new stdClass();
    $update_teacher_info->id=$teacher_info->id;

    if($data['patronymic'] !== $teacher_info->patronymic){
        $update_teacher_info->patronymic=$data['patronymic'];
        array_push($changed_fields, $local['patronymic']);
        $need_update_teacher_info = true;
    }

    if($data['phone_number'] !== $teacher_info->phone_number){
        $update_teacher_info->phone_number=$data['phone_number'];
        array_push($changed_fields, $local['phone_number']);
        $need_update_teacher_info = true;
    }

    if($data['email'] !== $teacher_info->email){
        $update_teacher_info->email=$data['email'];
        array_push($changed_fields, $local['email']);
        $need_update_teacher_info = true;
    }

    if($data['place_work'] !== $teacher_info->place_work){
        $update_teacher_info->place_work=$data['place_work'];
        array_push($changed_fields, $local['place_work']);
        $need_update_teacher_info = true;
    }

    if($data['position_work'] !== $teacher_info->position_work){
        $update_teacher_info->position_work=$data['position_work'];
        array_push($changed_fields, $local['position_work']);
        $need_update_teacher_info = true;
    }

    if($data['academic_title'] !== $teacher_info->academic_title){
        $update_teacher_info->academic_title=$data['academic_title'];
        array_push($changed_fields, $local['academic_title']);
        $need_update_teacher_info = true;
    }

    if($data['academic_degree'] !== $teacher_info->academic_degree){
        $update_teacher_info->academic_degree=$data['academic_degree'];
        array_push($changed_fields, $local['academic_degree']);
        $need_update_teacher_info = true;
    }

    if($need_update_teacher_info)
        $DB->update_record('nir_teacher_info',$update_teacher_info);

    return $changed_fields;
}

function get_messages($work_id, $type, $last_date){
    global $DB;

    if ($last_date != NULL){
        $sql_messages = "SELECT mdl_nir_messages.text, mdl_nir_messages.date, mdl_user.firstname, mdl_user.lastname, mdl_user.id FROM mdl_nir_messages, mdl_user WHERE 
                          mdl_nir_messages.date > '".$last_date."' AND mdl_nir_messages.nir_id=".$work_id." AND mdl_user.id=mdl_nir_messages.user_id AND 
                          mdl_nir_messages.nir_type='".$type."'";
    }
    else{
        $sql_messages = "SELECT mdl_nir_messages.text, mdl_nir_messages.date, mdl_user.firstname, mdl_user.lastname FROM mdl_nir_messages, mdl_user WHERE 
                          mdl_nir_messages.nir_id=".$work_id." AND mdl_user.id=mdl_nir_messages.user_id AND mdl_nir_messages.nir_type='".$type."'";
    }

    $messages = $DB->get_records_sql($sql_messages);
    $messages_data = render_messages($messages);

    return $messages_data;
}

function get_messages_for_kaf($work_id, $type, $last_date){
    global $DB;
    global $USER;

    if ($last_date != NULL){
        $sql_messages = "SELECT mdl_nir_messages.text, mdl_nir_messages.date FROM mdl_nir_messages WHERE mdl_nir_messages.date > '".$last_date."' AND 
                            mdl_nir_messages.nir_id=".$work_id." AND mdl_nir_messages.user_id=".$USER->id." AND mdl_nir_messages.nir_type='".$type."'";
    }
    else{
        $sql_messages = "SELECT mdl_nir_messages.text, mdl_nir_messages.date FROM mdl_nir_messages WHERE mdl_nir_messages.nir_id=".$work_id." AND 
                            mdl_nir_messages.user_id=".$USER->id." AND mdl_nir_messages.nir_type='".$type."'";
    }

    $messages = $DB->get_records_sql($sql_messages);
    $messages_data = render_messages($messages, true);

    return $messages_data;
}

function render_messages($messages, $is_for_kaf = false){
    $ADMIN = 2;
    $messages_data = '';

    foreach ($messages as $m){
        $messages_data .= html_writer::start_tag('div', array('class' => 'message'));
        $messages_data .= html_writer::start_tag('div', array('class' => ($m->id == $ADMIN || $is_for_kaf) ? 'header_message header_message_kaf' : 'header_message'));
        $messages_data .= html_writer::tag('p', ($m->id == $ADMIN || $is_for_kaf) ? 'Кафедра' : $m->lastname." ".$m->firstname, array('class' => 'header_message_name'));
        $messages_data .= html_writer::tag('p', $m->date, array('class' => 'header_message_date'));
        $messages_data .= html_writer::tag('div', '', array('style' => 'clear:both;'));
        $messages_data .= html_writer::end_tag('div');
        $messages_data .= html_writer::tag('p', $m->text, array('class' => 'message_text'));
        $messages_data .= html_writer::end_tag('div');
    }

    return $messages_data;
}

function build_message_edit_work_plan($message, $common_fields, $executor_fields, $teacher_fields, $consultant_fields, $consultant_create = false){
    global $local;

    $text = $message;

    array_walk($executor_fields, 'concat_changed_field_name', $local['tag_executor']);
    array_walk($teacher_fields, 'concat_changed_field_name', $local['tag_teacher']);
    array_walk($consultant_fields, 'concat_changed_field_name', $local['tag_consultant']);

    $fields = array_merge($common_fields, $executor_fields, $teacher_fields, $consultant_fields);

    if(count($fields) > 0){
        $text .= $local['beginning_message_changed_fields'].implode(", ", $fields).".";
    }

    if($consultant_create)
        $text .= $local['consultant_was_added'];

    return $text;
}

function sort_items($a, $b)
{
    return $a->order_number > $b->order_number;
}

function concat_changed_field_name(&$item, $key, $prefix)
{
    $item = "$item $prefix";
}
?>