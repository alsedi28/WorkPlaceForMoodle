<?php
require_once(dirname(__FILE__) . '/../config.php');
require_once('class.helper.php');
require_once('class.config.php');
require_once('class.datagateway.php');

class Render
{
    public static function render_modal_dialog_create_work($teachers, $user_id){
        $dialog = '';
        $dialog .= html_writer::start_tag('a', array('href' => '#win1'));
        $dialog .= html_writer::tag('div', 'Создать НИР', array('id' => 'button_create_nir'));
        $dialog .= html_writer::end_tag('a');

        $dialog .= html_writer::tag('a','', array('href' => '#x', 'class' => 'overlay', 'id' => 'win1'));

        $dialog .= html_writer::start_tag('div', array('class' => 'popup'));
        $dialog .= html_writer::start_tag('div');
        $dialog .= html_writer::tag('h2', 'Создание НИР', array('style' => 'text-align:center'));

        $dialog .= html_writer::start_tag('form', array('id' => 'form_create_nir', 'method' => 'post', 'action' => 'ajax/create_work.php'));
        $dialog .= html_writer::tag('p', 'Выберите научного руководителя:', array('id' => 'modal_d_teacher'));

        $dialog .= html_writer::start_tag('p');
        $dialog .= html_writer::start_tag('select', array('name' => 'teacher', 'required' => true));

        foreach ($teachers as $teacher) {
            $dialog .= html_writer::tag('option', $teacher->lastname . " " . $teacher->firstname, array('value' => $teacher->id));
        }

        $dialog .= html_writer::end_tag('select');
        $dialog .= html_writer::end_tag('p');

        $dialog .= html_writer::tag('p', 'Введите название научно-исследовательской работы:', array('id' => 'modal_d_title'));
        $dialog .= html_writer::tag('textarea', '', array('rows' => '3', 'name' => 'cols', 'required' => true, 'style' => 'resize: none;', 'cols' => '55'));
        $dialog .= html_writer::empty_tag('input', array('type' => 'hidden', 'name' => 'user', 'value' => $user_id));
        $dialog .= html_writer::tag('br');
        $dialog .= html_writer::empty_tag('input', array('type' => 'submit', 'id' => 'submit_modal_form', 'value' => 'Создать'));

        $dialog .= html_writer::end_tag('form');
        $dialog .= html_writer::end_tag('div');
        $dialog .= html_writer::end_tag('div');

        return $dialog;
    }

    public static function render_work_block_title_new_files($count_new_file, $work_plan_exist){
        $content = "";
        if ($count_new_file->count > 0){
            $title_file_m=" новых файлов";
            if($count_new_file->count==1){
                $title_file_m=" новый файл";
            }
            else if($count_new_file->count>1 && $count_new_file->count<5){
                $title_file_m=" новых файла";
            }

            $content .= html_writer::tag('p', 'Добавлено '.$count_new_file->count.$title_file_m, array('class' => 'new_file_message'));
        }

        if($work_plan_exist){
            $content .= html_writer::tag('p', 'Загружено задание на НИР', array('class' => 'new_file_message'));
        }

        return $content;
    }

    public static function render_header_work_block($work, $is_student = false){
        $header = '';
        $header .= html_writer::start_tag('p', array('class' => 'work_title'));
        $header .= html_writer::tag('span', $is_student ? 'Студент: ' : 'Научный руководитель: ', array('class' => 'work_title_title'));
        $header .= $work->lastname." ".$work->firstname;
        $header .= html_writer::end_tag('p');

        $header .= html_writer::start_tag('p', array('class' => 'work_teacher'));
        $header .= html_writer::tag('span', 'Описание: ', array('class' => 'work_teacher_title'));
        $header .= $work->title;
        $header .= html_writer::end_tag('p');

        return $header;
    }

    public static function render_student_info($student){
        $header = '';
        $header .= html_writer::start_tag('p', array('class' => 'single_work_teacher'));
        $header .= html_writer::tag('span', 'Студент: ', array('class' => 'single_work_teacher_title'));
        $header .= $student->lastname." ".$student->firstname;
        $header .= html_writer::end_tag('p');

        $header .= html_writer::start_tag('p', array('class' => 'single_work_teacher'));
        $header .= html_writer::tag('span', 'Группа: ', array('class' => 'single_work_teacher_title'));
        $header .= $student->data;
        $header .= html_writer::end_tag('p');

        return $header;
    }

