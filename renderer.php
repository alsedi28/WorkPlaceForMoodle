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
        $tab_content .= render_work_plan($user, $work_id);
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

function render_work_plan($user, $work_id){
    global $DB;
    $content = '';
    $content .= html_writer::tag('h2', 'Задание на НИР', array('class' => '', 'style' => 'text-align: center; color: rgba(0,0,0,.54);'));

    if($user->profile['isTeacher'] === "0"){//if page for student
        $sql_work_plan = "SELECT mdl_nir.id, mdl_user.firstname, mdl_user.lastname, mdl_nir_work_plans.theme, mdl_nir_work_plans.goal FROM mdl_nir, mdl_user, mdl_nir_work_plans 
                            WHERE mdl_user.id=".$user->id." AND mdl_nir.user_id=mdl_user.id AND mdl_nir.id=".$work_id." AND mdl_nir_work_plans.nir_id=mdl_nir.id AND mdl_nir.is_closed=0";
        $rs = $DB->get_records_sql($sql_work_plan);

        if(count($rs) == 0){
            $content .= render_work_plan_create($work_id);
        }
        else{
            $content .= render_work_plan_view($work_id);
        }
    }

    return $content;
}

function render_work_plan_view($work_id){
    global $DB;
    global $USER;

    $sql_work_plan_info = "SELECT * FROM mdl_nir_work_plans WHERE mdl_nir_work_plans.nir_id=".$work_id;
    $work_plan_info = $DB->get_record_sql($sql_work_plan_info);

    $sql_user_info = "SELECT * FROM mdl_nir_user_info WHERE mdl_nir_user_info.work_plan_id=".$work_plan_info->id." AND mdl_nir_user_info.user_id=".$USER->id;
    $user_info = $DB->get_record_sql($sql_user_info);

    $sql_teacher_info = "SELECT * FROM mdl_nir_teacher_info WHERE mdl_nir_teacher_info.work_plan_id=".$work_plan_info->id." AND mdl_nir_teacher_info.type='T'";
    $teacher_info = $DB->get_record_sql($sql_teacher_info);

    $sql_consultant_info = "SELECT * FROM mdl_nir_teacher_info WHERE mdl_nir_teacher_info.work_plan_id=".$work_plan_info->id." AND mdl_nir_teacher_info.type='C'";
    $consultant_info = $DB->get_record_sql($sql_consultant_info);

    $sql_work_plan_items = "SELECT * FROM mdl_nir_work_plan_items WHERE mdl_nir_work_plan_items.work_plan_id=".$work_plan_info->id;
    $work_plan_items = $DB->get_records_sql($sql_work_plan_items);

    print_r($work_plan_items);
    $content = '';
    $content .= html_writer::start_tag('div', array('class' => 'form_work_plan'));

    $content .= html_writer::start_tag('div', array('class' => 'man_block'));

    $content .= html_writer::tag('h3', 'Исполнитель', array('class' => 'header_block'));

    $content .= render_work_plan_input_block_view('Фамилия', $user_info->surname);
    $content .= render_work_plan_input_block_view('Имя', $user_info->name);
    $content .= render_work_plan_input_block_view('Отчество', $user_info->patronymic);
    $content .= render_work_plan_input_block_view('Номер телефона', $user_info->phone_number);
    $content .= render_work_plan_input_block_view('Электронная почта', $user_info->email);

    $content .= html_writer::end_tag('div');//end executor_block

    $content .= html_writer::start_tag('div', array('class' => 'man_block'));

    $content .= html_writer::tag('h3', 'Научный руководитель', array('class' => 'header_block'));

    $content .= render_work_plan_input_block_view('Фамилия', $teacher_info->surname);
    $content .= render_work_plan_input_block_view('Имя', $teacher_info->name);
    $content .= render_work_plan_input_block_view('Отчество', $teacher_info->patronymic);
    $content .= render_work_plan_input_block_view('Номер телефона', $teacher_info->phone_number);
    $content .= render_work_plan_input_block_view('Электронная почта', $teacher_info->email);
    $content .= render_work_plan_input_block_view('Место работы', $teacher_info->place_work);
    $content .= render_work_plan_input_block_view('Должность', $teacher_info->position_work);
    $content .= render_work_plan_input_block_view('Учёное звание', $teacher_info->academic_title);
    $content .= render_work_plan_input_block_view('Учёная степень', $teacher_info->academic_degree);

    $content .= html_writer::end_tag('div');//end teacher_block

    $content .= html_writer::start_tag('div', array('class' => 'man_block'));
    if($consultant_info){
        $content .= html_writer::tag('h3', 'Консультант', array('class' => 'header_block'));

        $content .= render_work_plan_input_block_view('Фамилия', $consultant_info->surname);
        $content .= render_work_plan_input_block_view('Имя', $consultant_info->name);
        $content .= render_work_plan_input_block_view('Отчество', $consultant_info->patronymic);
        $content .= render_work_plan_input_block_view('Номер телефона', $consultant_info->phone_number);
        $content .= render_work_plan_input_block_view('Электронная почта', $consultant_info->email);
        $content .= render_work_plan_input_block_view('Место работы', $consultant_info->place_work);
        $content .= render_work_plan_input_block_view('Должность', $consultant_info->position_work);
        $content .= render_work_plan_input_block_view('Учёное звание', $consultant_info->academic_title);
        $content .= render_work_plan_input_block_view('Учёная степень', $consultant_info->academic_degree);
    }
    $content .= html_writer::end_tag('div');//end consultant_block

    $content .= html_writer::tag('div', '', array('style' => 'clear:both;'));

    $content .= html_writer::start_tag('div', array('class' => 'work_info_block'));
    $content .= html_writer::tag('h3', 'Общие сведения о работе', array('class' => 'header_block'));

    $content .= render_work_plan_input_block_view('Тема работы', $work_plan_info->theme, 'textarea_block_view');
    $content .= render_work_plan_input_block_view('Цель работы', $work_plan_info->goal, 'textarea_block_view');

    $content .= render_work_plan_list_view($work_plan_items);

    $content .= html_writer::end_tag('div');//end work_info_block
    $content .= html_writer::end_tag('div');

    return $content;
}

