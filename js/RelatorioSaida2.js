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
        function valida() {
        var form = document.forms["relatorioSaida"];
        var valor = form.Relatorio_id.options[form.Relatorio_id.selectedIndex].value;
        if (valor>0){
            window.open('RelatorioSaida/RelatorioSaidaAjax/'+valor,'Relatorio_de_Auditoria','width=700,height=500,scrollbars=yes,resizable=yes,location=no,toolbar=no,status=no');
        }
    }
    
    function auditor_abre_div() {
        var form = document.forms["relatorioSaida"];
        var divAno = document.getElementById('ano');
        var divUnidadeAdministrativa = document.getElementById('unidade_administrativa');
        var valor = form.Relatorio_tipo_relatorio.options[form.Relatorio_tipo_relatorio.selectedIndex].value;

        // abre combo ANO para relatórios homologados
        if (valor==2){ 
            divAno.style.display='block';
        } else {
            divAno.style.display='none';
        }
        
        if ((valor != '') && (divUnidadeAdministrativa != null)) {
            divUnidadeAdministrativa.style.display = "block";
        } else if(divUnidadeAdministrativa != null) {
            divUnidadeAdministrativa.style.display = "none";
        }
    }    
     