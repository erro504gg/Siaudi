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

class PaintController extends GxController {

    public $titulo = 'PAINT';

    public function init() {
        if (!Yii::app()->user->verificaPermissao("Paint", "admin")) {
            $this->redirect(array(
                'site/acessoNegado'
            ));
            exit();
        }

        parent::init();
        $this->defaultAction = 'index';

        // verifica se usuário tem permissão de edição para o Paint
        $acesso_paint = Paint::model()->acesso_paint();
        if ($acesso_paint) {
            $this->menu_acao = array(
                array(
                    'label' => 'Consultar',
                    'url' => array(
                        'Paint/index'
                    )
                ),
                array(
                    'label' => 'Incluir',
                    'url' => array(
                        'Paint/admin'
                    )
                )
            );
        } else {
            $this->menu_acao = array(
                array(
                    'label' => 'Consultar',
                    'url' => array(
                        'Paint/index'
                    )
                )
            );
        }
    }

    // monta a árvore do paint para levar ao JSON
    public function arvore_paint(&$list, $parent) {
        $tree = array();
        foreach ($parent as $k => $l) {
            if (isset($list [$l ['id']])) {
                $l ['children'] = $this->arvore_paint($list, $list [$l ['id']]);
            }
            $tree [] = $l;
        }
        return $tree;
    }

    public function actionCarregaPaintAjax() {
        // gera vetor para carregar o JSON
        if ($_REQUEST ["exercicio"]) {
            $paint = Paint::carrega_paint($_REQUEST ["exercicio"]);
            if (is_array($paint)) {
                $paint_novo = array();
                foreach ($paint as $a) {
                    $paint_novo [$a ['numero_item_pai']] [] = $a;
                }
                $arvore = $this->arvore_paint($paint_novo, $paint_novo [0]); // changed

                if (is_array($arvore)) {
                    echo json_encode($arvore);
                }
            } else {
                echo '""';
            }
        }
        exit();
    }

    public function actionAdmin($id = 0) {
        $limite_inferior = Yii::app()->params ['limite_inferior_exercicio'];

        if (!empty($id)) {
            $this->subtitulo = 'Atualizar';
            $model = $this->loadModel($id, 'Paint');
        } else {
            $this->subtitulo = 'Inserir';
            $model = new Paint ();
            $model->data_gravacao = date("Y-m-d");
        }

        // valida se usuário preencheu todos os campos do exercício
        if (isset($_REQUEST ["Paint"] ["valor_exercicio"]) || isset($_REQUEST ["exercicio"])) {
            $exercicio = (isset($_REQUEST ["Paint"] ["valor_exercicio"])) ? $_REQUEST ["Paint"] ["valor_exercicio"] : $_REQUEST ["exercicio"];
            if (!is_numeric($exercicio) || strlen($exercicio) != 4) {
                $model->addError("valor_exercicio", "Exercício incorreto ou não informado");
                $this->render('admin', array(
                    'model' => $model,
                    'dados' => $dados,
                    'titulo' => $this->titulo
                ));
                exit();
            }

            if ($exercicio < $limite_inferior) {
                $model->addError("valor_exercicio", "Exercício inferior ao limite");
                $this->render('admin', array(
                    'model' => $model,
                    'dados' => $dados,
                    'titulo' => $this->titulo
                ));
                exit();
            }
        }

        if ($_REQUEST ["consultar"] == 1) {
            $this->subtitulo = 'Consultar';
        } else {
            // verifica se usuário tem permissão de edição para o Paint
            $acesso_paint = Paint::model()->acesso_paint();
            if (!$acesso_paint) {
                $this->redirect(array(
                    'site/acessoNegado'
                ));
                exit();
            }
        }

        if ($_REQUEST ["paint"]) {
            $model_paint = Paint::model()->prepara_paint();
            if ($_REQUEST ["paint_sair"] == 1) {
                $this->redirect(array(
                    'index'
                ));
            }
            if ($_REQUEST ["paint_sair"] == 2) {
                $this->redirect(array(
                    'ExportarPDFAjax',
                    'exercicio' => $exercicio
                ));
            }
        }

        if ($_REQUEST ["PaintFormValidar_dados"] == 1 && $_REQUEST ["Paint"] ["valor_exercicio"] > $limite_inferior) {
            $this->redirect(array(
                'admin',
                'exercicio' => $_REQUEST ["Paint"] ["valor_exercicio"]
            ));
        }

        if ($_REQUEST ["PaintFormValidar_dados"] && !$_REQUEST ["Paint"] ["valor_exercicio"]) {
            $this->redirect(array(
                'admin'
            ));
        }

        if ($_REQUEST ["Paint"] ["valor_exercicio"]) {
            if ($_REQUEST ["consultar"] == 1) {
                $this->redirect(array(
                    'admin',
                    'exercicio' => $_REQUEST ["Paint"] ["valor_exercicio"],
                    'consultar' => 1
                ));
            } else {
                $this->redirect(array(
                    'admin',
                    'exercicio' => $_REQUEST ["Paint"] ["valor_exercicio"]
                ));
            }
        }

        if (isset($_REQUEST ['Paint'])) {
            $model->attributes = $_REQUEST ['Paint'];
            if ($model->save()) {
                $this->setFlashSuccesso(($id > 0 ? 'alterar' : 'inserir'));
                $this->redirect(array(
                    'index',
                    'id' => $model->id
                ));
            }
        }

        $this->render('admin', array(
            'model' => $model,
            'titulo' => $this->titulo
        ));
    }

