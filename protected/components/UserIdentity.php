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
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity {

    private $_id;

    public function getId() {
        return $this->_id;
    }

    public function authenticate() {
        // This will return the environment code number
        // $currentEnvironment = Yii::app()->getParams()->environment;
        // Compare if the current environment is lower than production

    	
//        if (($_SERVER['SERVER_NAME'] != 'localhost') & $_SERVER['SERVER_NAME'] != '0.0.0.0') {
//        
//            require_once('/usr/share/php/josso-lib/php/class.jossoagent.php');
//            require_once('/usr/share/php/josso-lib/php/class.jossouser.php');
//            require_once('/usr/share/php/josso-lib/php/class.jossorole.php');
//            require_once('/usr/share/php/josso-lib/php/josso-cfg.inc');
//            $josso_agent = & jossoagent::getNewInstance();
//            $josso_agent->accessSession();
//
//            // jossoagent is automatically instantiated by josso.php,
//            // declared in auto_prepend_file property of php.ini.
//            // Get current bd_autenticacao user information,
//            $user = $josso_agent->getUserInSession();
//
//            if ($user == null) {
//                return FALSE;
//            }
//
//            $roles = $josso_agent->findRolesByUsername($user->getName());
//            $username = strtolower($user->getName());
//            //$role = (!count($roles) === false ? strtolower($roles[0]->getName()) : '');
//            
//            /*
//             * Filtra ROLE para sistema específico
//             */            
//            $id_aplicacao = Yii::app()->params['id_aplicacao'];
//            $nome_aplicacao =  preg_replace("/[^A-Z]/", "", $id_aplicacao); //remove números do nome da aplicação
//            $tamanho_nome_aplicacao = strlen($nome_aplicacao);
//            
//            foreach ($roles as $vetor){
//                $procura_perfil = substr($vetor->getName(),0,$tamanho_nome_aplicacao);
//                if (strtolower($procura_perfil)==strtolower($nome_aplicacao)){
//                    $role=$vetor->getName();
//                    break;
//                }
//                
//            }
//            // fim da role para sistema específico
//
//            
//        } else {
//            // ************* LOCALHOST *******************************
//
//            $vetor_localhost[0]['username'] = 'siaudi.auditor';
//            $vetor_localhost[0]['senha'] = 'aaaaaa';
//            $vetor_localhost[0]['role'] = 'siaudi_auditor';
//
//
//            $valor=0;
//
//            $username = $vetor_localhost[$valor]['username'];
//            $senha = $vetor_localhost[$valor]['senha'];
//            $role = $vetor_localhost[$valor]['role'];
//
//        }

        $username = $this->username;
        $senha = $this->password;
        
        if (!empty($username)) {
            /*
             * pesquisa usuario
             */
            $usuario = VwUsuario::model()->find('login=:login AND senha=:senha', array(':login' => $username, ':senha' => md5($senha)));

            /*
             * pesquisa sistema
             */
            $app = Yii::app()->getParams()->id_aplicacao;
            $sistema = VwSistema::model()->find('nome=:nome', array(':nome' => $app));

            /*
             * pesquisa perfil
             */
            $usu_perfil_sis = VwUsuarioPerfilSistema::model()->find(
                    'id_usuario=:id_usuario AND id_sistema=:id_sistema', array(':id_usuario' => $usuario->id_usuario, ':id_sistema' => $sistema->id)
            );

            if (empty($usu_perfil_sis->id_perfil)) {
                return FALSE;
            }

            /*
             * Pesquisa o nome do perfil
             */
            $perfil = VwPerfil::model()->find('id=:id', array(':id' => $usu_perfil_sis->id_perfil));
            $role = $perfil->nome;

            // recupera informaï¿½ï¿½es com Yii::app()->user->id_und_adm
            $this->_id = $usuario->id_usuario;
            $this->setState('nome', $usuario->nome_usuario);
            $this->setState('login', $username);
            $this->setState('funcao', $usuario->funcao);
            $this->setState('email', $usuario->email);
            $this->setState('id_und_adm', $usuario->id_und_adm);
            $this->setState('nome_und_adm', $usuario->nome_und_adm);
            $this->setState('uf_und_adm', $usuario->uf_und_adm);
            $this->setState('sigla_und_adm', $usuario->sigla_und_adm);
            $this->setState('id_sistema', $sistema->id);
            $this->setState('id_perfil', $usu_perfil_sis->id_perfil);
            $this->setState('role', strtolower($role));
            $this->setState('isGuest', true);
            $this->errorCode = self::ERROR_NONE;
        }else{
            $this->errorCode = self::ERROR_NONE;
        }
        return !$this->errorCode;
    }
}
