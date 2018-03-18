<?php
require_once(dirname(__FILE__) . '/../config.php');
require_once('class.config.php');

class Helper
{
    public static function update_work_plan_items($items_current, $items_new,  $work_plan_id, $item_type){
        global $DB;
        $changed_items = array("add" => array(), "remove" => array(), "mod" => array());

        $work_items_records = array();
        $need_add_items = false;

        $i = 0;
        while(true){
            if(array_key_exists($i, $items_new)){
                if(count($items_current) >= ($i + 1)){
                    if($items_new[$i] !== $items_current[$i]->text){
                        $update_work_item = new stdClass();
                        $update_work_item->id=$items_current[$i]->id;
                        $update_work_item->text=$items_new[$i];
                        $DB->update_record('nir_work_plan_items',$update_work_item);
                        array_push($changed_items["mod"], ($i + 1));
                    }
                }
                else{
                    $record = new stdClass();
                    $record->text = $items_new[$i];
                    $record->type = $item_type;
                    $record->work_plan_id = $work_plan_id;
                    $record->order_number = ($i + 1);

                    array_push($work_items_records, $record);
                    array_push($changed_items["add"], ($i + 1));
                    $need_add_items = true;
                }
            }
            else{
                break;
            }

            $i++;
        }

        if(count($items_current) == 4 && !array_key_exists(3, $items_new)){
            $DB->delete_records('nir_work_plan_items', array('work_plan_id' => $work_plan_id, 'type' => $item_type, 'order_number' => 4));
            array_push($changed_items["remove"], 4);
        }

        if(count($items_current) == 5 && !array_key_exists(4, $items_new)){
            $DB->delete_records('nir_work_plan_items', array('work_plan_id' => $work_plan_id, 'type' => $item_type, 'order_number' => 5));
            array_push($changed_items["remove"], 5);
        }

        if($need_add_items){
            $DB->insert_records('nir_work_plan_items', $work_items_records);
        }

        return $changed_items;
    }

