/********************************************************************************
*  Copyright 2015 Conab - Companhia Nacional de Abastecimento                   *
*                                                                               *
*  Este arquivo é parte do Sistema SIAUDI.                                      *
*                                                                               *
*  SIAUDI  é um software livre; você pode redistribui-lo e/ou                   *
*  modificá-lo sob os termos da Licença Pública Geral GNU conforme              *
*  publicada pela Free Software Foundation; tanto a versão 2 da                 *
*  Licença, como (a seu critério) qualquer versão posterior.                    *
*                                                                               *
*  SIAUDI é distribuído na expectativa de que seja útil,                        *
*  porém, SEM NENHUMA GARANTIA; nem mesmo a garantia implícita                  *
*  de COMERCIABILIDADE OU ADEQUAÇÃO A UMA FINALIDADE ESPECÍFICA.                *
*  Consulte a Licença Pública Geral do GNU para mais detalhes em português:     *
*  http://creativecommons.org/licenses/GPL/2.0/legalcode.pt                     *
*                                                                               *
*  Você deve ter recebido uma cópia da Licença Pública Geral do GNU             *
*  junto com este programa; se não, escreva para a Free Software                *
*  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA    *
*                                                                               *
*  Sistema   : SIAUDI - Sistema de Auditoria Interna                            *
*  Data      : 05/2015                                                          *
*                                                                               *
********************************************************************************/
    $(document).ready(function(){
    $('.ui-dialog-titlebar-close').attr({
        alt: 'Fechar janela',
        title: 'Fechar janela'
    });

    $('#popupBox').ajaxStart(function(){  
        //Quando a requisição começar, Exibe a DIV 
               
        $(this).show();
        $('#popupBoxFundo').show();
    });  
            
    $('#popupBox').ajaxStop(function(){  
        //Quando a requisição parar, Esconde a DIV  
        $(this).hide();  
        $('#popupBoxFundo').hide();
    });  
    
});




Array.prototype.last = function() {return this[this.length-1];}


function clear_form_elements(ele) {

    $(ele).find(':input').each(function() {
        switch(this.type) {
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

function readonly_form_elements(ele,flag) {

    $(ele).find(':input').each(function() {
        switch(this.type) {
            case 'password':
            case 'select-multiple':
            case 'select-one':
            case 'text':
            case 'textarea':
            case 'checkbox':
            case 'radio':
                if(flag)
                    $(this).attr("readonly","readonly");
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
            
            if( _for == classe+'_'+nome)
               _element.addClass("error");
                    
            switch(_element.attr('type') ) {
                case 'password':
                case 'select-multiple':
                case 'select-one':
                case 'text':
                case 'textarea':
                case 'checkbox':
                case 'radio':
                    if( _element.attr('id') == classe+'_'+nome)
                        _element.addClass("error");
                    break;
            }
        });
    });

}



