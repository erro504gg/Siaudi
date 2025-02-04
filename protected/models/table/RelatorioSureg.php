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

Yii::import('application.models.table._base.BaseRelatorioSureg');

class RelatorioSureg extends BaseRelatorioSureg
{
    public $unidade_administrativa_fk; 
    
	public static function model($className=__CLASS__) {
		return parent::model($className);
	}
        
       
    // Exclui as Unidades Regionais salvas do relatório
    // e inclui as Unidades Regionais marcadas no select múltiplo
    public function salvar($id, $suregs, $sureg_secundaria=false) {
    	$sureg_secundaria = $sureg_secundaria ? "true" : "false";
        $this->deleteAll("relatorio_fk=" . $id . " and sureg_secundaria='" . $sureg_secundaria  . "'");
        if (is_array($suregs)) {
            foreach ($suregs as $vetor) {
                $values .= " (" . $id . "," . $vetor . "," . $sureg_secundaria. "),";
            }

            $values = substr($values, 0, -1);
            $schema = Yii::app()->params['schema'];
            $sql = 'INSERT INTO ' . $schema . '.tb_relatorio_sureg (relatorio_fk, unidade_administrativa_fk, sureg_secundaria) VALUES ' . $values;
            $command = Yii::app()->db->createCommand($sql);
            $command->execute();
        }
    }         
        
    
    // recebe o ID do relatório e retorna os nomes das Unidades Regionais
    // associadas. Se link=1 então passar o link da view da Unidade Regional
    public function sureg_por_relatorio($id_relatorio,$link=null){
        $baseUrl = "..";
       //$baseUrl = Yii::app()->baseUrl;
       //$baseUrl2 = "http://".$_SERVER["HTTP_HOST"] . Yii::app()->baseUrl;
       $relatorio_sureg = RelatorioSureg::model()->findAllByAttributes(array('relatorio_fk'=>trim($id_relatorio)));
       $retorno="";
       if (sizeof($relatorio_sureg)>0){
            foreach ($relatorio_sureg as $vetor){
                $sureg = UnidadeAdministrativa::model()->findByAttributes(array('id'=>trim($vetor->unidade_administrativa_fk)));
                if($link==1){
                    $retorno.="<a href='{$baseUrl}/UnidadeAdministrativa/view/{$sureg->id}'>{$sureg->sigla}</a> / ";
                } else {
                    $retorno.="{$sureg->sigla}, ";
                }
            } 
       }
       $retorno = substr($retorno,0,-2);
       return $retorno;

    }        
}
