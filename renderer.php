<?php

function render_modal_dialog_create_work($teachers, $user_id){
    $dialog = '';
    $dialog .= html_writer::start_tag('a', array('href' => '#win1'));
    $dialog .= html_writer::tag('div', 'Создать НИР', array('id' => 'button_create_nir'));
    $dialog .= html_writer::end_tag('a');

    $dialog .= html_writer::tag('a', array('href' => '#x', 'class' => 'overlay', 'id' => 'win1'));

    $dialog .= html_writer::start_tag('div', array('class' => 'popup'));
    $dialog .= html_writer::start_tag('div');
    $dialog .= html_writer::tag('h2', 'Создание НИР', array('style' => 'text-align:center'));

    $dialog .= html_writer::start_tag('form', array('id' => 'form_create_nir', 'method' => 'post', 'action' => 'create_work.php'));
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

function render_work_block_title_new_files($count_new_file){
    if ($count_new_file->count > 0){
        $title_file_m=" новых файлов";
        if($count_new_file->count==1){
            $title_file_m=" новый файл";
        }
        else if($count_new_file->count>1 && $count_new_file->count<5){
            $title_file_m=" новых файла";
        }

        return html_writer::tag('p', 'Добавлено '.$count_new_file->count.$title_file_m, array('class' => 'new_file_message'));
    }
}

function render_header_work_block($work, $is_student = false){
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

function render_student_info($student){
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

function render_kafedra_tab($file, $messages, $result, $work_id, $need_review = false){
    $tab_content = '';
    $tab_content .= html_writer::start_tag('div', array('id' => 'content'));
    $tab_content .= html_writer::start_tag('div', array('class' => 'block_files_kaf'));

    if($file){
        $tab_content .= html_writer::start_tag('a', array('class' => 'a_file_block a_file_block_kaf', 'target' => '_blank', 'href' => $file->filename));
        $tab_content .= html_writer::start_tag('div', array('class' => 'block_file_prev_kaf'));

        $tab_content .= html_writer::empty_tag('img', array('src' => 'img/Filetype-Docs-icon.png', 'height' => '110px', 'class' => 'img_files_prev'));
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

    if($file){
        $tab_content .= html_writer::start_tag('div', array('class' => 'block_files_sign_kaf'));
        $tab_content .= html_writer::tag('div', 'Подписать', array('class' => $file->is_sign_kaf == 1 ? 'sign_kaf_button sign_kaf_button_not_active' : 'sign_kaf_button'));
        $tab_content .= html_writer::tag('div', 'Отклонить', array('class' => 'cancel_kaf_button'));
        $tab_content .= html_writer::end_tag('div');
        $tab_content .= html_writer::tag('br');
    }

    $tab_content .= html_writer::empty_tag('input', array('type' => 'hidden', 'name' => 'h_work', 'id' => $need_review ? 'h_work_2' : 'h_work', 'value' => $work_id));
    $tab_content .= html_writer::empty_tag('input', array('type' => 'hidden', 'name' => 'h_work_type', 'id' => $need_review ? 'h_work_type_2' : 'h_work_type', 'value' => $need_review ? 'O' : 'Z'));

    if($need_review){ //need delete (failed js when try get *_3)
        $tab_content .= html_writer::empty_tag('input', array('type' => 'hidden', 'name' => 'h_work', 'id' => 'h_work_3', 'value' => $work_id));
        $tab_content .= html_writer::empty_tag('input', array('type' => 'hidden', 'name' => 'h_work_type', 'id' => 'h_work_type_3', 'value' => 'O'));
    }

    if($result[$work_id]->review != "" && $result[$work_id]->mark != null){
        $tab_content .= html_writer::start_tag('div', array('id' => 'review_block_header'));
        $tab_content .= html_writer::tag('p', 'Отзыв научного руководителя', array('class' => 'review_header_title'));
        $tab_content .= html_writer::end_tag('div');

        $tab_content .= html_writer::start_tag('div', array('id' => 'review_block', 'style' => 'height: auto'));
        $tab_content .= html_writer::tag('p', 'Отзыв', array('class' => 'ex_review_title'));
        $tab_content .= html_writer::tag('p', $result[$work_id]->review, array('class' => 'ex_review_text'));
        $tab_content .= html_writer::start_tag('p', array('class' => 'ex_mark'));
        $tab_content .= 'Оценка (по 5-ти балльной шкале): ';
        $tab_content .= html_writer::tag('span', $result[$work_id]->mark);
        $tab_content .= html_writer::end_tag('div');
    }

    if(!(count($messages) == 0 && $result[$work_id]->is_closed == 1)){
        $tab_content .= html_writer::start_tag('div', array('class' => 'messages'));

        foreach ($messages as $mz){
            $tab_content .= html_writer::start_tag('div', array('class' => 'message'));
            $tab_content .= html_writer::start_tag('div', array('class' => 'header_message'));
            $tab_content .= html_writer::tag('p', 'Кафедра', array('class' => 'header_message_name'));
            $tab_content .= html_writer::tag('p', $mz->date, array('class' => 'header_message_date'));
            $tab_content .= html_writer::tag('div', '', array('style' => 'clear:both;'));
            $tab_content .= html_writer::end_tag('div');
            $tab_content .= html_writer::tag('p', $mz->text, array('class' => 'message_text'));
            $tab_content .= html_writer::end_tag('div');
        }

        if($result[$work_id]->is_closed != 1){
            $tab_content .= html_writer::start_tag('div', array('class' => 'textar_message_new'));
            $tab_content .= html_writer::tag('textarea', '', array('rows' => '3', 'name' => 'message', 'id' => $need_review ? 'message_textarea_tab2' : 'message_textarea_tab1', 'class' => 'send_block_message', 'style' => 'resize: none;', 'required' => true));
            $tab_content .= html_writer::start_tag('button', array('class' => 'send_message_button', 'id' => $need_review ? 'send_message_tab2' : 'send_message_tab1'));
            $tab_content .= html_writer::empty_tag('img', array('class' => 'send_icon', 'src' => 'img/send_icon.png', 'width' => '50px'));
            $tab_content .= html_writer::end_tag('button');
            $tab_content .= html_writer::end_tag('div');
        }

        $tab_content .= html_writer::end_tag('div');
    }

    $tab_content .= html_writer::end_tag('div');
    return $tab_content;
}

function render_tab($files, $messages, $result, $user, $work_id, $options){
    $ADMIN = 2;
    $tab_content = '';
    $tab_content .= html_writer::start_tag('div', array('class' => $options["tab_number"] === 1 ? 'tab active' : 'tab', 'id' => $options["tab_id"]));//tab1 tab2

    $tab_content .= html_writer::start_tag('div', array('id' => 'content'));
    $tab_content .= html_writer::start_tag('div', array('class' => $options["tab_number"] === 1 ? 'block_work_plan' : 'block_files'));

    if($options["tab_number"] === 1){
        $tab_content .= render_work_plan();
    }
    else {
        $i = 1;
        $total = count($files);
        $flag = true;

        foreach ($files as $file) {
            $height_block = '';
            if ($options["tab_number"] !== 3 && $result[$work_id]->is_closed == 0 && (($total == $i || $file->is_sign_teacher == 1) && $user->profile['isTeacher'] === "1" && $flag) || ($file->is_sign_teacher == 1 && $user->profile['isTeacher'] !== "1" && $user->profile['isTeacher'] !== "666")) {
                $height_block = 'height:340px';
            }
            $tab_content .= html_writer::start_tag('div', array('class' => 'block_file_prev', 'style' => $height_block));
            $tab_content .= html_writer::start_tag('a', array('class' => 'a_file_block', 'target' => '_blank', 'href' => $file->filename));
            $tab_content .= html_writer::empty_tag('img', array('src' => $options["image_path"], 'height' => '110px', 'class' => 'img_files_prev'));//img/Filetype-Docs-icon.png
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

            if ($options["tab_number"] !== 3 && $file->is_sign_teacher == 1 && $user->profile['isTeacher'] !== "1" && $user->profile['isTeacher'] !== "666") {
                $tab_content .= html_writer::tag('br');
                $tab_content .= html_writer::tag('p', 'Файл подписан научным руководителем. Ожидает подтверждения от кафедры.', array('class' => 'file_date'));
            }

            $tab_content .= html_writer::end_tag('a');

            if ($options["tab_number"] !== 3 && $result[$work_id]->is_closed == 0 && ($total == $i || $file->is_sign_teacher == 1) && $user->profile['isTeacher'] === "1" && $flag) {
                if ($total != $i)
                    $flag = false;

                $tab_content .= html_writer::start_tag('div', array('class' => 'block_files_sign_teacher'));
                $tab_content .= html_writer::tag('div', 'Подписать', array('class' => ($file->is_sign_teacher == 1 || ($options["tab_number"] === 2 && ($result[$work_id]->review == "" || $result[$work_id]->mark == ""))) ? 'sign_button_teacher sign_teacher_button_not_active' : 'sign_button_teacher'));
                $tab_content .= html_writer::tag('div', 'Отклонить', array('class' => $file->is_sign_teacher == 0 ? 'cancel_button_teacher sign_teacher_button_not_active' : 'cancel_button_teacher'));
                $tab_content .= html_writer::end_tag('div');
            }

            $tab_content .= html_writer::end_tag('div');

            $i++;
        }
    }

    $tab_content .= html_writer::tag('div', '', array('style' => 'clear:both;'));
    $tab_content .= html_writer::end_tag('div');

    if($result[$work_id]->is_closed != 1 && $options["tab_number"] !== 1){
        $tab_content .= html_writer::empty_tag('input', array('type' => 'file', 'name' => 'files[]', 'id' => $options["filer_input_id"]));//filer_input2 filer_input1 filer_input3
    }

    $tab_content .= html_writer::empty_tag('input', array('type' => 'hidden', 'name' => 'h_work', 'id' => $options["work_input_id"], 'value' => $work_id)); // h_work h_work_2 h_work_3
    $tab_content .= html_writer::empty_tag('input', array('type' => 'hidden', 'name' => 'h_work_type', 'id' => $options["work_input_type"], 'value' => $options["work_type"]));// h_work_type h_work_type_2 h_work_type_3 Z O P

    if($options["tab_number"] === 2){
        if($user->profile['isTeacher'] === "1"){
            $tab_content .= html_writer::start_tag('div', array('id' => 'review_block_header'));
            $tab_content .= html_writer::tag('p', 'Отзыв научного руководителя', array('class' => 'review_header_title'));
            $tab_content .= html_writer::end_tag('div');

            $height_review_block = '';
            if($result[$work_id]->review != "" && $result[$work_id]->mark != ""){
                $height_review_block = 'height:auto';
            }
            $tab_content .= html_writer::start_tag('div', array('id' => 'review_block', 'style' => $height_review_block));

            if($result[$work_id]->review == "" || $result[$work_id]->mark == ""){
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
                $tab_content .= html_writer::tag('p', $result[$work_id]->review, array('class' => 'ex_review_text'));

                $tab_content .= html_writer::start_tag('p', array('class' => 'ex_mark'));
                $tab_content .= 'Оценка (по 5-ти балльной шкале): ';
                $tab_content .= html_writer::tag('span', $result[$work_id]->mark);
                $tab_content .= html_writer::end_tag('p');
            }

            $tab_content .= html_writer::end_tag('div');
        }
    }

    if(!(count($messages) == 0 && $result[$work_id]->is_closed == 1)){
        $tab_content .= html_writer::start_tag('div', array('class' => 'messages'));

        foreach ($messages as $mz){
            $tab_content .= html_writer::start_tag('div', array('class' => 'message'));
            $tab_content .= html_writer::start_tag('div', array('class' => $mz->user_id == $ADMIN ? 'header_message header_message_kaf' :'header_message'));
            $tab_content .= html_writer::tag('p', $mz->user_id == $ADMIN ? 'Кафедра' : $mz->lastname." ".$mz->firstname, array('class' => 'header_message_name'));
            $tab_content .= html_writer::tag('p', $mz->date, array('class' => 'header_message_date'));
            $tab_content .= html_writer::tag('div', '', array('style' => 'clear:both;'));
            $tab_content .= html_writer::end_tag('div');
            $tab_content .= html_writer::tag('p', $mz->text, array('class' => 'message_text'));
            $tab_content .= html_writer::end_tag('div');
        }

        if($result[$work_id]->is_closed != 1){
            $tab_content .= html_writer::start_tag('div', array('class' => 'textar_message_new'));
            $tab_content .= html_writer::tag('textarea', '', array('rows' => '3', 'name' => 'message', 'id' => $options["message_textarea_id"], 'class' => 'send_block_message', 'style' => 'resize: none;', 'required' => true));//message_textarea_tab1 message_textarea_tab2 message_textarea_tab3
            $tab_content .= html_writer::start_tag('button', array('class' => 'send_message_button', 'id' => $options["send_message_id"]));//send_message_tab1 send_message_tab2 send_message_tab3
            $tab_content .= html_writer::empty_tag('img', array('class' => 'send_icon', 'src' => 'img/send_icon.png', 'width' => '50px'));
            $tab_content .= html_writer::end_tag('button');
            $tab_content .= html_writer::end_tag('div');
        }

        $tab_content .= html_writer::end_tag('div');
    }

    $tab_content .= html_writer::end_tag('div');
    $tab_content .= html_writer::end_tag('div');

    return $tab_content;
}

function render_work_plan(){
        $content = '';
		$content .= html_writer::tag('h2', 'Задание на НИР', array('class' => '', 'style' => 'text-align: center; color: rgba(0,0,0,.54);'));
        
        $content .= html_writer::start_tag('form', array('class' => 'form_work_plan'));
        
        $content .= html_writer::start_tag('div', array('class' => 'man_block'));
        
        $content .= html_writer::tag('h3', 'Исполнитель', array('class' => 'header_block'));

        $content .= render_work_plan_input_block('Фамилия', 'ex_surname', true);
        $content .= render_work_plan_input_block('Имя', 'ex_name', true);
        $content .= render_work_plan_input_block('Отчество', 'ex_patronymic', true);
        $content .= render_work_plan_input_block('Номер телефона', 'ex_phone_number', true);
        $content .= render_work_plan_input_block('Электронная почта', 'ex_email', true);

        $content .= html_writer::end_tag('div');//end executor_block
        
        $content .= html_writer::start_tag('div', array('class' => 'man_block'));
        
        $content .= html_writer::tag('h3', 'Научный руководитель', array('class' => 'header_block'));

        $content .= render_work_plan_input_block('Фамилия', 'th_surname', true);
        $content .= render_work_plan_input_block('Имя', 'th_name', true);
        $content .= render_work_plan_input_block('Отчество', 'th_patronymic', true);
        $content .= render_work_plan_input_block('Номер телефона', 'th_phone_number', true);
        $content .= render_work_plan_input_block('Электронная почта', 'th_email', true);
        
        $content .= html_writer::end_tag('div');//end teacher_block

        $content .= html_writer::start_tag('div', array('class' => 'man_block', 'id' => 'consultant_block'));

        $content .= html_writer::tag('div', 'Добавить консультанта', array('id' => 'button_add_consultant'));

        $content .= html_writer::end_tag('div');//end consultant_block
        
        $content .= html_writer::tag('div', '', array('style' => 'clear:both;'));

        $content .= html_writer::start_tag('div', array('class' => 'work_info_block'));
        $content .= html_writer::tag('h3', 'Общие сведения о работе', array('class' => 'header_block'));

        $content .=render_work_plan_textarea_block('Тема работы', 'work_theme', 2);
        $content .=render_work_plan_textarea_block('Цель работы', 'work_goal', 2);
        $content .=render_work_plan_textarea_block('Содержание и основные этапы работы', 'work_content', 4);
        $content .=render_work_plan_textarea_block('Ожидаемые результаты и формы их реализации', 'work_result', 4);
        $content .=render_work_plan_textarea_block('Основные источники информации', 'info_source', 4);

        $content .= html_writer::end_tag('div');//end work_info_block
        
        $content .= html_writer::end_tag('form');
		
		return $content;
}

function render_work_plan_input_block($label, $input_name, $required = false){
    $content = '';

    $content .= html_writer::start_tag('div', array('class' => ''));
    $content .= html_writer::start_tag('label',array('class' => 'label_block'));
    $content .= $label;
    if($required)
        $content .= html_writer::tag('span', ' *', array('style' => 'color:red;'));
    $content .= html_writer::end_tag('label');

    $params = array('type' => 'text', 'name' => $input_name, 'id' => $input_name, 'class' => 'input_block');
    if($required)
        $params['required'] = $required;

    $content .= html_writer::empty_tag('input', $params);
    $content .= html_writer::tag('div', '', array('style' => 'clear:both;'));
    $content .= html_writer::end_tag('div');

    return $content;
}

function render_work_plan_textarea_block($label, $textarea_name, $rows, $required = true){
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

    $content .= html_writer::tag('textarea', '', $params);
    $content .= html_writer::tag('div', '', array('style' => 'clear:both;'));
    $content .= html_writer::end_tag('div');

    return $content;
}

?>