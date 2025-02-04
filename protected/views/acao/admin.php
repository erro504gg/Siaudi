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
    $form = $this->beginWidget('GxActiveForm', array(
        'id' => 'acao-form',
        'enableAjaxValidation' => false,
    ));

    $baseUrl = Yii::app()->baseUrl;
    $cs = Yii::app()->getClientScript();
    $cs->registerScriptFile($baseUrl . '/js/Acao.js');

    
    ?>    
    <?php echo $form->errorSummary(array($model, $model_acao_sureg)); ?>
    <fieldset class="visivel">
        <legend class="legendaDiscreta"> <?php echo($model->isNewRecord ? 'Adicionar' : 'Atualizar'); ?> -  <?php echo $model->label(); ?></legend>

        <div class='row'>
            <div class='label'><?php echo $form->labelEx($model, 'valor_exercicio'); ?></div>
            <div class='field'><?php /* echo $form->textField($model, 'valor_exercicio'); */ ?>
                <?php
                echo
                $form->textField($model, 'valor_exercicio', array('maxlength' => 4,
                    'size' => 15,
                    'ajax' => array(
                        'type' => 'POST',
                        'url' => CController::createUrl('Acao/CarregaProcessoAjax'),
						'beforeSend' => 'function(){
									$("#div_processo_riscopre").html("");
								 }',
                        'update' => '#processo_fk',
                    )
                ));
                ?>              
                (Ex: <?php echo date(Y); ?>)            
            </div>
        </div><!-- row -->     


        <div class='row'>
            <div class='label'><?php echo $form->labelEx($model, 'acao_mes'); ?></div>
            <div class='field'>
                <?php
                $form->widget('ext.EchMultiSelect.EchMultiSelect', array(
                    'model' => $model,
                    'dropDownAttribute' => 'acao_mes',
                    'data' => array('1' => 'Janeiro',
                        '2' => 'Fevereiro',
                        '3' => 'Março',
                        '4' => 'Abril',
                        '5' => 'Maio',
                        '6' => 'Junho',
                        '7' => 'Julho',
                        '8' => 'Agosto',
                        '9' => 'Setembro',
                        '10' => 'Outubro',
                        '11' => 'Novembro',
                        '12' => 'Dezembro'),
                    'dropDownHtmlOptions' => array(
                        'id' => 'acao_mes',
                        'multiple' => true,
                    ),
                    'options' => array(
                        'selectedList' => 4,
                        'minWidth' => '410',
                        'filter' => true,
                    ),
                    'filterOptions' => array(
                        'width' => 150,
                        'label' => Yii::t('application', 'Filtrar:'),
                        'placeholder' => Yii::t('application', 'digite aqui'),
                        'autoReset' => false,
                    ),
                ));
                // marca no select multiplo as opções previamente salvas
                if (is_array($acao_mes)) {
                    echo "\n <script type='text/javascript'> { \n";
                    foreach ($acao_mes as $vetor) {
                        echo "$(\"#acao_mes option[value='" . $vetor->numero_mes . "']\").attr('selected', 'selected'); \n";
                    }
                    echo "}</script>";
                }
                ?>

            </div>
        </div><!-- row -->  


        <div style="height:10px;"/></div>
<div class='row'>
    <div class='label'><?php echo $form->labelEx($model, 'especie_auditoria_fk'); ?></div>
    <div class='field'><?php echo $form->dropDownList($model, 'especie_auditoria_fk', array(null => "Selecione") +  GxHtml::listDataEx(EspecieAuditoria::model()->findAllAttributes(null, true))); ?></div>
</div><!-- row -->        


<div style="height:15px;"/></div>
<div class='row'>
    <div class='label'><?php echo $form->labelEx($model, 'descricao_apresentacao'); ?></div>
    <div class='field'><?php echo $form->textArea($model, 'descricao_apresentacao', array('cols' => '100', 'rows' => '5')); ?></div>
</div><!-- row -->

<div style="height:10px;"/></div>

