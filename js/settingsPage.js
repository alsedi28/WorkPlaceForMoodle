$(document).ready(function() {

    $(".groups_table input").click(function () {
        $.ajax({
            url: 'ajax/set_group_status.php',
            type: 'POST',
            data: {'status': $(this).is(':checked'), 'id': $(this).attr('id')}
        });
    });
});