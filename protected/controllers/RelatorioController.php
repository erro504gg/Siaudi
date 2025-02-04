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

class RelatorioController extends GxController {

    public $titulo = 'Gerenciar Relatório';

    public function init() {
        parent::init();
        $this->defaultAction = 'index';
        if (!Yii::app()->user->verificaPermissao("relatorio", "admin") && (!Yii::app()->request->isAjaxRequest)) {
            $this->redirect(array('site/acessoNegado'));
            exit;
        }
//        Yii::app()->session['dataTree'] = null;
        $this->menu_acao = array(
        	array('label' => 'Consultar', 'url' => array('Relatorio/index')),
            array('label' => 'Incluir', 'url' => array('Relatorio/admin')),
        );
        

    }

    public function actionAdmin($id = 0) {
    	$bolEncontrouErro = false;
        $model_relatorio_diretoria = new RelatorioDiretoria();
        $model_relatorio_sureg = new RelatorioSureg();
        $model_relatorio_auditor = new RelatorioAuditor();
        $model_relatorio_gerente = new RelatorioGerente();
        $model_relatorio_riscopos = new RelatorioRiscoPos();
        $model_relatorio_area = new RelatorioArea();
        $model_relatorio_setor = new RelatorioSetor();
        if (!empty($id)) {
            $this->subtitulo = 'Atualizar';
            $model = $this->loadModel($id, 'Relatorio');
        } else {
            $this->subtitulo = 'Inserir';
            $model = new Relatorio;
            $model->login_relatorio = Yii::app()->user->login;
            $model->data_gravacao = date("Y-m-d");
        }

        if (isset($_POST['Relatorio'])) {
            $model->attributes = $_POST['Relatorio'];

            if (isset($_POST['RelatorioDiretoria']['diretoria_fk'])) $model->diretoria_fk=0;
            if (isset($_POST['RelatorioSureg']['unidade_administrativa_fk'])) $model->unidade_administrativa_fk=0;
            if (isset($_POST['RelatorioAuditor']['auditor_fk'])) $model->auditor_fk=0;
            if (isset($_POST['RelatorioGerente']['gerente_fk'])) $model->gerente_fk=0;
            if (isset($_POST['Relatorio']['relatorio_riscopos'])) $model->relatorio_riscopos=0;            
            
            //plan_especifico_fk não é obrigatório para relatório Extraordinário.
            if (($model->categoria_fk != 2)&&($model->plan_especifico_fk == '')){ 
            	$model->addError("plan_especifico_fk", "O planejamento específico é obrigatório.");
            	$bolEncontrouErro = true;
            }
            
            if (is_array($_POST['RelatorioSureg']['unidade_administrativa_fk']) && is_array($_POST['RelatorioSureg']['sureg_secundaria'])){
                if (sizeof(array_intersect($_POST['RelatorioSureg']['unidade_administrativa_fk'], $_POST['RelatorioSureg']['sureg_secundaria']))){
                    $model->addError("sureg_secundaria", "Unidade Auditada e Unidade Regional Secundária não podem ter valores em comum.");
                    $bolEncontrouErro = true;
                }
            }
            
            if (!$bolEncontrouErro){
	            if ($model->save()) {
	                $model_relatorio_riscopos->salvar($model->id, $_POST['Relatorio']['relatorio_riscopos']);
	                $model_relatorio_sureg->salvar($model->id, $_POST['RelatorioSureg']['unidade_administrativa_fk']);
	                $model_relatorio_sureg->salvar($model->id, $_POST['RelatorioSureg']['sureg_secundaria'], true);
	                $model_relatorio_diretoria->salvar($model->id, $_POST['RelatorioDiretoria']['diretoria_fk']);
	                $model_relatorio_auditor->salvar($model->id, $_POST['RelatorioAuditor']['auditor_fk']);
	                $model_relatorio_gerente->salvar($model->id, $_POST['RelatorioGerente']['gerente_fk']);
	                $model_relatorio_area->salvar($model->id, $_POST['RelatorioArea']['unidade_administrativa_fk']);
	                $model_relatorio_setor->salvar($model->id, $_POST['RelatorioSetor']['unidade_administrativa_fk']);
	
	                //se usuário deseja adicionar um capítulo
	                // após inserir o relatório, então redireciona
	                if ($_POST['inserir_capitulo']) {
	                    $this->setFlashSuccesso(($id > 0 ? 'alterar' : 'inserir'));
	                    $baseUrl = Yii::app()->baseUrl;
	                    header("Location: " . $baseUrl . "/capitulo/admin?relatorio_fk=" . $model->id);
	                } else {
	                    $this->setFlashSuccesso(($id > 0 ? 'alterar' : 'inserir'));
	                    $this->redirect(array('index', 'id' => $model->id));
	                }
	            }
            }
        }

        $relatorio_diretoria = RelatorioDiretoria::model()->findAllByAttributes(array('relatorio_fk' => $id));
        $relatorio_sureg = RelatorioSureg::model()->findAllByAttributes(array('relatorio_fk' => $id));
        $relatorio_auditor = RelatorioAuditor::model()->findAllByAttributes(array('relatorio_fk' => $id));
        $relatorio_gerente = RelatorioGerente::model()->findAllByAttributes(array('relatorio_fk' => $id));
        $relatorio_area = $model_relatorio_area->buscar($model->id);
        $relatorio_setor = $model_relatorio_setor->buscar($model->id);
        $this->render('admin', array(
            'model_relatorio_gerente' => $model_relatorio_gerente,
            'model_relatorio_area' => $model_relatorio_area,
            'model_relatorio_setor' => $model_relatorio_setor,
            'model_relatorio_auditor' => $model_relatorio_auditor,
            'model_relatorio_sureg' => $model_relatorio_sureg,
            'model_relatorio_diretoria' => $model_relatorio_diretoria,
            'relatorio_gerente' => $relatorio_gerente,
            'relatorio_auditor' => $relatorio_auditor,
            'relatorio_diretoria' => $relatorio_diretoria,
            'relatorio_sureg' => $relatorio_sureg,
            'relatorio_area' => $relatorio_area,
            'relatorio_setor' => $relatorio_setor,
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
	            $relatorio = Relatorio::model()->findByAttributes(array('id' => $id));
	            if ($relatorio->data_relatorio) {
	                echo "<script language='Javascript'>alert('Não é possível excluir este relatório \\npois ele já foi homologado.');</script>";
	            } else {
	                $relatorio_diretoria = RelatorioDiretoria::model()->deleteAll("relatorio_fk=" . $id);
	                $relatorio_sureg = RelatorioSureg::model()->deleteAll("relatorio_fk=" . $id);
	                $relatorio_auditor = RelatorioAuditor::model()->deleteAll("relatorio_fk=" . $id);
	                $relatorio_gerente = RelatorioGerente::model()->deleteAll("relatorio_fk=" . $id);
	                $this->loadModel($id, 'Relatorio')->delete();
	
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
                    $this->setFlash('erro', 'Relatório não pode ser excluído, pois o mesmo possui capítulos vinculados a ele.');
                	echo  $this->getMsgErroHtml();
        		}
        	}
        } else {
            throw new CHttpException(400, Yii::t('app', 'Sua requisição é inválida.'));
        }
    }

