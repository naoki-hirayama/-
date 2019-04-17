$(function() {
    // モーダルウィンドウを開く
    function showModal2(event) {
        event.preventDefault();

        var $shade = $('<div></div>');
        $shade
            .attr('id', 'shade')
            .on('click', hideModal);

        var $modalWin = $('#modalwin2');
        var $window = $(window);
        var posX = ($window.width() - $modalWin.outerWidth()) / 2;
        var posY = ($window.height() - $modalWin.outerHeight()) / 2;

        $modalWin
            .before($shade)
            .css({ left: posX, top: posY })
            .removeClass('hide')
            .addClass('show')
            .on('click', '#ressclose', function() {
                hideModal();
            });
    }

    function hideModal() {
        $('#shade').remove();
        $('#modalwin2')
            .removeClass('show')
            .addClass('hide');
    }

    $('.show-reply-modal').on('click', showModal2);

}());