    public function actionIndex() {
        $this->subtitulo = 'Consultar';
        $dados = null;
        $model = new Paint('search');

        $valor_exercicio = (isset($_REQUEST ["Paint"] ["valor_exercicio"])) ? $_REQUEST ["Paint"] ["valor_exercicio"] : $_REQUEST ["exercicio"];

        $limite_inferior = Yii::app()->params ['limite_inferior_exercicio'];
        if ($valor_exercicio) {
            if ($valor_exercicio < $limite_inferior) {
                $model->addError("valor_exercicio", "Exercício inferior ao limite");
                $this->render('admin', array(
                    'model' => $model,
                    'dados' => $dados,
                    'titulo' => $this->titulo
                ));
                exit();
            }
        }

        if ($_REQUEST && isset($valor_exercicio)) {
            if (!is_numeric($valor_exercicio) || strlen($valor_exercicio) != 4) {
                $model->addError("valor_exercicio", "Informe o exercício");
            } else {
                $valor_exercicio = $_REQUEST ["Paint"] ["valor_exercicio"];
                $this->redirect(array(
                    'admin',
                    'exercicio' => $valor_exercicio,
                    "consultar" => '1'
                ));
                $model->unsetAttributes(); // clear any default values
                if (isset($_REQUEST ['Paint'])) {
                    $model->attributes = $_REQUEST ['Paint'];
                    $dados = $model->search();
                }
            }
        }

        $this->render('index', array(
            'model' => $model,
            'dados' => $dados,
            'titulo' => $this->titulo
        ));
    }

    public function actionView($id) {
        $this->layout = false;
        $this->render('view', array(
            'model' => $this->loadModel($id, 'Paint')
        ));
    }

    public function actionDelete($id) {
        $this->layout = false;
        if (Yii::app()->getRequest()->getIsPostRequest()) {
            $this->loadModel($id, 'Paint')->delete();

            if (Yii::app()->getRequest()->getIsAjaxRequest()) {
                $this->setFlashSuccesso('excluir');
                echo $this->getMsgSucessoHtml();
            } else {
                $this->setFlashError('excluir');
                $this->redirect(array(
                    'admin'
                ));
            }
        }
        else
            throw new CHttpException(400, Yii::t('app', 'Sua requisição é inválida.'));
    }

    // ==========================================================
    // FUNÇÕES PARA GERAR PAINT
    // ==========================================================
    public function actionExportarPDFAjax() {
        // HTML2PDF has very similar syntax
        $html2pdf = Yii::app()->ePdf->HTML2PDF();
        $arquivo = utf8_encode($this->GerarPaintPDF());
// debug($arquivo);
        $html2pdf->pdf->SetAuthor('AUTOR');
        $html2pdf->pdf->SetTitle('Plano Anual de Atividades da Auditoria Interna - PAINT');
        $html2pdf->pdf->SetSubject('PAINT');
        $html2pdf->pdf->SetKeywords('PAINT,Ministerio');
        // $html2pdf->addFont("verdana", "regular", Yii::app()->basePath . "\common\extensions\html2pdf\\fonts\\verdana.ttf");
        //$html2pdf->addFont("Times New Roman", "times.php");        
        
        
        $html2pdf->pdf->SetDisplayMode('fullpage');
        $html2pdf->WriteHTML($arquivo);
        $html2pdf->createIndex(utf8_encode('Índice'), 20, 13, false, true, 2, 'helvetica');
        $html2pdf->Output('paint.pdf', 'D');
        exit();
    }

    // Função para gerar CSS do atributo risco
    public function calcula_risco($risco, $retornar_css) {
        if ($risco >= 0 && $risco < 1) {
            $retorno = "Irrelevante";
            $retorno_css = "irrelevante";
        }

        if ($risco >= 1 && $risco < 2) {
            $retorno = "Baixo";
            $retorno_css = "baixo";
        }
        if ($risco >= 2 && $risco < 3) {
            $retorno = "Médio";
            $retorno_css = "medio";
        }
        if ($risco >= 3 && $risco < 4) {
            $retorno = "Alto";
            $retorno_css = "alto";
        }
        if ($risco >= 4 && $risco <= 5) {
            $retorno = "Crítico";
            $retorno_css = "critico";
        }

        if ($retornar_css == 1) {
            return $retorno_css;
        } else {
            return $retorno;
        }
    }

    public function GerarPaintPDF() {
        $html = "";
        include_once (Yii::app()->basePath . '/views/paint/pdf/pdf_paint.inc');
        return ($html);
    }

    public function GerarAtributoRiscoPDF() {
        $html = "";
        include_once (Yii::app()->basePath . '/views/paint/pdf/pdf_paint_risco.inc');
        return ($html);
    }

    public function GerarAtributoSubRiscoPDF() {
        $html = "";
        include_once (Yii::app()->basePath . '/views/paint/pdf/pdf_paint_subrisco.inc');
        // echo($html); exit;
        return ($html);
    }

    public function GerarAtributoAcaoPDF() {
        $html = "<span>";
        include_once (Yii::app()->basePath . '/views/paint/pdf/pdf_paint_acao.inc');
        $html .= "<br></span>";
        return ($html);
    }

    public function GerarAtributoHomensHoraPDF() {
        $html = "<span>";
        include_once (Yii::app()->basePath . '/views/homensHora/pdf/pdf_homens_hora.inc');
        $html .= "<br></span>";
        return ($html);
    }

}
