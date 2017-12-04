$(document).ready(function(){
    
    var dataObj = {
        "work": document.getElementById("h_work").value,
        "type": document.getElementById("h_work_type").value,
    }; 
    
    var dataObj2 = {
        "work": document.getElementById("h_work_2").value,
        "type": document.getElementById("h_work_type_2").value,
    }; 
    
    var dataObj3 = {
        "work": document.getElementById("h_work_3").value,
        "type": document.getElementById("h_work_type_3").value,
    }; 
    
    $(document).on("click",".block_file_prev", function(){
        var img_n = this.querySelector('.img_new');
        
        if(img_n === null){
            return;
        }
        
        var id = this.querySelector('#file_id').value;
        
        $.ajax({
            url: 'update_file.php',
            type: 'POST',
            data: {'id': id},
            success: function(data){
                    if(data === "Ok")
                        img_n.remove();
                }
        });
    });
    
    if($("#review_block_header")){
        $("#review_block_header").click(function(){
            $("#review_block").slideToggle("slow");
        });
    }
    
    if($("#send_review")){
        $("#send_review").on("click", add_review);
    }
    
    if($(".finish_work_button")){
        $(".finish_work_button").click(finish_work);
    }
    
    if($("#tab1 .sign_kaf_button").is('.sign_kaf_button') && !document.querySelector('#tab1 .sign_kaf_button').classList.contains("sign_kaf_button_not_active")){
        $("#tab1 .sign_kaf_button").on("click", sign_nir_kaf);
    }
    
    if($("#tab2 .sign_kaf_button").is('.sign_kaf_button') && !document.querySelector('#tab2 .sign_kaf_button').classList.contains("sign_kaf_button_not_active")){
        $("#tab2 .sign_kaf_button").on("click", sign_nir_kaf);
    }
    
    if($("#tab1 .sign_button_teacher").is('.sign_button_teacher') && !document.querySelector('#tab1 .sign_button_teacher').classList.contains("sign_teacher_button_not_active")){
        $("#tab1 .sign_button_teacher").on("click", sign_nir_teacher);
    }
    
    if($("#tab2 .sign_button_teacher").is('.sign_button_teacher') && !document.querySelector('#tab2 .sign_button_teacher').classList.contains("sign_teacher_button_not_active")){
        $("#tab2 .sign_button_teacher").on("click", sign_nir_teacher);
    }

    $("#tab1 .cancel_kaf_button").on("click", cancel_sign_nir_kaf);

    $("#tab2 .cancel_kaf_button").on("click", cancel_sign_nir_kaf);

    if($("#tab1 .cancel_button_teacher").is('.cancel_button_teacher') && !document.querySelector('#tab1 .cancel_button_teacher').classList.contains("sign_teacher_button_not_active")){
        $("#tab1 .cancel_button_teacher").on("click", cancel_sign_nir_teacher);
    }

    if($("#tab2 .cancel_button_teacher").is('.cancel_button_teacher') && !document.querySelector('#tab2 .cancel_button_teacher').classList.contains("sign_teacher_button_not_active")){
        $("#tab2 .cancel_button_teacher").on("click", cancel_sign_nir_teacher);
    }

    function sign_nir_teacher(event){
        var tabs = $(event.target).closest('.tab');
        var current_tab_id = tabs[0].id;

        var isSignYes = confirm("Вы действительно хотите подписать работу?");

        if(isSignYes){
            var file_id = $('#' + current_tab_id + ' .sign_button_teacher').parent().parent().find("#file_id").val();

            $.ajax({
                url: 'sign_file_teacher.php',
                type: 'POST',
                data: {'id': file_id},
                success: function(data){
                    if(data === "Ok"){
                        alert("Документ подписан");
                        document.querySelector('#' + current_tab_id + ' .sign_button_teacher').classList.add("sign_teacher_button_not_active");
                        document.querySelector('#' + current_tab_id + ' .cancel_button_teacher').classList.remove("sign_teacher_button_not_active");
                        $('#' + current_tab_id + ' .sign_button_teacher').off();
                        $('#' + current_tab_id + ' .cancel_button_teacher').on("click", cancel_sign_nir_teacher);
                    }
                }
            });
        }
    }

    function cancel_sign_nir_teacher(event){
        var isCancelSignYes = confirm("Вы действительно хотите отменить подпись?");

        if(isCancelSignYes){
            var tabs = $(event.target).closest('.tab');
            var current_tab_id = tabs[0].id;

            var file_id = $('#' + current_tab_id + ' .cancel_button_teacher').parent().parent().find("#file_id").val();

            $.ajax({
                url: 'cancel_sign_file_teacher.php',
                type: 'POST',
                data: {'id': file_id},
                success: function(data){
                    if(data === "Ok"){
                        alert("Подпись отменена");
                        document.querySelector('#' + current_tab_id + ' .sign_button_teacher').classList.remove("sign_teacher_button_not_active");
                        document.querySelector('#' + current_tab_id + ' .cancel_button_teacher').classList.add("sign_teacher_button_not_active");
                        $('#' + current_tab_id + ' .sign_button_teacher').on("click", sign_nir_teacher);
                        $('#' + current_tab_id + ' .cancel_button_teacher').off();
                    }
                }
            });
        }
    }

    function sign_nir_kaf(event){
        var isSignYes = confirm("Вы действительно хотите подписать работу?");
        
        if(isSignYes){
            var tabs = $(event.target).closest('.tab');
            var current_tab_id = tabs[0].id;

            var file_id = document.querySelector('#' + current_tab_id + ' #file_id').value;
            
            $.ajax({
            url: 'sign_file.php',
            type: 'POST',
            data: {'id': file_id},
            success: function(data){
                    if(data === "Ok"){
                        alert("Документ подписан");
                        document.querySelector('#' + current_tab_id + ' .sign_kaf_button').classList.add("sign_kaf_button_not_active");
                        $('#' + current_tab_id + ' .sign_kaf_button').off();
                    }
                }
            });
        }
    }

    function cancel_sign_nir_kaf(event){
        var message = "Вы действительно хотите отклонить работу?";
        var tabs = $(event.target).closest('.tab');
        var current_tab_id = tabs[0].id;

        if(document.querySelector('#' + current_tab_id + ' .sign_kaf_button').classList.contains('sign_kaf_button_not_active')){
            message = "Вы действительно хотите отменить подпись?";
        }

        var isCancelSignYes = confirm(message);

        if(isCancelSignYes){
            var file_id = document.querySelector('#tab1 #file_id').value;

            $.ajax({
                url: 'cancel_sign_file.php',
                type: 'POST',
                data: {'id': file_id},
                success: function(data){
                    if(data === "Ok cancel sign document"){
                        alert("Подпись отменена");
                        document.querySelector('#' + current_tab_id + ' .sign_kaf_button').classList.remove('sign_kaf_button_not_active');
                        $('#' + current_tab_id + ' .sign_kaf_button').on("click", sign_nir_kaf);
                    }
                    else if(data === "Ok cancel document"){
                        alert("Документ отклонен");
                        $('#' + current_tab_id + ' .sign_kaf_button').remove();
                        $('#' + current_tab_id + ' .cancel_kaf_button').remove();
                        $('#' + current_tab_id + ' .block_files_sign_kaf').remove();
                        $('#' + current_tab_id + ' .block_files_kaf').empty();
                        $('#' + current_tab_id + ' .block_files_kaf').append("<p class='work_not_load'>Работа еще не была загружена.</p>");
                    }
                }
            });
        }
    }
    
    function add_review(){
        var nir_id = document.getElementById("h_work_2").value;
        var review = document.getElementById("review_area").value;
        var mark = document.getElementById("mark_input").value;
        
        if(review === ""){
            alert("Поле 'Отзыв' должно быть заполнено!");
            return;
        }
        
        $.ajax({
        url: 'add_review.php',
        type: 'POST',
        data: {'id': nir_id,
            'review': review,
            'mark': mark
        },
        success: function(data){
                if(data === "Ok"){
                    alert("Отзыв добавлен.");
                    
                    document.querySelector('#tab2 .sign_button_teacher').classList.remove("sign_teacher_button_not_active");
                    $("#tab2 .sign_button_teacher").on("click", sign_nir_teacher);
                    
                    $("#review_block").empty();
                    $("#review_block").append("<p class='ex_review_title'>Отзыв</p><p class= 'ex_review_text'>" + review + "</p><p class='ex_mark'>Оценка (по 5-ти балльной шкале): <span>" + mark + "</span></p>" );
                    $("#review_block").css('height','auto');
                }
                else{
                    alert("Произошла ошибка, попробуйте позже.");
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
        var date = "0";

        var date_lst = $("#" + current_tab_id + " .message .header_message_date");
        if (date_lst.length !== 0){
            date = date_lst[date_lst.length - 1].innerText;
        }

        if (text === ""){
            alert("Сообщение не должно быть пустым!");
            return;
        }

        $("#" + current_tab_id + " .send_message_button").attr("disabled", true);
        $("#" + current_tab_id + " [name='message']").attr("disabled", true);

        $.ajax({
            url: 'create_message.php',
            type: 'POST',
            data: {'nir': nir_id, 'type': type, 'text': text, 'date': date},
            success: function(data){
                document.querySelector("#" + current_tab_id + " [name='message']").value = "";
                $("#" + current_tab_id + " .send_message_button").attr("disabled", false);
                $("#" + current_tab_id + " [name='message']").attr("disabled", false);
                $("#" + current_tab_id + " .textar_message_new").before(data);
            }
        });
    }

    $("#send_message_tab2").click(send_comment);
    
    $("#send_message_tab1").click(send_comment);
    
    $("#send_message_tab3").click( send_comment);
    
    function finish_work(){
        var isSignYes = confirm("Вы действительно хотите завершить работу?");
        
        if(isSignYes){
            var id = document.querySelector('#work_f').value;
            
            $.ajax({
            url: 'finish_work.php',
            type: 'POST',
            data: {'id': id},
            success: function(data){
                    if(data === "Ok"){
                        window.location.reload()
                    }
                    else{
                        alert("Произошла ошибка. Попробуйте позднее.");
                    }
                }
            });
        }
    }
    
    
    jQuery('.tabs .tab-links a').on('click', function(e)  {
        var currentAttrValue = jQuery(this).attr('href');
 
        // Show/Hide Tabs
        jQuery('.tabs ' + currentAttrValue).show().siblings().hide();
 
        // Change/remove current tab to active
        jQuery(this).parent('li').addClass('active').siblings().removeClass('active');
 
        e.preventDefault();
    });

	//Example 2
	$("#filer_input2").filer({
		limit: 1,
		maxSize: null,
		extensions: ['txt', 'doc', 'docx', 'docm', 'odt', 'pages'],
		changeInput: '<div class="jFiler-input-dragDrop"><div class="jFiler-input-inner"><div class="jFiler-input-icon"><i id="icon_cloud" class="icon-jfi-cloud-up-o"></i></div><div class="jFiler-input-text"><h3 id="dragdrop_title">Перетащите файл сюда</h3> <span style="display:inline-block; margin: 15px 0">или</span></div><a class="jFiler-input-choose-btn blue">Выберите файл</a></div></div>',
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
			url: "ajax_upload_file.php",
			data: dataObj,
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
					$("<div class=\"jFiler-item-others text-success\"><i class=\"icon-jfi-check-circle\"></i> Файл загружен</div>").hide().appendTo(parent).fadeIn("slow");
				});
			},
			error: function(el){
				var parent = el.find(".jFiler-jProgressBar").parent();
				el.find(".jFiler-jProgressBar").fadeOut("slow", function(){
					$("<div class=\"jFiler-item-others text-error\"><i class=\"icon-jfi-minus-circle\"></i> Ошибка</div>").hide().appendTo(parent).fadeIn("slow");
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

		    $.post('ajax_remove_file.php', {file: file_name});
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
			button: "Выберите файл",
			feedback: "Выберите файлы для загрузки",
			feedback2: "файлы выбраны",
			drop: "Перетащите файл сюда, чтобы загрузить",
			removeConfirmation: "Вы уверены, что хотите удалить этот файл?",
			errors: {
				filesLimit: "Только {{fi-limit}} файл может быть загружен.",
				filesType: "Могут быть загружены только файлы с расширениями '.doc','.docx', '.docm.', '.txt', '.odt', '.pages'.",
				filesSize: "Файл {{fi-name}} слишком большой! Загрузите файл размером до {{fi-maxSize}} MB.",
				filesSizeAll: "Файлы, которые вы выбрали, слишком большие! Загрузите файлы размером до {{fi-maxSize}} MB."
			}
		}
	});
	
	
	$("#filer_input1").filer({
		limit: 1,
		maxSize: null,
		extensions: ['txt', 'doc', 'docx', 'docm', 'odt', 'pages'],
		changeInput: '<div class="jFiler-input-dragDrop"><div class="jFiler-input-inner"><div class="jFiler-input-icon"><i id="icon_cloud" class="icon-jfi-cloud-up-o"></i></div><div class="jFiler-input-text"><h3 id="dragdrop_title">Перетащите файл сюда</h3> <span style="display:inline-block; margin: 15px 0">или</span></div><a class="jFiler-input-choose-btn blue">Выберите файл</a></div></div>',
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
			url: "ajax_upload_file.php",
			data: dataObj2,
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
					$("<div class=\"jFiler-item-others text-success\"><i class=\"icon-jfi-check-circle\"></i> Файл загружен</div>").hide().appendTo(parent).fadeIn("slow");
				});
			},
			error: function(el){
				var parent = el.find(".jFiler-jProgressBar").parent();
				el.find(".jFiler-jProgressBar").fadeOut("slow", function(){
					$("<div class=\"jFiler-item-others text-error\"><i class=\"icon-jfi-minus-circle\"></i> Ошибка</div>").hide().appendTo(parent).fadeIn("slow");
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

		    $.post('ajax_remove_file.php', {file: file_name});
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
			button: "Выберите файл",
			feedback: "Выберите файлы для загрузки",
			feedback2: "файлы выбраны",
			drop: "Перетащите файл сюда, чтобы загрузить",
			removeConfirmation: "Вы уверены, что хотите удалить этот файл?",
			errors: {
				filesLimit: "Только {{fi-limit}} файл может быть загружен.",
				filesType: "Могут быть загружены только файлы с расширениями '.doc','.docx', '.docm.', '.txt', '.odt', '.pages'.",
				filesSize: "Файл {{fi-name}} слишком большой! Загрузите файл размером до {{fi-maxSize}} MB.",
				filesSizeAll: "Файлы, которые вы выбрали, слишком большие! Загрузите файлы размером до {{fi-maxSize}} MB."
			}
		}
	});
	
	$("#filer_input3").filer({
		limit: 1,
		maxSize: null,
		extensions: ['pdf', 'ppt', 'pptx', 'key'],
		changeInput: '<div class="jFiler-input-dragDrop"><div class="jFiler-input-inner"><div class="jFiler-input-icon"><i id="icon_cloud" class="icon-jfi-cloud-up-o"></i></div><div class="jFiler-input-text"><h3 id="dragdrop_title">Перетащите файл сюда</h3> <span style="display:inline-block; margin: 15px 0">или</span></div><a class="jFiler-input-choose-btn blue">Выберите файл</a></div></div>',
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
			url: "ajax_upload_file.php",
			data: dataObj3,
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
					$("<div class=\"jFiler-item-others text-success\"><i class=\"icon-jfi-check-circle\"></i> Файл загружен</div>").hide().appendTo(parent).fadeIn("slow");
				});
			},
			error: function(el){
				var parent = el.find(".jFiler-jProgressBar").parent();
				el.find(".jFiler-jProgressBar").fadeOut("slow", function(){
					$("<div class=\"jFiler-item-others text-error\"><i class=\"icon-jfi-minus-circle\"></i> Ошибка</div>").hide().appendTo(parent).fadeIn("slow");
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

		    $.post('ajax_remove_file.php', {file: file_name});
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
			button: "Выберите файл",
			feedback: "Выберите файлы для загрузки",
			feedback2: "файлы выбраны",
			drop: "Перетащите файл сюда, чтобы загрузить",
			removeConfirmation: "Вы уверены, что хотите удалить этот файл?",
			errors: {
				filesLimit: "Только {{fi-limit}} файл может быть загружен.",
				filesType: "Могут быть загружены только файлы с расширениями '.pdf','.ppt','.pptx', '.key'.",
				filesSize: "Файл {{fi-name}} слишком большой! Загрузите файл размером до {{fi-maxSize}} MB.",
				filesSizeAll: "Файлы, которые вы выбрали, слишком большие! Загрузите файлы размером до {{fi-maxSize}} MB."
			}
		}
	});
	
/*	// kaf navigate
	$('#cssmenu > ul > li ul').each(function(index, e){
        var count = $(e).find('li').length;
        var content = '<span class=\"cnt\">' + count + '</span>';
        $(e).closest('li').children('a').append(content);
    });
    $('#cssmenu ul ul li:odd').addClass('odd');
    $('#cssmenu ul ul li:even').addClass('even');
    $('#cssmenu > ul > li > a').click(function() {
        $('#cssmenu li').removeClass('active');
        $(this).closest('li').addClass('active');   
        var checkElement = $(this).next();
        if((checkElement.is('ul')) && (checkElement.is(':visible'))) {
            $(this).closest('li').removeClass('active');
            checkElement.slideUp('normal');
        }
        if((checkElement.is('ul')) && (!checkElement.is(':visible'))) {
            $('#cssmenu ul ul:visible').slideUp('normal');
            checkElement.slideDown('normal');
        }
        if($(this).closest('li').find('ul').children().length == 0) {
            return true;
        } else {
            return false;
        }
    });*/
})
