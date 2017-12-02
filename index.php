<style>
/*css NEW Modal Dialog */
/* Базовые стили слоя затемнения и модального окна  */
.overlay {
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
    z-index: 10;
    display: none;
/* фон затемнения */
    background-color: rgba(0, 0, 0, 0.65);
    position: fixed; /* фиксированное поцизионирование */
    cursor: default; /* тип курсара */
}
/* активируем слой затемнения */
.overlay:target {
    display: block;
}
/* стили модального окна */
.popup {
    top: -100%;
    right: 0;
    left: 50%;
    font-size: 14px;
    z-index: 20;
    margin: 0;
    width: 85%;
    min-width: 320px;
    max-width: 600px;
/* фиксированное позиционирование, окно стабильно при прокрутке */
    position: fixed;
    padding: 15px;
    padding-top: 5px;
    border: 1px solid #383838;
    background: #fefefe;
/* скругление углов */
    -webkit-border-radius: 4px;
    -moz-border-radius: 4px;
    -ms-border-radius: 4px;
    border-radius: 4px;
    font: 14px/18px 'Tahoma', Arial, sans-serif;
/* внешняя тень */
    -webkit-box-shadow: 0 15px 20px rgba(0,0,0,.22),0 19px 60px rgba(0,0,0,.3);
    -moz-box-shadow: 0 15px 20px rgba(0,0,0,.22),0 19px 60px rgba(0,0,0,.3);
    -ms-box-shadow: 0 15px 20px rgba(0,0,0,.22),0 19px 60px rgba(0,0,0,.3);
    box-shadow: 0 15px 20px rgba(0,0,0,.22),0 19px 60px rgba(0,0,0,.3);
    -webkit-transform: translate(-50%, -500%);
    -ms-transform: translate(-50%, -500%);
    -o-transform: translate(-50%, -500%);
    transform: translate(-50%, -500%);
    -webkit-transition: -webkit-transform 0.6s ease-out;
    -moz-transition: -moz-transform 0.6s ease-out;
    -o-transition: -o-transform 0.6s ease-out;
    transition: transform 0.6s ease-out;
}
/* активируем модальный блок */
.overlay:target+.popup {
    -webkit-transform: translate(-50%, 0);
    -ms-transform: translate(-50%, 0);
    -o-transform: translate(-50%, 0);
    transform: translate(-50%, 0);
    top: 20%;
}
#submit_modal_form{
    background-color: #46b6ac;
    margin:auto;
    width: 200px;
    display: block;
    font-size: 19px;
}

#submit_modal_form:hover {
    background-color: rgba(70,182,172, 0.85);
}
/*end*/

/*css Modal Dialog */
.modalDialog {
	position: fixed;
	font-family: Arial, Helvetica, sans-serif;
	top: 0;
	right: 0;
	bottom: 0;
	left: 0;
	background: rgba(0, 0, 0, 0.7);
	z-index: 99999;
	-webkit-transition: opacity 400ms ease-in;
	-moz-transition: opacity 400ms ease-in;
	transition: opacity 400ms ease-in;
	display: none;
	pointer-events: none;
}

.modalDialog:target {
	display: block;
	pointer-events: auto;
}

.modalDialog > div {
	width: 400px;
	position: relative;
	margin: 10% auto;
	padding: 5px 20px 13px 20px;
	border-radius: 2px;
	background: white;
	color: rgba(0,0,0,.54);
}

.close {
	background: #606061;
	color: #FFFFFF;
	line-height: 25px;
	position: absolute;
	right: -12px;
	text-align: center;
	top: -10px;
	width: 24px;
	text-decoration: none;
	font-weight: bold;
	-webkit-border-radius: 12px;
	-moz-border-radius: 12px;
	border-radius: 12px;
	-moz-box-shadow: 1px 1px 3px #000;
	-webkit-box-shadow: 1px 1px 3px #000;
	box-shadow: 1px 1px 3px #000;
}

.close:hover { background: #46b6ac !important; }

#modal_d_teacher, #modal_d_title{
    font-size: 17px;
}

select[name="teacher"]{
    width: 275px;
}
/*end css Modal Dialog */

/* css Style Tabs */
/*----- Tabs -----*/
.tabs {
    width:100%;
    display:inline-block;
}
 
/*----- Tab Links -----*/
/* Clearfix */
.tab-links:after {
    display:block;
    clear:both;
    content:'';
}

.tab-links li {
    margin:0px 5px;
    float:left;
    list-style:none;
}

.tab-links a {
    padding:9px 15px;
    display:inline-block;
    border-radius:3px 3px 0px 0px;
    background:#7FB5DA;
    font-size:16px;
    font-weight:600;
    color:#4c4c4c;
    transition:all linear 0.15s;
}

.tab-links a:hover {
    background:#a7cce5;
    text-decoration:none;
}

li.active a, li.active a:hover {
    background:#fff;
    color:#4c4c4c;
}

/*----- Content of Tabs -----*/
.tab-content {
    padding:15px;
    border-radius:3px;
    box-shadow:-1px 1px 1px rgba(0,0,0,0.15);
    background:#fff;
}

.tab {
    display:none;
}

.tab.active {
    display:block;
}
/* end Style Tabs */

.work_block{
    width: 300px;
    height: 230px;
    margin-left: 10px;
    margin-right: 10px;
    margin-bottom: 15px;
    cursor: pointer;
    float: left;
    background-color: #46b6ac;
    border-radius: 2px;
    box-shadow: 0 2px 2px 0 rgba(0,0,0,.14), 0 3px 1px -2px rgba(0,0,0,.2), 0 1px 5px 0 rgba(0,0,0,.12);
    -moz-box-shadow: 0 2px 2px 0 rgba(0,0,0,.14), 0 3px 1px -2px rgba(0,0,0,.2), 0 1px 5px 0 rgba(0,0,0,.12);
    -webkit-box-shadow: 0 2px 2px 0 rgba(0,0,0,.14), 0 3px 1px -2px rgba(0,0,0,.2), 0 1px 5px 0 rgba(0,0,0,.12);
}

.work_block_closed{
    background-color: rgba(204,204,204, 0.9);
}

.work_title{
    color: rgba(0,0,0,.54);
    background-color: #eeeeee;
    padding-bottom: 5px;
    padding-top: 5px;
    color: rgba(0,0,0,.54);
    padding-left: 10px;
    font-size: 20px;
    line-height: 23px;
}

.work_teacher{
    color: rgba(255,255,255,0.85);
    padding-bottom: 10px;
    padding-top: 0px;
    padding-left: 10px;
    font-size: 20px;
}

.work_block:hover{
    background-color: rgba(70,182,172, 0.85);
}

.work_block:hover .work_title{
    color: rgba(0,0,0,.4);
} 

.work_block_closed:hover{
    background-color: rgba(204,204,204, 0.5);
}
.work_block_closed .work_teacher{
    color: rgba(0,0,0,.4);
}

.work_teacher_title{
    font-weight: bold;
}

.work_title_title{
    font-weight: bold;
}

#button_create_nir{
    background-color: #46b6ac;
    height: 30px;
    width: 140px;
    border-radius: 3px;
    text-align: center;
    padding-top: 7px;
    color: rgba(255,255,255,0.85);
    box-shadow: 0 2px 2px 0 rgba(0,0,0,.14), 0 3px 1px -2px rgba(0,0,0,.2), 0 1px 5px 0 rgba(0,0,0,.12);
    -moz-box-shadow: 0 2px 2px 0 rgba(0,0,0,.14), 0 3px 1px -2px rgba(0,0,0,.2), 0 1px 5px 0 rgba(0,0,0,.12);
    -webkit-box-shadow: 0 2px 2px 0 rgba(0,0,0,.14), 0 3px 1px -2px rgba(0,0,0,.2), 0 1px 5px 0 rgba(0,0,0,.12);
    font-size: 17px;
    margin: auto;
}

#button_create_nir:hover{
    background-color: rgba(70,182,172, 0.85);
}

h1{
    text-align: center;
    color: rgba(0,0,0,.54) !important;
    margin-bottom: 20px !important;
    font-weight: bold !important;
}

body{
    height: auto !important;
}

.single_work_title{
    font-size:30px;
    color: rgba(0,0,0,.54);
    text-align: center;
    line-height: 34px;
}
.single_work_title_title, .single_work_teacher_title{
    font-weight: bold;
}

.single_work_teacher{
    font-size:25px;
    color: rgba(0,0,0,.54);
    text-align: center;
    line-height: 34px;
}

.tab-content{
     box-shadow: 0 2px 2px 0 rgba(0,0,0,.14), 0 3px 1px -2px rgba(0,0,0,.2), 0 1px 5px 0 rgba(0,0,0,.12);
    -moz-box-shadow: 0 2px 2px 0 rgba(0,0,0,.14), 0 3px 1px -2px rgba(0,0,0,.2), 0 1px 5px 0 rgba(0,0,0,.12);
    -webkit-box-shadow: 0 2px 2px 0 rgba(0,0,0,.14), 0 3px 1px -2px rgba(0,0,0,.2), 0 1px 5px 0 rgba(0,0,0,.12);
    background-color: rgba(238,238,238, 0.5);
}

