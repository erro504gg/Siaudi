<?php
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
?>
<?php

class RotinasCronController extends GxController {

	
	public function accessRules(){
		return array( array('allow',  // allow all users to perform 'index' and 'view' actions
                                'actions'=>array('index'),
                                'users'=>array('*'),
                         ));
	}
	

	
	
	
    // Rotinas  para colocar no Cron
    public function actionIndex() {
    	$this->layout = false;
        // rotina para verificar prazo dos 5 dias úteis
        // para manifestação dos relatórios finalizados
        // (encerra somente os relatórios onde NÃO HOUVE,
        //  manifestação contrária)
        $rotina = Relatorio::model()->FechaPrazoRelatorioFinalizado();    
        
        // rotina para verificar se existem critérios de avaliação
        // no exercício atual. Caso mude o ano e não haja critérios
        // de avaliação cadastrados, as Unidades Regionais não poderão realizar
        // a avaliação dos auditores no momento da manifestação do relatório.
        $rotina2 = AvaliacaoCriterio::model()->VerificaAvaliacaoCriterio();  
        
        //rotina para atualizar os feriados que se repetem todo o ano.
        //Esta rotina deverá ser executada no dia 01/11 e no dia 31/12 garatindo os feriados
        //do próximo ano, pois existem processos que podem depender destas datas.
        if (((date("d") == 1) && (date("m") == 11)) || ((date("d") == 31) && (date("m") == 12))) {
            $rotina3 = Feriado::model()->AtualizaFeriadosAutomaticamente();
        }
        
        //rotina para alertar o auditado que o relatório homologado está sem resposta por um período de 20 dias úteis ou 60 dias corridos.
        // $rotina4 = Relatorio::model()->VerificaManifestacaoAuditado();
//         
        //rotina para alertar o auditor que o relatório homologado está sem resposta por um período de 10 dias úteis.
        // $rotina5 = Relatorio::model()->VerificaManifestacaoAuditor();
        
        //rotina para atualizar os processos que se repetem todo o ano.
        //Esta rotina deverá ser executada no dia 01/11 e no dia 31/12 garatindo os feriados
        //do próximo ano, pois existem processos que podem depender destas datas.
        if ((date("d") == 31) && (date("m") == 12)) {
        	$rotina6 = Processo::model()->AtualizaProcessosAutomaticamente();
        }
    }


}
