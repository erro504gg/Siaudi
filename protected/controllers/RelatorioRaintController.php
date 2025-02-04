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

class RelatorioRaintController extends GxController {

    public $titulo = '';

    public function init() {
        parent::init();
        $this->defaultAction = 'index';
    }

    public function actionDescricaoDasAcoesAjax() {
        $this->menu_acao = array(
            array('label' => 'Consultar', 'url' => array('RelatorioRaint/DescricaoDasAcoesAjax')),
        );
        $this->titulo = 'Relatório de Descrição das Ações';
        $this->subtitulo = 'Consultar';
        $dados = null;
        $bolNaoEncontrouRegistro = false;
        $model = new Raint();
        if ($_POST != null) {
            $valor_exercicio = $_POST['Raint']['valor_exercicio'];
            if ((($valor_exercicio != null) || ($valor_exercicio != 0)) && (!(!is_numeric($valor_exercicio) || strlen($valor_exercicio) != 4))) {
                $dados = $model->ObtemDescricaoDasAcoes($valor_exercicio);
                if (sizeof($dados)) {
                    $this->render('descricaoDasAcoes/relatorio_descricao_acoes', array(
                        'dados' => $dados,
                        'model' => $model,
                    ));
                } else {
                    $bolNaoEncontrouRegistro = true;
                }
            } else {
                $model->addError("valor_exercicio", "Exercício incorreto ou não informado");
            }
        }

        if ($dados == null) {
            $this->render('descricaoDasAcoes/index', array(
                'model' => $model,
                'titulo' => $this->titulo,
                'bolNaoEncontrouRegistro' => $bolNaoEncontrouRegistro
            ));
        }
    }

    public function actionRecomendacaoGravidadeAjax() {
        $this->menu_acao = array(
            array('label' => 'Consultar', 'url' => array('RelatorioRaint/RecomendacaoGravidadeAjax')),
        );
        $this->titulo = 'Relatório de Recomendações por Gravidade';
        $this->subtitulo = 'Consultar';
        $model = new Raint();
        $bolNaoEncontrouRegistro = false;
        $limite_inferior = Yii::app()->params ['limite_inferior_exercicio'];
        $valor_exercicio = $_POST['Raint']['valor_exercicio'];
        
        if (isset($_POST['Raint'])){
	        if (!is_numeric($valor_exercicio) || strlen($valor_exercicio) != 4) {
	        	$model->addError("valor_exercicio", "Exercício incorreto ou não informado");
	                $this->render('recomendacaoGravidade/index', array(
	                    'model' => $model,
	                    'titulo' => $this->titulo,
	                    'bolNaoEncontrouRegistro' => $bolNaoEncontrouRegistro
	                ));
	        	exit();
	        }
	        
	        if ($valor_exercicio < $limite_inferior) {
	        	$model->addError("valor_exercicio", "Exercício inferior ao limite");
	                $this->render('recomendacaoGravidade/index', array(
	                    'model' => $model,
	                    'titulo' => $this->titulo,
	                    'bolNaoEncontrouRegistro' => $bolNaoEncontrouRegistro
	                ));
	        	exit();
	        }
        }
        
        $unidade_administrativa_fk = $_POST['Raint']['unidade_administrativa_fk'];
        $objeto_fk = $_POST['Raint']['objeto_fk'];
        if (($objeto_fk == null) && ($unidade_administrativa_fk == null)) {
            $this->render('recomendacaoGravidade/index', array(
                'model' => $model,
                'titulo' => $this->titulo,
            ));
        } else {
            $dados = $model->ObtemTotalDeRecomendacaoesPorGravidade($valor_exercicio, $unidade_administrativa_fk, $objeto_fk);
            if (sizeof($dados)) {
                $this->render('recomendacaoGravidade/relatorio_recomendacao_gravidade', array(
                    'dados' => $dados,
                    'model' => $model,
                ));
            } else {
                $bolNaoEncontrouRegistro = true;
                $this->render('recomendacaoGravidade/index', array(
                    'model' => $model,
                    'titulo' => $this->titulo,
                    'bolNaoEncontrouRegistro' => $bolNaoEncontrouRegistro
                ));
            }
        }
    }