<div class='row'>
    <div class='label'><?php echo $form->labelEx($model, 'numero_acao'); ?></div>
    <div class='field'><?php echo $form->textField($model, 'numero_acao', array('maxlength' => 4)); ?>
    </div>
</div><!-- row -->      

<div style="height:15px;"/></div>

<div class='row'>
    <div class='label'><?php echo $form->labelEx($model, 'nome_acao'); ?></div>
    <div class='field'><?php echo $form->textField($model, 'nome_acao', array('maxlength' => 1000, 'size' => 102)); ?></div>
</div><!-- row -->

<div class='row'>
    <div class='label'><?php echo $form->labelEx($model_acao_sureg, 'acao_sureg'); ?></div>
    <div class='field'>
        <?php
        $acao_sureg_dados = CHtml::listData(UnidadeAdministrativa::model()->findAll(array("condition" => '"sureg" = true', 'order' => 'sigla')), 'id', 'sigla');
        $form->widget('ext.EchMultiSelect.EchMultiSelect', array(
            'model' => $model_acao_sureg,
            'dropDownAttribute' => 'acao_sureg',
            'data' => $acao_sureg_dados,
            'dropDownHtmlOptions' => array(
                'id' => 'acao_sureg',
                'multiple' => true,
            ),
            'options' => array(
                'selectedList' => 1,
                'minWidth' => '410',
                'filter' => true,
            ),
            'filterOptions' => array(
                'width' => 150,
                'label' => Yii::t('application', 'Filtrar:'),
                'placeholder' => Yii::t('application', 'digite aqui'),
                'autoReset' => false,
            ),
        ));
        // marca no select multiplo as opções previamente salvas
        if (is_array($acao_sureg)) {
            echo "\n <script type='text/javascript'> { \n";
            foreach ($acao_sureg as $vetor) {
                echo "$(\"#acao_sureg option[value='" . $vetor->unidade_administrativa_fk . "']\").attr('selected', 'selected'); \n";
            }
            echo "}</script>";
        }


        // marca no select múltiplo as ações que vieram do $_POST
        $acao_sureg2 = $_POST['AcaoSureg']['acao_sureg'];
        if (sizeof($acao_sureg2) > 0) {
            echo "\n <script type='text/javascript'> { \n";
            foreach ($acao_sureg2 as $vetor) {
                echo "$(\"#acao_sureg option[value='" . $vetor . "']\").attr('selected', 'selected'); \n";
            }
            echo "}</script>";
        }
        ?>

    </div>
</div><!-- row -->                
<div style="height:15px;"/></div>        
<div style="height:15px;"/></div>       
<div class='row'>
    <div class='label'>Grau de risco / <?php echo $form->labelEx($model, 'processo_fk'); ?></div>
    <div class='field'><?
        $model_processofk = Processo::model()->findAll('valor_exercicio=:valor_exercicio ORDER BY nome_processo', array(':valor_exercicio' => (int) $model->valor_exercicio));
        if (sizeof($model_processofk)) {
            $list_processofk =  array(null => "Selecione") +  CHtml::listData($model_processofk, 'id', 'nome_processo');
        } else {
            $list_processofk = array("Sem processo cadastrado para o exercício " . $model->valor_exercicio);
        }
        echo CHtml::dropDownList('processo_fk', $model->processo_fk, $list_processofk,
            array(
		'ajax' => array(
				'type' => 'POST',
				'url' => CController::createUrl('Processo/CarregaProcessoRiscoPreAjax'),
				'beforeSend' => 'function(){
									$("#div_processo_riscopre").html("");
								 }',
				'success' => 'function(data){
								var conteudo_div = $("#div_processo_riscopre").html();
								$("#div_processo_riscopre").html(conteudo_div + data);
							  }',
),
// 				'update' => '#acao_riscopre'),
		'style' => 'width:525px;'));        
        ?>                
    </div>
