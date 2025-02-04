<?php
/********************************************************************************
*  Copyright 2015 Conab - Companhia Nacional de Abastecimento                   *
*                                                                               *
*  Este arquivo � parte do Sistema SIAUDI.                                      *
*                                                                               *
*  SIAUDI  � um software livre; voc� pode redistribui-lo e/ou                   *
*  modific�-lo sob os termos da Licen�a P�blica Geral GNU conforme              *
*  publicada pela Free Software Foundation; tanto a vers�o 2 da                 *
*  Licen�a, como (a seu crit�rio) qualquer vers�o posterior.                    *
*                                                                               *
*  SIAUDI � distribu�do na expectativa de que seja �til,                        *
*  por�m, SEM NENHUMA GARANTIA; nem mesmo a garantia impl�cita                  *
*  de COMERCIABILIDADE OU ADEQUA��O A UMA FINALIDADE ESPEC�FICA.                *
*  Consulte a Licen�a P�blica Geral do GNU para mais detalhes em portugu�s:     *
*  http://creativecommons.org/licenses/GPL/2.0/legalcode.pt                     *
*                                                                               *
*  Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral do GNU             *
*  junto com este programa; se n�o, escreva para a Free Software                *
*  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA    *
*                                                                               *
*  Sistema   : SIAUDI - Sistema de Auditoria Interna                            *
*  Data      : 05/2015                                                          *
*                                                                               *
********************************************************************************/
?>
<?php

class SiteController extends GxController {

    public function actionIndex() {
    	$this->titulo = "Início";
        // salva o acesso do usu�rio ao sistema, 
        // na tabela de logs, uma vez por sess�o.
        if(Yii::app()->user->id){
            if (!$_SESSION['gravar_log_entrada']) {
                LogEntrada::model()->SalvaLog();
                $_SESSION['gravar_log_entrada'] = 1;
            }
        }
                    $this->layout = '//layouts/coluna1';
                    $this->render('index');
        
    }

    public function actionAcessoNegado() {
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
//      ($_SERVER['SERVER_NAME']
    }
    
    /**
     * Login the current user and redirect to homepage.
     */
    public function actionLogin() {
        //$this->render('index');
            $this->layout = 'login';
            $this->titulo = 'Acesso ao sistema';
            $model = new LoginForm();
            $model->setScenario('cenario_login');

            // if it is ajax validation request
            if (isset($_POST['ajax']) && $_POST['ajax'] === 'login-form') {
                echo CActiveForm::validate($model);
                Yii::app()->end();
            }

            // collect user input data
            if (isset($_POST['LoginForm'])) {
                $model->attributes = $_POST['LoginForm'];
                // validate user input and redirect to the previous page if valid
                if ($model->validate() && $model->login()) {
                    //Retorna para a p�gina anterior que se desejava acessar
                    $this->redirect(Yii::app()->user->returnUrl);
                    //Yii::app()->request->redirect(Yii::app()->user->returnUrl);
                    return true;
                }
            }
            // display the login form
            $this->render('index_login', array('model' => $model));
            //FIM
    }

    
    /**
     * Logs out the current user and redirect to homepage.
     */
    public function actionVerificaSessao() {
        var_dump(Yii::app()->getUser()->isGuest);
    }

    /**
     * Funcionalidade para permitir o usu�rio alterar a pr�pria senha
     * @param type $id
     * @param type $cenario
     */
    public function actionAlterarMinhaSenhaAjax($id = 0, $cenario = '') {
        if (!empty($id)) {
            $this->subtitulo = 'Atualizar';
            $model = $this->loadModel($id, 'Usuario');
            if($cenario == 'p'){
                $model->scenario = 'alteraMinhaSenha';
            }
            $model->senha = '';
            $model->afterFind = false;
        }
        
        if (isset($_POST['Usuario'])) {
                $model->attributes = $_POST['Usuario'];
                if ($model->save()) {
                    $this->setFlashSuccesso(($id > 0 ? 'alterar' : 'inserir'));
                    $this->redirect(array('index?' . $_SERVER['QUERY_STRING']));
                } 
        }

        $this->render('adminMinhaSenha', array(
            'model' => $model,
            'titulo' => $this->titulo,
            'msgInconsistencia' => $msgInconsistencia
        ));
    }
    
    
}
