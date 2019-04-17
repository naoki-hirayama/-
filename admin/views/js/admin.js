$(function() {

    $('#deleteform').submit(function() {
        if (!confirm('本当に投稿を削除しますか？')) {
            return false;
        }
    });

    $("#delete_reply_form").submit(function() {
        if (!confirm('本当にレスを削除しますか？')) {
            return false;
        }
    });
});