    public function actionRecomendacaoSolucionadasAjax() {
        $this->menu_acao = array(
            array('label' => 'Consultar', 'url' => array('RelatorioRaint/RecomendacaoSolucionadasAjax')),
        );
        $this->titulo = 'Relatório de Resolutibilidade';
        $this->subtitulo = 'Consultar';
        $dados = null;
        $bolNaoEncontrouRegistro = false;
        $model = new Raint();
        if ($_POST != null) {
            $valor_exercicio = $_POST['Raint']['valor_exercicio'];
            if ((($valor_exercicio != null) || ($valor_exercicio != 0)) && ((!is_numeric($valor_exercicio) || strlen($valor_exercicio) != 4))) {
                $model->addError("valor_exercicio", "Exercício incorreto");
            } else {

                $unidade_administrativa_fk = $_POST['Raint']['unidade_administrativa_fk'];
                $dados = $model->ObtemTotalDeRecomendacoesSolucionadas($valor_exercicio, $unidade_administrativa_fk);
                if (sizeof($dados)) {
                    $this->render('recomendacaoSolucionadas/relatorio_resolutibilidade', array(
                        'model' => $model,
                        'dados' => $dados
                    ));
                } else {
                        $bolNaoEncontrouRegistro = true;
                }
            }
        }

        if ($dados == null) {
            $this->render('recomendacaoSolucionadas/index', array(
                'model' => $model,
                'titulo' => $this->titulo,
                    'bolNaoEncontrouRegistro' => $bolNaoEncontrouRegistro
            ));
        }
    }

    public function actionRecomendacaoPorCategoriaAjax() {
        $this->menu_acao = array(
            array('label' => 'Consultar', 'url' => array('RelatorioRaint/RecomendacaoPorCategoriaAjax')),
        );
        $this->titulo = 'Relatório de Recomendações por Categoria';
        $this->subtitulo = 'Consultar';
        $dados = null;
        $descRecomendacao = null;
        $bolNaoEncontrouRegistro = false;
        $model = new Raint();
        if ($_POST != null) {
            $valor_exercicio = $_POST['Raint']['valor_exercicio'];
            $unidade_administrativa_fk = $_POST['Raint']['unidade_administrativa_fk'];
            $objeto_fk = $_POST['Raint']['objeto_fk'];
            $bolInseriDescRecomendacao = $_POST['Raint']['bolInseriDescRecomendacao'];
            if ((($valor_exercicio != null) || ($valor_exercicio != 0)) && (!(!is_numeric($valor_exercicio) || strlen($valor_exercicio) != 4))) {
                $dados = $model->ObtemTotalDeRecomendacaoPorCategoria($valor_exercicio, $unidade_administrativa_fk, $objeto_fk);
                if (sizeof($dados)) {
                    if ($bolInseriDescRecomendacao) {
                        $descRecomendacao = $model->ObtemDescRecomendacaoPorCategoria($valor_exercicio, $unidade_administrativa_fk, $objeto_fk);
                    }
                    $this->render('recomendacaoCategoria/relatorio_recomendacao_categoria', array(
                        'model' => $model,
                        'dados' => $dados,
                        'descRecomendacao' => $descRecomendacao
                    ));
                } else {
                    $bolNaoEncontrouRegistro = true;
                }
            } else {
                $model->addError("valor_exercicio", "Exercício incorreto ou não informado");
            }
        }
        if ($dados == null) {
            $this->render('recomendacaoCategoria/index', array(
                'model' => $model,
                'titulo' => $this->titulo,
                'bolNaoEncontrouRegistro' => $bolNaoEncontrouRegistro
            ));
        }
    }

    public function actionRecomendacaoAuditoriaSemManifestacaoAjax() {
        $this->menu_acao = array(
            array('label' => 'Consultar', 'url' => array('RelatorioRaint/RecomendacaoAuditoriaSemManifestacaoAjax')),
        );
        $this->titulo = 'Relatório de Recomendações de auditoria sem manifestação';
        $this->subtitulo = 'Consultar';
        $bolNaoEncontrouRegistro = false;
        $model = new Raint();
        $dados = null;
        if ($_POST != null) {
            $valor_exercicio = $_POST['Raint']['valor_exercicio'];
            if ((($valor_exercicio != null) || ($valor_exercicio != 0)) && (!(!is_numeric($valor_exercicio) || strlen($valor_exercicio) != 4))) {
                $dados = $model->ObtemRecomendacoesSemManifestacao($valor_exercicio);
                if (sizeof($dados)) {
                    $this->render('recomendacaoAuditoriaSemManifestacao/relatorio_recomendacao_sem_manifestacao', array(
                        'model' => $model,
                        'dados' => $dados
                    ));
                } else {
                    $bolNaoEncontrouRegistro = true;
                }
            } else {
                $model->addError("valor_exercicio", "Exercício incorreto ou não informado");
            }
        }
        if ($dados == null) {
            $this->render('recomendacaoAuditoriaSemManifestacao/index', array(
                'model' => $model,
                'titulo' => $this->titulo,
                'bolNaoEncontrouRegistro' => $bolNaoEncontrouRegistro
            ));
        }
    }