    public function CarregaFilhos($unidadeAdministrativa_por_subordiante, $subordinantes) {
        if (sizeof($subordinantes)) {
            foreach ($subordinantes as $id => $filho) {
                $dadosFilhos[$id] = array('text' => CHtml::checkBox($id, false, array('onclick'=>'AdicionaElemento(this);')) . '<label for="' . $id . '">' . $filho . "</label>",
                                          'id' =>"campo_" . $id,
                                          'children' => $this->CarregaFilhos($unidadeAdministrativa_por_subordiante, $unidadeAdministrativa_por_subordiante[$id]),
                                          'hasChildren' => ($unidadeAdministrativa_por_subordiante[$id] ? true : false));
            }
        }
        return $dadosFilhos;
    }
    
    public function actionAbrirModalAreaAjax() {
        $this->layout = false;
        $this->render('modalArea');
    }
    public function actionAbrirModalSetorAjax() {
        $this->layout = false;
        $this->render('modalSetor');
    }
    
    public function actionAreaModalAjax() {
        $model = new Relatorio();
        $this->layout = false;
        if (!Yii::app()->session['dataTree']){
            $unidadeAdministrativa_por_subordiante = CHtml::listData(UnidadeAdministrativa::model()->findAll(), 'id', 'sigla', 'subordinante_fk');
            //carrega as unidades pai
            foreach ($unidadeAdministrativa_por_subordiante[''] as $id => $sigla) {
                $filhos = $this->CarregaFilhos($unidadeAdministrativa_por_subordiante, $unidadeAdministrativa_por_subordiante[$id]);
                $dados = array('text' => CHtml::checkBox($id, false, array('onclick'=>'AdicionaElemento(this);')) . '<label for="' . $id . '">' . $sigla . "</label>",
                               'id' =>"campo_" . $id,
                               'marcado' => false,
                               'children' => $filhos,
                               'class' => 'hasChildren expandable',
                );
                $dataTree[$id] = $dados;
            }
            Yii::app()->session['dataTree'] = $dataTree;
        } else{
            $dataTree = Yii::app()->session['dataTree'];
        }
        
        $this->render('area', array(
            'model' => $model,
            'dataTree' => $dataTree,
        ));
        
    }
    
