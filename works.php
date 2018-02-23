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
$node_works_list = $mainnode->add("Список работ студента", new moodle_url('/nirtest/works.php?std='.(isset($_GET["std"]) ? $_GET["std"] : "error")));
$node_works_list->make_active();
$node_students_list = $mainnode->add("Список студентов", new moodle_url('/nirtest/index.php'));
$node_students_list->make_inactive();

echo $OUTPUT->header();

$content = '';

if (!isset($_GET["std"]) || !intval($_GET['std'])){
        echo html_writer::tag('h3', '404 NOT FOUND');
        echo $OUTPUT->footer();
        exit();
}

$student_id = $_GET["std"];

// Page kafedra
if($USER->profile[Config::FIELD_USER_TYPE_NAME] === Config::USER_TYPE_KAFEDRA) {
    // Page kafedra select student
    $student_info = DataGateway::get_student_info($student_id);

    if (!$student_info) {
        echo html_writer::tag('h3', '404 NOT FOUND');
        echo $OUTPUT->footer();
        exit();
    }

    $content .= html_writer::tag('h1', 'Научно-исследовательские работы');

    $content .= Render::render_student_info($student_info);

    $works = DataGateway::get_list_nir_by_student($student_id);

    foreach ($works as $wk) {
        $url = "/nirtest/work.php?std=" . $student_id . "&id=" . $wk->id;

        $content .= html_writer::start_tag('a', array('href' => $url));
        $content .= html_writer::start_tag('div', array('class' => $wk->is_closed == 1 ? 'work_block work_block_closed' : 'work_block'));

        $content .= Render::render_header_work_block($wk);

        $content .= html_writer::end_tag('div');
        $content .= html_writer::end_tag('a');
    }
}
else if($USER->profile[Config::FIELD_USER_TYPE_NAME] === Config::USER_TYPE_TEACHER) { //page for teacher
    $works = DataGateway::get_list_nir_by_student_and_teacher($student_id, $USER->id);

    $content .= html_writer::tag('h1', 'Научно-исследовательские работы');

    foreach ($works as $wk){
        $count_new_file = DataGateway::get_number_new_files_uploaded_user_by_nir($USER->id, $wk->id);

        $work_plan = DataGateway::get_work_plan_by_nir($wk->id);
        $work_plan_exist = ($work_plan->is_sign_user == 1 && $work_plan->is_sign_teacher == 0 && $work_plan->is_sign_kaf == 0) ? true : false;

        $url = '/nirtest/work.php?std='.$wk->student_id.'&id='.$wk->id;

        $content .= html_writer::start_tag('a', array('href' => $url));
        $content .= html_writer::start_tag('div', array('class' => $wk->is_closed == 1 ? 'work_block work_block_closed' : 'work_block'));

        $content .= Render::render_header_work_block($wk, true);

        if($wk->is_closed == 0)
            $content .= Render::render_work_block_title_new_files($count_new_file, $work_plan_exist);

        $content .= html_writer::end_tag('div');
        $content .= html_writer::end_tag('a');
    }
}
else{
    $content .= html_writer::tag('h3', '404 NOT FOUND');
}

echo $content;
echo $OUTPUT->footer();
?>