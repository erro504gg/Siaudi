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

class CapituloController extends GxController {

    public $titulo = 'Gerenciar Capítulo';

    public function init() {
        if (!Yii::app()->user->verificaPermissao("Capitulo", "admin") && (!Yii::app()->request->isAjaxRequest)) {
            $this->redirect(array('site/acessoNegado'));
            exit;
        }

        parent::init();
        $this->defaultAction = 'index';

        $this->menu_acao = array(
        	array('label' => 'Consultar', 'url' => array('capitulo/index')),
            array('label' => 'Incluir', 'url' => array('capitulo/admin')),
        );
    }

    public function actionAdmin($id = 0) {

        if (!empty($id)) {
            $this->subtitulo = 'Atualizar';
            $model = $this->loadModel($id, 'Capitulo');
        } else {
            $this->subtitulo = 'Inserir';
            $model = new Capitulo;
            $model->data_gravacao = date("Y-m-d");
        }

        if (isset($_POST['Capitulo'])) {
            $model->attributes = $_POST['Capitulo'];
            $model->relatorio_fk = $_POST['relatorio_fk'];
            $model->numero_capitulo = strtoupper($model->numero_capitulo);
            $model->nome_capitulo = strtoupper($model->nome_capitulo);
            $model->numero_capitulo_decimal = MyFormatter::roman2number($model->numero_capitulo);
            

            if ($model->save()) {
                if ($_POST['inserir_capitulo']) {
                    $this->setFlashSuccesso(($id > 0 ? 'alterar' : 'inserir'));
                    $baseUrl = Yii::app()->baseUrl;
                    header("Location: " . $baseUrl . "/capitulo/admin?relatorio_fk=" . $model->relatorio_fk);
                    exit;
                }

                if ($_POST['inserir_item']) {
                    $this->setFlashSuccesso(($id > 0 ? 'alterar' : 'inserir'));
                    $baseUrl = Yii::app()->baseUrl;
                    header("Location: " . $baseUrl . "/item/admin?capitulo_fk=" . $model->id);
                    exit;
                }
                $this->setFlashSuccesso(($id > 0 ? 'alterar' : 'inserir'));
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
        	try{
	            // verifica se relatório foi homologado
	            // e neste caso impede a exclusão
	            $capitulo = Capitulo::model()->findByAttributes(array('id' => $id));
	            $relatorio = Relatorio::model()->findByAttributes(array('id' => $capitulo->relatorio_fk));
	            if ($relatorio->data_relatorio) {
	                echo "<script language='Javascript'>alert('Não é possível excluir este capítulo, pois seu\\nrelatório já foi homologado.');</script>";
	            } else {
	                $this->loadModel($id, 'Capitulo')->delete();
	
	                if (Yii::app()->getRequest()->getIsAjaxRequest()) {
	                    $this->setFlashSuccesso('excluir');
	                    echo $this->getMsgSucessoHtml();
	                } else {
	                    $this->setFlashError('excluir');
	                    $this->redirect(array('admin'));
	                }
	            }
        	}catch(Exception $e){
        		if ($e->errorInfo[0] == 23503){
                    $this->setFlash('erro', 'Capítulo não pode ser excluído, pois o mesmo possui itens vinculados a ele.');
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
        $model = new Capitulo('search');

        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Capitulo'])) {
            $model->attributes = $_GET['Capitulo'];
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
            'model' => $this->loadModel($id, 'Capitulo'),
        ));
    }

    // recebe o ID do relatório e carrega seus capítulos 
    public function actionCarregaCapituloAjax() {
        $data = Capitulo::model()->findAll('relatorio_fk=:relatorio_fk ORDER BY numero_capitulo_decimal', array(':relatorio_fk' => (int) $_POST['relatorio_fk']));

        $data = CHtml::listData($data, 'id', 'numero_capitulo');

        if (sizeof($data) > 0) {
            echo CHtml::tag('option', array('value' => ''), CHtml::encode('Selecione'), true);
        } else {
            echo CHtml::tag('option', array('value' => ''), CHtml::encode('Sem capítulos'), true);
        }
        foreach ($data as $value => $name) {
            echo CHtml::tag('option', array('value' => $value), CHtml::encode($name), true);
        }
    }
    
    
    // verifica se o número do capítulo já foi cadastrado
    // no mesmo relatório
    public function actionVerificaNumeroCapituloAjax() {
        $numero_capitulo = strtoupper($_POST["numero_capitulo"]);
        $id = (int)$_POST["capitulo_id"];
        $data = Capitulo::model()->findAll('relatorio_fk=:relatorio_fk and numero_capitulo=:numero_capitulo and id!=:id', array(':relatorio_fk' => (int) $_POST['relatorio_fk'],':numero_capitulo'=>$numero_capitulo,':id'=>$id));
        if (sizeof($data)>0){
            echo "O número deste capítulo já foi cadastrado neste relatório. Escolha outro número.";
        }
    }    
}
