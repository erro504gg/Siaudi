<?php

/**
 * Rights filter class file.
 *
 * @author Christoffer Niska <cniska@live.com>
 * @copyright Copyright &copy; 2010 Christoffer Niska
 * @since 0.7
 */
class UsuarioFilter extends CFilter {

    protected $_allowedActions = array();

    /**
     * Performs the pre-action filtering.
     * @param CFilterChain $filterChain the filter chain that the filter is on.
     * @return boolean whether the filtering process should continue and the action
     * should be executed.
     */
    protected function preFilter($filterChain) {
        // By default we assume that the user is allowed access
        $allow = false;
        
        $action = $filterChain->action;
        $controller = $filterChain->controller;
        $controller_id = ucfirst($controller->id);
        
         if (strpos(strtolower($action->id), 'autocomplete') !== false)
            $allow = true;
        
        if (strpos(strtolower($action->id) , 'combo' ) !== false)
            $allow = true;
        
        if (strpos(strtolower($action->id) , 'ajax' ) !== false)
            $allow = true;
        
        if (in_array($action->id, $this->_allowedActions))
            $allow = true;

        //recupera as permissoes
        $permissoes = VwRestricao::model()->findAll(
                'id_perfil=:id_perfil AND id_sistema=:id_sistema', array(':id_perfil' => Yii::app()->user->id_perfil, ':id_sistema' => Yii::app()->user->id_sistema)
        );

        //cria um array com indice controller e actions
        $permissaoTmp = array();

        foreach ($permissoes as $value) {
            $permissaoTmp[ucfirst($value->nome_modulo)][] = strtolower($value->nome_componente);
        }
        // adiciona as permissoes na variavel de sessao
        Yii::app()->user->setState('permissoes', $permissaoTmp);
        Yii::app()->user->setState('allowedActions', $this->_allowedActions);

        if (key_exists($controller_id, $permissaoTmp)) {
            if (is_array($permissaoTmp[$controller_id])) {
                // checa se o usuario tem permissao de acessar a acao atual

                if (in_array(strtolower($action->id), $permissaoTmp[$controller_id])) {
                    $allow = true;
                }
            }
        }
        // User is not allowed access, deny access
        if ($allow === false) {
            $controller->accessDenied();
            return false;
        }

        // Authorization item did not exist or the user had access, allow access
        return $allow;
    }

    /**
     * Sets the allowed actions.
     * @param string $allowedActions the actions that are always allowed separated by commas,
     * you may also use star (*) to represent all actions.
     */
    public function setAllowedActions($allowedActions) {
        if ($allowedActions === '*')
            $this->_allowedActions = $allowedActions;
        else
            $this->_allowedActions = preg_split('/[\s,]+/', $allowedActions, -1, PREG_SPLIT_NO_EMPTY);
    }

}
