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
   //$("#InteressadoExterno_fones").mask("(99) 9999-9999");
    $("#UnidadeExterna_cep").mask("99.999-999");
    
    
    if($("#UnidadeExterna_cod_orgao").val()==''){
        $("input, select").attr("disabled", "disabled");
        $("#UnidadeExterna_cod_orgao").removeAttr("disabled");
        $("#btnVerificar").removeAttr("disabled");
    }
    
});


function setDados(dados) {

    var newOptions = dados.municipios
    var selectedOption = dados.municipio_fk;
    var options;
    var select = $('#UnidadeExterna_municipio_fk');

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


    $('#UnidadeExterna_bairro').val(dados.municipios);
    $('#UnidadeExterna_nome').val(dados.nome);
    $('#UnidadeExterna_sigla').val(dados.sigla);
    $('#UnidadeExterna_cod_orgao').val(dados.cod_orgao);
    $('#UnidadeExterna_endereco').val(dados.endereco);
    $('#UnidadeExterna_complemento').val(dados.complemento);
    $('#UnidadeExterna_numero').val(dados.numero);
    $('#UnidadeExterna_fones').val(dados.fones);
    $('#UnidadeExterna_fax').val(dados.fax);
    $('#UnidadeExterna_ddd').val(dados.ddd);
    $('#UnidadeExterna_email').val(dados.email);
    $('#UnidadeExterna_bairro').val(dados.bairro);
    $('#UnidadeExterna_cep').val(dados.cep);
    $('#uf').val(dados.uf); 



}