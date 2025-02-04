<?php

/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class MyActiveRecord extends GxActiveRecord {

    private static $db_senior = null;

    public function init() {

        parent::init();
    }

    protected static function getSeniorDbConnection() {
        if (self::$db_senior !== null)
            return self::$db_senior;
        else {
            self::$db_senior = Yii::app()->db_senior;
            if (self::$db_senior instanceof CDbConnection) {
                self::$db_senior->setActive(true);
                return self::$db_senior;
            }
            else
                throw new CDbException(Yii::t('yii', 'Active Record requires a "db" CDbConnection application component.'));
        }
    }

//    public function behaviors() {
//        return array(
//            'datetimeI18NBehavior' => array('class' => 'ext.DateTimeI18NBehavior'),
//                //'LoggableBehavior' => 'application.modules.auditTrail.behaviors.LoggableBehavior',
//        );
//    }
//    protected function beforeValidate() {
//        if ($this->scenario != 'search') {
//            $this->unidade_fk = Yii::app()->user->id_und_adm;
//        }
//        $this->usuario_inclusao_fk = Yii::app()->user->id;
//        return parent::beforeValidate();
//    }

    public function trataPesquisaTexto(CDbCriteria $criteria, $campo, $parans, $alias = null) {

        $this->$campo = trim($this->$campo);
        if ($this->$campo) {
            $criteria->addCondition($alias . $campo . ' ilike :' . $campo, 'OR');
            $criteria->params = array_merge($parans, array(':' . $campo => $this->$campo . '%'));
        }
    }

    public function maiuscula($string) {
        return strtoupper(strtr($string, "áéíóúâêôãõàèìòùç", "ÁÉÍÓÚÂÊÔÃÕÀÈÌÒÙÇ"));
    }

    function retiraAcentos($texto) {
        $array1 = array("á", "à", "â", "ã", "ä", "é", "è", "ê", "ë", "í", "ì", "î", "ï", "ó", "ò", "ô", "õ", "ö", "ú", "ù", "û", "ü", "ç"
            , "Á", "À", "Â", "Ã", "Ä", "É", "È", "Ê", "Ë", "Í", "Ì", "Î", "Ï", "Ó", "Ò", "Ô", "Õ", "Ö", "Ú", "Ù", "Û", "Ü", "Ç");
        $array2 = array("a", "a", "a", "a", "a", "e", "e", "e", "e", "i", "i", "i", "i", "o", "o", "o", "o", "o", "u", "u", "u", "u", "c"
            , "A", "A", "A", "A", "A", "E", "E", "E", "E", "I", "I", "I", "I", "O", "O", "O", "O", "O", "U", "U", "U", "U", "C");
        return str_replace($array1, $array2, $texto);
    }

    public function camposVazioPesquisa($campos) {
        foreach ($campos as $key => $variable) {
            if ($variable || $variable === false || is_bool($variable)) {
                return true;
            }
        }
        return false;
    }
    
    public function alterarOrdenacao($classe) {
        if (!Yii::app()->request->getParam($classe.'_sort')){
            return 'data_recebimento DESC';
        } else{
            return str_replace('.', ' ', Yii::app()->request->getParam($classe.'_sort'));
        }
    }

}
