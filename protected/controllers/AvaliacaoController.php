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

class AvaliacaoController extends GxController {

    public   $titulo = 'Avaliação do Auditor';

    public function actionAdmin($id = 0) {

        if (!empty($id)) {
            $this->subtitulo = 'Atualizar';
            $model = $this->loadModel($id , 'Avaliacao');
        } else {
            $this->subtitulo = 'Inserir';
            $model = new Avaliacao;
        }
        
        if (isset($_POST['Avaliacao'])) {
            $model->relatorio_fk = $_GET['relatorio'];
            $model->unidade_administrativa_fk = $_GET['sureg'];
            $model->usuario_fk = $_GET['auditor'];
            $model->data = date("Y-m-d");
            $model->nome_login = Yii::app()->user->login;

             // salva avaliação   
             if ($model->save()) {

                  // salva notas
                  $notas = Avaliacao::model()->SalvaNotas($model->id,$_POST['Nota_Criterio']);
                  //salva observação
                  $observacao = Avaliacao::model()->SalvaObservacao($model->id,$_POST['Avaliacao']['observacao']);
                  
                  $this->setFlashSuccesso( ($id > 0 ? 'alterar' : 'inserir') );                  
                  
                    /* se avaliação veio da visualização do relatório 
                     * em PDF, então retorna para o relatório. Caso contrário,
                     * retorna para manifestação (caminho feliz da avaliação - que
                     * deve ser feita antes da manifestação do auditado).
                     */
                    if($_POST['visualizar_pdf']){
                        $this->redirect('../../RelatorioSaida/RelatorioSaidaAjax/'.$model->relatorio_fk."?confirma=1&visualizar_pdf=1");
                    }else{
                        $this->redirect('../../Manifestacao/ManifestacaoRelatorioAjax/'.$model->relatorio_fk."?confirma=1");
                    }
                   exit;
            }
        }

        $this->render('admin', array(
            'model' => $model,
            'titulo' => $this->titulo,
        ));
    }

    /*
    public function actionDelete($id) {
        $this->layout = false;
        if (Yii::app()->getRequest()->getIsPostRequest()) {
            $this->loadModel($id, 'Avaliacao')->delete();

             if (Yii::app()->getRequest()->getIsAjaxRequest()) {
                $this->setFlashSuccesso('excluir');
                echo  $this->getMsgSucessoHtml();
            } else {
                $this->setFlashError('excluir');
                $this->redirect(array('admin'));
            }
            
        } else
            throw new CHttpException(400, Yii::t('app', 'Sua requisição é inválida.'));
    }*/

    /*
    public function actionIndex() {
        $this->subtitulo = 'Consultar';
        $dados = null;
        $model = new Avaliacao('search');

        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Avaliacao'])) {
            $model->attributes = $_GET['Avaliacao'];
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
                'model' => $this->loadModel($id, 'Avaliacao'),
        ));
    }
      */
     
}
