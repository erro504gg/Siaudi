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

class RelatorioFinalizaController extends GxController {

    public   $titulo = 'Finalizar Relatório';
    
    public function init() {
        if (!Yii::app()->user->verificaPermissao("RelatorioFinaliza", "admin")) {
            $this->redirect(array('site/acessoNegado')); 
            exit;
        }                
        
        parent::init();
        $this->defaultAction = 'index';
    }             

    public function actionIndex() {
        $id = $_POST['Relatorio']['id'];   
        $prazo_manifestacao = $_POST['Relatorio']['prazo_manifestacao'];

        if($id){        
            $model = $this->loadModel($id , 'Relatorio');            
            $model->login_finaliza = Yii::app()->user->login;
            $model->data_finalizado = date("Y-m-d");
            
            /* se prazo para manifestação=0 , então
             * bloqueia manifestação e vai direto para  homologação
             */         
            if ($prazo_manifestacao==0) {  $model->st_libera_homologa = 1; }

            if ($model->save(false)) { 
                    $model->finalizar_relatorio($id,$prazo_manifestacao);
                    $this->setFlashSuccesso( ($id > 0 ? 'alterar' : 'inserir') );
                    $this->redirect(array('index', 'id' => $model->id));
            }        

        }

        $model = new Relatorio;    
        $this->render('index', array(
            'titulo' => $this->titulo,
            'model' => $model
        ));
    }
}
