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

/**
 * This is the model base class for the table "{{subcriterio}}".
 * DO NOT MODIFY THIS FILE! It is automatically generated by giix.
 * If any changes are necessary, you must set or override the required
 * property or method in class "Subcriterio".
 *
 * Columns in table "{{subcriterio}}" available as properties of the model,
 * followed by relations of table "{{subcriterio}}" available as properties of the model.
 *
 * @property integer $id
 * @property string $criterio_fk
 * @property string $nome_criterio
 * @property integer $valor_peso
 * @property integer $valor_exercicio
 *
 * @property mixed $criterioFk
 */
abstract class BaseSubcriterio extends GxActiveRecord {

	public static function model($className=__CLASS__) {
		return parent::model($className);
	}

	public function tableName() {
		return '{{subcriterio}}';
	}

	public static function label($n = 1) {
		return Yii::t('app', 'Sub critério|Sub critérios', $n);
	}

	public static function representingColumn() {
		return 'nome_criterio';
	}

	public function rules() {
		return array(
			array('criterio_fk, nome_criterio, valor_peso', 'required'),
			array('valor_peso', 'numerical', 'integerOnly'=>true),
			array('nome_criterio', 'length', 'max'=>200),
			array('criterio_fk, valor_peso', 'numerical', 'integerOnly'=>true),
			array('id, criterio_fk, nome_criterio, valor_peso, valor_exercicio', 'safe', 'on'=>'search'),
		);
	}

	public function relations() {
		return array(
			'criterioFk' => array(self::BELONGS_TO, 'Criterio', 'criterio_fk'),
		);
	}

	public function pivotModels() {
		return array(
		);
	}

	public function attributeLabels() {
		return array(
			'id' => Yii::t('app', 'ID'),
			'criterio_fk' => null,
			'nome_criterio' => Yii::t('app', 'Nome Criterio'),
			'valor_peso' => Yii::t('app', 'Valor Peso'),
			'valor_exercicio' => Yii::t('app', 'Valor Exercício'),
			'criterioFk' => null,
		);
	}

	public function search() {
                $schema = Yii::app()->params['schema'];
		$criteria = new CDbCriteria;
                $criteria->alias = "t";
                $criteria->select="t.*, criterio.valor_exercicio";
                $criteria->join = 'INNER JOIN '.$schema.'.tb_criterio as criterio ON criterio.id =  criterio_fk';

		$criteria->compare('t.id', $this->id);
		$criteria->compare('t.criterio_fk', $this->criterio_fk);
		$criteria->compare('t.nome_criterio', $this->nome_criterio, true);
		$criteria->compare('t.valor_peso', $this->valor_peso);
		$criteria->compare('criterio.valor_exercicio', $this->valor_exercicio);
		
		$criteria->order ='criterio.nome_criterio';

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}
}
