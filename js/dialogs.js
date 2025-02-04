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
    });

function carregaView(url) {

    var arrayUrl=url.split('/');
    var id = arrayUrl[arrayUrl.length-1];
 
    $.get('view/'+id, function(data) {
        $('#visualizar_reg').html(data);
        $('#dialog_visualizar').dialog('open');
    });
}; 
 
function viewDelete(url, grid_name) {

    var arrayUrl=url.split('/');
    var id = arrayUrl[arrayUrl.length-1];
    
    $('.ui-button:contains("Excluir")')
        .css("border", "1px solid #CD0A0A")
        .css('background','#FEF1EC').children().css("color", "#CD0A0A")
    
    $.get('view/'+id, function(data) {
        $('#excluir_reg').html(data);
        $('#dialog_excluir').dialog('open');
        $("#id_excluir").val(id)
        $("#ctrl_excluir").val(url)
        $("#id_grid_excluir").val(grid_name.parents('.grid-view').attr('id'))
    });
}; 
