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

class UnidadeAdministrativaController extends GxController {

    public $titulo = 'Unidade Administrativa (Lotação)';

    public function init() {
        parent::init();
        $this->menu_acao = array(
            array('label' => 'Consultar', 'url' => array('UnidadeAdministrativa/index')),
            array('label' => 'Incluir', 'url' => array('UnidadeAdministrativa/admin')),
        );
    }

    public function actionAdmin($id = 0) {
        if (!empty($id)) {	
            $this->subtitulo = 'Atualizar';
            $model = $this->loadModel($id, 'UnidadeAdministrativa');
        } else {
            $this->subtitulo = 'Inserir';
            $model = new UnidadeAdministrativa;
        }

        if (isset($_POST['UnidadeAdministrativa'])) {
            $model->attributes = $_POST['UnidadeAdministrativa'];
            $model->nome = strtoupper($_POST['UnidadeAdministrativa']['nome']);
            $model->sigla = strtoupper($_POST['UnidadeAdministrativa']['sigla']);
            $model->subordinante_fk = $_POST['UnidadeAdministrativa']['subordinante_fk'] ? $_POST['UnidadeAdministrativa']['subordinante_fk'] : null; 


        
            // valida data, se o parï¿½metro foi passado
            $salvar = 1;
            $data = $_POST["UnidadeAdministrativa"]["data_extincao"];
            if ($data != "") {
                $data_valida = explode("/", $data); //formato brasileiro
                $data_valida = checkdate($data_valida[1], $data_valida[0], $data_valida[2]); //ordem dos parÃ¢metros: mes, dia e ano.
                if (!$data_valida) {
                    $model->addError("data_extincao", "Data de Extinção inválida");
                    $salvar = 0;
                }
            }            
            
            $model->data_extincao = $_POST['UnidadeAdministrativa']['data_extincao'] ? $_POST['UnidadeAdministrativa']['data_extincao'] : null;
            
            if ($salvar){
                if ($model->save()) {
                    $this->setFlashSuccesso(($id > 0 ? 'alterar' : 'inserir'));
                    $this->redirect(array('index?' . $_SERVER['QUERY_STRING']));
                }
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
        	
            $this->loadModel($id, 'UnidadeAdministrativa')->delete();

            if (Yii::app()->getRequest()->getIsAjaxRequest()) {
                $this->setFlashSuccesso('excluir');
                echo $this->getMsgSucessoHtml();
            } else {
                $this->setFlashError('excluir');
                $this->redirect(array('admin'));
            }
        }
        else
            throw new CHttpException(400, Yii::t('app', 'Sua requisiï¿½ï¿½o ï¿½ invï¿½lida.'));
    }

    public function actionIndex() {
        $this->subtitulo = 'Consultar';
        $dados = null;
        $model = new UnidadeAdministrativa('search');
//        debug($_GET);
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['UnidadeAdministrativa'])) {
            $model->attributes = $_GET['UnidadeAdministrativa'];
            $model->diretoria = $_GET['UnidadeAdministrativa']['diretoria'] ? true : false;
            $model->sureg = $_GET['UnidadeAdministrativa']['sureg'] ? true : false;
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
            'model' => $this->loadModel($id, 'UnidadeAdministrativa'),
        ));
    }

    public function ObtemDadosUF() {
        $estados = array(
            "AC" => "AC",
            "AL" => "AL",
            "AM" => "AM",
            "AP" => "AP",
            "BA" => "BA",
            "CE" => "CE",
            "DF" => "DF",
            "ES" => "ES",
            "GO" => "GO",
            "MA" => "MA",
            "MT" => "MT",
            "MS" => "MS",
            "MG" => "MG",
            "PA" => "PA",
            "PB" => "PB",
            "PR" => "PR",
            "PE" => "PE",
            "PI" => "PI",
            "RJ" => "RJ",
            "RN" => "RN",
            "RO" => "RO",
            "RS" => "RS",
            "RR" => "RR",
            "SC" => "SC",
            "SE" => "SE",
            "SP" => "SP",
            "TO" => "TO");
        return $estados;
    }

   

}
