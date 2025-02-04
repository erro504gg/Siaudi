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

class WebUser extends CWebUser {
    
    public function login($identity, $duration = 0) {
    parent::login($identity, $duration);
}


    /*
     * Verifica se o usuario possui permissao de acesso
     * @param string $controller
     * @param string $action
     */
    public function verificaPermissao($controller, $action) {

        if(strpos('grid', $controller) === false){
            $controller =  str_replace('-', ' ', str_replace('-grid', '', $controller));
            $controller = ucwords ($controller);
            $controller = str_replace(' ', '', $controller);
        }

        $allow = false;
        if (isset($this->allowedActions)) {
            if (in_array($action, $this->allowedActions)) {
                $allow = true;
            }
        }

        if (isset($this->permissoes) && isset($this->permissoes[$controller])) {
            if (in_array(strtolower($action), $this->permissoes[$controller])) {
               $allow = true;
            }
        }
        return $allow;
    }

}
