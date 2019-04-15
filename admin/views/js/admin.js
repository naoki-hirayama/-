$(function() {

    $('#deleteform').submit(function() {
        if (!confirm('本当に投稿を削除しますか？')) {
            return false;
        }
    });

    //削除アラートが二個目から出ない
    $("#delete_reply_form").submit(function() {
        if (!confirm('本当にレスを削除しますか？')) {
            return false;
        }
    });

    // $("input[value=削除]").click(function() {
    //         if (!confirm('本当にレスを削除しますか？')) {
    //             return false;
    //         }
    //         else {
    //             $.ajax({
    //                 type: 'POST',
    //                 url: 'postdetail.php',
    //                 data: {
    //                     reply_id: $("input[name=reply_id]").val()
    //                 }
    //             })
    //             location.href = 'postdetail.php';
    //         }
    //     });
});
