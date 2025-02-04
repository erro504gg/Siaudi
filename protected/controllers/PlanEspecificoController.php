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

class PlanEspecificoController extends GxController {

    public $titulo = 'Planejamento Específico';

    public function init() {
        $perfil = strtolower(Yii::app()->user->role);
        $perfil = str_replace("siaudi2", "siaudi", $perfil);

        // Perfis de  "Chefe de Auditoria" e "CGU" tem permissÃ£o apenas para visualizaÃ§Ã£o do Planejamento
        if (!Yii::app()->user->verificaPermissao("PlanEspecifico", "admin") && $perfil != "siaudi_chefe_auditoria" && $perfil != "siaudi_cgu") {
            $this->redirect(array('site/acessoNegado'));
            exit;
        }
        parent::init();
        $this->defaultAction = 'index';

        $acesso_planEspecifico = PlanEspecifico::model()->acesso_PlanEspecifico();
        if ($acesso_planEspecifico) {
            $this->menu_acao = array(
                array('label' => 'Consultar', 'url' => array('PlanEspecifico/index')),
                array('label' => 'Incluir', 'url' => array('PlanEspecifico/admin')),
            );
        } else {
            $this->menu_acao = array(
                array('label' => 'Consultar', 'url' => array('PlanEspecifico/index')),
            );
        }
    }

    public function actionAdmin($id = 0) {
        $model_plan_auditor = new PlanEspecificoAuditor();
        if (!empty($id)) {
            $this->subtitulo = 'Atualizar';
            $model = $this->loadModel($id, 'PlanEspecifico');
        } else {
            $this->subtitulo = 'Inserir';
            $model = new PlanEspecifico;
        }

        if (isset($_POST['PlanEspecifico'])) {

            $model->attributes = $_POST['PlanEspecifico'];
            $model->id_usuario_log = Yii::app()->user->id;
            $model->data_log = $_POST['data_log'];
            $model->acao_fk = $_POST['acao_fk'];
            $model->valor_sureg = $_POST['valor_sureg'];
            $model->unidade_administrativa_fk = ($_POST['PlanEspecifico']['unidade_administrativa_fk']) ? $_POST['PlanEspecifico']['unidade_administrativa_fk'] : null;

            // valida data, se o parï¿½metro foi passado
            $salvar = 1;
            $data = $_POST["PlanEspecifico"]["data_inicio_atividade"];
            if ($data != "") {
                $data_valida = explode("/", $data); //formato brasileiro
                $data_valida = checkdate($data_valida[1], $data_valida[0], $data_valida[2]); //ordem dos parÃ¢metros: mes, dia e ano.
                if (!$data_valida) {
                    $model->addError("data_inicio_atividade", "Data de Início das Atividades inválida");
                    $salvar = 0;
                }
            }

            // valida limite inferior do exercício
            $valor_exercicio = $_POST['PlanEspecifico']['valor_exercicio'];
            $limite_inferior = Yii::app()->params['limite_inferior_exercicio'];


            if ($model->validate() && (sizeof($_POST['PlanEspecificoAuditor']) > 0) && ($valor_exercicio > $limite_inferior)) {

                if ($model->save()) {
                    $model_plan_auditor->salvar($model->id, $_POST['PlanEspecificoAuditor']['plan_auditor']);
                    // gerar a saída em PDF ou retorna para o sistema; 
                    if ($_POST['gerar_pdf'] == 1) {
                        $this->actionExportarPDFAJax($model->id);
                    } else {
                        $this->setFlashSuccesso(($id > 0 ? 'alterar' : 'inserir'));
                        $this->redirect(array('index', 'id' => $model->id));
                    }
                }
            }
            if ($valor_exercicio < $limite_inferior) {
                $model->addError("valor_exercicio", "Exercí­cio inferior ao limite");
            }

            // valida se auditores foram selecionados
            if (sizeof($_POST['PlanEspecificoAuditor']) == 0) {
                $model->addError("plan_auditor", "É obrigatório o preenchimento do campo Auditores.");
            }
        }

        $plan_auditor = PlanEspecificoAuditor::model()->findAllByAttributes(array('plan_especifico_fk' => $id));
        $this->render('admin', array(
            'model' => $model,
            'titulo' => $this->titulo,
            'model_plan_auditor' => $model_plan_auditor,
            'plan_auditor' => $plan_auditor,
        ));
    }

