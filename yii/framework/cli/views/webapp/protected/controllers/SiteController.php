<?php

class SiteController extends GxController {

    public function actionIndex() {
        $this->titulo = 'Início';
        $this->render('index');
    }

    public function actionAcessoNegado() {
        $this->titulo = 'Acesso Negado';
        $this->render('acessoNegado');
    }

    public function actionContatoAjax() {
        $this->pageTitle = Yii::app()->name;
        $this->titulo = 'Contato';
        $this->render('contato');
    }

    /**
     */
    public function actionError() {
        $this->titulo = 'Erro';
        $error = Yii::app()->errorHandler->error;
        if ($error) {
            if (Yii::app()->request->isAjaxRequest)
                echo $error['message'];
            else
                $this->render('error', $error);
        }
    }

    /**
     * Logs out the current user and redirect to homepage.
     */
    public function actionLogout() {
        Yii::app()->session->destroy();
        $session_name = 'is_logado_' . Yii::app()->params['id_aplicacao'];
        Yii::app()->session->add($session_name, false);
        $this->redirect('login');
    }

    /**
     * Login the current user and redirect to homepage.
     */
    public function actionLogin() {
        $this->render_partial('login');
    }

    /**
     * Logs out the current user and redirect to homepage.
     */
    public function actionVerificaSessao() {
//        var_dump(Yii::app()->getUser()->isGuest);
//        require_once('/usr/share/php/josso-lib/php/class.jossoagent.php');
//        require_once('/usr/share/php/josso-lib/php/class.jossouser.php');
//        require_once('/usr/share/php/josso-lib/php/class.jossorole.php');
//        require_once('/usr/share/php/josso-lib/php/josso-cfg.inc');
//        $josso_agent = & jossoagent::getNewInstance();
//        $josso_agent->accessSession();
//
//        // jossoagent is automatically instantiated by josso.php,
//        // declared in auto_prepend_file property of php.ini.
//        // Get current bd_autenticacao user information,
//        $user = $josso_agent->getUserInSession();
    }

}
