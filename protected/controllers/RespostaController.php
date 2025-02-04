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

class RespostaController extends GxController {

    public $titulo = 'Relatório para Follow Up';

    public function actionAdmin($id = 0) {
        $this->titulo = 'Follow Up';
        $model = new Resposta;
        if (!empty($id)) {
            $this->subtitulo = 'Atualizar';
            $model_recomendacao = $this->loadModel($id, 'Recomendacao');
        } else {
            $this->subtitulo = 'Inserir';
        }

        if (isset($_POST['Resposta'])) {
            $perfil = strtolower(Yii::app()->user->role);
            $perfil = str_replace("siaudi2", "siaudi", $perfil);
            $login = strtolower(Yii::app()->user->login);

            //Validação caso haja alteração da query string       
            //Please sanitize this later
            if ($perfil == 'siaudi_cliente_item' && $_POST['relatorio_fk']) {
                if (!$model->validaAcessoAoRelatorio($_POST['relatorio_fk'], $login)) {
                    $this->redirect(array('site/acessoNegado'));
                } //o usuário logado não tem nenhum item liberado para o relatório informado. 
            }

            $model->attributes = $_POST['Resposta'];
            $model->recomendacao_fk = $_POST['recomendacao_fk'];
            $model->data_resposta = date("Y-m-d");
            $model->id_usuario_log = Yii::app()->user->login;

            $CUploadedFile = CUploadedFile::getInstancesByName('attachment');

            if(sizeof($CUploadedFile)>0){
                foreach ($CUploadedFile as $file) {
                    $cod = rand(11111, 99999);
                    $end = $_SERVER ['DOCUMENT_ROOT'] . Yii::app()->request->baseUrl . '/anexos/' . $cod . '_' . $file->name;
                    move_uploaded_file($file->tempName, $end);
                    $content = file_get_contents($end);
                    unlink($end);

                    $arquivo_nome_completo = $CUploadedFile[0]->name;
                    $tipo_arquivo = substr(strrchr($arquivo_nome_completo, '.'), 1);
                    $nome_arquivo = substr($arquivo_nome_completo, 0, -(strlen($tipo_arquivo) + 1));

                    $model->nome_arquivo = $nome_arquivo;
                    $model->tipo_arquivo = $tipo_arquivo;
                    $model->conteudo_arquivo = base64_encode($content);
                }
            }
            if ($model->save()) {
                $envia_emails = Resposta::model()->FollowUp_email($_POST['numero_recomendacao'], $_POST['relatorio_fk'], $_POST['recomendacao_fk']);
                $this->setFlashSuccesso(($id > 0 ? 'alterar' : 'inserir'));
//                $this->redirect('../');
				$this->redirect(array('index?' . $_SERVER['QUERY_STRING'])); 
            }
        }

        $this->render('admin', array(
            'model' => $model,
            'model_recomendacao' => $model_recomendacao,
            'titulo' => $this->titulo,
        ));
    }

    public function actionDelete($id) {
        $this->layout = false;
        if (Yii::app()->getRequest()->getIsPostRequest()) {
            $this->loadModel($id, 'Resposta')->delete();

            if (Yii::app()->getRequest()->getIsAjaxRequest()) {
                $this->setFlashSuccesso('excluir');
                echo $this->getMsgSucessoHtml();
            } else {
                $this->setFlashError('excluir');
                $this->redirect(array('admin'));
            }
        }
        else
            throw new CHttpException(400, Yii::t('app', 'Sua requisição é inválida.'));
    }

    public function actionIndex() {
        $this->subtitulo = 'Consultar';
        $dados = null;
        $model = new Resposta('search');

        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Resposta'])) {
            $model->attributes = $_GET['Resposta'];
            $id = $_GET['Resposta']['relatorio_fk'];

            $perfil = strtolower(Yii::app()->user->role);
            $perfil = str_replace("siaudi2", "siaudi", $perfil);
            $login = strtolower(Yii::app()->user->login);

/*                        
            //Validação caso haja alteração da query string       
            if ($perfil == 'siaudi_cliente_item') {
                if (!$model->validaAcessoAoRelatorio($id, $login)) {
                    $this->redirect(array('site/acessoNegado'));
                } //o usuário logado não tem nenhum item liberado para o relatório informado. 
            }
*/
            
            //Verifica se o relatório que foi passado foi emitido pelo núcleo. 
            //O perfil siaudi_gerente_nucleo deve acessar somente os relatórios emitidos pelo núcleo.
            //Validação referente a alteração do valor do relatório na QueryString. 
            if ($perfil == "siaudi_gerente_nucleo") {
                $relatorio = Relatorio::model()->findAll("id=" . $id . " and nucleo is true");
                if (!sizeof($relatorio)) {
                    $this->redirect(array('site/acessoNegado'));
                }
            }

            $dados = $model->search($id);
        }
	    
        $this->render('index', array(
            'model' => $model,
            'dados' => $dados,
            'titulo' => $this->titulo,
        ));
    }

    public function actionBaixarAnexoAjax() {
        $this->layout = false;
        $resposta_fk = $_GET['resposta_fk'];
        $resposta = Resposta::model()->findByPk($resposta_fk);
        
        header("Content-type: application/". $resposta->tipo_arquivo);
        header('Content-Disposition: attachment; filename="' . $resposta->nome_arquivo . "." . $resposta->tipo_arquivo . '"');
        echo base64_decode($resposta->conteudo_arquivo);             
    }
    
    public function actionCarregaRelatorioPorExercicioAjax(){
    	$valor_exercicio = $_POST['Resposta']['valor_exercicio'];
    	$retorno = Resposta::model()->FollowUp_combo($valor_exercicio);
    	
    	if (sizeof($retorno) > 0) {
    		echo CHtml::tag('option', array('value' => ''), CHtml::encode("SELECIONE"), true);
    	}
    	
    	foreach($retorno as $key=>$vetor){
    		echo CHtml::tag('option', array('value' => $key), CHtml::encode($vetor), true);
    	}
    	
    }

}
