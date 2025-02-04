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

class RelatorioAcessoItemController extends GxController {

    public $titulo = 'Permissão de Acesso ao Item';

    public function init() {
        if (!Yii::app()->user->verificaPermissao("RelatorioAcessoItem", "admin")) {
            $this->redirect(array('site/acessoNegado'));
            exit;
        }

        parent::init();
        $this->defaultAction = 'index';
    }

    public function actionAdmin() {
        $this->subtitulo = 'Inserir';
        $model = new RelatorioAcessoItem;
        
        if ($_POST['yt0'] == 'Confirmar') {
            if ($this->validaInsert($model)) {
                if (isset($_POST['RelatorioAcessoItem'])) {
                    $model->InserirRegistros($_POST);
                }
            }
        }

        $this->render('admin', array(
            'model' => $model,
            'titulo' => $this->titulo,
        ));
    }

    public function validaInsert($model) {
        $bolNaoEncontrouErro = true;
        if (!$_POST['relatorio_fk']) {
            $bolNaoEncontrouErro = false;
            $model->addError('relatorio_fk', 'O relatório deve ser informado.');
        }
        if ((!isset($_POST['RelatorioAcessoItem']['item_fk'])) && (!$_POST['RelatorioAcessoItem']['liberar_todos_itens'])) {
            $bolNaoEncontrouErro = false;
            $model->addError('item_fk', 'Deve ser selecionado pelo menos um item.');
        }
        if (!$_POST['RelatorioAcessoItem']['auditor_fk']) {
            $bolNaoEncontrouErro = false;
            $model->addError('nome_login', 'O auditor deve ser informado.');
        }
        return $bolNaoEncontrouErro;
    }

    public function validaConsulta($model) {
        $bolNaoEncontrouErro = true;
        if ($_GET != null) {
            if (!(($_GET['RelatorioAcessoItem']['id_login']) || ($_GET['RelatorioAcessoItem']['unidade_administrativa_fk']))) {
                $bolNaoEncontrouErro = false;
                $model->addError('id_login', 'Pelo menos um parâmetro deve ser informado.');
            }
        }
        return $bolNaoEncontrouErro;
    }

    public function actionIndex() {
        $this->subtitulo = 'Consultar';
        $dados = null;
        $model = new RelatorioAcessoItem('search');

        if ($this->validaConsulta($model)) {
            $id = $_POST[relatorio_acesso_item_id];
            if ($id) {
                $this->loadModel($id, 'RelatorioAcessoItem')->delete();
            } elseif (($_GET['RelatorioAcessoItem']['id_login']) || ($_GET['RelatorioAcessoItem']['unidade_administrativa_fk'])) {
                $model->unsetAttributes();  // clear any default values
                if (isset($_GET['RelatorioAcessoItem'])) {
                    $model->attributes = $_GET['RelatorioAcessoItem'];
                    $dados = $model->BuscaRegistro($_GET['RelatorioAcessoItem']['id_login'], $_GET['RelatorioAcessoItem']['unidade_administrativa_fk']);
                }
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
            'model' => $this->loadModel($id, 'RelatorioAcessoItem'),
        ));
    }

}
