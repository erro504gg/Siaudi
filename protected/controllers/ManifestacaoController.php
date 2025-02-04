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

class ManifestacaoController extends GxController {

//    public   $titulo = 'Relatório para Manifestação';

    public function init() {
        if (!Yii::app()->user->verificaPermissao("Manifestacao", "admin")) {
            $this->redirect(array('site/acessoNegado')); 
            exit;
        }
        
        parent::init();
        $this->defaultAction = 'index';
    }      
    
    public function actionIndex() {
       
        $this->titulo  = 'Relatório para Manifestação';        
        $this->subtitulo = 'Consultar';
        $dados = null;
        $model = new Manifestacao();

        $this->render('index', array(
            'model' => $model,
            'dados' => $dados,
            'titulo' => $this->titulo,
        ));
    }    
    
    public function actionManifestacaoRelatorioAjax($id = 0) {
        $titulo = 'Relatório para Manifestação';
        $this->titulo  = 'Relatório para Manifestação';                
        $this->subtitulo = 'Inserir';
        $model = new Manifestacao();    
        
        if (isset($_POST['Manifestacao'])) {
            // Login
            $model->nome_login = Yii::app()->user->login;
            $model->relatorio_fk = $_POST['relatorio_fk'];
            // Pega sureg do auditado para gravar na tb_manifestacao
            $RelatorioAcesso = RelatorioAcesso::model()->findbyAttributes(array('nome_login'=>$model->nome_login,'relatorio_fk'=>$model->relatorio_fk));
            $model->unidade_administrativa_fk = $RelatorioAcesso->unidade_administrativa_fk;
            $model->data_manifestacao= date("Y-m-d");
            if($_POST['resposta']==1){ $model->status_manifestacao=1;
                                        $manifestacao="positiva";
            } else { $model->status_manifestacao=0;
                     $model->descricao_manifestacao = $_POST['Manifestacao']['descricao_manifestacao'];            
                     $manifestacao="negativa";
            } 

            if ($model->save()) {
                $verifica = Manifestacao::model()->VerificaManifestacao($model->relatorio_fk,$manifestacao);
                $this->setFlashSuccesso( ($id > 0 ? 'alterar' : 'inserir') );
                $this->redirect(array('/Manifestacao'));
           }
        }

        $this->render('manifestacao_relatorio', array(
            'model' => $model,
            'id'=>$id,
            'titulo' => $this->titulo,
        ));
    }


    public function actionResponderManifestacaoAjax($id = 0) {
        $this->titulo  = 'Manifestação';
        $this->subtitulo = 'Responder Manifestação';
        $model = new Manifestacao();    
        
        if (isset($_POST['Manifestacao'])){
            $model = $this->loadModel($id , 'Manifestacao');
            $model->data_resposta = date("Y-m-d");
            $model->descricao_resposta = $_POST['Manifestacao']['descricao_resposta'];
            $model->nome_login_resposta = Yii::app()->user->login; 
            $unidade_administrativa_fk = $model->unidade_administrativa_fk;
            if ($model->save()) {
                $verifica = Manifestacao::model()->VerificaManifestacao($model->relatorio_fk,'resposta',$unidade_administrativa_fk);
                $this->setFlashSuccesso( ($id > 0 ? 'alterar' : 'inserir') );
                $this->redirect(array('/Manifestacao/ResponderManifestacaoAjax'));
           }            
            
        }
        $this->render('manifestacao_responder', array(
                 'model' => $model,
                 'id'=>$id,
                 'titulo' => $this->titulo,
             ));                
         }
         
         
    public function actionManifestacaoSaidaAjax($id = 0) {
        $this->titulo  = 'Relatório de Manifestação';
        $this->subtitulo = 'Consultar';
        $dados = null;
        $model = new Manifestacao();

        $this->render('manifestacao_saida', array(
            'id' => $id,
            'model' => $model,
            'dados' => $dados,
            'titulo' => $this->titulo,
        ));
    }      
    
    
    

    // recebe o tipo de relatório  (1=> Não homologado, 2=> Homologado)
    // para carregar combo de Relatório de Manifestação
    public function actionCarregaManifestacaoSaidaAjax() {
        $tipo_relatorio = $_POST['Manifestacao']['tipo_relatorio'];
        $data = Manifestacao::model()->CarregaManifestacaoSaida($tipo_relatorio);
             if($tipo_relatorio==0) { 
              echo CHtml::tag('option',array('value'=>''),CHtml::encode(''),true); 
           } else {                  
               
            if (sizeof($data)>0) { echo CHtml::tag('option',array('value'=>''),CHtml::encode('Selecione'),true); }
                            else { echo CHtml::tag('option',array('value'=>''),CHtml::encode('Sem relatórios com manifestacao'),true); }
           
            foreach($data as $vetor){               
                // monta combo para relatórios não homologados
                if ($tipo_relatorio==1){ 
                    if ($vetor[sureg_sigla]){  $descricao = $vetor[sureg_sigla] ."/" .$vetor[sigla_auditoria]; }
                                       else {  $descricao = $vetor[sigla_auditoria];}
                    echo CHtml::tag('option',array('value'=>$vetor[manifestacao_id]),CHtml::encode($vetor[relatorio_id] ." - ".$descricao),true);
                }
                // monta combo para relatórios homologados
                if ($tipo_relatorio==2){ 
                     $data_relatorio = MyFormatter::converteData($vetor[data_relatorio]);
                    echo CHtml::tag('option',array('value'=>$vetor[manifestacao_id]),CHtml::encode($vetor[numero_relatorio] ." de ".$data_relatorio),true);
                }                
            }
          }
    }
    
 


    
    // recebe o tipo de relatório  (1=> Não homologado, 2=> Homologado)
    // para carregar combo de Relatório de Manifestação 
    // (auditado e auditor vê somente os relatórios envolvidos,
    // gerente vê todos os relatórios)
    public function actionCarregaManifestacaoGerenteSaidaAjax() {
        $perfil = strtolower(Yii::app()->user->role);   
        $perfil = str_replace("siaudi2","siaudi",$perfil);
        $tipo_relatorio = $_POST['Manifestacao']['tipo_relatorio'];
        $data = Manifestacao::model()->CarregaManifestacaoGerenteSaida($tipo_relatorio);
        
             if($tipo_relatorio==0) { 
              echo CHtml::tag('option',array('value'=>''),CHtml::encode(''),true); 
           } else {                  
               
            if (sizeof($data)>0) { echo CHtml::tag('option',array('value'=>''),CHtml::encode('Selecione'),true); }
                            else { echo CHtml::tag('option',array('value'=>''),CHtml::encode('Sem relatórios com manifestacao'),true); }
           
            foreach($data as $vetor){
                // monta combo para relatórios não homologados
                if ($tipo_relatorio==1){ 
                    echo CHtml::tag('option',array('value'=>$vetor[manifestacao_id]),CHtml::encode($vetor[relatorio_id] ." - ".$vetor[sigla_auditoria]),true);
                }
                
                // monta combo para relatórios homologados
                if ($tipo_relatorio==2){ 
                     $data_relatorio = MyFormatter::converteData($vetor[data_relatorio]);
                    echo CHtml::tag('option',array('value'=>$vetor[manifestacao_id]),CHtml::encode($vetor[numero_relatorio] ." de ".$data_relatorio),true);
                }                
            }
          }
    }          

}