    public static function render_kafedra_tab_report($file, $messages, $result, $work_id){
        $tab_content = '';
        $tab_content .= html_writer::start_tag('div', array('id' => 'content'));
        $tab_content .= html_writer::tag('div', '', array('class' => 'message_container_block', 'id' => 'message_kaf'));
        $tab_content .= html_writer::start_tag('div', array('class' => 'block_files_kaf'));

        if($file){
            $tab_content .= html_writer::start_tag('a', array('class' => 'a_file_block a_file_block_kaf', 'target' => '_blank', 'href' => $file->filename));
            $tab_content .= html_writer::start_tag('div', array('class' => 'block_file_prev_kaf'));

            $tab_content .= html_writer::empty_tag('img', array('src' => 'img/docs_icon.png', 'height' => '110px', 'class' => 'img_files_prev'));
            $tab_content .= html_writer::start_tag('p', array('class' => 'file_date'));
            $tab_content .= html_writer::tag('span', 'Дата загрузки: ', array('style' => 'font-weight: bold'));
            $tab_content .= $file->date;
            $tab_content .= html_writer::end_tag('p');

            $tab_content .= html_writer::start_tag('p', array('class' => 'file_date'));
            $tab_content .= html_writer::tag('span', 'Добавил: ', array('style' => 'font-weight: bold'));
            $tab_content .= $file->lastname." ".$file->firstname;
            $tab_content .= html_writer::end_tag('p');

            $tab_content .= html_writer::empty_tag('input', array('type' => 'hidden', 'name' => 'file_id', 'id' => 'file_id', 'value' => $file->id));

            $tab_content .= html_writer::end_tag('div');
            $tab_content .= html_writer::end_tag('a');
        }
        else{
            $tab_content .= html_writer::tag('p', 'Работа еще не была загружена.', array('class' => 'work_not_load'));
        }

        $tab_content .= html_writer::tag('div', '', array('style' => 'clear:both;'));
        $tab_content .= html_writer::end_tag('div');

        if($file && $result->is_closed == 0){
            $tab_content .= html_writer::start_tag('div', array('class' => 'block_files_sign_kaf'));
            $tab_content .= html_writer::tag('div', 'Подписать', array('class' => $file->is_sign_kaf == 1 ? 'sign_kaf_button sign_kaf_button_not_active' : 'sign_kaf_button'));
            $tab_content .= html_writer::tag('div', 'Отклонить', array('class' => 'cancel_kaf_button'));
            $tab_content .= html_writer::end_tag('div');
            $tab_content .= html_writer::tag('br');
        }

        $tab_content .= html_writer::empty_tag('input', array('type' => 'hidden', 'name' => 'h_work', 'id' => 'h_work_2', 'value' => $work_id));
        $tab_content .= html_writer::empty_tag('input', array('type' => 'hidden', 'name' => 'h_work_type', 'id' => 'h_work_type_2', 'value' => 'O'));

        //need delete (failed js when try get *_3)
        $tab_content .= html_writer::empty_tag('input', array('type' => 'hidden', 'name' => 'h_work', 'id' => 'h_work_3', 'value' => $work_id));
        $tab_content .= html_writer::empty_tag('input', array('type' => 'hidden', 'name' => 'h_work_type', 'id' => 'h_work_type_3', 'value' => 'O'));

        if($result->review != "" && $result->mark != null){
            $tab_content .= html_writer::start_tag('div', array('id' => 'review_block_header'));
            $tab_content .= html_writer::tag('p', 'Отзыв научного руководителя', array('class' => 'review_header_title'));
            $tab_content .= html_writer::end_tag('div');

            $tab_content .= html_writer::start_tag('div', array('id' => 'review_block', 'style' => 'height: auto'));
            $tab_content .= html_writer::tag('p', 'Отзыв', array('class' => 'ex_review_title'));
            $tab_content .= html_writer::tag('p', $result->review, array('class' => 'ex_review_text'));
            $tab_content .= html_writer::start_tag('p', array('class' => 'ex_mark'));
            $tab_content .= 'Оценка (по 5-ти балльной шкале): ';
            $tab_content .= html_writer::tag('span', $result->mark);
            $tab_content .= html_writer::end_tag('div');
        }

        $tab_content .= html_writer::start_tag('div', array('class' => 'messages'));

        foreach ($messages as $mz){
            $tab_content .= html_writer::start_tag('div', array('class' => 'message'));
            $tab_content .= html_writer::start_tag('div', array('class' => 'header_message header_message_kaf'));
            $tab_content .= html_writer::tag('p', 'Кафедра', array('class' => 'header_message_name'));
            $tab_content .= html_writer::tag('p', $mz->date, array('class' => 'header_message_date'));
            $tab_content .= html_writer::tag('div', '', array('style' => 'clear:both;'));
            $tab_content .= html_writer::end_tag('div');
            $tab_content .= html_writer::tag('p', $mz->text, array('class' => 'message_text'));
            $tab_content .= html_writer::end_tag('div');
        }

        if($result->is_closed == 0){
            $tab_content .= html_writer::start_tag('div', array('class' => 'textar_message_new'));
            $tab_content .= html_writer::tag('textarea', '', array('rows' => '3', 'name' => 'message', 'id' => 'message_textarea_tab2', 'class' => 'send_block_message', 'style' => 'resize: none;', 'required' => true));
            $tab_content .= html_writer::start_tag('button', array('class' => 'send_message_button', 'id' => 'send_message_tab2'));
            $tab_content .= html_writer::empty_tag('img', array('class' => 'send_icon', 'src' => 'img/send_icon.png', 'width' => '50px'));
            $tab_content .= html_writer::end_tag('button');
            $tab_content .= html_writer::end_tag('div');
        }

        $tab_content .= html_writer::end_tag('div');

        $tab_content .= html_writer::end_tag('div');
        return $tab_content;
    }

    public static function render_kafedra_tab_work_plan($messages, $is_closed, $work_id){
        $tab_content = '';
        $tab_content .= html_writer::start_tag('div', array('id' => 'content'));
        $tab_content .= html_writer::start_tag('div', array('class' => 'block_work_plan'));

        $work_plan_info = DataGateway::get_work_plan_by_nir($work_id);

        $tab_content .= self::render_message_container($work_plan_info->is_sign_user, $work_plan_info->is_sign_teacher, $work_plan_info->is_sign_kaf);

        if($work_plan_info->is_sign_user && $work_plan_info->is_sign_teacher)
            $tab_content .= self::render_work_plan_view($work_id, $is_closed);

        $tab_content .= html_writer::empty_tag('input', array('type' => 'hidden', 'name' => 'h_work', 'id' => 'h_work', 'value' => $work_id)); // h_work h_work_2 h_work_3
        $tab_content .= html_writer::empty_tag('input', array('type' => 'hidden', 'name' => 'h_work_type', 'id' => 'h_work_type', 'value' => 'Z'));

        $tab_content .= html_writer::start_tag('div', array('class' => 'messages'));

        foreach ($messages as $mz){
            $tab_content .= html_writer::start_tag('div', array('class' => 'message'));
            $tab_content .= html_writer::start_tag('div', array('class' => 'header_message header_message_kaf'));
            $tab_content .= html_writer::tag('p', 'Кафедра', array('class' => 'header_message_name'));
            $tab_content .= html_writer::tag('p', $mz->date, array('class' => 'header_message_date'));
            $tab_content .= html_writer::tag('div', '', array('style' => 'clear:both;'));
            $tab_content .= html_writer::end_tag('div');
            $tab_content .= html_writer::tag('p', $mz->text, array('class' => 'message_text'));
            $tab_content .= html_writer::end_tag('div');
        }

        if($is_closed != 1){
            $tab_content .= html_writer::start_tag('div', array('class' => 'textar_message_new'));
            $tab_content .= html_writer::tag('textarea', '', array('rows' => '3', 'name' => 'message', 'id' => 'message_textarea_tab1', 'class' => 'send_block_message', 'style' => 'resize: none;', 'required' => true));
            $tab_content .= html_writer::start_tag('button', array('class' => 'send_message_button', 'id' => 'send_message_tab1'));
            $tab_content .= html_writer::empty_tag('img', array('class' => 'send_icon', 'src' => 'img/send_icon.png', 'width' => '50px'));
            $tab_content .= html_writer::end_tag('button');
            $tab_content .= html_writer::end_tag('div');
        }

        $tab_content .= html_writer::end_tag('div');

        $tab_content .= html_writer::end_tag('div');
        $tab_content .= html_writer::end_tag('div');
        return $tab_content;
    }

