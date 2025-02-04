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

class SubriscoController extends GxController {

    public   $titulo = 'Tabela de Sub-riscos';

    
    public function init() {
        if (!Yii::app()->user->verificaPermissao("Subrisco", "admin")) {
            $this->redirect(array('site/acessoNegado')); 
            exit;
        }
        
        parent::init();
        $this->defaultAction = 'index';

        $this->menu_acao = array(
        	array('label' => 'Consultar', 'url' => array('Subrisco/index')),
            array('label' => 'Incluir', 'url' => array('Subrisco/admin')),
        );
    }          

    public function actionAdmin($id = 0) {
            $model_subrisco = new Subcriterio;

        if (!empty($id)) {
            $this->subtitulo = 'Atualizar';
            $model = $this->loadModel($id , 'Subrisco');
        } else {
        	if (isset($_GET['consultar'])){
            	$this->subtitulo = 'Consultar';
        	} else {
        		$this->subtitulo = 'Inserir';
        	}
            $model = new Subrisco;
        }
        
        /*
        if (isset($_POST['Subrisco'])) {
            $model->attributes = $_POST['Subrisco'];
          
          		if ($model->save()) {
                              $this->setFlashSuccesso( ($id > 0 ? 'alterar' : 'inserir') );
                    $this->redirect(array('index', 'id' => $model->id));
            }
        }
        */  
            if($_POST['Nota_Acao_Criterio']){
                $salvar = $model->salvar_tabela_risco($_POST['Nota_Acao_Criterio']);
                $this->setFlashSuccesso( ($id > 0 ? 'alterar' : 'inserir') );
                $this->redirect(array('index'));                
            }


            if (isset($_POST['Subcriterio'])) {
                $criterio= $_POST['Subcriterio']['criterio_fk'];
                 if ($criterio){
                 $this->redirect(array('admin', 'criterio' => $criterio));
                }
            }

            if (!$_POST['Nota_Acao_Criterio'] && isset($_POST["Subcriterio"]["criterio_fk"]) && $_POST["Subcriterio"]["criterio_fk"]==0){
                 //$model->addError("valor_exercicio,", "Informe o exercício e o critério");
                $model->addError("","Informe o critério de acordo com o exercício");
            }

            if (isset($_GET['criterio'])) {
                $criterio = $_GET['criterio']; 
                $model_criterio_dados = Subcriterio::model()->findAllByAttributes(array('criterio_fk'=>$criterio), array('order'=>'id'));
                $model_processo_dados = Processo::carrega_tabela_subrisco($criterio);
            }
        

        $this->render('admin', array(
            'model_criterio_dados' => $model_criterio_dados,
            'model_processo_dados' => $model_processo_dados,             
            'model' => $model,
            'model_subrisco' => $model_subrisco,
            'titulo' => $this->titulo,
        ));
    }

    public function actionDelete($id) {
        $this->layout = false;
        if (Yii::app()->getRequest()->getIsPostRequest()) {
            $this->loadModel($id, 'Subrisco')->delete();

             if (Yii::app()->getRequest()->getIsAjaxRequest()) {
                $this->setFlashSuccesso('excluir');
                echo  $this->getMsgSucessoHtml();
            } else {
                $this->setFlashError('excluir');
                $this->redirect(array('admin'));
            }
            
        } else
            throw new CHttpException(400, Yii::t('app', 'Sua requisição é inválida.'));
    }

    public function actionIndex() {
        $model_subrisco = new Subcriterio;        
        
        $this->subtitulo = 'Consultar';
        $dados = null;
        $model = new Subrisco('search');

        

        $criterio = $_GET['Subrisco']['criterio_fk'];
          if (isset($criterio)){
                if ($criterio=="" || $criterio==0){
                       $model->addError("valor_exercicio","Informe o exercício e o critério principal.");
                }else {             
                  $this->redirect(array('admin', 'criterio' => $criterio, 'consultar'=>'1' ));            
                }
          }
/*          
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Subrisco'])) {
            $model->attributes = $_GET['Subrisco'];
            $dados = $model->search();
        }
*/
        $this->render('index', array(
            'model_subrisco' => $model_subrisco,            
            'model' => $model,
            'dados' => $dados,
            'titulo' => $this->titulo,
        ));
    }

    public function actionView($id) {
        $this->layout = false;
        $this->render('view', array(
                'model' => $this->loadModel($id, 'Subrisco'),
        ));
    }
   
    // recebe o ano do exercício e carrega seus critérios
    public function actionCarregaCriterioAjax() {
          $ano_exercicio = $_POST['Subrisco']['valor_exercicio'];
          if ($ano_exercicio!=""){
            // Consulta a model
            $data = Subrisco::model()->RecuperaCriterioPorExercicio($ano_exercicio);
           
            $data=CHtml::listData($data,'id','nome_criterio');

            if (sizeof($data)>0) { 
                echo CHtml::tag('option',array('value'=>''),CHtml::encode('Selecione'),true); 
            }
            else { 
                echo CHtml::tag('option',array('value'=>''),CHtml::encode('Sem critérios'),true);
            }
            
            foreach($data as $value=>$name)
            {
                echo CHtml::tag('option',
                array('value'=>$value),CHtml::encode($name),true);
            }
          }
    } 
}
