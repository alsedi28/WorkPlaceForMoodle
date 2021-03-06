$(document).ready(function(){
    var messageArea = new MessageArea(document.querySelector('.block_work_plan .message_container_block'));
    var messageAreaReport = new MessageArea(document.querySelector('.block_files .message_container_block'));
    var messageAreaReportKaf = new MessageArea(document.querySelector('#message_kaf'));

    var dataObj2 = {
        "work": document.getElementById("h_work_2") ? document.getElementById("h_work_2").value : null,
        "type": document.getElementById("h_work_type_2") ? document.getElementById("h_work_type_2").value : null,
    };

    var dataObj3 = {
        "work": document.getElementById("h_work_3") ? document.getElementById("h_work_3").value : null,
        "type": document.getElementById("h_work_type_3") ? document.getElementById("h_work_type_3").value : null,
    };

    $(document).on("click",".block_file_prev", function(){
        var img_n = this.querySelector('.img_new');

        if(img_n === null){
            return;
        }

        var id = this.querySelector('#file_id').value;

        $.ajax({
            url: 'ajax/update_file.php',
            type: 'POST',
            data: {'id': id},
            success: function(data){
                if(data.status === "Ok")
                    img_n.remove();
            }
        });
    });

    $(".tab").on('click', '#review_block_header', function(){
        $("#review_block").slideToggle("slow");
    });

    $(".block_work_plan").on('click', '#button_add_consultant',  render_partial_form_consultant);
    $(".block_work_plan").on('click', '.remove_block',  close_consultant_block);

    $(".tab").on('click', '#send_review', add_review);

    if($(".finish_work_button")){
        $(".finish_work_button").click(finish_work);
    }

    if($("#tab2 .sign_kaf_button").is('.sign_kaf_button') && !document.querySelector('#tab2 .sign_kaf_button').classList.contains("sign_kaf_button_not_active")){
        $("#tab2 .sign_kaf_button").on("click", sign_nir_kaf);
    }

    if($("#tab2 .sign_button_teacher").is('.sign_button_teacher') && !document.querySelector('#tab2 .sign_button_teacher').classList.contains("sign_teacher_button_not_active")){
        $("#tab2 .sign_button_teacher").on("click", sign_nir_teacher);
    }

    $(".tab").on('click', '.cancel_kaf_button', cancel_sign_nir_kaf);

    $(".block_work_plan").on('click', '.work_info_block .plus_input', add_point);

    $(".block_work_plan").on('click', '.work_info_block .minus_input', remove_point);

    $(".block_work_plan").on('click', '#cancel_edit_work_plan', get_view_work_plan_page);

    $(".block_work_plan").on('click', '#edit_button_work_plan', get_edit_work_plan_page);

    $(".block_work_plan").on('click', '#send_work_plan_kaf', send_work_plan_to_kaf);

    $(".block_work_plan").on('click', '#approve_work_plan_kaf', sign_work_plan_kaf.bind(null, "approve"));

    $(".block_work_plan").on('click', '#cancel_work_plan_kaf', sign_work_plan_kaf.bind(null, "cancel"));

    $(".tab").on('click', '.download_messages_block', get_messages);

    $(".block_work_plan").on('input', "input[data-validation='numbers']", validation_numbers_input);
    $(".block_work_plan").on('input', "input[data-validation='letters']", validation_letters_input);

    $(".block_work_plan").on('focusout', ".work_info_block textarea", validation_textarea);

    $('.tabs .tab-links a').on('click', function(event)  {
        var currentAttrValue = $(this).attr('href');

        // Show/Hide Tabs
        $('.tabs ' + currentAttrValue).show().siblings().hide();

        // Change/remove current tab to active
        $(this).parent('li').addClass('active').siblings().removeClass('active');

        event.preventDefault();
    });

    $(".block_work_plan").on('submit', '#form_plan', send_form_work_plan);

    $(".block_work_plan").on('submit', '#form_plan_edit', send_edit_work_plan);

    $(".block_work_plan").on('click', '#form_plan_edit #submit_edit_work_plan', set_action_edit_work_plan);

    $("#send_message_tab2").click(send_comment);

    $("#send_message_tab1").click(send_comment);

    $("#send_message_tab3").click(send_comment);

    function set_action_edit_work_plan(event) {
        var action_type = $(event.target).attr('action_type');
        $("#form_plan_edit [name='action']").val(action_type);
    }

    function sign_work_plan_kaf(type){
        var question = type === 'approve' ?  loc.AnswerDoYouWantToApproveWork : loc.AnswerDoYouWantToRejectWork;
        var isQuestionYes = confirm(question);

        if(!isQuestionYes)
            return false;

        messageArea.AddLoading(loc.Waiting);
        $('body').scrollTop(0);

        var work_id = $("[name='work_id']").val();
        var params_obj = {'work_id' : work_id};

        var date_lst = $("#tab1 .message .header_message_date");
        if (date_lst.length !== 0){
            var date = date_lst[date_lst.length - 1].innerText;
            params_obj.last_date_message = date;
        }

        var url = type === 'approve' ? 'sign_work_plan_kaf.php' : 'cancel_work_plan_kaf.php';
        url = 'ajax/' + url;

        $.ajax({
            type: 'POST',
            url: url,
            data: $.param(params_obj),
            success: function (data) {
                if(data.status == 'Ok'){
                    if(type === 'cancel')
                        $('.form_work_plan').remove();
                    else {
                        $('#approve_work_plan_kaf').remove();
                        $('#cancel_work_plan_kaf').remove();
                    }

                    messageArea.AddSuccess(data.alert);

                    if(data.messages)
                        $("#tab1 .textar_message_new").before(data.messages);
                }
                else{
                    messageArea.AddError(loc.UnknownError);
                }
            },
            error: function (xhr, str) {
                messageArea.AddError(loc.ErrorTitle + xhr.responseCode);
            }
        });
    }

    function get_messages(event){
        var tabs = $(event.target).closest('.tab');
        var current_tab_id = tabs[0].id;

        var type = document.querySelector("#" + current_tab_id + " [name='h_work_type']").value;
        var work_id = document.querySelector("#" + current_tab_id + " [name='h_work']").value;

        var date_first = $("#" + current_tab_id + " .message .header_message_date");
        if (date_first.length !== 0){
            var date = date_first[0].innerText;
        }
        else{
            $(event.target).remove();
        }

        $.ajax({
            url: 'ajax/get_messages.php',
            type: 'GET',
            data: {'type': type, 'date': date, 'work_id': work_id},
            success: function(data){
                if(data.status === "Ok"){
                    $("#" + current_tab_id + " .download_messages_block").after(data.messages);

                    if(data.count <= 5)
                        $(event.target).remove();
                }
            }
        });
    }

    function get_edit_work_plan_page(){
        make_button_deactive('#edit_button_work_plan', 'click');
        make_button_deactive('#send_work_plan_kaf', 'click');

        messageArea.AddLoading(loc.Waiting);
        $('body').scrollTop(0);

        var work_id = $("[name='work_id']").val();

        $.ajax({
            type: 'POST',
            url: 'ajax/edit_work_plan_page.php',
            data: { 'work_id' : work_id },
            success: function (data) {
                if(data.status == 'Ok'){
                    $('div.form_work_plan').remove();
                    $('.block_work_plan').append(data.data);

                    messageArea.AddInformation(data.alert);
                }
                else{
                    messageArea.AddError(loc.UnknownError);
                }

                make_button_active('#edit_button_work_plan', get_edit_work_plan_page, 'click');
                make_button_active('#send_work_plan_kaf', send_work_plan_to_kaf, 'click');
            },
            error: function (xhr, str) {
                messageArea.AddError(loc.ErrorTitle + xhr.responseCode);
                make_button_active('#edit_button_work_plan', get_edit_work_plan_page, 'click');
                make_button_active('#send_work_plan_kaf', send_work_plan_to_kaf, 'click');
            }
        });
    }

    function send_form_work_plan(){
        if(!$('#form_plan')[0].checkValidity())
            return true;

        var isSendYes = confirm(loc.AnswerDoYouWantToSentWork);

        if(!isSendYes)
            return false;

        make_button_deactive('#form_plan', 'submit');

        messageArea.AddLoading(loc.Waiting);
        $('body').scrollTop(0);

        var msg   = $('#form_plan').serialize();

        var date_lst = $("#tab1 .message .header_message_date");
        if (date_lst.length !== 0){
            var date = date_lst[date_lst.length - 1].innerText;
            msg = msg + '&' + $.param({'last_date_message': date});
        }

        $.ajax({
            type: 'POST',
            url: 'ajax/add_work_plan.php',
            data: msg,
            success: function (data) {
                if(data.status == 'Ok'){
                    messageArea.AddInformation(data.alert);

                    $('#form_plan').remove();
                    $('.block_work_plan').append(data.data);

                    if(data.messages)
                        $("#tab1 .textar_message_new").before(data.messages);
                }
                else{
                    messageArea.AddError(loc.UnknownErrorWithCode + data.code);
                }

                make_button_active('#form_plan', send_form_work_plan, 'submit');
            },
            error: function (xhr, str) {
                messageArea.AddError(loc.ErrorTitle + xhr.responseCode);

                make_button_active('#form_plan', send_form_work_plan, 'submit');
            }
        });
    }

    function send_work_plan_to_kaf(event){
        var isSendYes = confirm(loc.AnswerDoYouWantToSentWork);

        if(!isSendYes)
            return false;

        make_button_deactive('#edit_button_work_plan', 'click');
        make_button_deactive('#send_work_plan_kaf', 'click');

        messageArea.AddLoading(loc.Waiting);
        $('body').scrollTop(0);

        var work_id = $("[name='work_id']").val();
        var params_obj = {'work_id' : work_id};

        var date_lst = $("#tab1 .message .header_message_date");
        if (date_lst.length !== 0){
            var date = date_lst[date_lst.length - 1].innerText;
            params_obj.last_date_message = date;
        }

        $.ajax({
            type: 'POST',
            url: 'ajax/sign_work_plan_teacher.php',
            data: $.param(params_obj),
            success: function (data) {
                if(data.status == 'Ok'){
                    $('#edit_button_work_plan').remove();
                    $('#send_work_plan_kaf').remove();
                    messageArea.AddInformation(data.alert);

                    if(data.messages)
                        $("#tab1 .textar_message_new").before(data.messages);
                }
                else{
                    messageArea.AddError(loc.UnknownError);
                }

                make_button_active('#edit_button_work_plan', get_edit_work_plan_page, 'click');
                make_button_active('#send_work_plan_kaf', send_work_plan_to_kaf, 'click');
            },
            error: function (xhr, str) {
                messageArea.AddError(loc.ErrorTitle + xhr.responseCode);

                make_button_active('#edit_button_work_plan', get_edit_work_plan_page, 'click');
                make_button_active('#send_work_plan_kaf', send_work_plan_to_kaf, 'click');
            }
        });
    }

    function send_edit_work_plan(event){
        if(!$('#form_plan_edit')[0].checkValidity())
            return true;

        var isSendYes = confirm(loc.AnswerDoYouWantToSentWork);

        if(!isSendYes)
            return false;

        make_button_deactive('#form_plan_edit', 'submit');
        make_button_deactive('#cancel_edit_work_plan', 'click');
        make_button_deactive('#form_plan_edit #submit_edit_work_plan', 'click');

        messageArea.AddLoading(loc.Waiting);
        $('body').scrollTop(0);

        var msg   = $('#form_plan_edit').serialize();

        var date_lst = $("#tab1 .message .header_message_date");
        if (date_lst.length !== 0){
            var date = date_lst[date_lst.length - 1].innerText;

            msg = msg + '&' + $.param({'last_date_message': date});
        }

        $.ajax({
            type: 'POST',
            url: 'ajax/edit_work_plan.php',
            data: msg,
            success: function (data) {
                if(data.status == 'Ok'){
                    messageArea.AddInformation(data.alert);
                    $('#form_plan_edit').remove();
                    $('.block_work_plan').append(data.data);

                    if(data.messages)
                        $("#tab1 .textar_message_new").before(data.messages);
                }
                else{
                    messageArea.AddError(loc.UnknownErrorWithCode + data.code);
                }

                make_button_active('#form_plan_edit', send_edit_work_plan, 'submit');
                make_button_active('#cancel_edit_work_plan', get_view_work_plan_page, 'click');
                make_button_active('#form_plan_edit #submit_edit_work_plan', set_action_edit_work_plan, 'click');
            },
            error: function (xhr, str) {
                messageArea.AddError(loc.ErrorTitle + xhr.responseCode);

                make_button_active('#form_plan_edit', send_edit_work_plan, 'submit');
                make_button_active('#cancel_edit_work_plan', get_view_work_plan_page, 'click');
                make_button_active('#form_plan_edit #submit_edit_work_plan', set_action_edit_work_plan, 'click');
            }
        });
    }

    function get_view_work_plan_page(){
        make_button_deactive('#form_plan_edit', 'submit');
        make_button_deactive('#cancel_edit_work_plan', 'click');

        messageArea.AddLoading(loc.Waiting);
        $('body').scrollTop(0);

        var work_id = $("[name='work_id']").val();

        $.ajax({
            type: 'POST',
            url: 'ajax/view_work_plan_page.php',
            data: { 'work_id' : work_id },
            success: function (data) {
                if(data.status == 'Ok'){
                    messageArea.Clear();
                    $('form.form_work_plan').remove();
                    $('.block_work_plan').append(data.data);
                }
                else{
                    messageArea.AddError(loc.UnknownError);
                }

                make_button_active('#form_plan_edit', send_edit_work_plan, 'submit');
                make_button_active('#cancel_edit_work_plan', get_view_work_plan_page, 'click');
            },
            error: function (xhr, str) {
                messageArea.AddError(loc.ErrorTitle + xhr.responseCode);

                make_button_active('#form_plan_edit', send_edit_work_plan, 'submit');
                make_button_active('#cancel_edit_work_plan', get_view_work_plan_page, 'click');
            }
        });
    }

    function add_point(event){
        var root = $(event.target).closest('.root_block_point');
        var count = root.find('.textarea_many_div_block').length;

        if(count > 4)
            return;

        var point = root.find('.textarea_many_div_block')[count - 1];
        var clone_element = point.cloneNode(true);
        $(clone_element).insertAfter(root.find('.textarea_many_div_block')[count - 1]);
        $(point).find('.plus_input')[0].remove();

        if($(point).find('.minus_input').length > 0)
            $(point).find('.minus_input')[0].remove();

        $(clone_element).find('.number_point')[0].innerText = count + 1;
        var current_name = $(point).find('.textarea_many_block')[0].name;
        var new_name = current_name.slice(0,current_name.length - 3) + '[' + count + ']';

        $(clone_element).find('.textarea_many_block')[0].name = new_name;
        $(clone_element).find('.textarea_many_block')[0].value = '';

        if(count === 4){
            $($(clone_element).find('.plus_input')[0]).remove();
        }

        if($(clone_element).find('.minus_input').length < 1){
            $($(clone_element).find('.minus_input_block')[0]).append("<div class='minus_input' title='" + loc.Remove + "'>" +
                "<img src='img/minus.png' height='26px'/></div>");
        }
    }

    function remove_point(event){
        var root = $(event.target).closest('.root_block_point');
        var count = root.find('.textarea_many_div_block').length;

        if(count < 4)
            return;

        $(event.target).closest('.textarea_many_div_block').remove();
        var prev_point = root.find('.textarea_many_div_block')[count - 2];

        if(count == 5){
            $($(prev_point).find('.minus_input_block')[0]).append("<div class='minus_input' title='" + loc.Remove + "'>" +
                "<img src='img/minus.png' height='26px'/></div>");
        }

        $($(prev_point).find('.plus_input_block')[0]).append("<div class='plus_input' title='" + loc.AddItem + "'>" +
            "<img src='img/plus.png' height='26px'/></div>");
    }

    function close_consultant_block(){
        $('#consultant_block').html("<div id='button_add_consultant'>Добавить консультанта</div>");
    }

    function render_partial_form_consultant(){
        $('#button_add_consultant').remove();
        $('#consultant_block').append("<div><h3 class='header_block_consultant'>Консультант</h3><div class='remove_block' title='Удалить'></div></div>" +
            "<div><label class='label_block'>Фамилия<span style='color:red'> *</span></label><input type='text' name='cn_surname' id='cn_surname' class='input_block' required maxlength='25' data-validation='letters'/><div style='clear:both'></div></div>" +
            "<div><label class='label_block'>Имя<span style='color:red'> *</span></label><input type='text' name='cn_name' id='cn_name' class='input_block' required maxlength='25' data-validation='letters'/><div style='clear:both'></div></div>" +
            "<div><label class='label_block'>Отчество<span style='color:red'> *</span></label><input type='text' name='cn_patronymic' id='cn_patronymic' class='input_block' required maxlength='25' data-validation='letters'/><div style='clear:both'></div></div>" +
            "<div><label class='label_block'>Номер телефона<span style='color:red'> *</span></label><input type='tel' name='cn_phone_number' id='cn_phone_number' class='input_block' required maxlength='20' data-validation='numbers'/><div style='clear:both'></div></div>" +
            "<div><label class='label_block'>Электронная почта<span style='color:red'> *</span></label><input type='email' name='cn_email' id='cn_email' class='input_block' required maxlength='30' data-validation='text'/><div style='clear:both'></div></div>" +
            "<div><label class='label_block'>Место работы<span style='color:red'> *</span></label><input type='text' name='cn_place_work' id='cn_place_work' class='input_block' required maxlength='50' data-validation='text'/><div style='clear:both'></div></div>" +
            "<div><label class='label_block'>Должность<span style='color:red'> *</span></label><input type='text' name='cn_position_work' id='cn_position_work' class='input_block' required maxlength='50' data-validation='text'/><div style='clear:both'></div></div>" +
            "<div><label class='label_block label_select'>Учёное звание</label><select name='cn_academic_title' class='select_block'>" +
            "<option value='Нет'>Нет</option><option value='Доцент'>Доцент</option><option value='Профессор'>Профессор</option><option value='Старший научный сотрудник'>Старший научный сотрудник</option><option value='Ведущий научный сотрудник'>Ведущий научный сотрудник</option></select><div style='clear:both'></div></div>" +
            "<div><label class='label_block label_select'>Учёная степень</label><select name='cn_academic_degree' class='select_block'>" +
            "<option value='Нет'>Нет</option><option value='Кандидат технических наук'>Кандидат технических наук</option><option value='Доктор технических наук'>Доктор технических наук</option>" +
            "<option value='Кандидат физико-математических наук'>Кандидат физико-математических наук</option><option value='Доктор физико-математических наук'>Доктор физико-математических наук</option></select><div style='clear:both'></div></div>");
    }

    function sign_nir_teacher(event){
        var tabs = $(event.target).closest('.tab');
        var current_tab_id = tabs[0].id;

        var isSignYes = confirm(loc.AnswerDoYouWantToSignWork);

        if(isSignYes){
            var file_id = $('#' + current_tab_id + ' .sign_button_teacher').parent().parent().find("#file_id").val();

            var params_obj = {'id' : file_id};

            var date_lst = $("#" + current_tab_id + " .message .header_message_date");
            if (date_lst.length !== 0){
                var date = date_lst[date_lst.length - 1].innerText;
                params_obj.last_date_message = date;
            }

            $.ajax({
                url: 'ajax/sign_file_teacher.php',
                type: 'POST',
                data: $.param(params_obj),
                success: function(data){
                    if(data.status === "Ok"){
                        messageAreaReport.AddSuccess(loc.DocumentIsSigned);
                        $('body').scrollTop(0);

                        document.querySelector('#' + current_tab_id + ' .sign_button_teacher').classList.add("sign_teacher_button_not_active");
                        $('#' + current_tab_id + ' .sign_button_teacher').off();
                        $("#" + current_tab_id + " .textar_message_new").before(data.messages);
                    }
                }
            });
        }
    }

    function sign_nir_kaf(event){
        var isSignYes = confirm(loc.AnswerDoYouWantToSignWork);

        if(isSignYes){
            var tabs = $(event.target).closest('.tab');
            var current_tab_id = tabs[0].id;

            var file_id = document.querySelector('#' + current_tab_id + ' #file_id').value;

            var params_obj = {'file_id' : file_id};

            var date_lst = $('#' + current_tab_id + ' .message .header_message_date');
            if (date_lst.length !== 0){
                var date = date_lst[date_lst.length - 1].innerText;
                params_obj.last_date_message = date;
            }

            $.ajax({
                url: 'ajax/sign_file_kaf.php',
                type: 'POST',
                data: $.param(params_obj),
                success: function(data){
                    if(data.status === "Ok"){
                        messageAreaReportKaf.AddSuccess(loc.DocumentIsSigned);

                        document.querySelector('#' + current_tab_id + ' .sign_kaf_button').classList.add("sign_kaf_button_not_active");
                        $('#' + current_tab_id + ' .sign_kaf_button').off();
                        $("#" + current_tab_id + " .textar_message_new").before(data.messages);
                    }
                }
            });
        }
    }

    function cancel_sign_nir_kaf(event){
        var message = loc.AnswerDoYouWantToRejectWork;
        var tabs = $(event.target).closest('.tab');
        var current_tab_id = tabs[0].id;

        if(document.querySelector('#' + current_tab_id + ' .sign_kaf_button').classList.contains('sign_kaf_button_not_active')){
            message = loc.AnswerDoYouWantToCancelSignature;
        }

        var isCancelSignYes = confirm(message);

        if(isCancelSignYes){
            var file_id = document.querySelector('#' + current_tab_id + ' #file_id').value;

            var params_obj = {'file_id' : file_id};

            var date_lst = $('#' + current_tab_id + ' .message .header_message_date');
            if (date_lst.length !== 0){
                var date = date_lst[date_lst.length - 1].innerText;
                params_obj.last_date_message = date;
            }

            $.ajax({
                url: 'ajax/cancel_sign_file.php',
                type: 'POST',
                data: $.param(params_obj),
                success: function(result){
                    if(result.status === "Ok cancel sign document"){
                        messageAreaReportKaf.AddSuccess(loc.SignatureCanceled);

                        document.querySelector('#' + current_tab_id + ' .sign_kaf_button').classList.remove('sign_kaf_button_not_active');
                        $('#' + current_tab_id + ' .sign_kaf_button').on("click", sign_nir_kaf);
                    }
                    else if(result.status === "Ok cancel document"){
                        messageAreaReportKaf.AddSuccess(loc.DocumentRejected);
                        $('body').scrollTop(0);

                        $('#' + current_tab_id + ' .sign_kaf_button').remove();
                        $('#' + current_tab_id + ' .cancel_kaf_button').remove();
                        $('#' + current_tab_id + ' .block_files_sign_kaf').remove();
                        $('#' + current_tab_id + ' .block_files_kaf').empty();
                        $('#' + current_tab_id + ' .block_files_kaf').append("<p class='work_not_load'>Работа еще не была загружена.</p>");
                    }

                    $("#" + current_tab_id + " .textar_message_new").before(result.messages);
                }
            });
        }
    }

    function add_review(){
        var nir_id = document.getElementById("h_work_2").value;
        var review = document.getElementById("review_area").value;
        var mark = document.getElementById("mark_input").value;

        if(review === ""){
            alert(loc.FieldReviewMustBeCompleted);
            return;
        }

        $.ajax({
            url: 'ajax/add_review.php',
            type: 'POST',
            data: {'id': nir_id,
                'review': review,
                'mark': mark
            },
            success: function(data){
                if(data.status === "Ok"){
                    alert(loc.ReviewAdded);

                    document.querySelector('#tab2 .sign_button_teacher').classList.remove("sign_teacher_button_not_active");
                    $("#tab2 .sign_button_teacher").on("click", sign_nir_teacher);

                    $("#review_block").empty();
                    $("#review_block").append("<p class='ex_review_title'>" + loc.Review + "</p><p class= 'ex_review_text'>" + review + "</p><p class='ex_mark'>" + loc.ReviewMark + "<span>" + mark + "</span></p>" );
                    $("#review_block").css('height','auto');
                }
                else{
                    alert(loc.UnknownError);
                }
            }
        });
    }

    function send_comment(event){
        var tabs = $(event.target).closest('.tab');
        var current_tab_id = tabs[0].id;

        var nir_id = document.querySelector("#" + current_tab_id + " [name='h_work']").value;
        var type = document.querySelector("#" + current_tab_id + " [name='h_work_type']").value;
        var text = document.querySelector("#" + current_tab_id + " [name='message']").value;

        if (text.trim().length === 0)
            return;

        var params_obj = {'nir' : nir_id, 'type': type, 'text': text};

        var date_lst = $('#' + current_tab_id + ' .message .header_message_date');
        if (date_lst.length !== 0){
            var date = date_lst[date_lst.length - 1].innerText;
            params_obj.last_date_message = date;
        }

        $("#" + current_tab_id + " .send_message_button").attr("disabled", true);
        $("#" + current_tab_id + " [name='message']").attr("disabled", true);

        $.ajax({
            url: 'ajax/create_message.php',
            type: 'POST',
            data: $.param(params_obj),
            success: function(data){
                document.querySelector("#" + current_tab_id + " [name='message']").value = "";
                $("#" + current_tab_id + " .send_message_button").attr("disabled", false);
                $("#" + current_tab_id + " [name='message']").attr("disabled", false);
                $("#" + current_tab_id + " .textar_message_new").before(data.messages);
            }
        });
    }

    function validation_numbers_input(event){
        event.target.value = event.target.value.replace(/[^0-9]/gim,'');
    }

    function validation_letters_input(event){
        event.target.value = event.target.value.replace(/[^а-яА-Я]/gim,'');
    }

    function validation_textarea(event){
        var value = event.target.value.trim();

        if(value.length === 0)
            event.target.value = "";
    }

    function finish_work(){
        var isSignYes = confirm(loc.AnswerDoYouWantToFinishWork);

        if(isSignYes)
            window.location.hash = "win1";
    }

    function get_obj_settings_download_file(data, extensions){
        return {
            limit: 1,
            maxSize: null,
            extensions: extensions,
            changeInput: '<div class="jFiler-input-dragDrop"><div class="jFiler-input-inner"><div class="jFiler-input-icon"><i id="icon_cloud" class="icon-jfi-cloud-up-o"></i></div><div class="jFiler-input-text"><h3 id="dragdrop_title">' + loc.DragFileHere + '</h3> <span style="display:inline-block; margin: 15px 0">' + loc.Or + '</span></div><a class="jFiler-input-choose-btn blue">' + loc.SelectFile + '</a></div></div>',
            showThumbs: true,
            theme: "dragdropbox",
            templates: {
                box: '<ul class="jFiler-items-list jFiler-items-grid" id="ul_file_block"></ul>',
                item: '<li class="jFiler-item">\
						<div class="jFiler-item-container">\
							<div class="jFiler-item-inner">\
								<div class="jFiler-item-thumb">\
									<div class="jFiler-item-status"></div>\
									<div class="jFiler-item-thumb-overlay">\
										<div class="jFiler-item-info">\
											<div style="display:table-cell;vertical-align: middle;">\
												<span class="jFiler-item-title"><b title="{{fi-name}}">{{fi-name}}</b></span>\
												<span class="jFiler-item-others">{{fi-size2}}</span>\
											</div>\
										</div>\
									</div>\
									{{fi-image}}\
								</div>\
								<div class="jFiler-item-assets jFiler-row">\
									<ul class="list-inline pull-left">\
										<li>{{fi-progressBar}}</li>\
									</ul>\
									<ul class="list-inline pull-right">\
										<li><a class="icon-jfi-trash jFiler-item-trash-action"></a></li>\
									</ul>\
								</div>\
							</div>\
						</div>\
					</li>',
                itemAppend: '<li class="jFiler-item">\
							<div class="jFiler-item-container">\
								<div class="jFiler-item-inner">\
									<div class="jFiler-item-thumb">\
										<div class="jFiler-item-status"></div>\
										<div class="jFiler-item-thumb-overlay">\
											<div class="jFiler-item-info">\
												<div style="display:table-cell;vertical-align: middle;">\
													<span class="jFiler-item-title"><b title="{{fi-name}}">{{fi-name}}</b></span>\
													<span class="jFiler-item-others">{{fi-size2}}</span>\
												</div>\
											</div>\
										</div>\
										{{fi-image}}\
									</div>\
									<div class="jFiler-item-assets jFiler-row">\
										<ul class="list-inline pull-left">\
											<li><span class="jFiler-item-others">{{fi-icon}}</span></li>\
										</ul>\
										<ul class="list-inline pull-right">\
											<li><a class="icon-jfi-trash jFiler-item-trash-action"></a></li>\
										</ul>\
									</div>\
								</div>\
							</div>\
						</li>',
                progressBar: '<div class="bar"></div>',
                itemAppendToEnd: false,
                canvasImage: true,
                removeConfirmation: true,
                _selectors: {
                    list: '.jFiler-items-list',
                    item: '.jFiler-item',
                    progressBar: '.bar',
                    remove: '.jFiler-item-trash-action'
                }
            },
            dragDrop: {
                dragEnter: null,
                dragLeave: null,
                drop: null,
                dragContainer: null,
            },
            uploadFile: {
                url: "ajax/upload_file.php",
                data: data,
                type: 'POST',
                enctype: 'multipart/form-data',
                synchron: true,
                beforeSend: function(){},
                success: function(data, itemEl, listEl, boxEl, newInputEl, inputEl, id){
                    var parent = itemEl.find(".jFiler-jProgressBar").parent(),
                        new_file_name = JSON.parse(data),
                        filerKit = inputEl.prop("jFiler");

                    filerKit.files_list[id].name = new_file_name;

                    itemEl.find(".jFiler-jProgressBar").fadeOut("slow", function(){
                        $("<div class=\"jFiler-item-others text-success\"><i class=\"icon-jfi-check-circle\"></i>" + loc.FileDownloaded + "</div>").hide().appendTo(parent).fadeIn("slow");
                    });
                },
                error: function(el){
                    var parent = el.find(".jFiler-jProgressBar").parent();
                    el.find(".jFiler-jProgressBar").fadeOut("slow", function(){
                        $("<div class=\"jFiler-item-others text-error\"><i class=\"icon-jfi-minus-circle\"></i>" + loc.Error + "</div>").hide().appendTo(parent).fadeIn("slow");
                    });
                },
                statusCode: null,
                onProgress: null,
                onComplete: null
            },
            files: null,
            addMore: false,
            allowDuplicates: true,
            clipBoardPaste: true,
            excludeName: null,
            beforeRender: null,
            afterRender: null,
            beforeShow: null,
            beforeSelect: null,
            onSelect: null,
            afterShow: null,
            onRemove: function(itemEl, file, id, listEl, boxEl, newInputEl, inputEl){
                var filerKit = inputEl.prop("jFiler"),
                    file_name = filerKit.files_list[id].name;

                $.post('ajax/remove_file.php', {file: file_name, work_id: data.work, type: data.type});
            },
            onEmpty: null,
            options: null,
            dialogs: {
                alert: function(text) {
                    return alert(text);
                },
                confirm: function (text, callback) {
                    confirm(text) ? callback() : null;
                }
            },
            captions: {
                button: loc.SelectFile,
                feedback: loc.SelectFilesToUpload,
                feedback2: loc.FilesSelected,
                drop: loc.DragTheFileHereToDownload,
                removeConfirmation: loc.AnswerDoYouWantToDeleteFile,
                errors: {
                    filesLimit: "Только {{fi-limit}} файл может быть загружен.",
                    filesType: "Могут быть загружены только файлы с расширениями " + extensions.join(', ') + ".",
                    filesSize: "Файл {{fi-name}} слишком большой! Загрузите файл размером до {{fi-maxSize}} MB.",
                    filesSizeAll: "Файлы, которые вы выбрали, слишком большие! Загрузите файлы размером до {{fi-maxSize}} MB."
                }
            }
        }
    }

    $("#filer_input1").filer(get_obj_settings_download_file(dataObj2, ['txt', 'doc', 'docx', 'docm', 'odt', 'pages']));
    $("#filer_input3").filer(get_obj_settings_download_file(dataObj3, ['pdf', 'ppt', 'pptx', 'key']));

    function make_button_deactive(selector, type){
        $(".block_work_plan").off(type, selector);
        $('.work_plan_edit_button').removeClass('work_plan_edit_button').addClass('work_plan_edit_button_not_active');
    }

    function make_button_active(selector, handler, type){
        $(".block_work_plan").on(type, selector, handler);
        $('.work_plan_edit_button_not_active').removeClass('work_plan_edit_button_not_active').addClass('work_plan_edit_button');
    }
})