    public static function render_tab($files, $messages, $result, $user, $work_id, $options){
        $tab_content = '';
        $tab_content .= html_writer::start_tag('div', array('class' => $options["tab_number"] === 1 ? 'tab active' : 'tab', 'id' => $options["tab_id"]));//tab1 tab2

        $tab_content .= html_writer::start_tag('div', array('id' => 'content'));
        $tab_content .= html_writer::start_tag('div', array('class' => $options["tab_number"] === 1 ? 'block_work_plan' : 'block_files'));

        $flag = false;

        if($options["tab_number"] === 1){
            $tab_content .= self::render_work_plan($work_id);
        }
        else {
            $i = 1;
            $total = count($files);

            $tab_content .= html_writer::tag('div', '', array('class' => 'message_container_block'));

            foreach ($files as $file) {
                $height_block = '';
                if ($options["tab_number"] === 2 && ($file->is_sign_teacher == 1 || $file->is_rejected == 1 || ($total == $i && $result->is_closed == 0))) {
                    $height_block = 'height:330px';
                }
                $tab_content .= html_writer::start_tag('div', array('class' => 'block_file_prev_main'));
                $tab_content .= html_writer::start_tag('div', array('class' => 'block_file_prev', 'style' => $height_block));
                $tab_content .= html_writer::start_tag('a', array('class' => 'a_file_block', 'target' => '_blank', 'href' => $file->filename));
                $tab_content .= html_writer::empty_tag('img', array('src' => $options["image_path"], 'height' => '110px', 'class' => 'img_files_prev'));
                $tab_content .= html_writer::tag('p', $options["file_type_name"] . ' ' . $i, array('class' => 'file_name'));//Задание Отчет Презентация

                $tab_content .= html_writer::start_tag('p', array('class' => 'file_date'));
                $tab_content .= html_writer::tag('span', 'Дата загрузки: ', array('style' => 'font-weight: bold'));
                $tab_content .= $file->date;
                $tab_content .= html_writer::end_tag('p');

                $tab_content .= html_writer::start_tag('p', array('class' => 'file_date'));
                $tab_content .= html_writer::tag('span', 'Добавил: ', array('style' => 'font-weight: bold'));
                $tab_content .= $file->lastname . " " . $file->firstname;
                $tab_content .= html_writer::end_tag('p');

                $tab_content .= html_writer::empty_tag('input', array('type' => 'hidden', 'name' => 'file_id', 'id' => 'file_id', 'value' => $file->id));

                if ($file->is_new != 0 && $file->user_id != $user->id) {
                    $tab_content .= html_writer::empty_tag('img', array('src' => 'img/new.gif', 'height' => '30px', 'class' => 'img_new'));
                }

                if ($options["tab_number"] === 2 && ($file->is_sign_teacher == 1 || $file->is_rejected == 1)) {
                    $flag = true;
                    $text = 'Файл подписан научным руководителем. Ожидает подтверждения от кафедры.';

                    if($file->is_sign_kaf == 1)
                        $text = 'Файл утвержден кафедрой.';
                    else if($file->is_rejected == 1){
                        $text = 'Файл отклонен кафедрой.';
                        $flag = false;
                    }

                    $tab_content .= html_writer::tag('p', $text, array('class' => 'file_info_sign'));
                }

                $tab_content .= html_writer::end_tag('a');

                if ($options["tab_number"] === 2 && $result->is_closed == 0 && $total == $i && !$flag &&
                    $file->is_rejected == 0 && $user->profile[Config::FIELD_USER_TYPE_NAME] === Config::USER_TYPE_TEACHER) {

                    $tab_content .= html_writer::start_tag('div', array('class' => 'block_files_sign_teacher'));
                    $tab_content .= html_writer::tag('div', 'Подписать', array('class' => ($result->review == "" || $result->mark == "") ?
                        'sign_button_teacher sign_teacher_button_not_active' : 'sign_button_teacher'));
                    $tab_content .= html_writer::end_tag('div');
                }

                $tab_content .= html_writer::end_tag('div');
                $tab_content .= html_writer::end_tag('div');

                $i++;
            }
        }

        $tab_content .= html_writer::tag('div', '', array('style' => 'clear:both;'));
        $tab_content .= html_writer::end_tag('div');

        if($result->is_closed == 0 && ($options["tab_number"] === 3 || ($options["tab_number"] === 2 && !$flag))){
            $tab_content .= html_writer::empty_tag('input', array('type' => 'file', 'name' => 'files[]', 'id' => $options["filer_input_id"]));//filer_input2 filer_input1 filer_input3
        }

        $tab_content .= html_writer::empty_tag('input', array('type' => 'hidden', 'name' => 'h_work', 'id' => $options["work_input_id"], 'value' => $work_id)); // h_work h_work_2 h_work_3
        $tab_content .= html_writer::empty_tag('input', array('type' => 'hidden', 'name' => 'h_work_type', 'id' => $options["work_input_type"], 'value' => $options["work_type"]));// h_work_type h_work_type_2 h_work_type_3 Z O P

        if($options["tab_number"] === 2 && ($result->is_closed == 0 || ($result->is_closed == 1 && $result->review != "" || $result->mark != ""))){
            if($user->profile[Config::FIELD_USER_TYPE_NAME] === Config::USER_TYPE_TEACHER){
                $tab_content .= html_writer::start_tag('div', array('id' => 'review_block_header'));
                $tab_content .= html_writer::tag('p', 'Отзыв научного руководителя', array('class' => 'review_header_title'));
                $tab_content .= html_writer::end_tag('div');

                $height_review_block = '';
                if($result->review != "" && $result->mark != ""){
                    $height_review_block = 'height:auto';
                }
                $tab_content .= html_writer::start_tag('div', array('id' => 'review_block', 'style' => $height_review_block));

                if($result->review == "" || $result->mark == ""){
                    $tab_content .= html_writer::tag('p', 'Отзыв', array('class' => 'review_title'));
                    $tab_content .= html_writer::tag('textarea', '', array('rows' => '4', 'name' => 'review', 'id' => 'review_area', 'placeholder' => 'Введите отзыв...', 'style' => 'resize: none;', 'required' => true));
                    $tab_content .= html_writer::empty_tag('br');
                    $tab_content .= html_writer::tag('span', 'Оценка (по 5-ти балльной шкале)', array('class' => 'mark_title'));
                    $tab_content .= html_writer::empty_tag('input', array('type' => 'number', 'value' => '4', 'min' => '1', 'max' => '5', 'size' => '3', 'id' => 'mark_input'));
                    $tab_content .= html_writer::empty_tag('br');
                    $tab_content .= html_writer::tag('p', 'Отправить', array('id' => 'send_review'));
                }
                else{
                    $tab_content .= html_writer::tag('p', 'Отзыв', array('class' => 'ex_review_title'));
                    $tab_content .= html_writer::tag('p', $result->review, array('class' => 'ex_review_text'));

                    $tab_content .= html_writer::start_tag('p', array('class' => 'ex_mark'));
                    $tab_content .= 'Оценка (по 5-ти балльной шкале): ';
                    $tab_content .= html_writer::tag('span', $result->mark);
                    $tab_content .= html_writer::end_tag('p');
                }

                $tab_content .= html_writer::end_tag('div');
            }
        }

        $tab_content .= html_writer::start_tag('div', array('class' => 'messages'));

        if(count($messages) > 5){
            array_shift($messages);
            $tab_content .= html_writer::tag('div', 'Загрузить еще сообщения', array('class' => 'download_messages_block'));
        }

        foreach ($messages as $mz){
            $tab_content .= html_writer::start_tag('div', array('class' => 'message'));
            $tab_content .= html_writer::start_tag('div', array('class' => $mz->user_id == Config::ADMIN ? 'header_message header_message_kaf' :'header_message'));
            $tab_content .= html_writer::tag('p', $mz->user_id == Config::ADMIN ? 'Кафедра' : $mz->lastname." ".$mz->firstname, array('class' => 'header_message_name'));
            $tab_content .= html_writer::tag('p', $mz->date, array('class' => 'header_message_date'));
            $tab_content .= html_writer::tag('div', '', array('style' => 'clear:both;'));
            $tab_content .= html_writer::end_tag('div');
            $tab_content .= html_writer::tag('p', $mz->text, array('class' => 'message_text'));
            $tab_content .= html_writer::end_tag('div');
        }

        if($result->is_closed == 0){
            $tab_content .= html_writer::start_tag('div', array('class' => 'textar_message_new'));
            $tab_content .= html_writer::tag('textarea', '', array('rows' => '3', 'name' => 'message', 'id' => $options["message_textarea_id"], 'class' => 'send_block_message', 'style' => 'resize: none;', 'required' => true));//message_textarea_tab1 message_textarea_tab2 message_textarea_tab3
            $tab_content .= html_writer::start_tag('button', array('class' => 'send_message_button', 'id' => $options["send_message_id"]));//send_message_tab1 send_message_tab2 send_message_tab3
            $tab_content .= html_writer::empty_tag('img', array('class' => 'send_icon', 'src' => 'img/send_icon.png', 'width' => '50px'));
            $tab_content .= html_writer::end_tag('button');
            $tab_content .= html_writer::end_tag('div');
        }

        $tab_content .= html_writer::end_tag('div');

        $tab_content .= html_writer::end_tag('div');
        $tab_content .= html_writer::end_tag('div');

        return $tab_content;
    }

