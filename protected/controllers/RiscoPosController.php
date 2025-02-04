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

class RiscoPosController extends GxController {

    public $titulo = 'Risco Pós Identificado';

    public function init() {
        if (!Yii::app()->user->verificaPermissao("RiscoPos", "admin")) {
            $this->redirect(array('site/acessoNegado')); 
            exit;
        }
        
        parent::init();
        $this->menu_acao = array(
            array('label' => 'Consultar', 'url' => array('RiscoPos/index')),
            array('label' => 'Incluir', 'url' => array('RiscoPos/admin')),
        );
    }

    public function actionAdmin($id = 0) {

        if (!empty($id)) {
            $this->subtitulo = 'Atualizar';
            $model = $this->loadModel($id, 'RiscoPos');
        } else {
            $this->subtitulo = 'Inserir';
            $model = new RiscoPos;
        }
        
        if (isset($_POST['RiscoPos'])) {
            $model->attributes = $_POST['RiscoPos'];

            if ($model->save()) {
                $this->setFlashSuccesso(($id > 0 ? 'alterar' : 'inserir'));
                $this->redirect(array('index', 'id' => $model->id));
            }
        }

        $this->render('admin', array(
            'model' => $model,
            'titulo' => $this->titulo
        ));
    }

    public function actionAdminModalAjax() {
        $this->layout = false;
        $this->subtitulo = 'Inserir';
        $model = new RiscoPos;
        $this->render('admin_modal', array(
            'model' => $model,
            'titulo' => $this->titulo
        ));
    }
    
    public function actionSalvarDadosModalAjax() {
         $model = new RiscoPos;
         
         $nome_risco = utf8_decode($_POST['nome_risco']);
         $descricao_impacto = utf8_decode($_POST['descricao_impacto']);
         $descricao_mitigacao = utf8_decode($_POST['descricao_mitigacao']);
                  
         $model->nome_risco = $nome_risco;
         $model->descricao_impacto = $descricao_impacto;
         $model->descricao_mitigacao = $descricao_mitigacao;
         
         if($model->save()){
             echo CHtml::tag('option', array('value' => $model->id), CHtml::encode($model->nome_risco), true);
         }
    }

    public function actionDelete($id) {
        $this->layout = false;
        if (Yii::app()->getRequest()->getIsPostRequest()) {
        	try{
	            $this->loadModel($id, 'RiscoPos')->delete();
	
	            if (Yii::app()->getRequest()->getIsAjaxRequest()) {
	                $this->setFlashSuccesso('excluir');
	                echo $this->getMsgSucessoHtml();
	            } else {
	                $this->setFlashError('excluir');
	                $this->redirect(array('admin'));
	            }
        	}catch(Exception $e){
        		if ($e->errorInfo[0] == 23503){
                    $this->setFlash('erro', 'Risco Pós Identificado não pode ser excluído, pois o mesmo esta associado a um relatório.');
                	echo  $this->getMsgErroHtml();
        		}
        	}
        }
        else
            throw new CHttpException(400, Yii::t('app', 'Sua requisição é inválida.'));
    }

    public function actionIndex() {
        $this->subtitulo = 'Consultar';
        $dados = null;
        $model = new RiscoPos('search');

        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['RiscoPos'])) {
            $model->attributes = $_GET['RiscoPos'];
            $dados = $model->search();
        }

        $this->render('index', array(
            'model' => $model,
            'dados' => $dados,
            'titulo' => $this->titulo,
        ));
    }

    public function actionView($id) {
        $this->layout = false;
        $this->render('view', array(
            'model' => $this->loadModel($id, 'RiscoPos'),
        ));
    }
    
    public function actionImportaRiscoPreAjax($id) {
        if ($id != 0) {
            $riscopre= RiscoPre::model()->findByPk($id);
            echo CJSON::encode($riscopre);
        }
        Yii::app()->end();
    }

}