    public static function update_teacher_info($teacher_info, $data){
        global $DB;
        global $local;

        $changed_fields = array();
        $need_update_teacher_info = false;

        $update_teacher_info = new stdClass();
        $update_teacher_info->id=$teacher_info->id;

        if(array_key_exists("name", $data) && $data['name'] !== $teacher_info->name){
            $update_teacher_info->name=$data['name'];
            array_push($changed_fields, $local['name']);
            $need_update_teacher_info = true;
        }

        if(array_key_exists("surname", $data) && $data['surname'] !== $teacher_info->surname){
            $update_teacher_info->surname=$data['surname'];
            array_push($changed_fields, $local['surname']);
            $need_update_teacher_info = true;
        }

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

    public static function get_messages($work_id, $type, $last_date){
        $messages = null;
        if ($last_date != NULL){
            $messages = DataGateway::get_comments_by_date($last_date, $work_id, $type);
        }
        else{
            $messages = DataGateway::get_comments($work_id, $type);
        }

        $messages_data = self::render_messages($messages);

        return $messages_data;
    }

    public static function get_messages_for_kaf($work_id, $type, $last_date){
        global $USER;
        $messages = null;

        if ($last_date != NULL){
            $messages = DataGateway::get_comments_for_kafedra_by_date($last_date, $work_id, $USER->id, $type);
        }
        else{
            $messages = DataGateway::get_comments_for_kafedra($work_id, $USER->id, $type);
        }

        $messages_data = self::render_messages($messages, true);

        return $messages_data;
    }

    public static function render_messages($messages, $is_for_kaf = false){
        $messages_data = '';

        foreach ($messages as $m){
            $messages_data .= html_writer::start_tag('div', array('class' => 'message'));
            $messages_data .= html_writer::start_tag('div', array('class' => ($m->id == Config::ADMIN || $is_for_kaf) ? 'header_message header_message_kaf' : 'header_message'));
            $messages_data .= html_writer::tag('p', ($m->id == Config::ADMIN || $is_for_kaf) ? 'Кафедра' : $m->lastname." ".$m->firstname, array('class' => 'header_message_name'));
            $messages_data .= html_writer::tag('p', $m->date, array('class' => 'header_message_date'));
            $messages_data .= html_writer::tag('div', '', array('style' => 'clear:both;'));
            $messages_data .= html_writer::end_tag('div');
            $messages_data .= html_writer::tag('p', $m->text, array('class' => 'message_text'));
            $messages_data .= html_writer::end_tag('div');
        }

        return $messages_data;
    }

    public static function build_message_edit_work_plan($message, $common_fields, $executor_fields, $teacher_fields, $consultant_fields,
                                          $work_content_items, $work_result_items, $info_source_items, $consultant_create){
        global $local;

        $text = $message;

        array_walk($executor_fields, 'self::concat_changed_field_name', $local['tag_executor']);
        array_walk($teacher_fields, 'self::concat_changed_field_name', $local['tag_teacher']);
        array_walk($consultant_fields, 'self::concat_changed_field_name', $local['tag_consultant']);

        $fields = array_merge($common_fields, $executor_fields, $teacher_fields, $consultant_fields);

        if(count($work_content_items["add"]) > 0 || count($work_content_items["remove"]) > 0 || count($work_content_items["mod"]) > 0){
            array_push($fields, Helper::create_message_for_changed_items($work_content_items, $local['work_content']));
        }

        if(count($work_result_items["add"]) > 0 || count($work_result_items["remove"]) > 0 || count($work_result_items["mod"]) > 0){
            array_push($fields, Helper::create_message_for_changed_items($work_result_items, $local['work_result']));
        }

        if(count($info_source_items["add"]) > 0 || count($info_source_items["remove"]) > 0 || count($info_source_items["mod"]) > 0){
            array_push($fields, Helper::create_message_for_changed_items($info_source_items, $local['info_source']));
        }

        if(count($fields) > 0){
            $text .= $local['beginning_message_changed_fields'].implode(", ", $fields).".";
        }

        if($consultant_create)
            $text .= $local['consultant_was_added'];

        return $text;
    }

    public static function create_message_for_changed_items($items, $title){
        global $local;
        $text = $title." (";

        $args = array();

        if(count($items["add"]) > 0){
            array_push($args, implode(", ", $items["add"])." - ".$local["tag_add"]);
        }

        if(count($items["remove"]) > 0){
            array_push($args, implode(", ", $items["remove"])." - ".$local["tag_remove"]);
        }

        if(count($items["mod"]) > 0){
            array_push($args, implode(", ", $items["mod"])." - ".$local["tag_mod"]);
        }

        $text .= implode("; ", $args);
        $text .= ")";

        return $text;
    }

    public static function sort_items($a, $b)
    {
        return $a->order_number > $b->order_number;
    }

    public static function synchronization_groups(){
        global $DB;

        $nir_groups = DataGateway::get_nir_groups();
        $groups = DataGateway::get_groups();

        $nir_groups_names = array_map("self::get_nir_group_name", $nir_groups);
        $groups_names = array_map("self::get_group_name", $groups);

        $new_groups = array();

        foreach ($groups_names as $group){
            if (!in_array($group, $nir_groups_names)) {
                $record = new stdClass();
                $record->group_name = $group;
                $record->is_active = 1;

                array_push($new_groups, $record);
            }
        }

        if(count($new_groups) > 0)
            $DB->insert_records('nir_groups', $new_groups);
    }

    public static function prepare_groups_for_output($groups){
        $active_groups = array();
        $not_active_groups = array();

        foreach ($groups as $group){
            if($group->is_active == 1)
                array_push($active_groups, $group);
            else
                array_push($not_active_groups, $group);
        }

        return array_merge($active_groups, $not_active_groups);
    }

    private static function concat_changed_field_name(&$item, $key, $prefix)
    {
        $item = "$item $prefix";
    }

    private static function get_nir_group_name($group){
        return $group->group_name;
    }

    private static function get_group_name($group){
        return $group->data;
    }
}