    public static function render_work_plan_view($work_id, $is_closed){
        global $USER;

        //JOIN
        /*    $sql = "SELECT wp.*, ui.*, ti.* FROM mdl_nir_work_plans AS wp
                      JOIN mdl_nir_user_info AS ui ON ui.work_plan_id = wp.id
                      JOIN mdl_nir_teacher_info AS ti ON ti.work_plan_id = wp.id AND ti.type = 'T' WHERE wp.nir_id = ?";*/

        $work_plan_info = DataGateway::get_work_plan_by_nir($work_id);
        $user_info = DataGateway::get_student_info_by_work_plan($work_plan_info->id);
        $teacher_info = DataGateway::get_teacher_info_by_work_plan($work_plan_info->id);
        $consultant_info = DataGateway::get_consultant_info_by_work_plan($work_plan_info->id);
        $work_plan_items = DataGateway::get_work_plan_items_by_id($work_plan_info->id);

        $content = '';
        $content .= html_writer::start_tag('div', array('class' => 'form_work_plan'));

        $content .= html_writer::start_tag('div', array('class' => 'man_block'));

        $content .= html_writer::tag('h3', 'Исполнитель', array('class' => 'header_block'));

        $content .= self::render_work_plan_input_block_view('Фамилия', $user_info->surname);
        $content .= self::render_work_plan_input_block_view('Имя', $user_info->name);
        $content .= self::render_work_plan_input_block_view('Отчество', $user_info->patronymic);
        $content .= self::render_work_plan_input_block_view('Номер телефона', $user_info->phone_number);
        $content .= self::render_work_plan_input_block_view('Электронная почта', $user_info->email);

        $content .= html_writer::end_tag('div');//end executor_block

        $content .= html_writer::start_tag('div', array('class' => 'man_block'));

        $content .= html_writer::tag('h3', 'Научный руководитель', array('class' => 'header_block'));

        $content .= self::render_work_plan_input_block_view('Фамилия', $teacher_info->surname);
        $content .= self::render_work_plan_input_block_view('Имя', $teacher_info->name);
        $content .= self::render_work_plan_input_block_view('Отчество', $teacher_info->patronymic);
        $content .= self::render_work_plan_input_block_view('Номер телефона', $teacher_info->phone_number);
        $content .= self::render_work_plan_input_block_view('Электронная почта', $teacher_info->email);
        $content .= self::render_work_plan_input_block_view('Место работы', $teacher_info->place_work);
        $content .= self::render_work_plan_input_block_view('Должность', $teacher_info->position_work);
        $content .= self::render_work_plan_input_block_view('Учёное звание', $teacher_info->academic_title);
        $content .= self::render_work_plan_input_block_view('Учёная степень', $teacher_info->academic_degree);

        $content .= html_writer::end_tag('div');//end teacher_block

        $content .= html_writer::start_tag('div', array('class' => 'man_block'));
        if($consultant_info){
            $content .= html_writer::tag('h3', 'Консультант', array('class' => 'header_block'));

            $content .= self::render_work_plan_input_block_view('Фамилия', $consultant_info->surname);
            $content .= self::render_work_plan_input_block_view('Имя', $consultant_info->name);
            $content .= self::render_work_plan_input_block_view('Отчество', $consultant_info->patronymic);
            $content .= self::render_work_plan_input_block_view('Номер телефона', $consultant_info->phone_number);
            $content .= self::render_work_plan_input_block_view('Электронная почта', $consultant_info->email);
            $content .= self::render_work_plan_input_block_view('Место работы', $consultant_info->place_work);
            $content .= self::render_work_plan_input_block_view('Должность', $consultant_info->position_work);
            $content .= self::render_work_plan_input_block_view('Учёное звание', $consultant_info->academic_title);
            $content .= self::render_work_plan_input_block_view('Учёная степень', $consultant_info->academic_degree);
        }
        $content .= html_writer::end_tag('div');//end consultant_block

        $content .= html_writer::tag('div', '', array('style' => 'clear:both;'));

        $content .= html_writer::start_tag('div', array('class' => 'work_info_block'));
        $content .= html_writer::tag('h3', 'Общие сведения о работе', array('class' => 'header_block'));

        $content .= self::render_work_plan_input_block_view('Тема работы', $work_plan_info->theme, 'textarea_block_view');
        $content .= self::render_work_plan_input_block_view('Цель работы', $work_plan_info->goal, 'textarea_block_view');

        $content .= self::render_work_plan_list($work_plan_items);

        $content .= html_writer::end_tag('div');//end work_info_block

        $content .= html_writer::empty_tag('input', array('type' => 'hidden', 'name' => 'work_id', 'value' => $work_id));

        if($is_closed == 0) {
            if($work_plan_info->is_sign_teacher == 0 && (($USER->profile[Config::FIELD_USER_TYPE_NAME] === Config::USER_TYPE_TEACHER && $work_plan_info->is_sign_user == 1) ||
                    ($USER->profile[Config::FIELD_USER_TYPE_NAME] !== Config::USER_TYPE_TEACHER && $work_plan_info->is_sign_user == 0)))
                $content .= html_writer::empty_tag('input', array('type' => 'button', 'value' => 'Редактировать', 'id' => 'edit_button_work_plan', 'class' => 'work_plan_edit_button'));

            if($USER->profile[Config::FIELD_USER_TYPE_NAME] === Config::USER_TYPE_TEACHER && $work_plan_info->is_sign_user == 1 && $work_plan_info->is_sign_teacher == 0) {
                $content .= html_writer::empty_tag('input', array('type' => 'button', 'value' => 'Отправить на согласование кафедре', 'id' => 'send_work_plan_kaf',
                    'action_type' => 'only_send_to_kaf', 'class' => 'work_plan_edit_button'));
            }

            if($USER->profile[Config::FIELD_USER_TYPE_NAME] === Config::USER_TYPE_KAFEDRA && $work_plan_info->is_sign_user == 1 && $work_plan_info->is_sign_teacher == 1 && $work_plan_info->is_sign_kaf == 0) {
                $content .= html_writer::empty_tag('input', array('type' => 'button', 'value' => 'Подтвердить', 'id' => 'approve_work_plan_kaf', 'class' => 'work_plan_edit_button'));
                $content .= html_writer::empty_tag('input', array('type' => 'button', 'value' => 'Отклонить', 'id' => 'cancel_work_plan_kaf', 'class' => 'work_plan_edit_button'));
            }
        }

        $content .= html_writer::end_tag('div');

        return $content;
    }

