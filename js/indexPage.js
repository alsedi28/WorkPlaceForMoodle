$(document).ready(function() {
    $("#region-main").on('click', '.button_delete_work', delete_work);
    $(".switch_active_groups").click(switch_block_users);

    // Set the height of blocks
    if($(".work_block").length > 0){
        var max_height = 0;

        $(".work_block").each(function(index, item) {
            var element_height = $(item).height();

            if(element_height > max_height)
                max_height = element_height;
        });

        $(".work_block").each(function(index, item) {
            $(item).height(max_height);
        });
    }
});

function delete_work(event){
    var isSignYes = confirm(loc.AnswerDoYouWantToDeleteWork);

    if(isSignYes)
    {
        var work_id = event.target.querySelector("input").value;
        var params_obj = {'work_id' : work_id};

        $.ajax({
            url: 'ajax/delete_work.php',
            type: 'POST',
            data: $.param(params_obj),
            success: function(data){
                window.location = "/nirtest/index.php";
            }
        });
    }
}

function switch_block_users(){
    if($(this).is(':checked')){
        $(".list_for_switch_active").show();
        $(".list_for_switch_not_active").hide();
    }
    else{
        $(".list_for_switch_active").hide();
        $(".list_for_switch_not_active").show();
    }
}