function render_work_plan_create($work_id){
    global $DB;
    global $USER;
    $sql_info = "SELECT mdl_user.firstname, mdl_user.lastname, mdl_user.email FROM mdl_nir, mdl_user 
                            WHERE mdl_nir.id=".$work_id." AND mdl_user.id=mdl_nir.teacher_id";

    $rs = $DB->get_record_sql($sql_info);

    $content = '';
    $content .= html_writer::start_tag('form', array('class' => 'form_work_plan', 'action' => 'javascript:void(null);', 'id' => 'form_plan'));

    $content .= html_writer::start_tag('div', array('class' => 'man_block'));

    $content .= html_writer::tag('h3', 'Исполнитель', array('class' => 'header_block'));

    $content .= render_work_plan_input_block('Фамилия', 'ex_surname', true, $USER->lastname, true);
    $content .= render_work_plan_input_block('Имя', 'ex_name', true, $USER->firstname, true);
    $content .= render_work_plan_input_block('Отчество', 'ex_patronymic', true);
    $content .= render_work_plan_input_block('Номер телефона', 'ex_phone_number', true);
    $content .= render_work_plan_input_block('Электронная почта', 'ex_email', true, $USER->email);

    $content .= html_writer::end_tag('div');//end executor_block

    $content .= html_writer::start_tag('div', array('class' => 'man_block'));

    $content .= html_writer::tag('h3', 'Научный руководитель', array('class' => 'header_block'));

    $content .= render_work_plan_input_block('Фамилия', 'th_surname', true, $rs->lastname, true);
    $content .= render_work_plan_input_block('Имя', 'th_name', true, $rs->firstname, true);
    $content .= render_work_plan_input_block('Отчество', 'th_patronymic', true);
    $content .= render_work_plan_input_block('Номер телефона', 'th_phone_number', true);
    $content .= render_work_plan_input_block('Электронная почта', 'th_email', true, $rs->email);
    $content .= render_work_plan_input_block('Место работы', 'th_place_work', true);
    $content .= render_work_plan_input_block('Должность', 'th_position_work', true);
    $content .= render_work_plan_selected_block('Учёное звание', 'th_academic_title', array('Доцент', 'Профессор',
        'Старший научный сотрудник', 'Ведущий научный сотрудник'));
    $content .= render_work_plan_selected_block('Учёная степень', 'th_academic_degree', array('Кандидат технических наук', 'Доктор технических наук',
        'Кандидат физико-математических наук', 'Доктор физико-математических наук'));

    $content .= html_writer::end_tag('div');//end teacher_block

    $content .= html_writer::start_tag('div', array('class' => 'man_block', 'id' => 'consultant_block'));

    $content .= html_writer::tag('div', 'Добавить консультанта', array('id' => 'button_add_consultant'));

    $content .= html_writer::end_tag('div');//end consultant_block

    $content .= html_writer::tag('div', '', array('style' => 'clear:both;'));

    $content .= html_writer::start_tag('div', array('class' => 'work_info_block'));
    $content .= html_writer::tag('h3', 'Общие сведения о работе', array('class' => 'header_block'));

    $content .= render_work_plan_textarea_block('Тема работы', 'work_theme', 2);
    $content .= render_work_plan_textarea_block('Цель работы', 'work_goal', 2);
    $content .= html_writer::empty_tag('hr', array('class' => 'separate_line'));
    $content .= render_work_plan_textarea_many_block('Содержание и основные этапы работы', 'work_content', 2);
    $content .= html_writer::empty_tag('hr', array('class' => 'separate_line'));
    $content .= render_work_plan_textarea_many_block('Ожидаемые результаты и формы их реализации', 'work_result', 2);
    $content .= html_writer::empty_tag('hr', array('class' => 'separate_line'));
    $content .= render_work_plan_textarea_many_block('Основные источники информации', 'info_source', 2);
    $content .= html_writer::empty_tag('hr', array('class' => 'separate_line'));

    $content .= html_writer::end_tag('div');//end work_info_block

    $content .= html_writer::empty_tag('input', array('type' => 'hidden', 'name' => 'work_id', 'value' => $work_id));
    $content .= html_writer::empty_tag('input', array('type' => 'submit', 'value' => 'Отправить на согласование научному руководителю', 'id' => 'submit_button_work_plan'));

    $content .= html_writer::end_tag('form');

    return $content;
}

