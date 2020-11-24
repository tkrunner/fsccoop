function format_number(n) {
    return parseFloat(n).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,");
}

function blockUI(){
    let block = '<div class="display-block"><div class="lds-roller"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div></div>';
    if($('body .display-block').hasClass('display-block') === false) {
        $('body').append(block);
        $('.display-block').css({
            'z-index': '9999999',
            'width': '100%',
            'height': '100%',
            'position': 'fixed',
            'background-color': 'rgba(0, 0, 0, 0.4)',
            'top': 0,
            'display': 'flex',
            'justify-content': 'center',
            'align-items': 'center'
        });
    }
}

function unblockUI(){
    $('.display-block').remove();
}