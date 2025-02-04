<?php

/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends CController {

    /**
     * @var string the default layout for the controller view. Defaults to '//layouts/column1',
     * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
     */
    public $layout = '//layouts/coluna1';
    public $titulo = 'Inicio';
    public $subtitulo = '';
    public $path_theme;
    public $sistemas;
    public $url_redirecionamento;
    public $session_name;

    /**
     * 
     */
    public function init() {
        parent::init();
        $this->session_name = 'is_logado_' . Yii::app()->params['id_aplicacao'];
        $this->url_redirecionamento = Yii::app()->request->baseUrl . '/site/login';
        if (Yii::app()->session->itemAt('controller') && !Yii::app()->session->itemAt($this->session_name) && Yii::app()->getRequest()->getIsAjaxRequest()) {
            Yii::app()->session->add($this->session_name, false);
            echo '<script>alert("A sessão expirou!!!"); window.location.href = "' . $this->url_redirecionamento . '";</script>';
            return false;
        }
        $this->path_theme = Yii::app()->request->baseUrl . '/themes/' . Yii::app()->params['tema'];

        $username = null;
        $password = null;
        $identity = new UserIdentity($username, $password);

        Yii::app()->session->add('controller', $this->id);
        if (!Yii::app()->session->itemAt($this->session_name)) {
            if ($identity->authenticate()) {
                Yii::app()->user->login($identity);
                Yii::app()->session->add($this->session_name, true);
            } else {
                Yii::app()->session->destroy();
                Yii::app()->session->add($this->session_name, false);
                echo '<script>alert("A sessão expirou!!!"); window.location.href = "' . $this->url_redirecionamento . '";</script>';
                return false;
            }
        }
        /* Monta menu */
        $menu = new Menu();
        $this->menu = $menu->gerarMenu();

        /* Monta a lista de sistemas */
        $this->getSistemas();
    }

    /**
     * Lista de sistemas
     */
    public function getSistemas() {
        $sql = "SELECT s.nome, s.url
                  FROM vw_usuario u
                       INNER JOIN vw_usuario_perfil up ON u.id_usuario = up.usuario_fk
                       INNER join vw_perfil p ON p.id = up.perfil_fk
                       INNER join tb_sistema s ON p.sistema_fk = s.id
                 WHERE u.login like '" . Yii::app()->user->login . "' 
                 ORDER By 1 ASC";
        $this->sistemas = Yii::app()->db->createCommand($sql)->queryAll();
    }

    /**
     */
    public function filterControleAcesso($filterChain) {
        $filter = new UsuarioFilter;
        $filter->allowedActions = $this->allowedActions();
        $filter->filter($filterChain);
    }

    /**
     * 
     */
    public function accessDenied() {

        $user = Yii::app()->getUser();
        if ($user->isGuest === true) {
            $this->redirect(array('site/acessoNegado'));
            // throw new CHttpException(403, $message);
        } else {
            Yii::app()->session->destroy();
            Yii::app()->session->add($this->session_name, 'false');
            $this->redirect($this->url_redirecionamento);
        }
    }

    public function filters() {
        return array(
            'controleAcesso',
//            array(
//                'application.filters.FiltroLog',
//            ),
        );
    }

    /**
     * @return string the actions that are always allowed separated by commas.
     */
    //PORTAL_SPB
    public function allowedActions() {
        return 'index, view, viewItem, acessoNegado, error, logout, teste, server';
    }

}