function render_work_plan_input_block_view($label, $value, $class = 'input_block_view'){
    $content = '';

    $content .= html_writer::start_tag('div', array('class' => ''));
    $content .= html_writer::tag('label', $label, array('class' => 'label_block'));

    $content .= html_writer::tag('p', $value, array('class' => $class));
    $content .= html_writer::tag('div', '', array('style' => 'clear:both;'));
    $content .= html_writer::end_tag('div');

    return $content;
}

function render_work_plan_list_view($items){
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

    $content .= html_writer::empty_tag('hr', array('class' => 'separate_line separate_line_view'));
    $content .= render_list('Содержание и основные этапы работы', $work_content_items);
    $content .= html_writer::empty_tag('hr', array('class' => 'separate_line separate_line_view'));
    $content .= render_list('Ожидаемые результаты и формы их реализации', $work_result_items);
    $content .= html_writer::empty_tag('hr', array('class' => 'separate_line separate_line_view'));
    $content .= render_list('Основные источники информации', $info_source_items);
    $content .= html_writer::empty_tag('hr', array('class' => 'separate_line separate_line_view'));

    return $content;
}

function render_list($label, $items){
    $content = '';

    usort($items, 'sort_items');
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

function render_work_plan_input_block($label, $input_name, $required = false, $value = '', $readonly = false){
    $content = '';

    $content .= html_writer::start_tag('div', array('class' => ''));
    $content .= html_writer::start_tag('label',array('class' => 'label_block'));
    $content .= $label;
    if($required)
        $content .= html_writer::tag('span', ' *', array('style' => 'color:red;'));
    $content .= html_writer::end_tag('label');

    $params = array('type' => 'text', 'name' => $input_name, 'id' => $input_name, 'class' => 'input_block', 'value' => $value);
    if($required)
        $params['required'] = $required;
    if($readonly)
        $params['readonly'] = $readonly;

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

function render_work_plan_selected_block($label, $name, $options){
    $content = '';

    $content .= html_writer::start_tag('div', array('class' => ''));
    $content .= html_writer::tag('label', $label, array('class' => 'label_block label_select'));
    $content .= html_writer::start_tag('select', array('class' => 'select_block', 'name' => $name));
    $content .= html_writer::tag('option', 'Нет', array('value' => 'Нет', 'selected' => true));

    foreach ($options as $value){
        $content .= html_writer::tag('option', $value, array('value' => $value));
    }

    $content .= html_writer::end_tag('select');
    $content .= html_writer::tag('div', '', array('style' => 'clear:both;'));
    $content .= html_writer::end_tag('div');

    return $content;
}

function render_work_plan_textarea_many_block($label, $textarea_name, $rows, $required = true){
    $content = '';

    $content .= html_writer::start_tag('div', array('class' => $textarea_name.'_block root_block_point'));
    $content .= html_writer::start_tag('label',array('class' => 'label_block'));
    $content .= $label;
    if($required)
        $content .= html_writer::tag('span', ' *', array('style' => 'color:red;'));
    $content .= html_writer::empty_tag('br');
    $content .= html_writer::tag('span','Каждый пункт необходимо вводить в новое поле. Номера пунктов указать не нужно.', array('class' => 'title_many_textarea'));
    $content .= html_writer::end_tag('label');

    $count = 3;
    for($i = 0; $i < $count; $i++){
        $params = array('rows' => $rows,'name' => $textarea_name.'['.$i.']', 'class' => 'textarea_many_block');
        if($required)
            $params['required'] = $required;

        $content .= html_writer::start_tag('div', array('class' => 'textarea_many_div_block'));
        $content .= html_writer::tag('div', $i + 1, array('class' => 'number_point'));
        $content .= html_writer::tag('textarea', '', $params);

        $content .= html_writer::start_tag('div', array('class' => 'plus_input_block'));
        if($i !== 4 && $i == $count - 1){
            $content .= html_writer::start_tag('div', array('class' => 'plus_input', 'title' => 'Добавить пункт'));
            $content .= html_writer::empty_tag('img', array('src' => 'img/PlusIcon_Small_Gray.png', 'height' => '26px'));
            $content .= html_writer::end_tag('div');
        }
        $content .= html_writer::end_tag('div');

        $content .= html_writer::start_tag('div', array('class' => 'minus_input_block'));
        if($i > 2 && $i == $count - 1){
            $content .= html_writer::start_tag('div', array('class' => 'minus_input', 'title' => 'Удалить'));
            $content .= html_writer::empty_tag('img', array('src' => 'img/PlusIcon_Small_Gray.png', 'height' => '26px'));
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

function sort_items($a, $b)
{
    return $a->order_number > $b->order_number;
}
?>