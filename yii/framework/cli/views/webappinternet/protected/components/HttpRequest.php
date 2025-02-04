<?php

class HttpRequest extends CHttpRequest {
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

    /**
     * Correcao de redirecionamento https
     * @param type $schema
     */
    public function getHostInfo($schema = '') {
//        $_SERVER['HTTP_X_FORWARDED_HOST'] = 'sistemas.conab.gov.br';
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
