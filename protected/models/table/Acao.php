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

Yii::import('application.models.table._base.BaseAcao');

class Acao extends BaseAcao
{
    public $acao_mes, $valor_exercicio, $processo_riscopre;     
        
	public static function model($className=__CLASS__) {
		return parent::model($className);
	}
        
        
    public function attributeLabels() {
        $attribute_default = parent::attributeLabels();

        $attribute_custom = array(
            'id' => Yii::t('app', 'ID'),
            'numero_acao' => Yii::t('app', 'Número da Ação'),            
            'nome_acao' => Yii::t('app', 'Ação de Auditoria'),
            'valor_exercicio' => Yii::t('app', 'Exercício'),
        	'processo_riscopre' => Yii::t('app', 'Riscos Pré-Identificados'),
            'acao_mes' => Yii::t('app', 'Meses da Ação'),                    
            'especie_auditoria_fk' => null,
            'especieAuditoriaFk' => null,
            'processo_fk' => Yii::t('app', 'Processo'),
            'descricao_apresentacao' => Yii::t('app', 'Apresentação'),
            'descricao_escopo' => Yii::t('app', 'Escopo'),
            'descricao_representatividade' => Yii::t('app', 'Representatividade / Amplitude'),
            'descricao_objetivo' => Yii::t('app', 'Objetivo da Auditoria'),
            'descricao_objetivo_estrategico' => Yii::t('app', 'Objetivo Estratégico'),
            'descricao_origem' => Yii::t('app', 'Origem da Demanda'),
            'descricao_resultados' => Yii::t('app', 'Resultados Esperados'),
            'descricao_conhecimentos' => Yii::t('app', 'Conhecimentos Específicos Requeridos'),
            
            
        );
        return array_merge($attribute_default, $attribute_custom);
    }

	public function rules() {
		return array(
			array('nome_acao, valor_exercicio, especie_auditoria_fk, processo_fk, descricao_apresentacao, descricao_escopo, descricao_representatividade, descricao_objetivo, descricao_objetivo_estrategico, descricao_origem, descricao_resultados, descricao_conhecimentos, numero_acao, acao_mes', 'required'),
			array('valor_exercicio, especie_auditoria_fk, processo_fk', 'numerical', 'integerOnly'=>true),
			array('nome_acao', 'length', 'max'=>200),
                        array('risco_pre,acao_sureg','safe'), 
			array('id, nome_acao, valor_exercicio, especie_auditoria_fk, processo_fk, descricao_apresentacao, descricao_escopo, descricao_representatividade, descricao_objetivo, descricao_objetivo_estrategico, descricao_origem, descricao_resultados, descricao_conhecimentos', 'safe', 'on'=>'search'),                    
		);
	}
        
	public function search() {
		$criteria = new CDbCriteria;
		$criteria->compare('id', $this->id, true);
                $criteria->compare('numero_acao', $this->numero_acao, true);
		$criteria->compare('nome_acao', $this->nome_acao, true);
		$criteria->compare('valor_exercicio', $this->valor_exercicio);
		$criteria->compare('especie_auditoria_fk', $this->especie_auditoria_fk);
		$criteria->compare('processo_fk', $this->processo_fk);
		$criteria->compare('descricao_apresentacao', $this->descricao_apresentacao, true);
		$criteria->compare('descricao_escopo', $this->descricao_escopo, true);
		$criteria->compare('descricao_representatividade', $this->descricao_representatividade, true);
		$criteria->compare('descricao_objetivo', $this->descricao_objetivo, true);
		$criteria->compare('descricao_objetivo_estrategico', $this->descricao_objetivo_estrategico, true);
		$criteria->compare('descricao_origem', $this->descricao_origem, true);
		$criteria->compare('descricao_resultados', $this->descricao_resultados, true);
		$criteria->compare('descricao_conhecimentos', $this->descricao_conhecimentos, true);
		$criteria->order="valor_exercicio,numero_acao";

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}
    
}
