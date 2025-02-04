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
            'LoggableBehavior' => 'application.modules.auditTrail.behaviors.LoggableBehavior',
        );
    }

    protected function beforeValidate() {
        if ($this->scenario != 'search') {
            $this->unidade_fk = Yii::app()->user->id_und_adm;
        }
        $this->usuario_inclusao_fk = Yii::app()->user->id;
        return parent::beforeValidate();
    }
    
    
    
    public function trapaPesquisaTexto( CDbCriteria $criteria , $campo,$parans, $alias = null){

        $this->$campo = trim($this->$campo);
        if($this->$campo){
            $criteria->addCondition($alias.$campo .' ilike :'.$campo, 'OR');
            $criteria->params = array_merge($parans , array(':'.$campo=> $this->$campo.'%') );
        }
        
    }

    public function maiuscula($string){
          return strtoupper(strtr($string ,"áéíóúâêôãõàèìòùç","ÁÉÍÓÚÂÊÔÃÕÀÈÌÒÙÇ"));
    }

}
