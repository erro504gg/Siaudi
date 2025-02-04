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

Yii::import('application.models.table._base.BaseAcaoRiscoPre');

class AcaoRiscoPre extends BaseAcaoRiscoPre
{
    public $acao_riscopre; 
    
	public static function model($className=__CLASS__) {
		return parent::model($className);
	}
        
        
    public function attributeLabels() {
        $attribute_default = parent::attributeLabels();

        $attribute_custom = array(
            'acao_fk' => null,
            'risco_pre_fk' => null,
            'riscoPreFk' => null,        
            'acao_riscopre' => Yii::t('app', 'Riscos Pré-Identificados'),
        );
        return array_merge($attribute_default, $attribute_custom);
    }
    
    
    // Exclui os riscos salvos da ação
    // e inclui os riscos pré-identificados marcados no select múltiplo
    public function salvar($id, $riscos) {
        $this->deleteAll("acao_fk=" . $id);
        if (is_array($riscos)) {
            foreach ($riscos as $vetor) {
                $values .= " (" . $id . "," . $vetor . "),";
            }

            $values = substr($values, 0, -1);
            $schema = Yii::app()->params['schema'];
            $sql = 'INSERT INTO ' . $schema . '.tb_acao_risco_pre (acao_fk, risco_pre_fk) VALUES ' . $values;
            $command = Yii::app()->db->createCommand($sql);
            $command->execute();
        }
    }
    
//     public function salvarRiscoPreAcao($id_risco, $acao) {
//     	$this->deleteAll("risco_pre_fk=" . $id_risco);
//     	if (is_array($acao)) {
//     		foreach ($acao as $acao_fk) {
//     			$values .= " (" . $acao_fk . "," . $id_risco . "),";
//     		}
    
//     		$values = substr($values, 0, -1);
//     		$schema = Yii::app()->params['schema'];
//     		$sql = 'INSERT INTO ' . $schema . '.tb_acao_risco_pre (acao_fk, risco_pre_fk) VALUES ' . $values;
    		
//     		$command = Yii::app()->db->createCommand($sql);
//     		$command->execute();
//     	}
//     }    
    
    
        
}