    public function actionRecomendacaoNaoAvaliadaAuditorAjax() {
        $this->menu_acao = array(
            array('label' => 'Consultar', 'url' => array('RelatorioRaint/RecomendacaoNaoAvaliadaAuditorAjax')),
        );
        $this->titulo = 'Relatório de Recomendações não avaliadas pelo auditor';
        $this->subtitulo = 'Consultar';
        $model = new Raint();
        $bolNaoEncontrouRegistro = false;
        $dados = null;
        if ($_POST != null) {
            $valor_exercicio = $_POST['Raint']['valor_exercicio'];
            if ((($valor_exercicio != null) || ($valor_exercicio != 0)) && (!(!is_numeric($valor_exercicio) || strlen($valor_exercicio) != 4))) {
                $dados = $model->ObtemRecomendacoesNaoAvaliadasPeloAuditor($valor_exercicio);
                if (sizeof($dados)) {
                    $this->render('recomendacaoNaoAvaliadaAuditor/relatorio_recomendacao_nao_avaliadas_auditor', array(
                        'model' => $model,
                        'dados' => $dados
                    ));
                } else {
                    $bolNaoEncontrouRegistro = true;
                }
            } else {
                $model->addError("valor_exercicio", "Exercício incorreto ou não informado");
            }
        }
        if ($dados == null) {
            $this->render('recomendacaoNaoAvaliadaAuditor/index', array(
                'model' => $model,
                'titulo' => $this->titulo,
                'bolNaoEncontrouRegistro' => $bolNaoEncontrouRegistro
            ));
        }
    }

    public function actionRecomendacaoPendenteRespostaAuditadoAjax() {
        $this->menu_acao = array(
            array('label' => 'Consultar', 'url' => array('RelatorioRaint/RecomendacaoPendenteRespostaAuditadoAjax')),
        );
        $this->titulo = 'Relatório de Recomendações pendentes de resposta pelo auditado';
        $this->subtitulo = 'Consultar';
        $model = new Raint();
        $bolNaoEncontrouRegistro = false;
        $dados = null;
        if ($_POST != null) {
            $valor_exercicio = $_POST['Raint']['valor_exercicio'];
            if ((($valor_exercicio != null) || ($valor_exercicio != 0)) && (!(!is_numeric($valor_exercicio) || strlen($valor_exercicio) != 4))) {
                $dados = $model->ObtemRecomendacoesPendenteRespostaAuditado($valor_exercicio);
                if (sizeof($dados)) {
                    $this->render('recomendacaoPendenteRespostaAuditado/relatorio_recomendacao_pendente_resposta_auditado', array(
                        'model' => $model,
                        'dados' => $dados
                    ));
                } else {
                    $bolNaoEncontrouRegistro = true;
                }
            } else {
                $model->addError("valor_exercicio", "Exercício incorreto ou não informado");
            }
        }
        if ($dados == null) {
            $this->render('recomendacaoPendenteRespostaAuditado/index', array(
                'model' => $model,
                'titulo' => $this->titulo,
                'bolNaoEncontrouRegistro' => $bolNaoEncontrouRegistro
            ));
        }
    }

