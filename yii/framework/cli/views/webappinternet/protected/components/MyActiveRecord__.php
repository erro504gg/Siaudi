<?php

/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class MyActiveRecord extends GxActiveRecord {

    public function init() {
        parent::init();
    }

    public function behaviors() {
        return array(
            'datetimeI18NBehavior' => array('class' => 'ext.DateTimeI18NBehavior'),
            //'LoggableBehavior' => 'application.modules.auditTrail.behaviors.LoggableBehavior',
        );
    }

    protected function beforeValidate() {
        if ($this->scenario != 'search') {
            $this->unidade_fk = Yii::app()->user->id_und_adm;
        }
        $this->usuario_inclusao_fk = Yii::app()->user->id;
        return parent::beforeValidate();
    }

    public function trapaPesquisaTexto(CDbCriteria $criteria, $campo, $parans, $alias = null) {

        $this->$campo = trim($this->$campo);
        if ($this->$campo) {
            $criteria->addCondition($alias . $campo . ' ilike :' . $campo, 'OR');
            $criteria->params = array_merge($parans, array(':' . $campo => $this->$campo . '%'));
        }
    }

    public function maiuscula($string) {
        return strtoupper(strtr($string, "áéíóúâêôãõàèìòùç", "ÁÉÍÓÚÂÊÔÃÕÀÈÌÒÙÇ"));
    }

    public function camposVazioPesquisa($campos) {
        foreach ($campos as $key => $variable) {
            if ($variable || $variable === false || is_bool($variable)) {
                return true;
            }
        }
        return false;
    }

}
