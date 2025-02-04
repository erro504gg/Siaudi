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
<div class="formulario" Style="width: 70%;">
    <?php
    // Verifica se usuário tem permissão para manifestar
    // neste relatório (ou se o prazo já expirou)
    $relatorios = Manifestacao::model()->RelatorioManifestacaoPrazo();
    foreach ($relatorios as $vetor){
        if($vetor[id]==$id){
            $autorizado=1;
        }
    }
    if(!$autorizado){ echo "<br><br><div align=center><b>Acesso negado</b></div>"; } 
    else {
    
     // Verifica se esta Unidade Auditada já se manifestou para este relatório
     // (mesmo que o siaudi_cliente ou chefe tenha sido trocado)
       $Manifestacao_realizada = Manifestacao::model()->VerificaManifestacaoAuditado($id);
       if($Manifestacao_realizada){
            echo "<br><br><div align=center><b>A manifestação deste relatório já foi enviada. </b></div>";            
       } else {
           

     // Verifica se existe alguma avaliação de auditor
     // a ser feita antes de realizar a manifestação
       $login= Yii::app()->user->login;
       $RelatorioAcesso=  RelatorioAcesso::model()->findByAttributes(array('relatorio_fk'=>$id,'nome_login'=>$login));
       $sureg = $RelatorioAcesso->unidade_administrativa_fk;
       $avaliacao_auditor = Avaliacao::model()->VerificaAvaliacao($id,$sureg);
       
       // variável $avaliacao_auditor contém o ID do auditor a 
       // ser avaliado ou vazio caso todas as avaliações já tenham
       // sido realizadas
       if ($avaliacao_auditor) {
           $confirma = ($_GET["confirma"]) ? "&confirma=1":"";
           header("Location:../../Avaliacao/admin/?relatorio={$id}&sureg={$sureg}&auditor={$avaliacao_auditor}{$confirma}");
           exit; 
       } else {
           
        
        if ($_GET["confirma"]==1){
            $this->setFlashSuccesso( ($id > 0 ? 'alterar' : 'inserir') );
        }           
    $baseUrl = Yii::app()->baseUrl; 
    $cs = Yii::app()->getClientScript();    
    $cs->registerScriptFile($baseUrl.'/js/Manifestacao.js');        
    $Relatorio= Relatorio::model()->findbyAttributes(array('id'=>$id));        
    $form = $this->beginWidget('GxActiveForm', array(
        'id' => 'manifestacao-form',
        'enableAjaxValidation' => false,
            ));
    ?>    
<?php echo $form->errorSummary($model); ?>
            <div style="text-align:center;width:800px;">Relatório ID:
             <?php $Especie_auditoria = EspecieAuditoria::model()->findbyAttributes(array('id'=>$Relatorio->especie_auditoria_fk));
                   echo $id." -  " . $Especie_auditoria->sigla_auditoria;
             ?>
            </div><br><br>
   
    <fieldset class="visivel">
        <legend class="legendaDiscreta" style="color:black;font-size:12px;"> <b>Esta manifestação pode ser enviada até <?php echo Feriado::model()->DiasUteis($Relatorio->data_finalizado,5);?> </b></legend>
        <br><br><div style="font-size:13px;text-align:justify">
        A manifestação é um importante mecanismo de controle. No caso de não manifestação em tempo hábil - 5 dias úteis -, considerar-se-ão pertinentes os fatos relatados pela equipe de auditores internos. 
        Os fatos abordados no Relatório de Auditoria <b>Id Relatório: <?php echo $id;?></b> estão condizentes com os objetivos traçados pela equipe de auditores internos na reunião de abertura, com os trabalhos de campo efetivamente realizados e na reunião de encerramento? 
        </div>

        <div style='height:10px;'></div>
        <div class='row'>
            <div class='label'>Resposta</div>
            <div class='field'><input id='resposta_sim' name='resposta' type='radio' value="1" /><label for="resposta_sim"> Sim </label> 
                                &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; 
                              <input id='resposta_nao' name='resposta' type='radio' value="2" /><label for="resposta_nao">Não </label><br />
            </div>
        </div><!-- row -->        

        <div style='height:10px;'></div>        
        <div class='row' id='descricao_manifestacao' style='display:none'>
            <div class='label'><?php echo $form->labelEx($model, 'descricao_manifestacao'); ?></div>
            <div class='field'><?php echo $form->textArea($model, 'descricao_manifestacao',array('cols'=>100,'rows'=>8)); ?></div>
        </div><!-- row -->        

    </fieldset>
    <p class="note">
        <?php echo Yii::t('app', 'Fields with'); ?>
        <span class="required">*</span>
    <?php echo Yii::t('app', 'are required'); ?>.
    </p>
    <div class="rowButtonsN1">
         <input type="hidden" name="relatorio_fk" value="<?php echo $id;?>">
         <div id="botao_submit">
             <a class="imitacaoBotao" href="javascript:valida_envio();">Confirmar</a>
        <?php echo CHtml::link(Yii::t('app', 'Cancel'), $this->createUrl('/Manifestacao'), array('class' => 'imitacaoBotao')); ?>
         </div>             
    </div>
<?php $this->endWidget(); ?>
</div><!-- form -->

<?php 
        }
    }
}
?>