    public function actionRelatorioCGUAjax() {
        $this->menu_acao = array(
            array('label' => 'Consultar', 'url' => array('RelatorioRaint/RelatorioCGUAjax')),
        );
        $this->titulo = 'Relatório da CGU';
        $this->subtitulo = 'Consultar';
        $bolNaoEncontrouRegistro = false;
        $model = new Raint();
        $dados = null;
        if ($_POST != null) {
            $valor_exercicio = $_POST['Raint']['valor_exercicio'];
            if ((($valor_exercicio != null) || ($valor_exercicio != 0)) && (!(!is_numeric($valor_exercicio) || strlen($valor_exercicio) != 4))) {
                $dados = Item::model()->findAllByAttributes(
                        array(), $condition = "date_part('year', data_gravacao) = :exercicio order by numero_item desc", $params = array(
                    ':exercicio' => $valor_exercicio,
                ));
                if (sizeof($dados)) {
                    $this->render('relatorioCGU/relatorio_cgu', array(
                        'model' => $model,
                        'dados' => $dados
                    ));
                } else {
                    $bolNaoEncontrouRegistro = true;
                }
            } else {
                $model->addError("valor_exercicio", "Exercício incorreto ou não informado");
            }
        }
        if ($dados == null) {
            $this->render('relatorioCGU/index', array(
                'model' => $model,
                'titulo' => $this->titulo,
                'bolNaoEncontrouRegistro' => $bolNaoEncontrouRegistro
            ));
        }
    }

    public function actionRelatorioTempoExecucaoTrabalhoAuditorAjax() {
        $this->menu_acao = array(
            array('label' => 'Consultar', 'url' => array('RelatorioRaint/RelatorioTempoExecucaoTrabalhoAuditorAjax')),
        );
        $this->titulo = 'Relatório de Tempo de Execução dos Trabalhos por Auditor';
        $this->subtitulo = 'Consultar';
        $model = new Raint();
        $bolNaoEncontrouRegistro = false;
        $dados = null;
        if ($_POST != null) {
            $valor_exercicio = $_POST['Raint']['valor_exercicio'];
            if ((($valor_exercicio != null) || ($valor_exercicio != 0)) && (!(!is_numeric($valor_exercicio) || strlen($valor_exercicio) != 4))) {
                $dados = Relatorio::model()->findAllByAttributes(
                        array(), $condition = "numero_relatorio is not null and  data_relatorio is not null and date_part('year', data_relatorio) = :exercicio", $params = array(
                    ':exercicio' => $valor_exercicio,
                ));
                if (sizeof($dados)) {
                    $this->render('tempoExecucaoTrabalhoAuditor/relatorio_tempo_execucao_trabalho_auditor', array(
                        'model' => $model,
                        'dados' => $dados
                    ));
                } else {
                    $bolNaoEncontrouRegistro = true;
                }
            } else {
                $model->addError("valor_exercicio", "Exercício incorreto ou não informado");
            }
        }
        if ($dados == null) {
            $this->render('tempoExecucaoTrabalhoAuditor/index', array(
                'model' => $model,
                'titulo' => $this->titulo,
                    'bolNaoEncontrouRegistro' => $bolNaoEncontrouRegistro
            ));
        }
    }

    public function actionRecomendacaoPorAcaoAjax() {
        $this->menu_acao = array(
            array('label' => 'Consultar', 'url' => array('RelatorioRaint/RecomendacaoPorAcaoAjax')),
        );
        $this->titulo = 'Relatório de Recomendações por Ação Consolidado';
        $this->subtitulo = 'Consultar';
        $model = new Raint();
        $bolNaoEncontrouRegistro = false;
        $dados = null;
        if ($_POST != null) {
            $valor_exercicio = $_POST['Raint']['valor_exercicio'];
            if ((($valor_exercicio != null) || ($valor_exercicio != 0)) && (!(!is_numeric($valor_exercicio) || strlen($valor_exercicio) != 4))) {
                $dados = $model->ObtemRecomendacaoPorAcao($valor_exercicio);
                if (sizeof($dados)) {
                    $this->render('recomendacaoPorAcao/relatorio_recomendacao_acao', array(
                        'model' => $model,
                        'valor_exercicio' => $valor_exercicio,
                        'dados' => $dados
                    ));
                } else {
                    $bolNaoEncontrouRegistro = true;
                }
            } else {
                $model->addError("valor_exercicio", "Exercício incorreto ou não informado");
            }
        }
        if ($dados == null) {
            $this->render('recomendacaoPorAcao/index', array(
                'model' => $model,
                'titulo' => $this->titulo,
                'bolNaoEncontrouRegistro' => $bolNaoEncontrouRegistro
            ));
        }
    }