    public static function render_work_plan_edit($work_id){
        global $USER;

        $work_plan_info = DataGateway::get_work_plan_by_nir($work_id);
        $user_info = DataGateway::get_student_info_by_work_plan($work_plan_info->id);
        $teacher_info = DataGateway::get_teacher_info_by_work_plan($work_plan_info->id);
        $consultant_info = DataGateway::get_consultant_info_by_work_plan($work_plan_info->id);
        $work_plan_items = DataGateway::get_work_plan_items_by_id($work_plan_info->id);

        $content = '';
        $content .= html_writer::start_tag('form', array('class' => 'form_work_plan', 'action' => 'javascript:void(null);', 'id' => 'form_plan_edit'));

        $content .= html_writer::start_tag('div', array('class' => 'man_block'));

        $content .= html_writer::tag('h3', 'Исполнитель', array('class' => 'header_block'));

        $content .= self::render_work_plan_input_block('Фамилия', 'ex_surname', true, $USER->lastname, true, 'text', 25, 'letters');
        $content .= self::render_work_plan_input_block('Имя', 'ex_name', true, $USER->firstname, true, 'text', 25, 'letters');
        $content .= self::render_work_plan_input_block('Отчество', 'ex_patronymic', true, $user_info->patronymic, false,'text', 25, 'letters');
        $content .= self::render_work_plan_input_block('Номер телефона', 'ex_phone_number', true, $user_info->phone_number, false, 'tel', 20, 'numbers');
        $content .= self::render_work_plan_input_block('Электронная почта', 'ex_email', true, $user_info->email, false, 'email', 30);

        $content .= html_writer::end_tag('div');//end executor_block

        $content .= html_writer::start_tag('div', array('class' => 'man_block'));

        $content .= html_writer::tag('h3', 'Научный руководитель', array('class' => 'header_block'));

        $content .= self::render_work_plan_input_block('Фамилия', 'th_surname', true, $teacher_info->surname, true, 'text', 25, 'letters');
        $content .= self::render_work_plan_input_block('Имя', 'th_name', true, $teacher_info->name, true, 'text', 25, 'letters');
        $content .= self::render_work_plan_input_block('Отчество', 'th_patronymic', true, $teacher_info->patronymic, false,'text', 25, 'letters');
        $content .= self::render_work_plan_input_block('Номер телефона', 'th_phone_number', true, $teacher_info->phone_number, false, 'tel', 20, 'numbers');
        $content .= self::render_work_plan_input_block('Электронная почта', 'th_email', true, $teacher_info->email, false, 'email', 30);
        $content .= self::render_work_plan_input_block('Место работы', 'th_place_work', true, $teacher_info->place_work, false, 'text', 50);
        $content .= self::render_work_plan_input_block('Должность', 'th_position_work', true, $teacher_info->position_work, false, 'text', 50);

        $content .= self::render_work_plan_selected_block('Учёное звание', 'th_academic_title', array('Доцент', 'Профессор',
            'Старший научный сотрудник', 'Ведущий научный сотрудник'), $teacher_info->academic_title);
        $content .= self::render_work_plan_selected_block('Учёная степень', 'th_academic_degree', array('Кандидат технических наук', 'Доктор технических наук',
            'Кандидат физико-математических наук', 'Доктор физико-математических наук'), $teacher_info->academic_degree);

        $content .= html_writer::end_tag('div');//end teacher_block

        $content .= html_writer::start_tag('div', array('class' => 'man_block', 'id' => 'consultant_block'));
        if($consultant_info){
            $content .= html_writer::tag('h3', 'Консультант', array('class' => 'header_block'));

            $content .= self::render_work_plan_input_block('Фамилия', 'cn_surname', true, $consultant_info->surname, false,'text', 25, 'letters');
            $content .= self::render_work_plan_input_block('Имя', 'cn_name', true, $consultant_info->name, false,'text', 25, 'letters');
            $content .= self::render_work_plan_input_block('Отчество', 'cn_patronymic', true, $consultant_info->patronymic, false,'text', 25, 'letters');
            $content .= self::render_work_plan_input_block('Номер телефона', 'cn_phone_number', true, $consultant_info->phone_number, false, 'tel', 20, 'numbers');
            $content .= self::render_work_plan_input_block('Электронная почта', 'cn_email', true, $consultant_info->email, false, 'email', 30);
            $content .= self::render_work_plan_input_block('Место работы', 'cn_place_work', true, $consultant_info->place_work, false, 'text', 50);
            $content .= self::render_work_plan_input_block('Должность', 'cn_position_work', true, $consultant_info->position_work, false, 'text', 50);

            $content .= self::render_work_plan_selected_block('Учёное звание', 'cn_academic_title', array('Доцент', 'Профессор',
                'Старший научный сотрудник', 'Ведущий научный сотрудник'), $consultant_info->academic_title);
            $content .= self::render_work_plan_selected_block('Учёная степень', 'cn_academic_degree', array('Кандидат технических наук', 'Доктор технических наук',
                'Кандидат физико-математических наук', 'Доктор физико-математических наук'), $consultant_info->academic_degree);
        }
        else{
            $content .= html_writer::tag('div', 'Добавить консультанта', array('id' => 'button_add_consultant'));
        }

        $content .= html_writer::end_tag('div');//end consultant_block

        $content .= html_writer::tag('div', '', array('style' => 'clear:both;'));

        $content .= html_writer::start_tag('div', array('class' => 'work_info_block'));
        $content .= html_writer::tag('h3', 'Общие сведения о работе', array('class' => 'header_block'));

        $content .= self::render_work_plan_textarea_block('Тема работы', 'work_theme', 2, true, $work_plan_info->theme);
        $content .= self::render_work_plan_textarea_block('Цель работы', 'work_goal', 2, true, $work_plan_info->goal);

        $content .= self::render_work_plan_list($work_plan_items, false);

        $content .= html_writer::empty_tag('input', array('type' => 'hidden', 'name' => 'work_id', 'value' => $work_id));
        $content .= html_writer::empty_tag('input', array('type' => 'hidden', 'name' => 'action', 'value' => ''));

        $text_button_edit = 'Отправить на согласование научному руководителю';
        $attr_button_type = 'send_to_teacher';
        if($USER->profile[Config::FIELD_USER_TYPE_NAME] === Config::USER_TYPE_TEACHER){
            $text_button_edit = 'Сохранить и отправить студенту';
            $attr_button_type = 'send_to_user';
            $content .= html_writer::empty_tag('input', array('type' => 'submit', 'value' => 'Отправить на согласование кафедре', 'id' => 'submit_edit_work_plan',
                'action_type' => 'send_to_kaf', 'class' => 'work_plan_edit_button'));
        }

        $content .= html_writer::empty_tag('input', array('type' => 'submit', 'value' => $text_button_edit, 'id' => 'submit_edit_work_plan',
            'action_type' => $attr_button_type, 'class' => 'work_plan_edit_button'));

        $content .= html_writer::empty_tag('input', array('type' => 'button', 'value' => 'Отменить', 'id' => 'cancel_edit_work_plan', 'class' => 'work_plan_edit_button'));

        return $content;
    }

