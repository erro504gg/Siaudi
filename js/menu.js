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
    //nivel 1
    $('#menuNiv1 ul li').click(function() {
        $('#menuNiv2 div').each(function() {
            $(this).hide();
        });
        $('#menuNiv3 div').each(function() {
            $(this).hide();
        });

        var id_li = $(this).attr("id");
        niv2 = $('#menuNiv2 #'+id_li);
        niv2.fadeToggle('fast');
    });

    $('#cabecalho, #conteudo').mouseover(function(){
        $('#menuNiv3 div, #correcaoMenuIE').each(function() {
            $(this).hide();
        });
    });

    //nivel 1
    $('#menuNiv2 ul li').click(function() {
        $('#menuNiv3 div, #correcaoMenuIE').each(function() {
            $(this).hide();
        });

        $("#correcaoMenuIE").show();
        var id_li = $(this).attr("id");
        niv3 = $('#menuNiv3 #'+id_li);
        niv3.fadeToggle('slow');
    });
});