    public function actionRecomendacaoPorSubCategoriaAjax() {
        $this->menu_acao = array(
            array('label' => 'Consultar', 'url' => array('RelatorioRaint/RecomendacaoPorSubCategoriaAjax')),
        );
        $this->titulo = 'Relatório de Recomendações por SubCategoria';
        $this->subtitulo = 'Consultar';
        $dadosCategoria = null;
        $descRecomendacao = null;
        $bolNaoEncontrouRegistro = false;
        $model = new Raint();
        if ($_POST != null) {
            $valor_exercicio = $_POST['Raint']['valor_exercicio'];
            $unidade_administrativa_fk = $_POST['Raint']['unidade_administrativa_fk'];
            $objeto_fk = $_POST['Raint']['objeto_fk'];
            $bolInseriDescRecomendacao = $_POST['Raint']['bolInseriDescRecomendacao'];
            if ((($valor_exercicio != null) || ($valor_exercicio != 0)) && (!(!is_numeric($valor_exercicio) || strlen($valor_exercicio) != 4))) {
                $dadosCategoria = $model->ObtemTotalDeRecomendacaoPorCategoria($valor_exercicio, $unidade_administrativa_fk, $objeto_fk);
                if (sizeof($dadosCategoria)) {
                    $dadosSubCategoria = $model->ObtemTotalDeRecomendacaoPorSubCategoria($valor_exercicio, $unidade_administrativa_fk, $objeto_fk);
                    if ($bolInseriDescRecomendacao) {
                        $descRecomendacao = $model->ObtemDescRecomendacaoPorCategoria($valor_exercicio, $unidade_administrativa_fk, $objeto_fk);
                    }
                    $this->render('recomendacaoSubCategoria/relatorio_recomendacao_subcategoria', array(
                        'model' => $model,
                        'dadosCategoria' => $dadosCategoria,
                        'dadosSubCategoria' => $dadosSubCategoria,
                        'descRecomendacao' => $descRecomendacao
                    ));
                } else {
                    $bolNaoEncontrouRegistro = true;
                }
            } else {
                $model->addError("valor_exercicio", "Exercício incorreto ou não informado");
            }
        }
        if ($dadosCategoria == null) {
            $this->render('recomendacaoSubCategoria/index', array(
                'model' => $model,
                'titulo' => $this->titulo,
                'bolNaoEncontrouRegistro' => $bolNaoEncontrouRegistro
            ));
        }
    }

    public function actionRelatorioRiscoPorObjetoAjax() {
        $this->menu_acao = array(
            array('label' => 'Consultar', 'url' => array('RelatorioRaint/RelatorioRiscoPorObjetoAjax')),
        );
        $this->titulo = 'Relatório de Risco por objeto';
        $this->subtitulo = 'Consultar';
        $dadosRiscoPreIdentificados = null;
        $bolNaoEncontrouRegistro = false;
        $model = new Raint();
        if ($_POST != null) {
            $valor_exercicio = $_POST['Raint']['valor_exercicio'];
            $objeto_fk = $_POST['Raint']['objeto_fk'];
            if ((($valor_exercicio != null) || ($valor_exercicio != 0)) && (!(!is_numeric($valor_exercicio) || strlen($valor_exercicio) != 4))) {
                $dadosRiscoPreIdentificados = $model->ObtemRiscoPreIdentificados($valor_exercicio, $objeto_fk);
                $dadosRiscoPosIdentificados = $model->ObtemRiscoPosIdentificados($valor_exercicio, $objeto_fk);
                if ((sizeof($dadosRiscoPreIdentificados)) || (sizeof($dadosRiscoPosIdentificados))) {
                    $this->render('relatorioRiscoObjeto/relatorio_risco_objeto', array(
                        'model' => $model,
                        'dadosRiscoPreIdentificados' => $dadosRiscoPreIdentificados,
                        'dadosRiscoPosIdentificados' => $dadosRiscoPosIdentificados
                    ));
                } else {
                    $bolNaoEncontrouRegistro = true;
                }
            } else {
                $model->addError("valor_exercicio", "Exercício incorreto ou não informado");
            }
        }
        if (($dadosRiscoPreIdentificados == null) && ($dadosRiscoPosIdentificados == null)) {
            $this->render('relatorioRiscoObjeto/index', array(
                'model' => $model,
                'titulo' => $this->titulo,
                'bolNaoEncontrouRegistro' => $bolNaoEncontrouRegistro
            ));
        }
    }

}