</div><!-- row -->        
<div style="height:15px;"/></div>    
<div class='row'>
    <div class='field'>
    	<table width='800' border=0>
    	<tr>
    		<td width=222 valign=top><?php echo $form->label($model, 'processo_riscopre'); ?></td>
    		<td id="div_processo_riscopre"   valign=top>
    		<?php if (!$model->isNewRecord): ?>
    		<?php 
    			$data = ProcessoRiscoPre::model()->findAll(array("condition" => "processo_fk = " . $model->processo_fk));
    			if (!sizeof($data) > 0) {
    				echo CHtml::encode('Nenhum risco pré-identificado cadastrado para este processo');
    			} else {
					$str_in = "";
					foreach($data as $vetor){
						$str_in .= $vetor->risco_pre_fk . ",";
					}
					$str_in = substr($str_in, 0, -1);
					
					$data = RiscoPre::model()->findAll(array("condition" => "id in (" . $str_in . ")", 'order' => 'nome_risco'));
					
					$data = CHtml::listData($data, 'id', 'nome_risco');
					
					foreach ($data as $value => $name) {
						echo '* '.CHtml::encode($name).'<br>';
					}
				}
    		?>
    		<?php endif; ?>
    		</td>
    		</tr>
    	</table>
    	
    </div>
</div><!-- row -->        
<div style="height:15px;"/></div>
<div class='row'>
    <div class='label'><?php echo $form->labelEx($model, 'descricao_objetivo_estrategico'); ?></div>
    <div class='field'><?php echo $form->textArea($model, 'descricao_objetivo_estrategico', array('cols' => '100', 'rows' => '5')); ?></div>
</div><!-- row -->
<div style="height:15px;"/></div>          
<div class='row'>
    <div class='label'><?php echo $form->labelEx($model, 'descricao_objetivo'); ?></div>
    <div class='field'><?php echo $form->textArea($model, 'descricao_objetivo', array('cols' => '100', 'rows' => '5')); ?></div>
</div><!-- row -->  
<div style="height:15px;"/></div>        
<div class='row'>
    <div class='label'><?php echo $form->labelEx($model, 'descricao_escopo'); ?></div>
    <div class='field'><?php echo $form->textArea($model, 'descricao_escopo', array('cols' => '100', 'rows' => '5')); ?></div>
</div><!-- row -->        
<div style="height:15px;"/></div>        
<div class='row'>
    <div class='label'><?php echo $form->labelEx($model, 'descricao_representatividade'); ?></div>
    <div class='field'><?php echo $form->textArea($model, 'descricao_representatividade', array('cols' => '100', 'rows' => '5')); ?></div>
</div><!-- row -->     
<div style="height:15px;"/></div>        
<div class='row'>
    <div class='label'><?php echo $form->labelEx($model, 'descricao_origem'); ?></div>
    <div class='field'><?php echo $form->textField($model, 'descricao_origem', array('size' => '103')); ?></div>
</div><!-- row -->
<div class='row'>
    <div class='label'><?php echo $form->labelEx($model, 'descricao_resultados'); ?></div>
    <div class='field'><?php echo $form->textArea($model, 'descricao_resultados', array('cols' => '100', 'rows' => '5')); ?></div>
</div><!-- row -->
<div style="height:15px;"/></div>        
<div class='row'>
    <div class='label'><?php echo $form->labelEx($model, 'descricao_conhecimentos'); ?></div>
    <div class='field'><?php echo $form->textArea($model, 'descricao_conhecimentos', array('cols' => '100', 'rows' => '5')); ?>                               
    </div>
</div><!-- row --> 



</fieldset>
<p class="note">
<?php echo Yii::t('app', 'Fields with'); ?>
    <span class="required">*</span>
    <?php echo Yii::t('app', 'are required'); ?>.
</p>
<div class="rowButtonsN1">
<?php echo GxHtml::submitButton(Yii::t('app', 'Confirm'), array('class' => 'botao')); ?>
    <?php echo CHtml::link(Yii::t('app', 'Cancel'), $this->createUrl('Acao/index'), array('class' => 'imitacaoBotao')); ?>
</div>
    <?php $this->endWidget(); ?>
</div><!-- form -->
