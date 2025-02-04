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
    function valida(id, nome_login_cliente, nome_login_autenticado) {
    if (nome_login_cliente == nome_login_autenticado) {
        if (confirm("Deseja realmente revogar o acesso deste item? ")) {
            $('#relatorio_acesso_item_id').val(id);
            document.forms["acesso_item"].submit();
        }
    } else
    {
        alert("Somente o usuário que concedeu o acesso pode revogá-lo.")
    }
}

$(document).ajaxComplete(function() {
    $("#item_fk").multiselect('refresh');
    var item = document.getElementById("item_fk");
    if (item.length == 0) {
        alert("Relatório não possui nenhum item cadastrado!");
        window.location.reload();
    }
    radiobtn = document.getElementById("RelatorioAcessoItem_liberar_todos_itens_1");
    radiobtn.checked = true;
    habilitarComponentes();
});

$(function() {
    $(document).ready(function() {
        desabilitaComponentes();
    });
});

function desabilitaComponentes() {
    $("#item_fk").multiselect("checkAll");
    $("#item_fk").multiselect('refresh');
    $("#item_fk").multiselect('disable');
    $("#relatorio_fk").multiselect('disable');
}

function habilitarComponentes() {
    $("#item_fk").multiselect('enable');
//    $("#item_fk").multiselect("uncheckAll");
    $("#item_fk").multiselect('refresh');
}

$(function() {
    $('#RelatorioAcessoItem_liberar_todos_itens_0').click(function() {
    	if (!this.disabled){
    		desabilitaComponentes();
    	}
    });
});

$(function() {
    $('#RelatorioAcessoItem_liberar_todos_itens_1').click(function() {
    	if (!this.disabled){
    		habilitarComponentes();
    	}
    });
});