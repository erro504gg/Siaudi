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

class RelatorioPreFinalizaController extends GxController {

    public $titulo = 'Pré Finalizar Relatório';

    public function init() {
        parent::init();
        $this->defaultAction = 'index';
    }

    public function actionIndex() {
        $id = $_POST['Relatorio']['id'];
        if ($id) {

            $model = $this->loadModel($id, 'Relatorio');
            $model->login_pre_finaliza = Yii::app()->user->login;
            $model->data_pre_finalizado = date("Y-m-d");
                       
            try{
            if ($model->save(false)) {
                
                $model->pre_finalizar_relatorio($id);
                $this->setFlashSuccesso(($id > 0 ? 'alterar' : 'inserir'));
                $this->redirect(array('index', 'id' => $model->id));
            }} catch (Exception $e){
                print_r($e->getMessage());
            }
        }

        $model = new Relatorio;
        $this->render('index', array(
            'titulo' => $this->titulo,
            'model' => $model
        ));
    }

}