    public function actionDelete($id) {
        $this->layout = false;
        if (Yii::app()->getRequest()->getIsPostRequest()) {
            $model_plan_auditor = new PlanEspecificoAuditor();
            $model_plan_auditor->deleteAll("plan_especifico_fk=" . $id);
            $this->loadModel($id, 'PlanEspecifico')->delete();
            if (Yii::app()->getRequest()->getIsAjaxRequest()) {
                $this->setFlashSuccesso('excluir');
                echo $this->getMsgSucessoHtml();
            } else {
                $this->setFlashError('excluir');
                $this->redirect(array('admin'));
            }
        }
        else
            throw new CHttpException(400, Yii::t('app', 'Sua requisão é inválida.'));
    }

    public function actionIndex() {
        $this->subtitulo = 'Consultar';
        $dados = null;
        $model = new PlanEspecifico('search');

        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['PlanEspecifico'])) {
            $valor_exercicio = $_GET['PlanEspecifico']['valor_exercicio'];
            if ($valor_exercicio != "" && (!is_numeric($valor_exercicio) || strlen($valor_exercicio) != 4)) {
                $model->addError("valor_exercicio", "Exercí­cio incorreto.");
            } else {
                $model->attributes = $_GET['PlanEspecifico'];
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
            'model' => $this->loadModel($id, 'PlanEspecifico'),
        ));
    }

    public function actionCarregaAcaoAjax() {
        $data = Acao::model()->findAll('valor_exercicio=:valor_exercicio ORDER BY nome_acao', array(':valor_exercicio' => (int) $_POST['PlanEspecifico']['valor_exercicio']));

        $data = CHtml::listData($data, 'id', 'nome_acao');

        if (sizeof($data) > 0) {
            echo CHtml::tag('option', array('value' => ''), CHtml::encode('Selecione'), true);
        } else {
            echo CHtml::tag('option', array('value' => ''), CHtml::encode('Nenhuma ação cadastrada em ' . $_POST['PlanEspecifico']['valor_exercicio']), true);
        }
        foreach ($data as $value => $name) {
            echo CHtml::tag('option', array('value' => $value), CHtml::encode($name), true);
        }
    }

    public function actionCarregaAcaoSuregAjax() {
        $data = AcaoSureg::model()->carrega_acaosureg((int) $_POST['acao_fk']);
        $data = CHtml::listData($data, 'id', 'nome_sureg');

        if (sizeof($data) > 0) {
            echo CHtml::tag('option', array('value' => ''), CHtml::encode('Selecione'), true);
        } else {
            echo CHtml::tag('option', array('value' => ''), CHtml::encode('Nenhuma Unidade Regional cadastrada nesta ação'), true);
        }
        foreach ($data as $value => $name) {
            echo CHtml::tag('option', array('value' => $value), CHtml::encode($name), true);
        }
    }

    public function actionCarregaEscopoAcaoAjax() {
        $data = Acao::model()->findByPk((int) $_POST['acao_fk']);
        echo $data->descricao_escopo;
    }

    public function actionExportarPDFAJax($id) {
        $model = $this->loadModel($id, 'PlanEspecifico');
        $html2pdf = Yii::app()->ePdf->HTML2PDF();
        $arquivo = utf8_encode($this->GerarPDF($model));
        $html2pdf->pdf->SetAuthor('AUTOR');
        $html2pdf->pdf->SetTitle('Planejamento de Relatoria');
        $html2pdf->pdf->SetSubject('Planejamento Específico');
        $html2pdf->pdf->SetKeywords('Planejamento,Especifico');
        $html2pdf->pdf->SetDisplayMode('fullpage');
        $html2pdf->WriteHTML($arquivo);
        $html2pdf->Output('planejamento.pdf', 'D');
        exit;
    }

    public function GerarPDF($model) {
        $html = "";
        include_once(Yii::app()->basePath . '/views/planEspecifico/pdf/pdf_planejamento.inc');
        return($html);
    }

    // trata os textos para se adaptarem ao PDF
    public function FiltrarTextoPDF($texto) {

        // inserindo imagens
        $texto = str_replace("../../js", $baseUrl2 . "js", $texto);
        $texto = str_replace("../js", $baseUrl2 . "js", $texto);
        $texto = str_replace("<img ", "<br><img ", $texto);

        // inserindo quebras de pï¿½gina
        $texto = str_replace("<!-- pagebreak -->", "<div style='page-break-after:always; clear:both'></div>", $texto);

        // limpa spam com fundo cinza
        $texto = str_replace("<span style=\"background-color: #a0a0a0;\">&nbsp;</span>", "", $texto);

        return($texto);
    }

}
