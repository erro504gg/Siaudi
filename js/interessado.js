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

    $("#InteressadoExterno_telefone").mask("(99) 9999-9999");
    $("#InteressadoExterno_telefone_comercial").mask("(99) 9999-9999");
    if($("#uf").val()=='SP')
        $("#InteressadoExterno_celular").mask("(99) 99999-9999");
    else
        $("#InteressadoExterno_celular").mask("(99) 9999-9999");


    $("#InteressadoExterno_cep").mask("99.999-999");


    if(!$('#InteressadoExterno_cpf_cnpj').val()){
        $('input, select').attr('disabled', 'disabled');
        $('#InteressadoExterno_cpf_cnpj').removeAttr('disabled');
        $('#btnVerificar').removeAttr('disabled');
    }


    $("#uf").change(function(){

        if($("#uf").val()=='SP'){
            $('#InteressadoExterno_celular').remove();
            $('#fdCelular').html('<input type="text" id="InteressadoExterno_celular" name="InteressadoExterno[celular]" size="14" maxlength="14">');
            $("#InteressadoExterno_celular").mask("(99) 99999-9999");
        }
        else{
            $('#InteressadoExterno_celular').remove();
            $('#fdCelular').html('<input type="text" id="InteressadoExterno_celular" name="InteressadoExterno[celular]" size="14" maxlength="14">');
            $("#InteressadoExterno_celular").mask("(99) 9999-9999");
        }
    });


    $('#InteressadoExterno_cpf_cnpj').blur(function(){

        var padrao_cpf = /^([\d]{3})([\d]{3})([\d]{3})([\d]{2})$/;
        var padrao_cnpj = /^([\d]{2})([\d]{3})([\d]{3})([\d]{4})([\d]{2})$/;

        var cpf = replaceAll($('#InteressadoExterno_cpf_cnpj').val(), '.', '');
        cpf = replaceAll(cpf, '/', '');
        cpf = replaceAll(cpf, '-', '');

        if(cpf.length=='11'){
            cpf = cpf.replace(padrao_cpf, '$1.$2.$3-$4');
        }
        else if(cpf.length=='14'){
            cpf = cpf.replace(padrao_cnpj, '$1.$2.$3/$4-$5');
        }
        else{
            cpf = $('#InteressadoExterno_cpf_cnpj').val();
        }

        $('#InteressadoExterno_cpf_cnpj').val(cpf);



    });

});
Array.prototype.last = function() {
    return this[this.length-1];
}


function replaceAll(string, token, newtoken) {
    while (string.indexOf(token) != -1) {
        string = string.replace(token, newtoken);
    }
    return string;

}


function setDados(dados) {

    var newOptions = dados.municipios
    var selectedOption = dados.municipio_fk;
    var options;
    var select = $('#InteressadoExterno_municipio_fk');

    if(select.prop) {
        options = select.prop('options');
    }
    else {
        options = select.attr('options');
    }

    $('option', select).remove();

    $.each(newOptions, function(val, text) {
        options[options.length] = new Option(text, val);
    });
    select.val(selectedOption);


    $('#InteressadoExterno_bairro').val(dados.municipios);
    $('#InteressadoExterno_nome').val(dados.nome);
    $('#InteressadoExterno_endereco').val(dados.endereco);
    $('#InteressadoExterno_complemento').val(dados.complemento);
    $('#InteressadoExterno_numero').val(dados.numero);
    $('#InteressadoExterno_telefone').val(dados.telefone);
    $('#InteressadoExterno_telefone_comercial').val(dados.telefone_comercial);
    $('#InteressadoExterno_email').val(dados.email);
    $('#uf').val(dados.uf);



    $('#InteressadoExterno_bairro').val(dados.bairro);
    $('#InteressadoExterno_cep').val(dados.cep);
    

    if($("#uf").val()=='SP'){
        $('#InteressadoExterno_celular').remove();
        $('#fdCelular').html('<input type="text" id="InteressadoExterno_celular" name="InteressadoExterno[celular]" size="14" maxlength="14">');
        $("#InteressadoExterno_celular").mask("(99) 99999-9999");
    }
    else{
        $('#InteressadoExterno_celular').remove();
        $('#fdCelular').html('<input type="text" id="InteressadoExterno_celular" name="InteressadoExterno[celular]" size="14" maxlength="14">');
        $("#InteressadoExterno_celular").mask("(99) 9999-9999");
    }
        $('#InteressadoExterno_celular').val(dados.celular);
}


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