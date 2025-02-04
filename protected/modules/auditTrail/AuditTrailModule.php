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

class AuditTrailModule extends CWebModule
{
	/**
	 * @var string the name of the User class. Defaults to "User"
	 */
	public $userClass = "User";

	/**
	 * @var string the name of the column of the user class that is the primary key. Defaults to "id"
	 */	
	public $userIdColumn = "id";

	/**
	 * @var string the name of the column of the user class that is the username. Defaults to "username"
	 */	
	public $userNameColumn = "username";
	
	/**
	 * @var AuditTrailModule static variable to hold the module so we don't have to instantiate it a million times to get config values
	 */
	private static $__auditTrailModule;

	public function init()
	{
		// this method is called when the module is being created
		// you may place code here to customize the module or the application

		// import the module-level models and components
		$this->setImport(array(
			'auditTrail.models.*',
			'auditTrail.components.*',
		));
	}

	public function beforeControllerAction($controller, $action)
	{
		if(parent::beforeControllerAction($controller, $action))
		{
			// this method is called before any module controller action is performed
			// you may place customized code here
			return true;
		}
		else
			return false;
	}
	
	
	/**
	 * Returns the value you want to look up, either from the config file or a user's override
	 * @var value The name of the value you would like to look up
	 * @return the config value you need
	 */
	public static function getFromConfigOrObject($value) {
		$at = Yii::app()->modules['auditTrail'];

		//If we can get the value from the config, do that to save overhead
		if( isset( $at[$value]) && !empty($at[$value] ) ) {
			return $at[$value];
		}

		//If we cannot get the config value from the config file, get it from the
		//instantiated object. Only instantiate it once though, its probably 
		//expensive to do. PS I feel this is a dirty trick and I don't like it
		//but I don't know a better way
		if(!is_object(self::$__auditTrailModule)) {
			self::$__auditTrailModule = new AuditTrailModule(microtime(), null);
		}
		
		return self::$__auditTrailModule->$value;
	}

}