    private static function render_work_plan_textarea_many_block($label, $textarea_name, $rows, $required = true, $items = null){
        $content = '';

        if($items !== null)
            usort($items, array('Helper','sort_items'));

        $content .= html_writer::start_tag('div', array('class' => $textarea_name.'_block root_block_point'));
        $content .= html_writer::start_tag('label',array('class' => 'label_block'));
        $content .= $label;
        if($required)
            $content .= html_writer::tag('span', ' *', array('style' => 'color:red;'));
        $content .= html_writer::empty_tag('br');
        $content .= html_writer::tag('span','Каждый пункт необходимо вводить в новое поле. Номера пунктов указывать не нужно.', array('class' => 'title_many_textarea'));
        $content .= html_writer::end_tag('label');

        $count = $items == null ? 3 : count($items);
        for($i = 0; $i < $count; $i++){
            $params = array('rows' => $rows,'name' => $textarea_name.'['.$i.']', 'class' => 'textarea_many_block');
            if($required)
                $params['required'] = $required;

            $val = $items == null ? '' : $items[$i]->text;
            $content .= html_writer::start_tag('div', array('class' => 'textarea_many_div_block'));
            $content .= html_writer::tag('div', $i + 1, array('class' => 'number_point'));
            $content .= html_writer::tag('textarea', $val, $params);

            $content .= html_writer::start_tag('div', array('class' => 'plus_input_block'));
            if($i !== 4 && $i == $count - 1){
                $content .= html_writer::start_tag('div', array('class' => 'plus_input', 'title' => 'Добавить пункт'));
                $content .= html_writer::empty_tag('img', array('src' => 'img/plus.png', 'height' => '26px'));
                $content .= html_writer::end_tag('div');
            }
            $content .= html_writer::end_tag('div');

            $content .= html_writer::start_tag('div', array('class' => 'minus_input_block'));
            if($i > 2 && $i == $count - 1){
                $content .= html_writer::start_tag('div', array('class' => 'minus_input', 'title' => 'Удалить'));
                $content .= html_writer::empty_tag('img', array('src' => 'img/minus.png', 'height' => '26px'));
                $content .= html_writer::end_tag('div');
            }
            $content .= html_writer::end_tag('div');
            $content .= html_writer::tag('div', '', array('style' => 'clear:both;'));

            $content .= html_writer::end_tag('div');
        }

        $content .= html_writer::tag('div', '', array('style' => 'clear:both;'));
        $content .= html_writer::end_tag('div');

        return $content;
    }

    private static function render_work_plan_selected_block($label, $name, $options, $selected_option = 'Нет'){
        $content = '';

        $content .= html_writer::start_tag('div', array('class' => ''));
        $content .= html_writer::tag('label', $label, array('class' => 'label_block label_select'));
        $content .= html_writer::start_tag('select', array('class' => 'select_block', 'name' => $name));

        $params_opt_not = array('value' => 'Нет');
        if($selected_option === 'Нет')
            $params_opt_not['selected'] = true;
        $content .= html_writer::tag('option', 'Нет', $params_opt_not);

        foreach ($options as $value){
            $params = array('value' => $value);

            if($value === $selected_option)
                $params['selected'] = true;

            $content .= html_writer::tag('option', $value, $params);
        }

        $content .= html_writer::end_tag('select');
        $content .= html_writer::tag('div', '', array('style' => 'clear:both;'));
        $content .= html_writer::end_tag('div');

        return $content;
    }

