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

class ProcessoController extends GxController {

    public $titulo = 'Gerenciar Processo';

    public function init() {
        if (!Yii::app()->user->verificaPermissao("Processo", "admin")) {
            $this->redirect(array('site/acessoNegado'));
            exit;
        }

        parent::init();
        $this->defaultAction = 'index';

        $this->menu_acao = array(
        	array('label' => 'Consultar', 'url' => array('Processo/index')),
            array('label' => 'Incluir', 'url' => array('Processo/admin')),
        );
    }

    public function actionAdmin($id = 0) {
        $model_processo_especie_auditoria = new ProcessoEspecieAuditoria();
        $model_especie_auditoria = new EspecieAuditoria();
        if (!empty($id)) {
            $this->subtitulo = 'Atualizar';
            $model = $this->loadModel($id, 'Processo');
        } else {
            $this->subtitulo = 'Inserir';
            $model = new Processo;
        }
		
        if (isset($_POST['Processo'])) {
            $model->attributes = $_POST['Processo'];
            $exercicio = $_POST['Processo']['valor_exercicio'];
            $limite_inferior =Yii::app()->params['limite_inferior_exercicio'];
            if ($exercicio != "" && (!is_numeric($exercicio) || strlen($exercicio) != 4)) {
                $model->addError("valor_exercicio", "Exercício incorreto");
            } elseif ($exercicio  < $limite_inferior){
        		$model->addError("valor_exercicio", "Exercício inferior ao limite");
	        } else {	        	
                if ($model->save()) {
                    $model_processo_especie_auditoria->salvar($model->id, $_POST['EspecieAuditoria']['especie_auditoria']);
                    $this->setFlashSuccesso(($id > 0 ? 'alterar' : 'inserir'));
                    $this->redirect(array('index', 'id' => $model->id));
                }
            }
        }
        
        $model_processo_especie_auditoria = ProcessoEspecieAuditoria::model()->findAllByAttributes(array('processo_fk' => $id));
        $this->render('admin', array(
            'model_especie_auditoria' => $model_especie_auditoria,
            'model_processo_especie_auditoria' => $model_processo_especie_auditoria,
            'model' => $model,
            'titulo' => $this->titulo,
        ));
    }

    public function actionDelete($id) {
        $this->layout = false;
        if (Yii::app()->getRequest()->getIsPostRequest()) {
            $model_PEA = ProcessoEspecieAuditoria::model()->deleteAll("processo_fk=" . $id);
            try{
            	$this->loadModel($id, 'Processo')->delete();
	            if (Yii::app()->getRequest()->getIsAjaxRequest()) {
	                $this->setFlashSuccesso('excluir');
	                echo $this->getMsgSucessoHtml();
	            } else {
	                $this->setFlashError('excluir');
	                $this->redirect(array('admin'));
	            }
            }catch(Exception $e){
        		if ($e->errorInfo[0] == 23503){
                    $this->setFlash('erro', 'Processo não pode ser excluído, pois o mesmo possui um sub-risco vinculado a ele.');
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
        $model = new Processo('search');

        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Processo'])) {

            $exercicio = $_GET['Processo']['valor_exercicio'];
            if ($exercicio != "" && (!is_numeric($exercicio) || strlen($exercicio) != 4)) {
                $model->addError("valor_exercicio", "Exercício incorreto");
            } else {
                $model->attributes = $_GET['Processo'];
                $dados = $model->search();
            }
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
            'model' => $this->loadModel($id, 'Processo'),
        ));
    }
    
    public function actionCarregaProcessoRiscoPreAjax() {
    	$data = ProcessoRiscoPre::model()->findAll(array("condition" => "processo_fk = " . $_POST['processo_fk']));
    
    	if (!sizeof($data) > 0) {
    		echo CHtml::encode('Nenhum risco pré-identificado cadastrado para este processo');
    	} else {
    		
    		$str_in = "";
    		foreach($data as $vetor){
    			$str_in .= $vetor->risco_pre_fk . ",";
    		}    		
    		$str_in = substr($str_in, 0, -1);
    		
    		$data = RiscoPre::model()->findAll(array("condition" => "id in (" . $str_in . ")", 'order' => 'nome_risco'));
    		
	    	$data = CHtml::listData($data, 'id', 'nome_risco');
	    	
	    	foreach ($data as $value => $name) {
	    		echo '* '.CHtml::encode($name).'<br>';
	    	}
    	}
    }  

    public function actionCarregaProcessoPorExercicioAjax(){
    	$valor_exercicio = "";
    	if (isset($_POST['valor_exercicio'])){
    		$valor_exercicio = $_POST['valor_exercicio'];
    		if ($valor_exercicio){
    			$limite_inferior =Yii::app()->params['limite_inferior_exercicio'];
    			if($valor_exercicio < $limite_inferior){
    				echo CHtml::tag('option', array('value' => ''), CHtml::encode("Exercício inferior ao limite suportado."));
    				exit;
    			}
    		} else {
    			echo CHtml::tag('option', array('value' => ''), CHtml::encode("Exercício deve ser informado."));
    			exit;
    		}
    	} else {
    		echo CHtml::tag('option', array('value' => ''), CHtml::encode("Exercício deve ser informado."));
    		exit;
    	}

    	$risco_pre_fk = $_POST['risco_pre_fk'];
    	if (($risco_pre_fk != 'null') && ($valor_exercicio != date(Y))) {
    		$data = CHtml::listData(ProcessoRiscoPre::model()->with('processoFk')->findAll(array("condition" => 'risco_pre_fk = ' . $risco_pre_fk . ' and valor_exercicio = ' . $valor_exercicio)), 'processo_fk', 'processoFk');
    		if (!sizeof($data) > 0) {
    			echo CHtml::tag('option', array('value' => ''), CHtml::encode('Nenhum processo vinculado ao risco pré-identificado em ' . $_POST['valor_exercicio']), true);
    		}
    		foreach ($data as $value => $name) {
    			echo CHtml::tag('option', array('value' => $value, 'selected' => 'selected', 'disabled' => 'disabled'), CHtml::encode($name), true);
    		}
    	} else {
    		$data_selecionado = array();
    		$bolSeleciona = false;
    		if ($risco_pre_fk){
    			$data_selecionado = ProcessoRiscoPre::model()->with('processoFk')->findAll(array("condition" => 'risco_pre_fk = ' . $risco_pre_fk . ' and valor_exercicio = ' . $valor_exercicio));
    		}
    		    		
    		$data = Processo::model()->findAll('valor_exercicio=:valor_exercicio ORDER BY nome_processo', array(':valor_exercicio' => (int) $valor_exercicio));
    		$data = CHtml::listData($data, 'id', 'nome_processo');
    		if (!sizeof($data) > 0) {
    			echo CHtml::tag('option', array('value' => ''), CHtml::encode('Nenhum processo cadastrada em ' . $_POST['valor_exercicio']), true);
    		}
    		
    		foreach ($data as $value => $name) {
    			if (sizeof($data_selecionado)){
    				foreach($data_selecionado as $index => $vetor_selecionado){
    					if ($vetor_selecionado->processo_fk == $value) {
    						$bolSeleciona = true;
    						unset($data_selecionado[$index]);
    					} 
    				}
    			}
    			if ($bolSeleciona){
    				echo CHtml::tag('option', array('value' => $value, 'selected' => 'selected'), CHtml::encode($name), true);
    			} else {
    			    echo CHtml::tag('option', array('value' => $value), CHtml::encode($name), true);
    			}
    			$bolSeleciona = false;
    		}
    	}
    	 
    	
    }    

}
