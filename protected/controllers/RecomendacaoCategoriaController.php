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

class RecomendacaoCategoriaController extends GxController {

	public $titulo = 'Gerenciar Categoria da Recomendação';

	public function init() {
		if (!Yii::app()->user->verificaPermissao("RecomendacaoCategoria", "admin")) {
			$this->redirect(array('site/acessoNegado'));
			exit;
		}

		parent::init();
		$this->defaultAction = 'index';

		$this->menu_acao = array(
		array('label' => 'Consultar', 'url' => array('RecomendacaoCategoria/index')),
		array('label' => 'Incluir', 'url' => array('RecomendacaoCategoria/admin')),
		);

	}

	public function actionAdmin($id = 0) {

		if (!empty($id)) {
			$this->subtitulo = 'Atualizar';
			$model = $this->loadModel($id, 'RecomendacaoCategoria');
		} else {
			$this->subtitulo = 'Inserir';
			$model = new RecomendacaoCategoria;
		}

		if (isset($_POST['RecomendacaoCategoria'])) {
			$model->attributes = $_POST['RecomendacaoCategoria'];

			if ($model->save()) {
				$this->setFlashSuccesso(($id > 0 ? 'alterar' : 'inserir'));
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
			$dados_subcategoria = RecomendacaoSubcategoria::model()->findAll('recomendacao_categoria_fk=' . $id);
			 
			if (!sizeof($dados_subcategoria)){
				$this->loadModel($id, 'RecomendacaoCategoria')->delete();

				if (Yii::app()->getRequest()->getIsAjaxRequest()) {
					$this->setFlashSuccesso('excluir');
					echo $this->getMsgSucessoHtml();
				} else {
					$this->setFlashError('excluir');
					$this->redirect(array('admin'));
				}
			} else {
				$this->setFlash('erro','A categoria não pode ser deletada, pois a mesma esta vinculada a uma subcategoria.');
				echo $this->getMsgErroHtml();
			}
		}
		else
		throw new CHttpException(400, Yii::t('app', 'Sua requisição é inválida.'));
	}

	public function actionIndex() {
		$this->subtitulo = 'Consultar';
		$dados = null;
		$model = new RecomendacaoCategoria('search');

		$model->unsetAttributes();  // clear any default values
		if (isset($_GET['RecomendacaoCategoria'])) {
			$model->attributes = $_GET['RecomendacaoCategoria'];
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
            'model' => $this->loadModel($id, 'RecomendacaoCategoria'),
		));
	}

}
