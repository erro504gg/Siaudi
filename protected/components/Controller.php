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
        //Retorna o id do usuário
            parent::init();
            $this->session_name = 'is_logado_' . Yii::app()->params['id_aplicacao'];

//            $this->url_redirecionamento = ;
//            if (Yii::app()->session->itemAt('controller') && !Yii::app()->session->itemAt($this->session_name) && Yii::app()->getRequest()->getIsAjaxRequest()) {
//                Yii::app()->session->add($this->session_name, false);
//                echo '<script>alert("A sessão expirou!!!");window.location.href = "' . $this->url_redirecionamento . '";</script>';
//                return false;
//            }
//            $this->path_theme = Yii::app()->request->baseUrl . '/themes/' . Yii::app()->params['tema'];
//
//            $username = null;
//            $password = null;
//            $identity = new UserIdentity($username, $password);
//
//
//            Yii::app()->session->add('controller', $this->id);
//            if (!Yii::app()->session->itemAt($this->session_name)) {
//                if ($identity->authenticate()) {
//                    Yii::app()->user->login($identity);
//                    Yii::app()->session->add($this->session_name, true);
//                } else {
//                    Yii::app()->session->destroy();
//                    Yii::app()->session->add($this->session_name, false);
//                    echo '<script>alert("A sessão expirou!!!"); window.location.href = "' . $this->url_redirecionamento . '";</script>';
//                    return false;
//                }
//            }
            
                    /* Controle do tempo de sessão */
                    if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > Yii::app()->params['session_time'])) {
                        Yii::app()->session->destroy();                    
                    }
                    $_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp
                    
                    
                    if(!Yii::app()->user->id && !strpos(Yii::app()->request->requestUri,'site/login')){
                        $this->redirect(array('site/login'));
                    } 
            /* Monta menu */
            if(Yii::app()->user->id){
                $menu = new Menu();
                $this->menu = $menu->gerarMenu();
            }else{
                $this->menu = "";
            }

            /* Monta a lista de sistemas */
            //$this->getSistemas();
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
        /*if(Yii::app()->user->id)*/{
            $filter = new UsuarioFilter;
            $filter->allowedActions = $this->allowedActions();
            $filter->filter($filterChain);
        }
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
            $this->redirect(array('site/acessoNegado'));
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
        return 'rotinasCron/index, index, view, viewItem, acessoNegado, error, logout, teste, server';
    }

   
}