    public function actionAjaxFillTree() {
        if (!Yii::app()->request->isAjaxRequest) {
            exit();
        }
        $parent_id = NULL;
        if (isset($_GET['root']) && $_GET['root'] !== 'source') {
            $parent_id = (int) $_GET['root'];
        }
        $criteria = new CDbCriteria();
        if ($parent_id){
            $criteria->addCondition("subordinante_fk = $parent_id");
            $unidadeAdministrativa_por_subordiante = CHtml::listData(UnidadeAdministrativa::model()->findAll($criteria), 'id', 'sigla', 'subordinante_fk');
            $dados = $unidadeAdministrativa_por_subordiante[$parent_id];
        } else{
            $unidadeAdministrativa_por_subordiante = CHtml::listData(UnidadeAdministrativa::model()->findAll($criteria), 'id', 'sigla', 'subordinante_fk');
            $dados = $unidadeAdministrativa_por_subordiante[''];
        }
        //carrega as unidades pai
        foreach ($dados as $id => $sigla) {
            //$filhos = $this->CarregaFilhos($unidadeAdministrativa_por_subordiante, $unidadeAdministrativa_por_subordiante[$id]);
            $dados = array('text' => CHtml::checkBox($id, false, array('onclick'=>'AdicionaElemento(this);')) . $sigla,
                           'id' =>$id,
                           'marcado' => false,
                           //'children' => $filhos,
                           'hasChildren' => ($unidadeAdministrativa_por_subordiante[$id] ? true : false),
            );
            $dataTree[$id] = $dados;
        }
        echo str_replace(
                '"hasChildren":"0"', '"hasChildren":false', CTreeView::saveDataAsJson($dataTree)
        );
        exit();
    }
    
    public function actionSetorModalAjax() {
        $model = new Relatorio();
        $this->layout = false;
        $unidadeAdministrativa_por_subordiante = CHtml::listData(UnidadeAdministrativa::model()->findAll(), 'id', 'sigla', 'subordinante_fk');
        //carrega as unidades pai
        foreach ($unidadeAdministrativa_por_subordiante[''] as $id => $sigla) {
            $dados = array('text' => CHtml::checkBox($id, false, array('onclick'=>'AdicionaElemento(this);')) . '<label for="' . $id . '">' . $sigla . "</label>",
                           'id' =>"campo_" . $id,
                           'marcado' => false,
                           'children' => $this->CarregaFilhos($unidadeAdministrativa_por_subordiante, $unidadeAdministrativa_por_subordiante[$id])
            );
            $dataTree[$id] = $dados;
        }        
        
        $this->renderPartial('setor', array(
            'model' => $model,
            'dataTree' => $dataTree,
        ), false, true);
        
    }      

    public function actionIndex() {


        $this->subtitulo = 'Consultar';
        $dados = null;
        $model = new Relatorio('search');

        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Relatorio'])) {
//        	debug($_GET['Relatorio']);
            $model->attributes = $_GET['Relatorio'];
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
            'model' => $this->loadModel($id, 'Relatorio'),
        ));
    }

    public function actionProrrogarPrazoAjax() {
    	$this->menu_acao = array();
        $this->titulo = 'Prorrogar prazo de respostas das recomendações';
        if ($_POST) {
            $Relatorio_prorrogar = Relatorio::model()->ProrrogarRelatorioHomologado($_POST['Relatorio']['id'], $_POST['Relatorio']['dias_uteis']);
            $this->setFlashSuccesso('alterar');
        }

        $model = new Relatorio();
        $this->render('prorrogar_prazo', array(
            'model' => $model,
        ));
    }

    public function actionHomologarAjax() {
    	$this->menu_acao = array();
        $this->titulo = 'Homologar Relatório de Auditoria';
        if ($_POST) {
            $Relatorio_prorrogar = Relatorio::model()->HomologarRelatorio($_POST['Relatorio']['id']);
            // $this->setFlashSuccesso('alterar');
        }

        $model = new Relatorio();
        $this->render('homologar', array(
            'model' => $model,
        ));
    }

    public function actionReiniciarContagemAjax() {
    	$this->menu_acao = array();
        if (!Yii::app()->user->verificaPermissao("ReiniciarContagem", "admin")) {
            $this->redirect(array('site/acessoNegado'));
            exit;
        }
        $this->titulo = 'Reiniciar a contagem dos itens do Relatório';
        if ($_POST[reiniciar]) {
            $Relatorio_reiniciar = RelatorioReiniciar::model()->ReiniciarContagem();
            // $this->setFlashSuccesso('alterar');
        }

        $model = new Relatorio();
        $this->render('reiniciar_contagem', array(
            'model' => $model,
        ));
    }

}
