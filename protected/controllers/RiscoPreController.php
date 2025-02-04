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

class RiscoPreController extends GxController {

    public   $titulo = 'Riscos Pré-Identificados';
    
    public function init() {
        if (!Yii::app()->user->verificaPermissao("RiscoPre", "admin")) {
            $this->redirect(array('site/acessoNegado')); 
            exit;
        }
        
        parent::init();
        $this->defaultAction = 'index';

        $this->menu_acao = array(
        	array('label' => 'Consultar', 'url' => array('RiscoPre/index')),
            array('label' => 'Incluir', 'url' => array('RiscoPre/admin')),
        );
    }       

    public function actionAdmin($id = 0) {
        
    	if (!empty($id)) {
            $this->subtitulo = 'Atualizar';
            $model = $this->loadModel($id , 'RiscoPre');
        } else {
            $this->subtitulo = 'Inserir';
            $model = new RiscoPre;
        }
        
        if (isset($_POST['RiscoPre'])) {
        	if (sizeof($_POST['RiscoPre']['processo_riscopre']) == 1){
        		if (!$_POST['RiscoPre']['processo_riscopre'][0]){
        			unset($_POST['RiscoPre']['processo_riscopre'][0]);
        		}
        	}
        	
            $model->attributes = $_POST['RiscoPre'];
          	
            if (isset($_POST['RiscoPre']['acao_riscopre'])){
            		if (sizeof($_POST['RiscoPre']['acao_riscopre']) == 1){
            			if (!$_POST['RiscoPre']['acao_riscopre'][0]){
	            			$model->addError("acao_riscopre", "A ação deve ser informada.");
	            			$this->render('admin', array(
	            					'model' => $model,
	            					'titulo' => $this->titulo,
	            			));
	            			exit;
            			}
            		}
            }
          	

                        
          	if ($model->save()) {
          			$model_processo_riscopre = new ProcessoRiscoPre();
          			$model_processo_riscopre->salvarProcessoRiscoPre($model->id, $_POST['RiscoPre']['processo_riscopre'], $_POST['RiscoPre']['valor_exercicio']);
                    $this->setFlashSuccesso( ($id > 0 ? 'alterar' : 'inserir') );
                    $this->redirect(array('index', 'id' => $model->id));
            }
        }

        $this->render('admin', array(
            'model' => $model,
            'titulo' => $this->titulo,
        ));
    }

    public function actionDelete($id) {
        $this->layout = false;
        if (Yii::app()->getRequest()->getIsPostRequest()) {
        		$model_processo_risco_pre = new ProcessoRiscoPre();
        		if (!$model_processo_risco_pre->verificaSePodeDeletarRisco($id)){
        			$model_processo_risco_pre->deleteAll("risco_pre_fk=" . $id);
	            	$this->loadModel($id, 'RiscoPre')->delete();
		            if (Yii::app()->getRequest()->getIsAjaxRequest()) {
		                $this->setFlashSuccesso('excluir');
		                echo  $this->getMsgSucessoHtml();
		            } else {
		                $this->setFlashError('excluir');
		                $this->redirect(array('admin'));
		            }
        		}else {
        			$this->setFlash('erro', 'Risco Pré-identificado não pode ser excluído, pois está sendo utilizado em uma Ação por meio de um Processo.');
        			echo  $this->getMsgErroHtml();
        		}
        } else
            throw new CHttpException(400, Yii::t('app', 'Sua requisição é inválida.'));
    }

    public function actionIndex() {
        $this->subtitulo = 'Consultar';
        $dados = null;
        $model = new RiscoPre('search');

        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['RiscoPre'])) {
            $model->attributes = $_GET['RiscoPre'];
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
                'model' => $this->loadModel($id, 'RiscoPre'),
        ));
    }
}
