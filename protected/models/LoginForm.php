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
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class LoginForm extends CFormModel {

    public $usuario_login;
    public $usuario_senha;
    private $_identity;

    /**
     * Declares the validation rules.
     * The rules state that usuario_login and senha are required,
     * and senha needs to be authenticated.
     */
    public function rules() {
        return array(
            array('usuario_login, usuario_senha', 'required', 'on' => 'cenario_login'),
            array('password', 'authenticate', 'on' => 'cenario_login'),
        );
    }

    /**
     * Declares attribute labels.
     */
    public function attributeLabels() {
        return array(
            'usuario_login' => 'Usuário',
            'usuario_senha' => 'Senha',
        );
    }

    /**
     * Valida o acesso de um novo cadastro
     */
    public function validarAcesso() {
        if (!$this->getErrors()) {
            try {
                // verifica se o codigo informado existe na base
                $usuario = VwUsuario::model()->find('login=:login', array(':login' => $this->usuario_login));
                if (!$usuario) {
                    $this->addError('novo_usuario_login', 'Usuário não cadastrado no sistema. Entre em contato com a área reponsável.');
                    return false;
                }
                // verifica se a senha possui ao menos 8 caracteres
                if (strlen(trim($this->usuario_senha)) < 8) {
                    $this->addError('novo_usuario_senha', 'A senha deve conter ao menos 8 caracteres.');
                    return false;
                }
            } catch (Exception $exc) {
                $exc->getMessage();
                $this->addError('usuario_login', $exc);
                return false;
            }

            return true;
        }
    }

    /**
     * Authenticates the senha.
     * This is the 'authenticate' validator as declared in rules().
     */
    public function authenticate($attribute, $params) {
        if (!$this->hasErrors()) {
//            $this->_identity = new UserIdentity($this->usuario_login, $this->usuario_senha);
//            if (!$this->_identity->authenticate()){
//                $this->addError('usuario_senha', 'Usuário ou Senha Inválidos.');
//            }
        }
    }

    /**
     * Logs in the user using the given usuario_login and senha in the model.
     * @return boolean whether login is successful
     */
    public function login() {
        $this->_identity = new UserIdentity($this->usuario_login, $this->usuario_senha);
        if (!$this->_identity->authenticate()) {
            $this->addError('usuario_senha', 'Usuário ou Senha Inválidos.');
            return false;
        }else{
        //if ($this->_identity->errorCode === UserIdentity::ERROR_NONE) {
            Yii::app()->session['identity'] = serialize($this->_identity);
            Yii::app()->user->login($this->_identity);
            return true;
        }
    }

}
