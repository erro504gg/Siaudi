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

class HttpRequest extends CHttpRequest{
/*
    private $_csrfToken;
    
    public function getCsrfToken() {
        
        
        if ($this->_csrfToken === null) {
            $session = Yii::app()->session;
            $csrfToken = $session->itemAt($this->csrfTokenName);
            if ($csrfToken === null) {
                $csrfToken = sha1(uniqid(mt_rand(), true));
                $session->add($this->csrfTokenName, $csrfToken);
            }
            $this->_csrfToken = $csrfToken;
        }

        return $this->_csrfToken;
    }

    public function validateCsrfToken($event) {
        if ($this->getIsPostRequest()) {
            // only validate POST requests
            $session = Yii::app()->session;
         
            if ($session->contains($this->csrfTokenName) && isset($_POST[$this->csrfTokenName])) {
                $tokenFromSession = $session->itemAt($this->csrfTokenName);
                $tokenFromPost = $_POST[$this->csrfTokenName];
                
                $valid = $tokenFromSession === $tokenFromPost;
            }
            else
                $valid = false;
            if (!$valid)
                throw new CHttpException(400, Yii::t('yii', 'O CSRF token não pode ser verificado.'));
        }
    }
*/
    
    public function getHostInfo($schema = '') {
//        $_SERVER['HTTP_X_FORWARDED_HOST'] = ;
//        if (CHttpRequest::getHostInfo() != null) {
//            if ($secure = $this->getIsSecureConnection()) {
//                $http = 'http';
//            } else {
//                $http = 'https';
//            }
//            if (isset($_SERVER['HTTP_X_FORWARDED_HOST'])) {
//                CHttpRequest::setHostInfo($http . '://' . $_SERVER['HTTP_X_FORWARDED_HOST']);
//            } elseif (isset($_SERVER['HTTP_HOST'])) {
//                CHttpRequest::setHostInfo($http . '://' . $_SERVER['HTTP_HOST']);
//            } else {
//                $info = $http . '://' . $_SERVER['SERVER_NAME'];
//                $port = $_SERVER['SERVER_PORT'];
//                if (($port != 80 && !$secure) || ($port != 443 && $secure)) {
//                    $info .= ':' . $port;
//                }
//                CHttpRequest::setHostInfo($info);
//            }
//        }
//        if ($schema !== '' && ($pos = strpos(CHttpRequest::getHostInfo(), ':')) !== false) {
//            return $schema . substr(CHttpRequest::getHostInfo(), $pos);
//        } else {
//            return CHttpRequest::getHostInfo();
//        }
    }    
}
