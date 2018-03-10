<?php
// The number of lines in front of config file determine the // hierarchy of files.
require_once(dirname(__FILE__) . '/../config.php');
require_once('class.config.php');
require_once('class.datagateway.php');
require_once('class.render.php');
require_once('class.cssbuilder.php');

CssBuilder::build(array("menu_kaf", "modal_dialog", "tabs", "main", "work_plan_form"));

$context = context_user::instance($USER->id);
$PAGE->set_blocks_editing_capability('moodle/my:manageblocks');
$header = fullname($USER);

$PAGE->set_context($context);
$PAGE->set_pagelayout('incourse');
$PAGE->set_title("НИР");
$PAGE->set_heading($header);
$PAGE->set_url($CFG->wwwroot.'/nirtest/index.php');

$PAGE->requires->css('/nirtest/material/jquery.filer.css');
$PAGE->requires->css('/nirtest/material/jquery.filer-dragdropbox-theme.css');
$PAGE->requires->js('/nirtest/js/jquery-3.2.0.min.js', true);
$PAGE->requires->js('/nirtest/material/jquery.filer.min.js', true);
$PAGE->requires->js('/nirtest/js/MessageArea.js', true);
$PAGE->requires->js('/nirtest/js/resource.js', true);
$PAGE->requires->js('/nirtest/js/main.js', true);

if ($CFG->forcelogin) {
    require_login();
}

$mainnode = $PAGE->navigation->add("НИР", new moodle_url('/nirtest/index.php'), navigation_node::TYPE_CONTAINER);

$node_current_work = $mainnode->add("Открытая работа", new moodle_url('/nirtest/work.php?id='.(isset($_GET["id"]) ? $_GET["id"] : "error").(isset($_GET["std"]) ? "&std=".$_GET["std"] : "")));
$node_current_work->make_active();

if($USER->profile[Config::FIELD_USER_TYPE_NAME] === Config::USER_TYPE_KAFEDRA || $USER->profile[Config::FIELD_USER_TYPE_NAME] === Config::USER_TYPE_TEACHER){
    $node_students_list = $mainnode->add("Список студентов", new moodle_url('/nirtest/index.php'));
    $node_students_list->make_inactive();
}

$url_works_list = '/nirtest/works.php?std='.(isset($_GET["std"]) ? $_GET["std"] : "error");
$url_works_list_title = "Список работ студента";

if($USER->profile[Config::FIELD_USER_TYPE_NAME] === Config::USER_TYPE_STUDENT){
    $url_works_list = '/nirtest/index.php';
    $url_works_list_title = "Список работ";
}

$node_works_list = $mainnode->add($url_works_list_title, new moodle_url($url_works_list));
$node_works_list->make_inactive();

echo $OUTPUT->header();

$content = '';

if (!isset($_GET["id"]) || !intval($_GET['id']) ||
    ($USER->profile[Config::FIELD_USER_TYPE_NAME] === Config::USER_TYPE_KAFEDRA && !isset($_GET["std"])) ||
    (isset($_GET["std"]) && !intval($_GET['std']))) {
    echo html_writer::tag('h3', '404 NOT FOUND');
    echo $OUTPUT->footer();
    exit();
}

$work_id = $_GET["id"];

