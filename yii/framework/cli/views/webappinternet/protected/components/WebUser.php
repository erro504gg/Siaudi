<?php

class WebUser extends CWebUser {
    
//    public function login($identity) {
//        parent::login($identity);
//    }
//
//    /*
//     * Verifica se o usuario possui permissao de acesso
//     * @param string $controller
//     * @param string $action
//     */
//    public function verificaPermissao($controller, $action) {
//
//        if(strpos('grid', $controller) === false){
//            $controller =  str_replace('-', ' ', str_replace('-grid', '', $controller));
//            $controller = ucwords ($controller);
//            $controller = str_replace(' ', '', $controller);
//        }
//
//        $allow = false;
//        if (isset($this->allowedActions)) {
//            if (in_array($action, $this->allowedActions)) {
//                $allow = true;
//            }
//        }
//
//        if (isset($this->permissoes) && isset($this->permissoes[$controller])) {
//            if (in_array(strtolower($action), $this->permissoes[$controller])) {
//               $allow = true;
//            }
//        }
//        return $allow;
//    }

}
