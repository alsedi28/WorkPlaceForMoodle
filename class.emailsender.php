<?php
require_once(dirname(__FILE__) . '/../config.php');
require_once('class.config.php');
require_once('class.datagateway.php');

class EmailSender
{
    const NOREPLY_EMAIL = "noreply@int-kaf42.ru";

    //$supportuser = core_user::get_support_user(); get admin user

    public static function send_email_add_work_plan($user, $work_id)
    {
        $work = DataGateway::get_nir_by_id($work_id);

        $user_to = DataGateway::get_user($work->teacher_id);

        $noreply_user = self::create_noreply_user();

        $user_group = $user->profile[Config::FIELD_STUDENT_GROUP_NAME];
        $title = "Научно-исследовательская работа \"$work->title\"";
        $message = "Студент $user->lastname $user->firstname группы $user_group добавил \"Задание на НИР\" для работы \"$work->title\".";

        email_to_user($user_to, $noreply_user, $title, $message);
    }

    public static function send_email_edit_work_plan($user, $work_id, $type)
    {
        $work = DataGateway::get_nir_by_id($work_id);

        $user_to = NULL;
        $message = "";

        switch($type){
            case 1:
                $user_to = DataGateway::get_user(Config::KAFEDRA);
                $student = DataGateway::get_user($work->user_id);
                $message = "\"Задание на НИР\" для работы \"$work->title\" студента $student->lastname $student->firstname было отправлено на кафедру для согласования.";
                break;
            case 2:
                $user_to = DataGateway::get_user($work->user_id);
                $message = "\"Задание на НИР\" для работы \"$work->title\" было отправлено вам научным руководителем для доработки.";
                break;
            case 3:
                $user_to = DataGateway::get_user($work->teacher_id);
                $student_group = $user->profile[Config::FIELD_STUDENT_GROUP_NAME];
                $message = "Студент $user->lastname $user->firstname группы $student_group отправил вам \"Задание на НИР\" для работы \"$work->title\".";
                break;
        }

        if($user_to == NULL)
            return;

        $noreply_user = self::create_noreply_user();

        $title = "Научно-исследовательская работа \"$work->title\"";
        email_to_user($user_to, $noreply_user, $title, $message);
    }

    public static function send_email_upload_file($user, $work_id, $file_type)
    {
        $work = DataGateway::get_nir_by_id($work_id);

        $user_to = NULL;
        $message = "";
        $type = NULL;

        switch($file_type){
            case "O":
                $type = "новый отчёт";
                break;
            case "P":
                $type = "новую презентацию";
                break;
            default:
                return;
        }

        if($user->id == $work->user_id){
            $user_to = DataGateway::get_user($work->teacher_id);
            $student_group = $user->profile[Config::FIELD_STUDENT_GROUP_NAME];

            $message = "Студент $user->lastname $user->firstname группы $student_group загрузил $type для работы \"$work->title\".";
        }
        else if($user->id == $work->teacher_id){
            $user_to = DataGateway::get_user($work->user_id);
            $message = "Научный руководитель для работы \"$work->title\" загрузил $type.";
        }

        if($user_to == NULL)
            return;

        $noreply_user = self::create_noreply_user();

        $title = "Научно-исследовательская работа \"$work->title\"";
        email_to_user($user_to, $noreply_user, $title, $message);
    }

    public static function send_email_sign_file_teacher($work_id)
    {
        $work = DataGateway::get_nir_by_id($work_id);

        $noreply_user = self::create_noreply_user();

        $title = "Научно-исследовательская работа \"$work->title\"";

        $user_to = DataGateway::get_user(Config::KAFEDRA);
        $student = DataGateway::get_user($work->user_id);
        $message = "Отчёт о научно-исследовательской работе \"$work->title\" студента  $student->lastname $student->firstname был отправлен на кафедру для согласования.";

        email_to_user($user_to, $noreply_user, $title, $message);

        $message = "Отчёт о научно-исследовательской работе \"$work->title\" был подписан научным руководителем и отправлен на кафедру для согласования.";

        email_to_user($student, $noreply_user, $title, $message);
    }

    public static function send_email_cancel_file_kafedra($work_id, $type)
    {
        $work = DataGateway::get_nir_by_id($work_id);

        $noreply_user = self::create_noreply_user();

        $title = "Научно-исследовательская работа \"$work->title\"";

        $file_type = "";
        $was_text = "";

        switch($type){
            case "file":
                $file_type = "Отчёт о научно-исследовательской работе";
                $was_text = "был отклонён";
                break;
            case "workplan":
                $file_type = "\"Задание на НИР\" для работы";
                $was_text = "было отклонёно";
                break;
            default:
                return;
        }

        $student_to = DataGateway::get_user($work->user_id);
        $message = "$file_type \"$work->title\" $was_text кафедрой.";

        email_to_user($student_to, $noreply_user, $title, $message);

        $teacher_to = DataGateway::get_user($work->teacher_id);
        $message = "$file_type \"$work->title\" студента  $student_to->lastname $student_to->firstname $was_text кафедрой.";

        email_to_user($teacher_to, $noreply_user, $title, $message);
    }

    public static function send_email_approve_file_kafedra($work_id, $type)
    {
        $work = DataGateway::get_nir_by_id($work_id);

        $noreply_user = self::create_noreply_user();

        $title = "Научно-исследовательская работа \"$work->title\"";

        $file_type = "";
        $was_text = "";

        switch($type){
            case "file":
                $file_type = "Отчёт о научно-исследовательской работе";
                $was_text = "был утверждён";
                break;
            case "workplan":
                $file_type = "\"Задание на НИР\" для работы";
                $was_text = "было утверждено";
                break;
            default:
                return;
        }

        $student_to = DataGateway::get_user($work->user_id);
        $message = "$file_type \"$work->title\" $was_text кафедрой.";

        email_to_user($student_to, $noreply_user, $title, $message);

        $teacher_to = DataGateway::get_user($work->teacher_id);
        $message = "$file_type \"$work->title\" студента  $student_to->lastname $student_to->firstname $was_text кафедрой.";

        email_to_user($teacher_to, $noreply_user, $title, $message);
    }

    private static function create_noreply_user()
    {
        $noreply_user = new stdClass();
        $noreply_user->firstname = "Кафедра 42";
        $noreply_user->lastname = "'Криптология и кибербезопасность'";
        $noreply_user->username = "notificationuser";
        $noreply_user->email = self::NOREPLY_EMAIL;
        $noreply_user->maildisplay = 2;

        return $noreply_user;
    }
}

?>