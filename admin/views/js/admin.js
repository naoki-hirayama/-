$(function() {
    $('#delete').click(function() {
        if (!confirm('本当に投稿を削除しますか？')) {
            return false;
        }
        else {
            $.post("deleted.php", {
                post_id: $("input[name=post_id]").val()
            });
            location.href = 'deleted.php';
        }
    });
    //削除アラートが二個目から出ない
    $("input[value=削除]").click(function() {
        if (!confirm('本当にレスを削除しますか？')) {
            return false;
        }
        else {

        }
    });
});
