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
        if ( ($_SERVER['SERVER_NAME'] != 'localhost') & $_SERVER['SERVER_NAME'] != 'IP_MAQUINA_DESENVOLVIMENTO_LOCAL' ) {
            require_once('/usr/share/php/josso-lib/php/class.jossoagent.php');
            require_once('/usr/share/php/josso-lib/php/class.jossouser.php');
            require_once('/usr/share/php/josso-lib/php/class.jossorole.php');
            require_once('/usr/share/php/josso-lib/php/josso-cfg.inc');
            $josso_agent = & jossoagent::getNewInstance();
            $josso_agent->accessSession();

            // jossoagent is automatically instantiated by josso.php,
            // declared in auto_prepend_file property of php.ini.
            // Get current bd_autenticacao user information,
            $user = $josso_agent->getUserInSession();

            if ($user == null) {
                return FALSE;
            }

            $roles = $josso_agent->findRolesByUsername($user->getName());
            $username = strtolower($user->getName());
            $role = (!count($roles) === false ? strtolower($roles[0]->getName()) : '');
        } else {
            // ************* LOCALHOST *******************************
            $username = 'USUARIO_SISTEMA';
            $role = 'PERFIL_USUARIO_SISTEMA';
        }

        /*
         * pesquisa usuario
         */
        $usuario = VwUsuario::model()->find('login=:login', array(':login' => $username));

        /*
         * pesquisa sistema
         */
        $app = Yii::app()->getParams()->id_aplicacao;
        $sistema = VwSistema::model()->find('nome=:nome', array(':nome' => $app));
//debug($sistema);
        /*
         * pesquisa perfil
         */
        $usu_perfil_sis = VwUsuarioPerfilSistema::model()->find(
                'id_usuario=:id_usuario AND id_sistema=:id_sistema', array(':id_usuario' => $usuario->id_usuario, ':id_sistema' => $sistema->id)
        );


        if (empty($usu_perfil_sis->id_perfil)) {
            return FALSE;
        }

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
        $this->setState('id_perfil', $usu_perfil_sis->id_perfil);
        $this->setState('role', $role);
        $this->setState('isGuest', true);

        $this->errorCode = self::ERROR_NONE;
        return !$this->errorCode;
    }

}
