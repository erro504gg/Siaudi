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
        function valida_envio() {
        var form = document.forms["prorrogar_prazo"];
        var envio=1;   	
        var combo = form.Relatorio_id.value; 
        var prazo = form.Relatorio_dias_uteis.value;         
        if (combo==0){
            alert("Informe o relatório.");
            envio=0;
        }
        if (prazo==0){
            alert("Informe o prazo a prorrogar.");
            envio=0;            
        }
        
        if (!isNumeric(prazo) && prazo){
            alert("Prazo deve conter somente números.");
            envio=0;            
        }        
        if(envio){
           form.submit();
        }
    }  
    
    
    function isNumeric(str){
      var er = /^[0-9]+$/;
      return (er.test(str));
    }    
    
    
    function valida_homologacao() {
        if (!$('#Relatorio_id').val() || $('#Relatorio_id').val()==0) {
            alert ("Selecione um relatório.");
        }
        else {
            if(confirm("Deseja realmente homologar o Relatório? ")){
                 document.forms["homologar"].submit();
            } 
        }
    }
    
    function valida_reiniciarContagem() {
            if(confirm("Confirma o reinício da contagem dos itens \npara o PRÓXIMO relatório homologado? ")){
                document.forms["reiniciar_contagem"].submit();
            } 

    }
            

    function envia_relatorio(){
        $('#inserir_capitulo').val("1"); 
        $('#relatorio-form').submit();
        $('#inserir_capitulo').val("0");
    }
    
    
    function salvar() {
        document.getElementById('yt1').click(); 
    }
    
    function obtemDadosModal() {   
        var nome_risco = document.getElementById("RiscoPos_nome_risco").value;
        var descricao_impacto = document.getElementById("RiscoPos_descricao_impacto").value;
        var descricao_mitigacao = document.getElementById("RiscoPos_descricao_mitigacao").value;
        var dados = new Array();
        
        dados = {'nome_risco':nome_risco,'descricao_impacto':descricao_impacto, 'descricao_mitigacao': descricao_mitigacao};
                
        return dados;
    }
    
    function atualizaComboRiscoPos(data){
        var s= document.getElementById('relatorio_riscopos');
        s.innerHTML += data;
        $("#relatorio_riscopos").multiselect('refresh');
        alert("Risco adicionado com sucesso!");
    }
    
    function validaDadosModal() {
        //implementar o tooltip
        //procurar como fazer a quebra de linha.
        
        retorno = true;
        msgInconsistencia = 'Por favor, corrija os seguintes erros: <br>';
        tips = $( ".validateTips" );
        
        e_nome_risco = $( "#RiscoPos_nome_risco");
        e_descricao_impacto = $( "#RiscoPos_descricao_impacto");
        e_descricao_mitigacao = $( "#RiscoPos_descricao_mitigacao");
                
        e_nome_risco.removeClass( "ui-state-error" );
        e_descricao_impacto.removeClass( "ui-state-error" );
        e_descricao_mitigacao.removeClass( "ui-state-error" );
        
        if (e_nome_risco.val() === '') { 
            e_nome_risco.addClass( "ui-state-error" );
            msgInconsistencia += "<li>É obrigatório o preenchimento do campo Risco. </li>";
            retorno = false;
        }
        /*
        if (e_descricao_impacto.val() === '') { 
            e_descricao_impacto.addClass( "ui-state-error" );
            msgInconsistencia += "<li> É obrigatório o preenchimento do campo Impacto. </li>";
            retorno = false;
        }
        
        if (e_descricao_mitigacao.val() === '') { 
            e_descricao_mitigacao.addClass( "ui-state-error" );
            msgInconsistencia += "<li> É obrigatório o preenchimento do campo Mitigação. </li>";
            retorno = false;
        }
        */
        if (!retorno){
            tips
            .html(msgInconsistencia)
//            .cssHTML("color: #B43419; background-color: #FDF5F3;")
            .addClass( "ui-state-highlight" );
        }
        
        return retorno;
        
    }