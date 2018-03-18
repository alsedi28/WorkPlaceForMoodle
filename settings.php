<?php
// The number of lines in front of config file determine the // hierarchy of files.
require_once(dirname(__FILE__) . '/../config.php');
require_once('class.config.php');
require_once('class.helper.php');
require_once('class.datagateway.php');
require_once('class.render.php');
require_once('class.cssbuilder.php');

CssBuilder::build(array("settings_page", "switch_control"));

$context = context_user::instance($USER->id);
$PAGE->set_blocks_editing_capability('moodle/my:manageblocks');
$header = fullname($USER);

$PAGE->set_context($context);
$PAGE->set_pagelayout('incourse');
$PAGE->set_title("НИР");
$PAGE->set_heading($header);
$PAGE->set_url($CFG->wwwroot.'/nirtest/index.php');

$PAGE->requires->js('/nirtest/js/jquery-3.2.0.min.js', true);
$PAGE->requires->js('/nirtest/js/settingsPage.js', true);

if ($CFG->forcelogin) {
    require_login();
}

$mainnode = $PAGE->navigation->add("НИР", new moodle_url('/nirtest/index.php'), navigation_node::TYPE_CONTAINER);

if($USER->profile[Config::FIELD_USER_TYPE_NAME] === Config::USER_TYPE_KAFEDRA){
    $node_students_list = $mainnode->add("Настройки", new moodle_url('/nirtest/settings.php'));
    $node_students_list->make_active();
}

echo $OUTPUT->header();

$content = '';

if($USER->profile[Config::FIELD_USER_TYPE_NAME] === Config::USER_TYPE_KAFEDRA){
    Helper::synchronization_groups();

    $content .= html_writer::tag('h1', 'Настройки', array('id' => 'page_title'));

    $content .= html_writer::tag('h2', 'Группы', array('class' => 'settings_groups_title'));
    $content .= html_writer::empty_tag('hr', array('class' => 'settings_groups_hr'));

    $groups = DataGateway::get_groups();
    $groups = Helper::prepare_groups_for_output($groups);

    $content .= html_writer::start_tag('table', array('class' => 'groups_table'));

    $content .= html_writer::start_tag('tr');
    $content .= html_writer::tag('th', 'Номер группы');
    $content .= html_writer::tag('th', 'Не активная/Активная');
    $content .= html_writer::end_tag('tr');

    foreach ($groups as $group) {
        $content .= html_writer::start_tag('tr');
        $content .= html_writer::tag('td', $group->data);
        $content .= html_writer::start_tag('td');

        $content .= html_writer::start_tag('label', array('class' => 'switch'));

        $input_params = array('type' => 'checkbox', 'id' => $group->nir_group_id);
        if($group->is_active == 1)
            $input_params['checked'] = 'checked';

        $content .= html_writer::empty_tag('input', $input_params);
        $content .= html_writer::tag('span','', array('class' => 'slider round'));
        $content .= html_writer::end_tag('label');

        $content .= html_writer::end_tag('td');
        $content .= html_writer::end_tag('tr');
    }

    $content .= html_writer::end_tag('table');
}
else{
    $content .= '404 NOT FOUND';
}

echo $content;
echo $OUTPUT->footer();

?>