.tab-links a{
    background-color: rgba(70,182,172, 0.35);
    color: rgba(0,0,0,.54) !important;
}

.tab-links a:hover{
    background-color: rgba(70,182,172, 0.85);
}

li.active a, li.active a:hover{
    background-color: rgba(70,182,172, 0.85) !important;
}


#icon_cloud{
    width: 65px !important;
    padding-top: 3px !important;
}

#dragdrop_title{
    padding-top: 10px;
}

/*.jFiler-items-grid .jFiler-item .jFiler-item-container{
    margin-top:-222px !important;
}*/

.jFiler-input-dragDrop{
    width: 600px !important;
}

.block_file_prev{
    background-color: white;
    width: 210px;
    height: 250px;
    border-radius: 2px;
    border: 1px solid rgba(0,0,0,.2);
    margin-right: 15px;
    margin-left: 15px;
    margin-bottom: 15px;
    float: left;
    
    box-shadow: 0 2px 2px 0 rgba(0,0,0,.14), 0 3px 1px -2px rgba(0,0,0,.2), 0 1px 5px 0 rgba(0,0,0,.12);
    -moz-box-shadow: 0 2px 2px 0 rgba(0,0,0,.14), 0 3px 1px -2px rgba(0,0,0,.2), 0 1px 5px 0 rgba(0,0,0,.12);
    -webkit-box-shadow: 0 2px 2px 0 rgba(0,0,0,.14), 0 3px 1px -2px rgba(0,0,0,.2), 0 1px 5px 0 rgba(0,0,0,.12);
}

.block_file_prev:hover{
    background-color: rgba(0,0,0, 0.1);
    color: #2C3E50;
}

.block_file_prev_kaf{
    background-color: white;
    width: 210px;
    height: 250px;
    border-radius: 2px;
    border: 1px solid rgba(0,0,0,.2);
    margin-bottom: 15px;
    
    box-shadow: 0 2px 2px 0 rgba(0,0,0,.14), 0 3px 1px -2px rgba(0,0,0,.2), 0 1px 5px 0 rgba(0,0,0,.12);
    -moz-box-shadow: 0 2px 2px 0 rgba(0,0,0,.14), 0 3px 1px -2px rgba(0,0,0,.2), 0 1px 5px 0 rgba(0,0,0,.12);
    -webkit-box-shadow: 0 2px 2px 0 rgba(0,0,0,.14), 0 3px 1px -2px rgba(0,0,0,.2), 0 1px 5px 0 rgba(0,0,0,.12);
}

.block_file_prev_kaf:hover{
    background-color: rgba(0,0,0, 0.1);
    color: #2C3E50;
}

.a_file_block:focus{
    color: #2C3E50 !important;
}

.a_file_block:hover{
    color: #2C3E50 !important;
}

.a_file_block_kaf{
    width: 210px;
    display: block;
    margin: auto;
}

.work_not_load{
    font-size: 20px;
    text-align: center;
}

.sign_kaf_button_not_active, .cancel_kaf_button_not_active, .sign_teacher_button_not_active{
    background-color: rgba(0, 0, 0, 0.1) !important;
    cursor: default !important;
}

.sign_kaf_button{
    width: 150px;
    background-color: rgba(70,182,172, 0.35);
    padding: 10px;
    text-align: center;
    cursor: pointer;
    font-size: 19px;
    float: left;
    
    box-shadow: 0 2px 2px 0 rgba(0,0,0,.14), 0 3px 1px -2px rgba(0,0,0,.2), 0 1px 5px 0 rgba(0,0,0,.12);
    -moz-box-shadow: 0 2px 2px 0 rgba(0,0,0,.14), 0 3px 1px -2px rgba(0,0,0,.2), 0 1px 5px 0 rgba(0,0,0,.12);
    -webkit-box-shadow: 0 2px 2px 0 rgba(0,0,0,.14), 0 3px 1px -2px rgba(0,0,0,.2), 0 1px 5px 0 rgba(0,0,0,.12);
}

.cancel_kaf_button{
    width: 150px;
    background-color: rgba(70,182,172, 0.35);
    padding: 10px;
    text-align: center;
    cursor: pointer;
    font-size: 19px;
    float: right;
    
    box-shadow: 0 2px 2px 0 rgba(0,0,0,.14), 0 3px 1px -2px rgba(0,0,0,.2), 0 1px 5px 0 rgba(0,0,0,.12);
    -moz-box-shadow: 0 2px 2px 0 rgba(0,0,0,.14), 0 3px 1px -2px rgba(0,0,0,.2), 0 1px 5px 0 rgba(0,0,0,.12);
    -webkit-box-shadow: 0 2px 2px 0 rgba(0,0,0,.14), 0 3px 1px -2px rgba(0,0,0,.2), 0 1px 5px 0 rgba(0,0,0,.12);
}

.cancel_kaf_button:hover, .sign_kaf_button:hover, .sign_button_teacher:hover, .cancel_button_teacher:hover, .finish_work_button:hover{
    background-color: rgba(70,182,172, 0.85);
}

.block_files_sign_kaf{
    width: 400px;
    height: 45px;
    margin:auto;
}

.block_files_sign_teacher{
    width: 200px;
}

.sign_button_teacher{
    width: 150px;
    background-color: rgba(70,182,172, 0.35);
    padding: 10px;
    text-align: center;
    cursor: pointer;
    font-size: 19px;
    margin-left: 20px;
    margin-top: 5px;
    
    box-shadow: 0 2px 2px 0 rgba(0,0,0,.14), 0 3px 1px -2px rgba(0,0,0,.2), 0 1px 5px 0 rgba(0,0,0,.12);
    -moz-box-shadow: 0 2px 2px 0 rgba(0,0,0,.14), 0 3px 1px -2px rgba(0,0,0,.2), 0 1px 5px 0 rgba(0,0,0,.12);
    -webkit-box-shadow: 0 2px 2px 0 rgba(0,0,0,.14), 0 3px 1px -2px rgba(0,0,0,.2), 0 1px 5px 0 rgba(0,0,0,.12);
}

.finish_work_button{
    width: 200px;
    background-color: rgba(70,182,172, 0.35);
    padding: 10px;
    text-align: center;
    cursor: pointer;
    font-size: 19px;
    margin: auto;
    margin-top: 20px;
    
    box-shadow: 0 2px 2px 0 rgba(0,0,0,.14), 0 3px 1px -2px rgba(0,0,0,.2), 0 1px 5px 0 rgba(0,0,0,.12);
    -moz-box-shadow: 0 2px 2px 0 rgba(0,0,0,.14), 0 3px 1px -2px rgba(0,0,0,.2), 0 1px 5px 0 rgba(0,0,0,.12);
    -webkit-box-shadow: 0 2px 2px 0 rgba(0,0,0,.14), 0 3px 1px -2px rgba(0,0,0,.2), 0 1px 5px 0 rgba(0,0,0,.12);
}

.cancel_button_teacher{
    width: 150px;
    background-color: rgba(70,182,172, 0.35);
    padding: 10px;
    text-align: center;
    cursor: pointer;
    font-size: 19px;
    margin-left: 20px;
    margin-top: 5px;
    
    box-shadow: 0 2px 2px 0 rgba(0,0,0,.14), 0 3px 1px -2px rgba(0,0,0,.2), 0 1px 5px 0 rgba(0,0,0,.12);
    -moz-box-shadow: 0 2px 2px 0 rgba(0,0,0,.14), 0 3px 1px -2px rgba(0,0,0,.2), 0 1px 5px 0 rgba(0,0,0,.12);
    -webkit-box-shadow: 0 2px 2px 0 rgba(0,0,0,.14), 0 3px 1px -2px rgba(0,0,0,.2), 0 1px 5px 0 rgba(0,0,0,.12);
}

.img_files_prev{
    margin-top: 22px;
    margin-left: 46px;
}

.block_files{
    margin-bottom: 30px;
    padding-left: 40px;
}

.block_files_kaf{
    margin-bottom: 30px;
}

.file_name{
    text-align: center;
    padding-top: 10px;
    font-weight: bold !important;
    margin-bottom: 0px !important;
}

.file_date{
    text-align: center;
    margin-bottom: 0px !important;
}

.messages{
    height: auto;
    border: 1px rgba(0,0,0,.2) solid;
    background-color: white;
    margin: auto;
    width: 600px;
    margin-top: 15px;
    
    box-shadow: 0 2px 2px 0 rgba(0,0,0,.14), 0 3px 1px -2px rgba(0,0,0,.2), 0 1px 5px 0 rgba(0,0,0,.12);
    -moz-box-shadow: 0 2px 2px 0 rgba(0,0,0,.14), 0 3px 1px -2px rgba(0,0,0,.2), 0 1px 5px 0 rgba(0,0,0,.12);
    -webkit-box-shadow: 0 2px 2px 0 rgba(0,0,0,.14), 0 3px 1px -2px rgba(0,0,0,.2), 0 1px 5px 0 rgba(0,0,0,.12);
}

