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

class FuncaoController extends GxController {

    public   $titulo = 'Função';

  public function init() {
        parent::init();
        $this->menu_acao = array(
        	array('label' => 'Incluir', 'url' => array('Funcao/admin')),
            array('label' => 'Consultar', 'url' => array('Funcao/index')),
        );
    }

    public function actionAdmin($id = 0) {

        if (!empty($id)) {
            $this->subtitulo = 'Atualizar';
            $model = $this->loadModel($id , 'Funcao');
        } else {
            $this->subtitulo = 'Inserir';
            $model = new Funcao;
        }
        
        if (isset($_POST['Funcao'])) {
            $model->attributes = $_POST['Funcao'];
          
          		if ($model->save()) {
                              $this->setFlashSuccesso( ($id > 0 ? 'alterar' : 'inserir') );
                    $this->redirect(array('index?' . $_SERVER['QUERY_STRING']));
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
        	try{
	            $this->loadModel($id, 'Funcao')->delete();
	
	             if (Yii::app()->getRequest()->getIsAjaxRequest()) {
	                $this->setFlashSuccesso('excluir');
	                echo  $this->getMsgSucessoHtml();
	            } else {
	                $this->setFlashError('excluir');
	                $this->redirect(array('admin'));
	            }
        	}catch(Exception $e){
        		if ($e->errorInfo[0] == 23503){
                    $this->setFlash('erro', 'Função não pode ser excluída, pois a mesma está associada a um usuário.');
                	echo  $this->getMsgErroHtml();
        		}
        	}
            
        } else
            throw new CHttpException(400, Yii::t('app', 'Sua requisição é inválida.'));
    }

    public function actionIndex() {
        $this->subtitulo = 'Consultar';
        $dados = null;
        $model = new Funcao('search');

        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Funcao'])) {
            $model->attributes = $_GET['Funcao'];
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
                'model' => $this->loadModel($id, 'Funcao'),
        ));
    }
}