// Page kafedra
if($USER->profile[Config::FIELD_USER_TYPE_NAME] === Config::USER_TYPE_KAFEDRA) {
    $student_id = $_GET["std"];

    $student_info = DataGateway::get_student_info($student_id);

    if(!$student_info){
        echo html_writer::tag('h3', '404 NOT FOUND');
        echo $OUTPUT->footer();
        exit();
    }

    $work = DataGateway::get_nir_by_student($student_id, $work_id);

    if(!$work){
        echo html_writer::tag('h3', '404 NOT FOUND');
        echo $OUTPUT->footer();
        exit();
    }

    $content .= html_writer::tag('h1', 'Научно-исследовательская работа');

    $messages_type_z = DataGateway::get_comments_for_kafedra($work_id, $USER->id, 'Z');

    $file_type_o = DataGateway::get_file_for_kafedra($work_id, 'O');
    $messages_type_o = DataGateway::get_comments_for_kafedra($work_id, $USER->id, 'O');

    $content .= html_writer::start_tag('p', array('class' => 'single_work_teacher'));
    $content .= html_writer::tag('span', 'Научный руководитель: ', array('class' => 'single_work_teacher_title'));
    $content .= $work->lastname." ".$work->firstname;
    $content .= html_writer::end_tag('p');

    $content .= Render::render_student_info($student_info);

    $content .= html_writer::start_tag('div', array('class' => 'tabs'));

    $content .= html_writer::start_tag('ul', array('class' => 'tab-links'));
    $content .= html_writer::start_tag('li', array('class' => 'active'));
    $content .= html_writer::tag('a', 'Задание на НИР', array('href' => '#tab1'));
    $content .= html_writer::end_tag('li');
    $content .= html_writer::start_tag('li');
    $content .= html_writer::tag('a', 'Отчет', array('href' => '#tab2'));
    $content .= html_writer::end_tag('li');
    $content .= html_writer::end_tag('ul');

    $content .= html_writer::start_tag('div', array('class' => 'tab-content'));
    $content .= html_writer::start_tag('div', array('class' => 'tab active', 'id' => 'tab1'));

    $content .= Render::render_kafedra_tab_work_plan($messages_type_z, $work->is_closed, $work_id);

    $content .= html_writer::end_tag('div');

    $content .= html_writer::start_tag('div', array('class' => 'tab', 'id' => 'tab2'));

    $content .= Render::render_kafedra_tab_report($file_type_o, $messages_type_o, $work, $work_id); // tab2

    $content .= html_writer::end_tag('div');
    $content .= html_writer::end_tag('div');

    if ($work->is_closed == 0){
        $content .= html_writer::tag('p', 'Завершить работу', array('class' => 'finish_work_button'));

        $content .= Render::render_modal_dialog_finish_work($work_id);
    }
}
else{ //Page for teacher and student
    if(isset($_GET["std"])){
        $student_id = $_GET["std"];

        $work = DataGateway::get_nir_by_student_and_teacher($student_id, $USER->id, $work_id);
    }
    else{
        $work = DataGateway::get_nir_by_student($USER->id, $work_id);
    }

    if(!$work){
        echo html_writer::tag('h3', '404 NOT FOUND');
        echo $OUTPUT->footer();
        exit();
    }

    $messages_type_z = DataGateway::get_comments_limit($work_id, 'Z');

    $files_type_o = DataGateway::get_files_by_type($USER->id, $work_id, 'O');
    $messages_type_o = DataGateway::get_comments_limit($work_id, 'O');

    $files_type_p = DataGateway::get_files_by_type($USER->id, $work_id, 'P');
    $messages_type_p = DataGateway::get_comments_limit($work_id, 'P');

    $content .= html_writer::tag('h1', 'Научно-исследовательская работа');

    $content .= html_writer::start_tag('p', array('class' => 'single_work_title'));
    $content .= html_writer::tag('span', 'Описание: ', array('class' => 'single_work_title_title'));
    $content .= $work->title;
    $content .= html_writer::end_tag('p');

    if(isset($_GET["std"])){
        $content .= Render::render_student_info($work);
    }
    else{
        $content .= html_writer::start_tag('p', array('class' => 'single_work_teacher'));
        $content .= html_writer::tag('span', 'Научный руководитель: ', array('class' => 'single_work_teacher_title'));
        $content .= $work->lastname." ".$work->firstname;
        $content .= html_writer::end_tag('p');
    }

    $content .= html_writer::start_tag('div', array('class' => 'tabs'));

    $content .= html_writer::start_tag('ul', array('class' => 'tab-links'));
    $content .= html_writer::start_tag('li', array('class' => 'active'));
    $content .= html_writer::tag('a', 'Задание на НИР', array('href' => '#tab1'));
    $content .= html_writer::end_tag('li');
    $content .= html_writer::start_tag('li');
    $content .= html_writer::tag('a', 'Отчет', array('href' => '#tab2'));
    $content .= html_writer::end_tag('li');
    $content .= html_writer::start_tag('li');
    $content .= html_writer::tag('a', 'Презентация', array('href' => '#tab3'));
    $content .= html_writer::end_tag('li');
    $content .= html_writer::end_tag('ul');

    $content .= html_writer::start_tag('div', array('class' => 'tab-content'));

    $content .= Render::render_tab(null, $messages_type_z, $work, $USER, $work_id, array ('tab_id' => 'tab1', 'tab_number' => 1,
        'work_input_id' => 'h_work', 'work_input_type' => 'h_work_type', 'work_type' => 'Z',
        'message_textarea_id' => 'message_textarea_tab1', 'send_message_id' => 'send_message_tab1'));

    $content .= Render::render_tab($files_type_o, $messages_type_o, $work, $USER, $work_id, array ('tab_id' => 'tab2', 'tab_number' => 2, 'image_path' => 'img/docs_icon.png',
        'file_type_name' => 'Отчет', 'filer_input_id' => 'filer_input1', 'work_input_id' => 'h_work_2', 'work_input_type' => 'h_work_type_2', 'work_type' => 'O',
        'message_textarea_id' => 'message_textarea_tab2', 'send_message_id' => 'send_message_tab2'));

    $content .= Render::render_tab($files_type_p, $messages_type_p, $work, $USER, $work_id, array ('tab_id' => 'tab3', 'tab_number' => 3, 'image_path' => 'img/presentation_icon.png',
        'file_type_name' => 'Презентация', 'filer_input_id' => 'filer_input3', 'work_input_id' => 'h_work_3', 'work_input_type' => 'h_work_type_3', 'work_type' => 'P',
        'message_textarea_id' => 'message_textarea_tab3', 'send_message_id' => 'send_message_tab3'));


    $content .= html_writer::end_tag('div');
    $content .= html_writer::end_tag('div');
}

echo $content;
echo $OUTPUT->footer();
?>