.message{
    border-bottom: 1px rgba(0,0,0,.2) solid;
    height: auto;
}

.header_message{
    border-bottom: 1px rgba(0,0,0,.2) solid;
    color: rgba(0,0,0,.54) !important;
    font-weight: bold !important;
    background-color: rgba(0, 0, 0, 0.1);
}

.header_message_kaf{
    background-color: rgba(255, 187, 63, 0.5);
}

.header_message_name{
    width: 280px;
    float: left;
    padding-left: 15px;
    padding-top: 5px;
}

.header_message_date{
    width: 280px;
    float: right;
    text-align: right;
    padding-right: 15px;
    padding-top: 5px;
}

.message_text{
    padding-left: 15px;
    padding-right: 15px;
}

.send_block_message{
    margin-bottom: 0px !important;
    width:533px;
}

.send_message_button{
    width: 68px;
    height:70px;
    margin-top: -70px;
    margin-left: 532px;
    background-color: rgba(70,182,172, 0.35) !important;
    color: rgba(70,182,172, 0.35) !important;
    margin-bottom: 0px !important;
    border-radius: 0px !important;
}

.send_message_button:hover{
    background-color: rgba(70,182,172, 0.85) !important;
    box-shadow: 0 2px 2px 0 rgba(0,0,0,.14), 0 3px 1px -2px rgba(0,0,0,.2), 0 1px 5px 0 rgba(0,0,0,.12);
    -moz-box-shadow: 0 2px 2px 0 rgba(0,0,0,.14), 0 3px 1px -2px rgba(0,0,0,.2), 0 1px 5px 0 rgba(0,0,0,.12);
    -webkit-box-shadow: 0 2px 2px 0 rgba(0,0,0,.14), 0 3px 1px -2px rgba(0,0,0,.2), 0 1px 5px 0 rgba(0,0,0,.12);
}

.send_icon{
    margin-top: 0px;
    margin-left: -5px;
}

.img_new{
    margin-top: -208px;
    margin-left: 159px;
}

.new_file_message{
    padding-left: 10px;
    background-color: rgba(255, 255, 255, 0.3);
    padding-bottom: 5px;
    padding-top: 5px;
    color: rgba(255,255,255,0.85);
}

#ul_file_block{
    margin: auto;
    width: 215px;
}

.users_list_el{
    text-align: right;
    padding: 18px;
    font-size: 25px;
    margin-bottom: 5px;
    background-color: #46b6ac;
    color: rgba(255,255,255,0.85);
    border-radius: 2px;
    box-shadow: 0 2px 2px 0 rgba(0,0,0,.14), 0 3px 1px -2px rgba(0,0,0,.2), 0 1px 5px 0 rgba(0,0,0,.12);
    -moz-box-shadow: 0 2px 2px 0 rgba(0,0,0,.14), 0 3px 1px -2px rgba(0,0,0,.2), 0 1px 5px 0 rgba(0,0,0,.12);
    -webkit-box-shadow: 0 2px 2px 0 rgba(0,0,0,.14), 0 3px 1px -2px rgba(0,0,0,.2), 0 1px 5px 0 rgba(0,0,0,.12);
}

.users_list_el:hover{
    background-color: rgba(70,182,172, 0.85);
    color: rgba(255,255,255,0.85);
}

#review_block{
    height: 230px;
    border: 1px rgba(0,0,0,.2) solid;
    background-color: white;
    margin: auto;
    width: 600px;
    display: none;
    
    box-shadow: 0 2px 2px 0 rgba(0,0,0,.14), 0 3px 1px -2px rgba(0,0,0,.2), 0 1px 5px 0 rgba(0,0,0,.12);
    -moz-box-shadow: 0 2px 2px 0 rgba(0,0,0,.14), 0 3px 1px -2px rgba(0,0,0,.2), 0 1px 5px 0 rgba(0,0,0,.12);
    -webkit-box-shadow: 0 2px 2px 0 rgba(0,0,0,.14), 0 3px 1px -2px rgba(0,0,0,.2), 0 1px 5px 0 rgba(0,0,0,.12);
}

#review_block_header{
    height: 43px;
    border: 1px rgba(0,0,0,.2) solid;
    background-color: white;
    margin: auto;
    width: 600px;
    background-color: rgba(70,182,172, 0.35);
    cursor: pointer;
    
    box-shadow: 0 2px 2px 0 rgba(0,0,0,.14), 0 3px 1px -2px rgba(0,0,0,.2), 0 1px 5px 0 rgba(0,0,0,.12);
    -moz-box-shadow: 0 2px 2px 0 rgba(0,0,0,.14), 0 3px 1px -2px rgba(0,0,0,.2), 0 1px 5px 0 rgba(0,0,0,.12);
    -webkit-box-shadow: 0 2px 2px 0 rgba(0,0,0,.14), 0 3px 1px -2px rgba(0,0,0,.2), 0 1px 5px 0 rgba(0,0,0,.12);
}

.review_title{
    font-size: 19px;
    padding: 8px;
    text-align: center;
    padding-bottom: 0px;
}

.mark_title{
    font-size: 19px;
    padding: 8px;
    margin-left: 88px;
    margin-right: 10px;
}

#review_area{
    margin-left: 50px;
    width: 500px;
}

#mark_input{
    font-size: 19px;
}

#send_review{
    width: 200px;
    background-color: rgba(70,182,172, 0.35);
    padding: 10px;
    text-align: center;
    cursor: pointer;
    font-size: 19px;
    margin-left: 200px;
}

#send_review:hover{
    background-color: rgba(70,182,172, 0.85);
}

.review_header_title{
    font-size: 21px;
    text-align: center;
    padding-top: 12px;
}

#review_block_header:hover{
    background-color: rgba(70,182,172, 0.85);
}

.ex_review_title{
    font-size: 19px;
    padding: 8px;
    text-align: center;
    padding-bottom: 0px;
}

.ex_review_text{
    padding-left: 16px;
    padding-right: 16px;
}

.ex_mark{
    font-size: 19px;
    text-align: center;
}

.ex_mark span{
    font-weight: bold;
    font-size: 25px;
}
/* Admin menu */

#cssmenu,
#cssmenu ul,
#cssmenu li,
#cssmenu a {
    margin: 0;
    padding: 0;
    border: 0;
    list-style: none;
    font-weight: normal;
    text-decoration: none;
    line-height: 1;
    font-family:sans-serif,arial;
    font-size: 14px;
    position: relative;
}
#cssmenu a {
    line-height: 1.3;
}
#cssmenu {
    width: auto;
}
#cssmenu > ul > li > a {
    padding-right: 40px;
    font-size: 25px;
    font-weight: bold;
    display: block;
    background: rgba(70,182,172, 0.65);
    color: #ffffff;
    border-bottom: 2px solid rgba(255,255,255, 0.85);
    text-transform: uppercase;
}
#cssmenu > ul > li > a > span {
    background-color: #46b6ac;
    color: rgba(255,255,255,0.85) !important;
    padding: 10px;
    display: block;
    font-size: 21px;
    font-weight: 300;
}
#cssmenu > ul > li > a:hover {
    text-decoration: none;
}
#cssmenu > ul > li.act {
    border-bottom: none;
}
#cssmenu > ul > li.act > a {
    color: #fff !important;
}
#cssmenu > ul > li.act > a span {
    /*background-color: rgba(70,182,172, 0.85);
    color: rgba(255,255,255,0.85) !important;*/
}
#cssmenu span.cnt {
    position: absolute;
    top: 8px;
    right: 15px;
    padding: 0;
    margin: 0;
    background: none;
}
/* Sub menu */
#cssmenu ul ul {
    display: none;
}
#cssmenu ul ul li {
    border: 1px solid #e0e0e0;
    border-top: 0;
}
#cssmenu ul ul a {
    padding: 10px;
    display: block;
    font-size: 16px;
}
#cssmenu ul ul a:hover {
    color: rgba(0,0,0,.54);
}
#cssmenu ul ul li.odd {
    background: #f4f4f4;
}
#cssmenu ul ul li.even {
    background: #fff;
}


/* end admin menu */

.sign_files_kaf_icon{
    margin-top: -25px;
    text-align: right;
}
</style>
<?php

// The number of lines in front of config file determine the // hierarchy of files. 
require_once(dirname(__FILE__) . '/../config.php');

$context = context_user::instance($USER->id);
$PAGE->set_blocks_editing_capability('moodle/my:manageblocks');
$header = fullname($USER);

