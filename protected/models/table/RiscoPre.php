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

Yii::import('application.models.table._base.BaseRiscoPre');

class RiscoPre extends BaseRiscoPre
{
    public $valor_exercicio, $processo_riscopre; 
    
    public function attributeLabels() {
		$attribute_default = parent::attributeLabels();

		$attribute_custom = array(
			'valor_exercicio' => Yii::t('app', 'Exercício'),
			'processo_riscopre' => Yii::t('app', 'Processo'),
		);
		return array_merge($attribute_default, $attribute_custom);
    }    
    
	public static function model($className=__CLASS__) {
		return parent::model($className);
	}
	
	// recebe o ID do risco pré-identificado e retorna os nomes dos processos associadas. 
	public function ProcessosPorRiscos($risco_pre_fk, $bolExercicio=false){		
		$processo_risco_pre = ProcessoRiscoPre::model()->with('processoFk')->findAll(array("condition" => 'risco_pre_fk = ' . $risco_pre_fk));
		$retorno="";
		$valor_exercicio = array();
		if (sizeof($processo_risco_pre)>0){
			foreach ($processo_risco_pre as $vetor){
				if($bolExercicio){
					$valor_exercicio[] = $vetor->processoFk->valor_exercicio;
				} else {
					$retorno.= $vetor->processoFk . "<br>";
				}
			}
		}
		
		if ($bolExercicio){
			if (sizeof($valor_exercicio)){
				$valor_exercicio = array_unique($valor_exercicio);
				foreach($valor_exercicio as $value){
					$retorno.= $value . "<br>";
				}
			}
		}
		
		return $retorno;
	}
		
}
