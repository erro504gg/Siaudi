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
    // tela de consulta dos relatórios possíveis para resposta
    // da manifestação
    if (!$id){ ?>
        <div class="formulario" Style="width: 70%;">

            <?php
            $baseUrl = Yii::app()->baseUrl; 
            $cs = Yii::app()->getClientScript();    
            $cs->registerScriptFile($baseUrl.'/js/Manifestacao.js');
            $form = $this->beginWidget('GxActiveForm', array(
                'action' => Yii::app()->createUrl($this->route),
                'method' => 'get',
                'id'=>'Manifestacao'
                    ));
            ?>
            <fieldset class="visivel">
                <legend class="legendaDiscreta">Consultar -  Manifestações por Relatório / Unidade Regional</legend>

                <div class='row'>
                    <div class='label'><?php echo $form->label($model, 'relatorio_fk'); ?></div>
                    <div class='field'>
                            <?php
                            $list_relatoriofk= array(null=> "Selecione") + GxHtml::listDataEx(Manifestacao::model()->RelatorioManifestacaoResponder(),'id','sigla_auditoria');
                            echo CHtml::dropDownList('relatorio_fk', null, $list_relatoriofk, 
                                                                    array('style' => 'width:130px;',
                                                                          'onchange'=>'valida_responder_manifestacao()')); 
                            ?>                
                    </div>
                </div> 
            </fieldset>

        <?php $this->endWidget(); ?>
        </div><!-- search-form -->

<?php 
        // tela com manifestação 
        }else{ 
?>

<div class="formulario" Style="width: 70%;">
    <?php
        // Verifica se manifestação (id) existe. 
       $Manifestacao = Manifestacao::model()->findByAttributes(array('id'=>$id));
       if(sizeof($Manifestacao)==0){
            echo "<br><br><div align=center><b>Manifestação não encontrada. </b></div>";            
            $manifestacao_erro=1;
       }        
       
     // Verifica se esta manifestação já foi respondida .
     // (mesmo que o siaudi_cliente ou chefe tenha sido trocado)
       $Manifestacao_respondida = Manifestacao::model()->find('id='.$id.' AND descricao_resposta IS NOT NULL');       
       if($Manifestacao_respondida){
            echo "<br><br><div align=center><b>A manifestação deste relatório já foi respondida. </b></div>";            
            $manifestacao_erro=1;
       } 
       
    // Se não houve erro, então exibe a tela de resposta da manifestação
    if($manifestacao_erro==0){
    $baseUrl = Yii::app()->baseUrl; 
    $cs = Yii::app()->getClientScript();    
    $cs->registerScriptFile($baseUrl.'/js/Manifestacao.js');        
    
    $Relatorio= Relatorio::model()->findByAttributes(array('id'=>$Manifestacao->relatorio_fk));        
    $Especie_auditoria = EspecieAuditoria::model()->findByAttributes(array('id'=>$Relatorio->especie_auditoria_fk));
    $UnidadeAdministrativa = UnidadeAdministrativa::model()->findByAttributes(array('id'=>$Manifestacao->unidade_administrativa_fk));
    $form = $this->beginWidget('GxActiveForm', array(
        'id' => 'manifestacao-form',
        'enableAjaxValidation' => false,
            ));
    echo $form->errorSummary($model); ?>

    <table align="center">
        <Tr>
            <td align="right"><b>Relatório ID: </b></td>
            <td><?php echo $Relatorio->id ." -  " . $Especie_auditoria->nome_auditoria;?></td>            
        </tr>
        <Tr>
            <td align="right"><b>Unidade Auditada: </b></td>
            <td><?php echo $UnidadeAdministrativa->nome;?></td>            
        </tr>        
    </table>

    <br><br>
   
    <fieldset class="visivel">
        <legend class="legendaDiscreta" style="color:black;font-size:12px;"> <b>Manifestação de <?php echo $Manifestacao->nome_login; ?> em <?php echo $Manifestacao->data_manifestacao;?> </b></legend>
        <br><div style="font-size:13px;text-align:justify">
          <?php echo $Manifestacao->descricao_manifestacao; ?>  
        </div>

        <div style='height:30px;'></div>        
        <div class='row'>
            <div class='label'><?php echo $form->labelEx($model, 'descricao_resposta'); ?><span class="required">*</span></div>
            <div class='field'><?php echo $form->textArea($model, 'descricao_resposta',array('cols'=>100,'rows'=>8)); ?></div>
        </div><!-- row -->        

    </fieldset>
    <p class="note">
        <?php echo Yii::t('app', 'Fields with'); ?>
        <span class="required">*</span>
    <?php echo Yii::t('app', 'are required'); ?>.
    </p>
    <div class="rowButtonsN1">
      <div id="botao_submit">
         <a class="imitacaoBotao" href="javascript:valida_envio_resposta();">Confirmar</a>
        <?php echo CHtml::link(Yii::t('app', 'Cancel'), $this->createUrl('/Manifestacao/ResponderManifestacaoAjax'), array('class' => 'imitacaoBotao')); ?>
      </div>
    </div>
<?php $this->endWidget(); ?>
</div><!-- form -->

<?php 
  }
}
?>