$PAGE->set_context($context);
$PAGE->set_pagelayout('incourse');
$PAGE->set_title("НИР");
$PAGE->set_heading($header);
$PAGE->set_url($CFG->wwwroot.'/nir/index.php');
$PAGE->requires->js('/nir/js/jquery-3.2.0.min.js', true);
$PAGE->requires->css('/nir/material/jquery.filer.css', true);
$PAGE->requires->css('/nir/material/jquery.filer-dragdropbox-theme.css', true);
$PAGE->requires->js('/nir/material/jquery.filer.min.js', true);
$PAGE->requires->js('/nir/js/main.js', true);

if ($CFG->forcelogin) {
    require_login();
}

$previewnode = $PAGE->navigation->add("НИР", new moodle_url('/nir/index.php'), navigation_node::TYPE_CONTAINER);
$previewnode->make_active();


$ADMIN = 2; // kaf id hardcode

echo $OUTPUT->header();
if($USER->profile['isTeacher'] === "666"){
    
    
    if(isset($_GET["std"])){
        $student_id = (int) $_GET["std"];
        
        $sql_student = "SELECT mdl_user.firstname, mdl_user.lastname, mdl_user.id, mdl_user_info_data.data FROM mdl_user, mdl_user_info_data WHERE mdl_user.id=".$student_id." AND mdl_user_info_data.userid=mdl_user.id AND mdl_user_info_data.fieldid='3'";
        $rs_student = $DB->get_record_sql($sql_student);
            
        
        if(isset($_GET["id"])){
            echo "<h1>Научно-исследовательская работа</h1>";
            $work_id = (int) $_GET["id"];
            
            $sql_work = "SELECT mdl_nir.id, mdl_nir.title, mdl_nir.is_closed, mdl_nir.review, mdl_nir.mark, mdl_user.firstname, mdl_user.lastname FROM mdl_nir, mdl_user WHERE mdl_nir.user_id=".$student_id." AND mdl_user.id=mdl_nir.teacher_id AND mdl_nir.id=".$work_id;
            $rs = $DB->get_records_sql($sql_work);

            if(count($rs) == 0){
                echo "404 NOT FOUND";
            }
            else{
                $sql_file_type_z = "SELECT mdl_nir_files.id, mdl_nir_files.filename, mdl_nir_files.date, mdl_nir_files.is_new, mdl_nir_files.is_sign_kaf, mdl_user.firstname, mdl_user.lastname FROM mdl_nir_files, mdl_nir, mdl_user WHERE mdl_nir.id=".$work_id." AND mdl_nir_files.nir_id=".$work_id." AND mdl_nir_files.type='Z' AND mdl_user.id=mdl_nir_files.user_id AND mdl_nir_files.is_sign_teacher=1";
                $file_type_z = $DB->get_record_sql($sql_file_type_z);
                $sql_messages_type_z = "SELECT mdl_nir_messages.id, mdl_nir_messages.text, mdl_nir_messages.date FROM mdl_nir_messages WHERE mdl_nir_messages.nir_id=".$work_id." AND mdl_nir_messages.user_id=".$USER->id." AND mdl_nir_messages.nir_type='Z' ORDER BY mdl_nir_messages.date";
                $messages_type_z = $DB->get_records_sql($sql_messages_type_z);
                
                $sql_file_type_o = "SELECT mdl_nir_files.id, mdl_nir_files.filename, mdl_nir_files.date, mdl_nir_files.is_new, mdl_nir_files.is_sign_kaf, mdl_user.firstname, mdl_user.lastname FROM mdl_nir_files, mdl_nir, mdl_user WHERE mdl_nir.id=".$work_id." AND mdl_nir_files.nir_id=".$work_id." AND mdl_nir_files.type='O' AND mdl_user.id=mdl_nir_files.user_id AND mdl_nir_files.is_sign_teacher=1";
                $file_type_o = $DB->get_record_sql($sql_file_type_o);
                $sql_messages_type_o = "SELECT mdl_nir_messages.id, mdl_nir_messages.text, mdl_nir_messages.date FROM mdl_nir_messages WHERE mdl_nir_messages.nir_id=".$work_id." AND mdl_nir_messages.user_id=".$USER->id." AND mdl_nir_messages.nir_type='O' ORDER BY mdl_nir_messages.date";
                $messages_type_o = $DB->get_records_sql($sql_messages_type_o);
        
        
                echo "<p class='single_work_teacher'><span class='single_work_teacher_title'>Научный руководитель: </span>";
                echo $rs[$work_id]->lastname." ".$rs[$work_id]->firstname."</p>";
                
                echo "<p class='single_work_teacher'><span class='single_work_teacher_title'>Студент: </span>";
                echo $rs_student->lastname." ".$rs_student->firstname."</p>";
                
                echo "<p class='single_work_teacher'><span class='single_work_teacher_title'>Группа: ";
                echo "</span>".$rs_student->data."</p>";
                echo "<br/>";
                
                
                echo "<div class='tabs'>";
                    echo "<ul class='tab-links'>";
                        echo "<li class='active'><a href='#tab1'>Задание на НИР</a></li>";
                        echo "<li><a href='#tab2'>Отчет</a></li>";
                    echo "</ul>";
         
                    echo "<div class='tab-content'>";
                        echo "<div id='tab1' class='tab active'>";
                            echo "<div id='content'>";
                                echo "<div class='block_files_kaf'>";
                                if($file_type_z){
                                    echo "<a href='".$file_type_z->filename."' target='_blank' class='a_file_block a_file_block_kaf'>";
                                        echo "<div class='block_file_prev_kaf'>";
                                            echo "<img src='img/Filetype-Docs-icon.png' height='110px' class='img_files_prev'/>";
                                            echo "<p class='file_date'><span style='font-weight: bold'>Дата загрузки: </span>".$file_type_z->date."</p>";
                                            echo "<p class='file_date'><span style='font-weight: bold'>Добавил: </span>".$file_type_z->lastname." ".$file_type_z->firstname."</p>";
                                            echo "<input type='hidden' name='file_id' id='file_id' value='".$file_type_z->id."'>";
                                        echo "</div>";
                                    echo "</a>";
                                }
                                else{
                                    echo "<p class='work_not_load'>Работа еще не была загружена.</p>";
                                }
                                    echo "<div style='clear:both;'></div>";
                                echo "</div>";
                                
                                if($file_type_z){
                                    echo "<div class='block_files_sign_kaf'>";
                                        echo "<div class='sign_kaf_button";
                                        if($file_type_z->is_sign_kaf == 1){
                                            echo " sign_kaf_button_not_active";
                                        }
                                        echo "' >Подписать</div>";
                                        echo "<div class='cancel_kaf_button' >Отклонить</div>";
                                    echo "</div>";
                                    echo "<br/>";
                                }
                                
                                echo "<input type='hidden' name='h_work' id='h_work' value='".$work_id."'>";
                                echo "<input type='hidden' name='h_work_type' id='h_work_type' value='Z'>";
                                
                                if(count($messages_type_z) == 0 && $rs[$work_id]->is_closed == 1){
                                }
                                else
                                {
                                    echo "<div class='messages' >";
                                    
                                    foreach ($messages_type_z as $mz){
                                        echo "<div class='message'>";
                                            echo "<div class='header_message'>";
                                                echo "<p class='header_message_name'>Кафедра</p>";
                                                echo "<p class='header_message_date'>".$mz->date."</p>";
                                                echo "<div style='clear:both;'></div>";
                                            echo "</div>";
                                            echo "<p class='message_text'>".$mz->text."</p>";
                                        echo "</div>";
                                    }
                                    
                                    if($rs[$work_id]->is_closed != 1){
                                        echo "<div class='textar_message_new'>";
                                            echo "<textarea rows='3' name='message' required style='resize: none;' class='send_block_message' id='message_textarea_tab1'></textarea>";
                                            echo "<button class='send_message_button' id='send_message_tab1'>";
                                                echo "<img class='send_icon' src='img/send_icon.png' width='50px'/>";
                                            echo "</button>";
                                        echo "</div>";
                                    }
                                    echo "</div>";
                                }
                                
                            echo "</div>";
                        echo "</div>";
                 
                        echo "<div id='tab2' class='tab'>";
                            echo "<div id='content'>";
                                echo "<div class='block_files_kaf'>";
                                
                                if($file_type_o){
                                    echo "<a href='".$file_type_o->filename."' target='_blank' class='a_file_block a_file_block_kaf'>";
                                        echo "<div class='block_file_prev_kaf'>";
                                            echo "<img src='img/Filetype-Docs-icon.png' height='110px' class='img_files_prev'/>";
                                            echo "<p class='file_date'><span style='font-weight: bold'>Дата загрузки: </span>".$file_type_o->date."</p>";
                                            echo "<p class='file_date'><span style='font-weight: bold'>Добавил: </span>".$file_type_o->lastname." ".$file_type_o->firstname."</p>";
                                            echo "<input type='hidden' name='file_id' id='file_id' value='".$file_type_o->id."'>";
                                        echo "</div>";
                                    echo "</a>";
                                }   
                                else{
                                    echo "<p class='work_not_load'>Работа еще не была загружена.</p>";
                                }
                                    echo "<div style='clear:both;'></div>";
                                echo "</div>";
                                
                                if($file_type_o){
                                    echo "<div class='block_files_sign_kaf'>";
                                        echo "<div class='sign_kaf_button";
                                        if($file_type_o->is_sign_kaf == 1){
                                            echo " sign_kaf_button_not_active";
                                        }
                                        echo "' >Подписать</div>";
                                        echo "<div class='cancel_kaf_button' >Отклонить</div>";
                                    echo "</div>";
                                    echo "<br/>";
                                }
                                
                                echo "<input type='hidden' name='h_work' id='h_work_2' value='".$work_id."'>";
                                echo "<input type='hidden' name='h_work_type' id='h_work_type_2' value='O'>";
                                
                                echo "<input type='hidden' name='h_work' id='h_work_3' value='".$work_id."'>";
                                echo "<input type='hidden' name='h_work_type' id='h_work_type_3' value='O'>";
                                
                                if($rs[$work_id]->review != "" && $rs[$work_id]->mark != null){
                                    echo "<div id='review_block_header'>";
                                        echo "<p class='review_header_title'>Отзыв научного руководителя</p>";
                                    echo "</div>";
                                    echo "<div id='review_block' style='height: auto' >";
                                        echo "<p class='ex_review_title'>Отзыв</p>";
                                        echo "<p class= 'ex_review_text'>".$rs[$work_id]->review."</p>";
                                        echo "<p class='ex_mark'>Оценка (по 5-ти балльной шкале): <span>".$rs[$work_id]->mark."</span></p>";
                                    echo "</div>";   
                                }
                                
                                if(count($messages_type_o) == 0 && $rs[$work_id]->is_closed == 1){
                                }
                                else
                                {
                                    echo "<div class='messages' >";
                                        foreach ($messages_type_o as $mo){
                                            echo "<div class='message'>";
                                                echo "<div class='header_message'>";
                                                    echo "<p class='header_message_name'>Кафедра</p>";
                                                    echo "<p class='header_message_date'>".$mo->date."</p>";
                                                    echo "<div style='clear:both;'></div>";
                                                echo "</div>";
                                                echo "<p class='message_text'>".$mo->text."</p>";
                                            echo "</div>";
                                        }
                                        
                                        if($rs[$work_id]->is_closed != 1){
                                            echo "<div class='textar_message_new'>";
                                                echo "<textarea rows='3' name='message' required style='resize: none;' class='send_block_message' id='message_textarea_tab2'></textarea>";
                                                echo "<button class='send_message_button' id='send_message_tab2'>";
                                                    echo "<img class='send_icon' src='img/send_icon.png' width='50px'/>";
                                                echo "</button>";
                                            echo "</div>";
                                        }
                                    echo "</div>";
                                }
                            echo "</div>";
                        echo "</div>";
                 
                    echo "</div>";
                echo "</div>";
            }
            
        }
        else{
            echo "<h1>Научно-исследовательские работы</h1>";
            
            echo "<p class='single_work_teacher'><span class='single_work_teacher_title'>Студент: </span>";
            echo $rs_student->lastname." ".$rs_student->firstname."</p>";
            
            echo "<p class='single_work_teacher'><span class='single_work_teacher_title'>Группа: ";
            echo "</span>".$rs_student->data."</p>";
            echo "<br/>";
            
            $sql_works = "SELECT mdl_nir.id, mdl_nir.title, mdl_nir.is_closed, mdl_user.firstname, mdl_user.lastname FROM mdl_nir, mdl_user WHERE mdl_nir.user_id=".$student_id." AND  mdl_user.id=mdl_nir.teacher_id";
            $works = $DB->get_records_sql($sql_works);
        
            foreach ($works as $wk){
                echo "<a href='/nir/index.php?std=".$student_id."&id=".$wk->id."'><div class='work_block";
                if($wk->is_closed == 1)
                {
                    echo " work_block_closed";
                }
                echo "'>";
                echo "<p class='work_title'><span class='work_title_title'>Научный руководитель: </span>".$wk->lastname." ".$wk->firstname."</p>";
                echo "<p class='work_teacher'><span class='work_teacher_title'>Описание: </span></br>".$wk->title."</p>";
                echo "</div></a>";
            }
        }
    }
    else{
        echo "<h1>Научно-исследовательская работа</h1>";
        
        $sql_groups = "SELECT DISTINCT data FROM mdl_user_info_data WHERE fieldid=3  AND data!='' ORDER BY data";
        $rs = $DB->get_records_sql($sql_groups);
            
        echo "<div id='cssmenu'>";
        echo "<ul>";
        
        foreach($rs as $grp){
            echo "<li class='has-sub'><a href='#'><span>".$grp->data."</span></a>";
            echo "<ul>";
            
            $sql_users_groups = "SELECT mdl_user.firstname, mdl_user.lastname, mdl_user.id FROM mdl_user, mdl_user_info_data WHERE mdl_user.id=mdl_user_info_data.userid AND mdl_user_info_data.data='".$grp->data."'";
            $users_group = $DB->get_records_sql($sql_users_groups);
            
            foreach($users_group as $u){
                $sql_users_count_files = "SELECT COUNT(mdl_nir_files.id) as count FROM mdl_nir, mdl_nir_files WHERE mdl_nir.user_id=".$u->id." AND mdl_nir_files.nir_id=mdl_nir.id AND mdl_nir_files.is_sign_teacher=1 AND mdl_nir_files.is_sign_kaf=0";
                $count = $DB->get_record_sql($sql_users_count_files);
                echo "<li><a href='/nir/index.php?std=".$u->id."'><span>".$u->lastname." ".$u->firstname."</span>";
                if($count->count > 0){
                    echo "<div class='sign_files_kaf_icon'><img title='Добавлен новый документ' src='img/report-3-xxl.png' height='25px'/></div>";
                }
                echo "</a></li>";
            }
            
            echo "</ul>";
            echo "</li>";
        }
        
        echo "</ul>";
        echo "</div>";
    }

}
else if(isset($_GET["id"])){
    $work_id = (int) $_GET["id"];
    
    if(isset($_GET["std"])){
        $student_id = (int) $_GET["std"];
        $sql_work = "SELECT mdl_nir.id, mdl_nir.title, mdl_nir.is_closed, mdl_nir.review, mdl_nir.mark, mdl_user.firstname, mdl_user.lastname, mdl_user_info_data.data FROM mdl_nir, mdl_user, mdl_user_info_data WHERE mdl_nir.user_id=".$student_id." AND mdl_nir.teacher_id=".$USER->id."  AND mdl_user.id=mdl_nir.user_id AND mdl_nir.id=".$work_id." AND mdl_user_info_data.userid=".$student_id." AND mdl_user_info_data.fieldid=3";
        $rs = $DB->get_records_sql($sql_work);
    }
    else{
        $sql_work = "SELECT mdl_nir.id, mdl_nir.title, mdl_nir.is_closed, mdl_nir.review, mdl_nir.mark, mdl_user.firstname, mdl_user.lastname FROM mdl_nir, mdl_user WHERE mdl_nir.user_id=".$USER->id." AND mdl_user.id=mdl_nir.teacher_id AND mdl_nir.id=".$work_id;
        $rs = $DB->get_records_sql($sql_work);
    }

    if(count($rs) == 0){
        echo "404 NOT FOUND";
    }
    else{
        require_once($CFG->dirroot.'/nir/ajax_remove_file.php');
        require_once($CFG->dirroot.'/nir/ajax_upload_file.php');
        
        $sql_files_type_z = "SELECT mdl_nir_files.id, mdl_nir_files.filename, mdl_nir_files.is_sign_teacher, mdl_nir_files.date, mdl_nir_files.is_new, mdl_user.firstname, mdl_user.lastname, mdl_user.id as user_id FROM mdl_nir_files, mdl_nir, mdl_user WHERE mdl_nir.id=".$work_id." AND (mdl_nir.teacher_id=".$USER->id." OR  mdl_nir.user_id=".$USER->id.") AND mdl_nir_files.nir_id=".$work_id." AND mdl_nir_files.type='Z' AND mdl_user.id=mdl_nir_files.user_id ORDER BY mdl_nir_files.date";
        $files_type_z = $DB->get_records_sql($sql_files_type_z);
        $sql_messages_type_z = "SELECT mdl_nir_messages.id, mdl_nir_messages.text, mdl_nir_messages.date, mdl_user.firstname, mdl_user.lastname, mdl_user.id as user_id FROM mdl_nir_messages, mdl_user WHERE mdl_nir_messages.nir_id=".$work_id." AND mdl_user.id=mdl_nir_messages.user_id AND mdl_nir_messages.nir_type='Z' ORDER BY mdl_nir_messages.date";
        $messages_type_z = $DB->get_records_sql($sql_messages_type_z);
        
        $sql_files_type_o = "SELECT mdl_nir_files.id, mdl_nir_files.filename, mdl_nir_files.is_sign_teacher, mdl_nir_files.date, mdl_nir_files.is_new, mdl_user.firstname, mdl_user.lastname, mdl_user.id as user_id FROM mdl_nir_files, mdl_nir, mdl_user WHERE mdl_nir.id=".$work_id." AND (mdl_nir.teacher_id=".$USER->id." OR  mdl_nir.user_id=".$USER->id.") AND mdl_nir_files.nir_id=".$work_id." AND mdl_nir_files.type='O' AND mdl_user.id=mdl_nir_files.user_id ORDER BY mdl_nir_files.date";
        $files_type_o = $DB->get_records_sql($sql_files_type_o);
        $sql_messages_type_o = "SELECT mdl_nir_messages.id, mdl_nir_messages.text, mdl_nir_messages.date, mdl_user.firstname, mdl_user.lastname, mdl_user.id as user_id FROM mdl_nir_messages, mdl_user WHERE mdl_nir_messages.nir_id=".$work_id." AND mdl_user.id=mdl_nir_messages.user_id AND mdl_nir_messages.nir_type='O' ORDER BY mdl_nir_messages.date";
        $messages_type_o = $DB->get_records_sql($sql_messages_type_o);
        
        $sql_files_type_p = "SELECT mdl_nir_files.id, mdl_nir_files.filename, mdl_nir_files.is_sign_teacher, mdl_nir_files.date, mdl_nir_files.is_new, mdl_user.firstname, mdl_user.lastname, mdl_user.id as user_id FROM mdl_nir_files, mdl_nir, mdl_user WHERE mdl_nir.id=".$work_id." AND (mdl_nir.teacher_id=".$USER->id." OR  mdl_nir.user_id=".$USER->id.") AND mdl_nir_files.nir_id=".$work_id." AND mdl_nir_files.type='P' AND mdl_user.id=mdl_nir_files.user_id ORDER BY mdl_nir_files.date";
        $files_type_p = $DB->get_records_sql($sql_files_type_p);
        $sql_messages_type_p = "SELECT mdl_nir_messages.id, mdl_nir_messages.text, mdl_nir_messages.date, mdl_user.firstname, mdl_user.lastname FROM mdl_nir_messages, mdl_user WHERE mdl_nir_messages.nir_id=".$work_id." AND mdl_user.id=mdl_nir_messages.user_id AND mdl_nir_messages.nir_type='P' ORDER BY mdl_nir_messages.date";
        $messages_type_p = $DB->get_records_sql($sql_messages_type_p);

        echo "<h1>Научно-исследовательская работа</h1>";
        echo "<p class='single_work_title'><span class='single_work_title_title'>Описание: </span>".$rs[$work_id]->title."</p>";
        echo "<p class='single_work_teacher'><span class='single_work_teacher_title'>";
        
        if(isset($_GET["std"])){
            echo "Студент: ";
        }
        else{
            echo "Научный руководитель: ";
        }
        
        echo "</span>".$rs[$work_id]->lastname." ".$rs[$work_id]->firstname."</p>";
        
        if(isset($_GET["std"])){
            echo "<p class='single_work_teacher'><span class='single_work_teacher_title'>Группа: ";
            echo "</span>".$rs[$work_id]->data."</p>";
        }
        
        echo "<div class='tabs'>";
            echo "<ul class='tab-links'>";
                echo "<li class='active'><a href='#tab1'>Задание на НИР</a></li>";
                echo "<li><a href='#tab2'>Отчет</a></li>";
                echo "<li><a href='#tab3'>Презентация</a></li>";
            echo "</ul>";
 
            echo "<div class='tab-content'>";
                echo "<div id='tab1' class='tab active'>";
                    echo "<div id='content'>";
                        echo "<div class='block_files'>";
                        
                            $i = 1;
                            $total = count($files_type_z);
                            $flag = true;
                            
                            foreach ($files_type_z as $file){
                                
                                    echo "<div class='block_file_prev'";
                                    if($rs[$work_id]->is_closed == 0 && (($total == $i || $file->is_sign_teacher == 1) && $USER->profile['isTeacher'] === "1" && $flag)  || ($file->is_sign_teacher == 1 && $USER->profile['isTeacher'] !== "1" && $USER->profile['isTeacher'] !== "666")){
                                        echo " style='height:340px'";
                                    }
                                    echo ">";
                                    echo "<a href='".$file->filename."' target='_blank' class='a_file_block'>";
                                        echo "<img src='img/Filetype-Docs-icon.png' height='110px' class='img_files_prev'/>";
                                        echo "<p class='file_name'>Задание ".$i."</p>";
                                        echo "<p class='file_date'><span style='font-weight: bold'>Дата загрузки: </span>".$file->date."</p>";
                                        echo "<p class='file_date'><span style='font-weight: bold'>Добавил: </span>".$file->lastname." ".$file->firstname."</p>";
                                        echo "<input type='hidden' name='file_id' id='file_id' value='".$file->id."'>";
                                        if($file->is_new != 0 && $file->user_id != $USER->id){
                                            echo "<img src='img/new.gif' height='30px' class='img_new'/>";
                                        }
                                        
                                        if($file->is_sign_teacher == 1 && $USER->profile['isTeacher'] !== "1" && $USER->profile['isTeacher'] !== "666"){
                                            echo "<br/>";
                                            echo "<br/>";
                                            echo "<p class='file_date'>Файл подписан научным руководителем. Ожидает подтверждения от кафедры.</p>";
                                        }
                                    echo "</a>";
                                    if($rs[$work_id]->is_closed == 0 && ($total == $i || $file->is_sign_teacher == 1) && $USER->profile['isTeacher'] === "1" && $flag)  
                                    {
                                        if($total != $i)
                                            $flag = false;
                                            
                                        echo "<div class='block_files_sign_teacher'>";
                                            echo "<div class='sign_button_teacher";
                                            if($file->is_sign_teacher == 1){
                                                echo " sign_teacher_button_not_active";
                                            }
                                            echo "' >Подписать</div>";
                                            echo "<div class='cancel_button_teacher";
                                            if($file->is_sign_teacher == 0){
                                                echo " sign_teacher_button_not_active";
                                            }
                                            echo "' >Отклонить</div>";
                                        echo "</div>";
                                    }
                                    
                                    echo "</div>";
                                
                                $i++;
                            }
                            
                            echo "<div style='clear:both;'></div>";
                        echo "</div>";
                        
                        if($rs[$work_id]->is_closed != 1){
                            echo "<input type='file' name='files[]' id='filer_input2'>";
                        }
                        
                        echo "<input type='hidden' name='h_work' id='h_work' value='".$work_id."'>";
                        echo "<input type='hidden' name='h_work_type' id='h_work_type' value='Z'>";
                        
                        if(count($messages_type_z) == 0 && $rs[$work_id]->is_closed == 1){
                        }
                        else
                        {
                            echo "<div class='messages' >";
                            
                            foreach ($messages_type_z as $mz){
                                echo "<div class='message'>";
                                    echo "<div class='header_message";
                                    if($mz->user_id == $ADMIN){
                                        echo " header_message_kaf";
                                    }
                                    echo "'>";
                                    if($mz->user_id == $ADMIN){
                                        echo "<p class='header_message_name'>Кафедра</p>";
                                    }
                                    else{
                                        echo "<p class='header_message_name'>".$mz->lastname." ".$mz->firstname."</p>";
                                    }
                                        echo "<p class='header_message_date'>".$mz->date."</p>";
                                        echo "<div style='clear:both;'></div>";
                                    echo "</div>";
                                    echo "<p class='message_text'>".$mz->text."</p>";
                                echo "</div>";
                            }
                            
                            if($rs[$work_id]->is_closed != 1){
                                echo "<div class='textar_message_new'>";
                                    echo "<textarea rows='3' name='message' required style='resize: none;' class='send_block_message' id='message_textarea_tab1'></textarea>";
                                    echo "<button class='send_message_button' id='send_message_tab1'>";
                                        echo "<img class='send_icon' src='img/send_icon.png' width='50px'/>";
                                    echo "</button>";
                                echo "</div>";
                            }
                            echo "</div>";
                        }
                        
                    echo "</div>";
                echo "</div>";
         
                echo "<div id='tab2' class='tab'>";
                    echo "<div id='content'>";
                        echo "<div class='block_files'>";
                        
                            $m = 1;
                            $total_o = count($files_type_o);
                            $flag = true;
                            
                            foreach ($files_type_o as $file){
                                
                                    echo "<div class='block_file_prev'";
                                    if($rs[$work_id]->is_closed == 0 && (($total_o == $m || $file->is_sign_teacher == 1) && $USER->profile['isTeacher'] === "1" && $flag)  || ($file->is_sign_teacher == 1 && $USER->profile['isTeacher'] !== "1" && $USER->profile['isTeacher'] !== "666")){
                                        echo " style='height:340px'";
                                    }
                                    echo ">";
                                    echo "<a href='".$file->filename."' target='_blank' class='a_file_block'>";
                                        echo "<img src='img/Filetype-Docs-icon.png' height='110px' class='img_files_prev'/>";
                                        echo "<p class='file_name'>Отчет ".$m."</p>";
                                        echo "<p class='file_date'><span style='font-weight: bold'>Дата загрузки: </span>".$file->date."</p>";
                                        echo "<p class='file_date'><span style='font-weight: bold'>Добавил: </span>".$file->lastname." ".$file->firstname."</p>";
                                        echo "<input type='hidden' name='file_id' id='file_id' value='".$file->id."'>";
                                        if($file->is_new != 0 && $file->user_id != $USER->id){
                                            echo "<img src='img/new.gif' height='30px' class='img_new'/>";
                                        }
                                        
                                        if($file->is_sign_teacher == 1 && $USER->profile['isTeacher'] !== "1" && $USER->profile['isTeacher'] !== "666"){
                                            echo "<br/>";
                                            echo "<br/>";
                                            echo "<p class='file_date'>Файл подписан научным руководителем. Ожидает подтверждения от кафедры.</p>";
                                        }
                                    echo "</a>";
                                    if($rs[$work_id]->is_closed == 0 && ($total_o == $m || $file->is_sign_teacher == 1) && $USER->profile['isTeacher'] === "1" && $flag)  
                                    {
                                        if($total_o != $m)
                                            $flag = false;
                                            
                                        echo "<div class='block_files_sign_teacher'>";
                                            echo "<div class='sign_button_teacher";
                                            if($file->is_sign_teacher == 1 || $rs[$work_id]->review == "" || $rs[$work_id]->mark == ""){
                                                echo " sign_teacher_button_not_active";
                                            }
                                            echo "' ";
                                            if($rs[$work_id]->review == "" || $rs[$work_id]->mark == ""){
                                                echo "title='Необходимо добавить отзыв'";
                                            }
                                            echo ">Подписать</div>";
                                            echo "<div class='cancel_button_teacher";
                                            if($file->is_sign_teacher == 0){
                                                echo " sign_teacher_button_not_active";
                                            }
                                            echo "' >Отклонить</div>";
                                        echo "</div>";
                                    }
                                    
                                    echo "</div>";
                                
                                $m++;
                            }
                            
                            
                            echo "<div style='clear:both;'></div>";
                        echo "</div>";
                        
                        if($rs[$work_id]->is_closed != 1){
                            echo "<input type='file' name='files[]' id='filer_input1'>";
                        }
                        echo "<input type='hidden' name='h_work' id='h_work_2' value='".$work_id."'>";
                        echo "<input type='hidden' name='h_work_type' id='h_work_type_2' value='O'>";
                        
                        if($USER->profile['isTeacher'] === "1"){
                            echo "<div id='review_block_header'>";
                                echo "<p class='review_header_title'>Отзыв научного руководителя</p>";
                            echo "</div>";
                            echo "<div id='review_block'";
                             if($rs[$work_id]->review != "" && $rs[$work_id]->mark != ""){
                                echo " style='height: auto'";
                            }
                            echo ">";
                            
                            if($rs[$work_id]->review == "" || $rs[$work_id]->mark == ""){
                                echo "<p class='review_title'>Отзыв</p>";
                                echo "<textarea placeholder='Введите отзыв...' rows='4' name='review' required style='resize: none;' id='review_area'></textarea>";
                                echo "<br/>";
                                echo "<span class='mark_title'>Оценка (по 5-ти балльной шкале)</span>";
                                echo "<input type='number' min='1' max='5' size='3' value='4' id='mark_input'/>";
                                echo "<br/>";
                                echo "<p id='send_review'>Отправить</p>";
                            }
                            else{
                                echo "<p class='ex_review_title'>Отзыв</p>";
                                echo "<p class= 'ex_review_text'>".$rs[$work_id]->review."</p>";
                                echo "<p class='ex_mark'>Оценка (по 5-ти балльной шкале): <span>".$rs[$work_id]->mark."</span></p>";
                            }
                            
                            echo "</div>";
                        }
                        
                        if(count($messages_type_o) == 0 && $rs[$work_id]->is_closed == 1){
                        }
                        else
                        {
                            echo "<div class='messages' >";
                                foreach ($messages_type_o as $mo){
                                    echo "<div class='message'>";
                                    echo "<div class='header_message";
                                    if($mo->user_id == $ADMIN){
                                        echo " header_message_kaf";
                                    }
                                    echo "'>";
                                            if($mo->user_id == $ADMIN){
                                                echo "<p class='header_message_name'>Кафедра</p>";
                                            }
                                            else{
                                                echo "<p class='header_message_name'>".$mo->lastname." ".$mo->firstname."</p>";
                                            }
                                            echo "<p class='header_message_date'>".$mo->date."</p>";
                                            echo "<div style='clear:both;'></div>";
                                        echo "</div>";
                                        echo "<p class='message_text'>".$mo->text."</p>";
                                    echo "</div>";
                                }
                                
                                if($rs[$work_id]->is_closed != 1){
                                    echo "<div class='textar_message_new'>";
                                        echo "<textarea rows='3' name='message' required style='resize: none;' class='send_block_message' id='message_textarea_tab2'></textarea>";
                                        echo "<button class='send_message_button' id='send_message_tab2'>";
                                            echo "<img class='send_icon' src='img/send_icon.png' width='50px'/>";
                                        echo "</button>";
                                    echo "</div>";
                                }
                            echo "</div>";
                        }
                    echo "</div>";
                echo "</div>";
         
                echo "<div id='tab3' class='tab'>";
                    echo "<div id='content'>";
                        echo "<div class='block_files'>";
                        
                            $n = 1;
                            $total_p = count($files_type_p);
                            $flag = true;
                            
                            foreach ($files_type_p as $file){
                                
                                    echo "<div class='block_file_prev'";

                                    echo ">";
                                    echo "<a href='".$file->filename."' target='_blank' class='a_file_block'>";
                                        echo "<img src='img/Filetype-Docs-icon.png' height='110px' class='img_files_prev'/>";
                                        echo "<p class='file_name'>Презентация ".$n."</p>";
                                        echo "<p class='file_date'><span style='font-weight: bold'>Дата загрузки: </span>".$file->date."</p>";
                                        echo "<p class='file_date'><span style='font-weight: bold'>Добавил: </span>".$file->lastname." ".$file->firstname."</p>";
                                        echo "<input type='hidden' name='file_id' id='file_id' value='".$file->id."'>";
                                        if($file->is_new != 0 && $file->user_id != $USER->id){
                                            echo "<img src='img/new.gif' height='30px' class='img_new'/>";
                                        }
                                        
                                    echo "</a>";
                                    
                                    echo "</div>";
                                
                                $n++;
                            }
                            
                            echo "<div style='clear:both;'></div>";
                        echo "</div>";
                        
                        if($rs[$work_id]->is_closed != 1){
                            echo "<input type='file' name='files[]' id='filer_input3'>"; 
                        }
                        
                        echo "<input type='hidden' name='h_work' id='h_work_3' value='".$work_id."'>";
                        echo "<input type='hidden' name='h_work_type' id='h_work_type_3' value='P'>";
                        
                        if(count($messages_type_p) == 0 && $rs[$work_id]->is_closed == 1){
                        }
                        else
                        {
                            echo "<div class='messages' >";
                                foreach ($messages_type_p as $mp){
                                    echo "<div class='message'>";
                                        echo "<div class='header_message'>";
                                            echo "<p class='header_message_name'>".$mp->lastname." ".$mp->firstname."</p>";
                                            echo "<p class='header_message_date'>".$mp->date."</p>";
                                            echo "<div style='clear:both;'></div>";
                                        echo "</div>";
                                        echo "<p class='message_text'>".$mp->text."</p>";
                                    echo "</div>";
                                }
                                
                                if($rs[$work_id]->is_closed != 1){
                                    echo "<div class='textar_message_new'>";
                                        echo "<textarea rows='3' name='message' required style='resize: none;' class='send_block_message' id='message_textarea_tab3'></textarea>";
                                        echo "<button class='send_message_button' id='send_message_tab3'>";
                                            echo "<img class='send_icon' src='img/send_icon.png' width='50px'/>";
                                        echo "</button>";
                                    echo "</div>";
                                }
                            echo "</div>";
                        }
                    echo "</div>";
                echo "</div>";
            echo "</div>";
        echo "</div>";
        
        if ($USER->profile['isTeacher'] === "1" && $rs[$work_id]->is_closed == 0){
            echo "<p class='finish_work_button'>Завершить работу</p>";   
            echo "<input type='hidden' name='work_f' id='work_f' value='".$work_id."'>";
        }
    }
}
else if($USER->profile['isTeacher'] === "1"){
    //Доступ в дополнительным полям, в данном случае к группе
    //echo $USER->profile['isTeacher']; 
    if(isset($_GET["std"])){
        $student_id = (int) $_GET["std"];
        
        $sql_works = "SELECT mdl_nir.id, mdl_nir.title, mdl_nir.is_closed, mdl_user.firstname, mdl_user.lastname, mdl_user.id as student_id FROM mdl_nir, mdl_user WHERE mdl_nir.user_id=".$student_id." AND mdl_nir.teacher_id=".$USER->id." AND  mdl_user.id=mdl_nir.user_id";
        $works = $DB->get_records_sql($sql_works);
        
        echo "<h1>Научно-исследовательские работы</h1>";
    
        foreach ($works as $wk){
            $sql_new_files_amount = "SELECT COUNT(*) as count FROM mdl_nir_files WHERE nir_id=".$wk->id." AND user_id!=".$USER->id." AND is_new=1";
            $count_new_file = $DB->get_record_sql($sql_new_files_amount);
            
            echo "<a href='/nir/index.php?std=".$wk->student_id."&id=".$wk->id."'><div class='work_block";
            if($wk->is_closed == 1)
            {
                echo " work_block_closed";
            }
            echo "'>";
            echo "<p class='work_title'><span class='work_title_title'>Студент: </span>".$wk->lastname." ".$wk->firstname."</p>";
            echo "<p class='work_teacher'><span class='work_teacher_title'>Описание: </span></br>".$wk->title."</p>";
            if ($count_new_file->count > 0){
                $title_file_m=" новых файлов";
                if($count_new_file->count==1){
                    $title_file_m=" новый файл";
                }
                else if($count_new_file->count>1 && $count_new_file->count<5){
                    $title_file_m=" новых файла";
                }
                echo "<p class='new_file_message'>Добавлено ".$count_new_file->count.$title_file_m."</p>";
            }
            echo "</div></a>";
        }
    
    }
    else{
        $sql_users_of_teacher = "SELECT mdl_user.id, mdl_user.firstname, mdl_user.lastname, mdl_user_info_data.data FROM mdl_nir, mdl_user, mdl_user_info_data WHERE mdl_nir.teacher_id=".$USER->id." AND mdl_user.id=mdl_nir.user_id AND mdl_user_info_data.userid=mdl_nir.user_id AND mdl_user_info_data.fieldid=3";
        $users_of_teacher = $DB->get_records_sql($sql_users_of_teacher); 
        
        echo "<h1>Студенты</h1>";
        
        foreach ($users_of_teacher as $us){
            $sql_count_n_f = "SELECT COUNT(*) as count FROM mdl_nir_files, mdl_nir WHERE mdl_nir_files.user_id=".$us->id." AND mdl_nir.teacher_id=".$USER->id." AND mdl_nir_files.is_new=1 AND mdl_nir_files.nir_id=mdl_nir.id";
            $count_n_f = $DB->get_record_sql($sql_count_n_f);
            echo "<a href='index.php?std=".$us->id."'><div class='users_list_el'>";
            echo "<span style='float: left'>".$us->lastname." ".$us->firstname."</span>";
            echo $us->data;
            if($count_n_f->count > 0){
                echo " <img title='Добавлен новый документ' src='img/report-3-xxl.png' height='25px'/>";
            }
            echo "</div></a>";
        }
    }
}
else{
    $sql = "SELECT mdl_user.id, mdl_user.firstname, mdl_user.lastname FROM mdl_user, mdl_user_info_data WHERE mdl_user.deleted=0 AND mdl_user_info_data.userid=mdl_user.id AND mdl_user_info_data.fieldid=2 AND mdl_user_info_data.data=1";
    $rs = $DB->get_records_sql($sql);
    
    $sql_works = "SELECT mdl_nir.id, mdl_nir.title, mdl_nir.is_closed, mdl_user.firstname, mdl_user.lastname FROM mdl_nir, mdl_user WHERE mdl_nir.user_id=".$USER->id." AND mdl_user.id=mdl_nir.teacher_id";
    $works = $DB->get_records_sql($sql_works);
    
    
    echo "<h1>Научно-исследовательские работы</h1>";
    
    $count_open_works = 0;
    
    foreach ($works as $wk){
        $sql_new_files_amount = "SELECT COUNT(*) as count FROM mdl_nir_files WHERE nir_id=".$wk->id." AND user_id!=".$USER->id." AND is_new=1";
        $count_new_file = $DB->get_record_sql($sql_new_files_amount);
        
        echo "<a href='/nir/index.php?id=".$wk->id."'><div class='work_block";
        if($wk->is_closed == 1)
        {
            echo " work_block_closed";
        }
        else{
            $count_open_works++;
        }
        echo "'>";
        echo "<p class='work_title'><span class='work_title_title'>Научный руководитель: </span>".$wk->lastname." ".$wk->firstname."</p>";
        echo "<p class='work_teacher'><span class='work_teacher_title'>Описание: </span></br>".$wk->title."</p>";
        if ($count_new_file->count > 0){
            $title_file_m=" новых файлов";
            if($count_new_file->count==1){
                $title_file_m=" новый файл";
            }
            else if($count_new_file->count>1 && $count_new_file->count<5){
                $title_file_m=" новых файла";
            }
            echo "<p class='new_file_message'>Добавлено ".$count_new_file->count.$title_file_m."</p>";
        }
        echo "</div></a>";
    }
    
    echo "<div style='clear:both;'></div>";
    echo "</br>";
    
    if($count_open_works === 0)
    {
        /*echo "<a href='#openModal' ><div id='button_create_nir'>Создать НИР</div></a>";*/
    
        // Modal window for create NIR
/*        echo "<div id='openModal' class='modalDialog'>";
        echo "<div>";
        		echo "<a href='#close' title='Закрыть' class='close' id='modal_close_button'>X</a>";
        		echo "<h2>Создание НИР</h2>";
        		echo "<form id='form_create_nir' method='post' action='create_work.php'>";
                	echo "<p id='modal_d_teacher'>Выберите научного руководителя:</p>";
                    echo "<p><select name='teacher' required>";
                    	       foreach ($rs as $teacher){
                    	           echo "<option value='".$teacher->id."'>".$teacher->lastname." ".$teacher->firstname."</option>";
                    	       }
                    echo "</select></p>";
                    echo "<p id='modal_d_title'>Введите название научно-исследовательской работы:</p>";
                    echo "<textarea rows='3' cols='40' name='title' required style='resize: none;'></textarea>";
                    echo "<input type='hidden' name='user' value='".$USER->id."'>";
                    echo "<input type='submit' value='Создать' id='submit_modal_form'>";
                echo "</form>";
        	echo "</div>";
        echo "</div>";*/
        // end
        
        // NEW Modal window for create NIR
        echo "<a href='#win1'><div id='button_create_nir'>Создать НИР</div></a>";
        
        echo "<a href='#x' class='overlay' id='win1'></a>";
        echo "<div class='popup'>";
            echo "<div>";
        		echo "<h2 style='text-align:center'>Создание НИР</h2>";
        		echo "<form id='form_create_nir' method='post' action='create_work.php'>";
                	echo "<p id='modal_d_teacher'>Выберите научного руководителя:</p>";
                    echo "<p><select name='teacher' required>";
                    	       foreach ($rs as $teacher){
                    	           echo "<option value='".$teacher->id."'>".$teacher->lastname." ".$teacher->firstname."</option>";
                    	       }
                    echo "</select></p>";
                    echo "<p id='modal_d_title'>Введите название научно-исследовательской работы:</p>";
                    echo "<textarea rows='3' cols='55' name='title' required style='resize: none;'></textarea>";
                    echo "<input type='hidden' name='user' value='".$USER->id."'>";
                    echo "<br/>";
                    echo "<input type='submit' value='Создать' id='submit_modal_form'>";
                echo "</form>";
        	echo "</div>";
        echo "</div>";
        // end
    }

}
echo $OUTPUT->footer();
?>
<script>

	// kaf navigate
	$('#cssmenu > ul > li ul').each(function(index, e){
        var count = $(e).find('li').length;
        var content = '<span class=\"cnt\">' + count + '</span>';
        $(e).closest('li').children('a').append(content);
    });
    $('#cssmenu ul ul li:odd').addClass('odd');
    $('#cssmenu ul ul li:even').addClass('even');
    $('#cssmenu > ul > li > a').click(function() {
        $('#cssmenu li').removeClass('act');
        $(this).closest('li').addClass('act');   
        var checkElement = $(this).next();
        if((checkElement.is('ul')) && (checkElement.is(':visible'))) {
            $(this).closest('li').removeClass('act');
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
    });
    
    
</script>