    private static function render_work_plan_textarea_block($label, $textarea_name, $rows, $required = true, $value = ''){
        $content = '';

        $content .= html_writer::start_tag('div', array('class' => ''));
        $content .= html_writer::start_tag('label',array('class' => 'label_block'));
        $content .= $label;
        if($required)
            $content .= html_writer::tag('span', ' *', array('style' => 'color:red;'));
        $content .= html_writer::end_tag('label');

        $params = array('rows' => $rows,'name' => $textarea_name, 'id' => $textarea_name, 'class' => 'textarea_block');
        if($required)
            $params['required'] = $required;

        $content .= html_writer::tag('textarea', $value, $params);
        $content .= html_writer::tag('div', '', array('style' => 'clear:both;'));
        $content .= html_writer::end_tag('div');

        return $content;
    }

    private static function render_work_plan_input_block($label, $input_name, $required = false, $value = '', $readonly = false, $type = 'text',
                                          $max_length = 100, $attr_type = 'text'){
        $content = '';

        $content .= html_writer::start_tag('div', array('class' => ''));
        $content .= html_writer::start_tag('label',array('class' => 'label_block'));
        $content .= $label;
        if($required)
            $content .= html_writer::tag('span', ' *', array('style' => 'color:red;'));
        $content .= html_writer::end_tag('label');

        $params = array('type' => $type, 'name' => $input_name, 'id' => $input_name, 'class' => 'input_block', 'value' => htmlspecialchars_decode($value),
            'maxlength' => $max_length, 'data-validation' => $attr_type);

        if($required)
            $params['required'] = $required;
        if($readonly)
            $params['readonly'] = $readonly;

        if($attr_type == "numbers"){
            $params['pattern'] = "[0-9]+";
            $params['title'] = "Поле должно содержать только цифры.";
        }

        if($type == "text"){
            $params['pattern'] = "^[^\s]{1}.*";
            $params['title'] = "Поле не должно начинаться или состоять только из пробелов.";
        }

        $content .= html_writer::empty_tag('input', $params);
        $content .= html_writer::tag('div', '', array('style' => 'clear:both;'));
        $content .= html_writer::end_tag('div');

        return $content;
    }

    private static function render_list($label, $items){
        $content = '';

        usort($items, array('Helper','sort_items'));
        $text = '';

        $i = 1;
        foreach($items as $item){
            $text .= $i.'. '.($item->text)."<br/>";
            $i++;
        }

        $content .= html_writer::start_tag('div', array('class' => ''));
        $content .= html_writer::tag('label', $label, array('class' => 'label_block'));

        $content .= html_writer::tag('p', $text, array('class' => 'textarea_block_view'));
        $content .= html_writer::tag('div', '', array('style' => 'clear:both;'));
        $content .= html_writer::end_tag('div');

        return $content;
    }

