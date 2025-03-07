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
 * This is the model base class for the table "{{relatorio_acesso}}".
 * DO NOT MODIFY THIS FILE! It is automatically generated by giix.
 * If any changes are necessary, you must set or override the required
 * property or method in class "RelatorioAcesso".
 *
 * Columns in table "{{relatorio_acesso}}" available as properties of the model,
 * followed by relations of table "{{relatorio_acesso}}" available as properties of the model.
 *
 * @property string $relatorio_fk
 * @property string $nome_login
 * @property string $unidade_administrativa_fk
 *
 * @property mixed $relatorioFk
 */
abstract class BaseRelatorioAcesso extends GxActiveRecord {

	public static function model($className=__CLASS__) {
		return parent::model($className);
	}

	public function tableName() {
		return '{{relatorio_acesso}}';
	}

	public static function label($n = 1) {
		return Yii::t('app', 'RelatorioAcesso|RelatorioAcessos', $n);
	}

	public static function representingColumn() {
		return 'nome_login';
	}

	public function rules() {
		return array(
			array('relatorio_fk, nome_login', 'required'),
			array('nome_login', 'length', 'max'=>50),
			array('unidade_administrativa_fk', 'safe'),
			array('relatorio_fk, unidade_administrativa_fk', 'numerical', 'integerOnly'=>true),
			array('relatorio_fk, nome_login, unidade_administrativa_fk', 'safe', 'on'=>'search'),
		);
	}

	public function relations() {
		return array(
			'relatorioFk' => array(self::BELONGS_TO, 'Siaudi.relatorio', 'relatorio_fk'),
		);
	}

	public function pivotModels() {
		return array(
		);
	}

	public function attributeLabels() {
		return array(
			'relatorio_fk' => null,
			'nome_login' => Yii::t('app', 'Nome Login'),
			'unidade_administrativa_fk' => Yii::t('app', 'Unidade Administrativa (Lotação)'),
			'relatorioFk' => null,
		);
	}

	public function search() {
		$criteria = new CDbCriteria;

		$criteria->compare('relatorio_fk', $this->relatorio_fk);
		$criteria->compare('nome_login', $this->nome_login, true);
		$criteria->compare('unidade_administrativa_fk', $this->unidade_administrativa_fk, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}
}
