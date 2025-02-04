$(document).ready(function() {
    $('.ui-dialog-titlebar-close').attr({
        alt: 'Fechar janela',
        title: 'Fechar janela'
    });

    //Quando a requisição começar, Exibe a DIV 
    $('#popupBox').ajaxStart(function() {
//        $.get('/tiss_conab/site/verificaSessaoAjax', function(data) {
//            var parte = data.split('|');
//            if (parte[0] == 1) {
//                window.location.href = parte[1];
//            }
//        }
//        );
        $(this).show();
        $('#popupBoxFundo').show();
    });

    //Quando a requisição parar, Esconde a DIV  
    $('#popupBox').ajaxStop(function() {
        $(this).hide();
        $('#popupBoxFundo').hide();
    });

});




Array.prototype.last = function() {
    return this[this.length - 1];
}


function clear_form_elements(ele) {

    $(ele).find(':input').each(function() {
        switch (this.type) {
            case 'password':
            case 'select-multiple':
            case 'select-one':
            case 'text':
            case 'textarea':
                $(this).val('');
                break;
            case 'checkbox':
            case 'radio':
                this.checked = false;
        }
    });

}

function readonly_form_elements(ele, flag) {

    $(ele).find(':input').each(function() {
        switch (this.type) {
            case 'password':
            case 'select-multiple':
            case 'select-one':
            case 'text':
            case 'textarea':
            case 'checkbox':
            case 'radio':
                if (flag)
                    $(this).attr("readonly", "readonly");
                else
                    $(this).removeAttr("readonly");
                break;
        }
    });

}

function remove_class_error(ele) {

    $(ele).find('.error').each(function() {
        $(this).removeClass("error");
    });

}

function add_class_error(ele, erros, classe) {

    $(ele).find('input , label').each(function(i, element) {

        var _element = $(this);
        var _for = $(this).attr('for');

        $.each(erros, function(nome, msg) {

            if (_for == classe + '_' + nome)
                _element.addClass("error");

            switch (_element.attr('type')) {
                case 'password':
                case 'select-multiple':
                case 'select-one':
                case 'text':
                case 'textarea':
                case 'checkbox':
                case 'radio':
                    if (_element.attr('id') == classe + '_' + nome)
                        _element.addClass("error");
                    break;
            }
        });
    });

}