    private static function render_work_plan_list($items, $is_view = true){
        $content = '';

        $work_content_items = array();
        $work_result_items = array();
        $info_source_items = array();

        foreach ($items as $item) {
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

        if($is_view){
            $content .= html_writer::empty_tag('hr', array('class' => 'separate_line separate_line_view'));
            $content .= self::render_list('Содержание и основные этапы работы', $work_content_items);
            $content .= html_writer::empty_tag('hr', array('class' => 'separate_line separate_line_view'));
            $content .= self::render_list('Ожидаемые результаты и формы их реализации', $work_result_items);
            $content .= html_writer::empty_tag('hr', array('class' => 'separate_line separate_line_view'));
            $content .= self::render_list('Основные источники информации', $info_source_items);
            $content .= html_writer::empty_tag('hr', array('class' => 'separate_line separate_line_view'));
        }
        else{
            $content .= html_writer::empty_tag('hr', array('class' => 'separate_line separate_line_view'));
            $content .= self::render_work_plan_textarea_many_block('Содержание и основные этапы работы', 'work_content', 2, true, $work_content_items);
            $content .= html_writer::empty_tag('hr', array('class' => 'separate_line'));
            $content .= self::render_work_plan_textarea_many_block('Ожидаемые результаты и формы их реализации', 'work_result', 2, true, $work_result_items);
            $content .= html_writer::empty_tag('hr', array('class' => 'separate_line'));
            $content .= self::render_work_plan_textarea_many_block('Основные источники информации', 'info_source', 2, true, $info_source_items);
            $content .= html_writer::empty_tag('hr', array('class' => 'separate_line separate_line_view'));
        }

        return $content;
    }

    private static function render_work_plan_input_block_view($label, $value, $class = 'input_block_view'){
        $content = '';

        $content .= html_writer::start_tag('div', array('class' => ''));
        $content .= html_writer::tag('label', $label, array('class' => 'label_block'));

        $content .= html_writer::tag('p', $value, array('class' => $class));
        $content .= html_writer::tag('div', '', array('style' => 'clear:both;'));
        $content .= html_writer::end_tag('div');

        return $content;
    }

    private static function render_work_plan_create($work_id){
        global $USER;

        $teacher_info = DataGateway::get_teacher_info_by_nir($work_id);

        $content = '';
        $content .= html_writer::start_tag('form', array('class' => 'form_work_plan', 'action' => 'javascript:void(null);', 'id' => 'form_plan'));

        $content .= html_writer::start_tag('div', array('class' => 'man_block'));

        $content .= html_writer::tag('h3', 'Исполнитель', array('class' => 'header_block'));

        $content .= self::render_work_plan_input_block('Фамилия', 'ex_surname', true, $USER->lastname, true,'text', 25, 'letters');
        $content .= self::render_work_plan_input_block('Имя', 'ex_name', true, $USER->firstname, true,'text', 25, 'letters');
        $content .= self::render_work_plan_input_block('Отчество', 'ex_patronymic', true, '', false,'text', 25, 'letters');
        $content .= self::render_work_plan_input_block('Номер телефона', 'ex_phone_number', true, '',false, 'tel', 20, 'numbers');
        $content .= self::render_work_plan_input_block('Электронная почта', 'ex_email', true, $USER->email, false, 'email', 30);

        $content .= html_writer::end_tag('div');//end executor_block

        $content .= html_writer::start_tag('div', array('class' => 'man_block'));

        $content .= html_writer::tag('h3', 'Научный руководитель', array('class' => 'header_block'));

        $content .= self::render_work_plan_input_block('Фамилия', 'th_surname', true, $teacher_info->lastname, true,'text', 25, 'letters');
        $content .= self::render_work_plan_input_block('Имя', 'th_name', true, $teacher_info->firstname, true,'text', 25, 'letters');
        $content .= self::render_work_plan_input_block('Отчество', 'th_patronymic', true, '', false, 'text', 25, 'letters');
        $content .= self::render_work_plan_input_block('Номер телефона', 'th_phone_number', true, '', false, 'tel', 20, 'numbers');
        $content .= self::render_work_plan_input_block('Электронная почта', 'th_email', true, $teacher_info->email, false, 'email', 30);
        $content .= self::render_work_plan_input_block('Место работы', 'th_place_work', true, '', false, 'text', 50);
        $content .= self::render_work_plan_input_block('Должность', 'th_position_work', true, '', false, 'text', 50);
        $content .= self::render_work_plan_selected_block('Учёное звание', 'th_academic_title', array('Доцент', 'Профессор',
            'Старший научный сотрудник', 'Ведущий научный сотрудник'));
        $content .= self::render_work_plan_selected_block('Учёная степень', 'th_academic_degree', array('Кандидат технических наук', 'Доктор технических наук',
            'Кандидат физико-математических наук', 'Доктор физико-математических наук'));

        $content .= html_writer::end_tag('div');//end teacher_block

        $content .= html_writer::start_tag('div', array('class' => 'man_block', 'id' => 'consultant_block'));

        $content .= html_writer::tag('div', 'Добавить консультанта', array('id' => 'button_add_consultant'));

        $content .= html_writer::end_tag('div');//end consultant_block

        $content .= html_writer::tag('div', '', array('style' => 'clear:both;'));

        $content .= html_writer::start_tag('div', array('class' => 'work_info_block'));
        $content .= html_writer::tag('h3', 'Общие сведения о работе', array('class' => 'header_block'));

        $content .= self::render_work_plan_textarea_block('Тема работы', 'work_theme', 2);
        $content .= self::render_work_plan_textarea_block('Цель работы', 'work_goal', 2);
        $content .= html_writer::empty_tag('hr', array('class' => 'separate_line'));
        $content .= self::render_work_plan_textarea_many_block('Содержание и основные этапы работы', 'work_content', 2);
        $content .= html_writer::empty_tag('hr', array('class' => 'separate_line'));
        $content .= self::render_work_plan_textarea_many_block('Ожидаемые результаты и формы их реализации', 'work_result', 2);
        $content .= html_writer::empty_tag('hr', array('class' => 'separate_line'));
        $content .= self::render_work_plan_textarea_many_block('Основные источники информации', 'info_source', 2);
        $content .= html_writer::empty_tag('hr', array('class' => 'separate_line'));

        $content .= html_writer::end_tag('div');//end work_info_block

        $content .= html_writer::empty_tag('input', array('type' => 'hidden', 'name' => 'work_id', 'value' => $work_id));
        $content .= html_writer::empty_tag('input', array('type' => 'submit', 'value' => 'Отправить на согласование научному руководителю', 'id' => 'submit_button_work_plan', 'class' => 'work_plan_edit_button'));

        $content .= html_writer::end_tag('form');

        return $content;
    }

    private static function render_work_plan($work_id){
        global $USER;
        $content = '';
        $content .= html_writer::tag('h2', 'Задание на НИР', array('class' => '', 'style' => 'text-align: center; color: rgba(0,0,0,.54);'));

        $work_plan = DataGateway::get_work_plan_by_nir_and_user($work_id, $USER->id, false);
        $nir = DataGateway::get_nir_by_id($work_id);

        if($nir->is_closed == 1)
            $content .= self::render_message_container_with_text("Работа закрыта.");
        else if(!$work_plan)
            $content .= self::render_message_container(false, false, false, false);
        else
            $content .= self::render_message_container($work_plan->is_sign_user, $work_plan->is_sign_teacher, $work_plan->is_sign_kaf);

        if($work_plan){
            $content .= self::render_work_plan_view($work_id, $nir->is_closed);
        }
        else if($USER->profile[Config::FIELD_USER_TYPE_NAME] === Config::USER_TYPE_STUDENT && $nir->is_closed == 0){
            $content .= self::render_work_plan_create($work_id);
        }

        return $content;
    }

    private static function render_message_container($is_sign_user, $is_sign_teacher, $is_sign_kaf, $is_exist = true){
        global $USER;
        $content = '';
        $text = '';

        $empty_block = html_writer::tag('div', '', array('class' => 'message_container_block'));

        if($USER->profile[Config::FIELD_USER_TYPE_NAME] === Config::USER_TYPE_TEACHER){
            if($is_sign_user){
                if($is_sign_teacher){
                    if($is_sign_kaf){
                        $text = "Задание на НИР утверждено кафедрой.";
                    }
                    else{
                        $text = "Задание на НИР находится на рассмотрении у кафедры.";
                    }
                }
                else{
                    return $empty_block;
                }
            }
            else if($is_exist){
                return $empty_block;
            }
            else{
                $text = "Задание на НИР еще не было загружено студентом.";
            }
        }
        else if($USER->profile[Config::FIELD_USER_TYPE_NAME] === Config::USER_TYPE_STUDENT){
            if($is_sign_user){
                if($is_sign_teacher){
                    if($is_sign_kaf){
                        $text = "Задание на НИР утверждено кафедрой.";
                    }
                    else{
                        $text = "Задание на НИР находится на рассмотрении у кафедры.";
                    }
                }
                else{
                    $text = "Задание на НИР находится на рассмотрении у научного руководителя.";
                }
            }
            else{
                return $empty_block;
            }
        }
        else if($USER->profile[Config::FIELD_USER_TYPE_NAME] === Config::USER_TYPE_KAFEDRA){
            if($is_sign_user && $is_sign_teacher){
                if($is_sign_kaf)
                    $text = "Текущее задание на НИР согласовано кафедрой.";
                else
                    $text = "Текущее задание на НИР ожидает решения кафедры.";
            }
            else{
                $text = "Задание на НИР еще не было загружено студентом.";
            }
        }

        $content .= html_writer::start_tag('div', array('class' => 'message_container_block message_container'));
        $content .= html_writer::empty_tag('img', array('src' => 'img/information.png', 'class' => 'message_icon'));
        $content .= html_writer::tag('p', $text, array('class' => 'message_text'));
        $content .= html_writer::end_tag('div');

        return $content;
    }

    private static function render_message_container_with_text($text){
        $content = '';

        $content .= html_writer::start_tag('div', array('class' => 'message_container_block message_container'));
        $content .= html_writer::empty_tag('img', array('src' => 'img/information.png', 'class' => 'message_icon'));
        $content .= html_writer::tag('p', $text, array('class' => 'message_text'));
        $content .= html_writer::end_tag('div');

        return $content;
    }
}