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
<script type="text/javascript">
    $('#gerente_fk').live('change', function() {
        $('#gerente_fk').multiselect({
            close: function(event, ui) {
                $("#gerente_fk").multiselect("refresh");
            }
        });
    });

    function BotaoConcluidoArea() {
        var value = $(document).find("#selecionar_area").find("#frmArea").contents().find("#unidade_administrativa_fk_popup").html();
        carregaAreas(value);
    }
    function BotaoConcluidoSetor() {
        var value = $(document).find("#selecionar_setor").find("#frmSetor").contents().find("#unidade_administrativa_fk_popup").html();
        carregaSetores(value);
    }


    $(function() {
        $(document).ready(function() {

            $("#area_fk").multiselect("checkAll");
            $("#area_fk").multiselect('refresh');
            $("#setor_fk").multiselect("checkAll");
            $("#setor_fk").multiselect('refresh');

            $("#area_fk").multiselect({
                click: function(e) {
                    var value = e.target.value;
                    if (!e.target.checked) {
                        $("option:[value='" + value + "']").attr('selected', false);
                    } else {
                        $("option:[value='" + value + "']").attr('selected', true);
                    }
                    $("#area_fk").multiselect('refresh');
                }
            });
            $("#setor_fk").multiselect({
                click: function(e) {
                    var value = e.target.value;
                    if (!e.target.checked) {
                        $("option:[value='" + value + "']").attr('selected', false);
                    } else {
                        $("option:[value='" + value + "']").attr('selected', true);
                    }
                    $("#setor_fk").multiselect('refresh');
                }
            });

        });
    });

    function carregaAreas(value) {
        $("#area_fk").html(value);
        $("#area_fk").multiselect('refresh');
        $("#area_fk").multiselect("checkAll");
        $("#dialog_selecionar_area").dialog("close");
    }
    function carregaSetores(value) {
        $("#setor_fk").html(value);
        $("#setor_fk").multiselect('refresh');
        $("#setor_fk").multiselect("checkAll");
        $("#dialog_selecionar_setor").dialog("close");
    }
</script>
<?php
if ($model->data_relatorio) {
    echo "<center><b>Não é possível editar este relatório, pois ele já foi homologado.</b></center>";
} else {
    include_once(Yii::app()->basePath . '/../js/relatorio/tiny-mce-js.jsp');

    $baseUrl = Yii::app()->baseUrl;
    $cs = Yii::app()->getClientScript();
    $cs->registerScriptFile($baseUrl . '/js/Relatorio.js');
    $cs->registerCssFile($baseUrl . '/css/estiloChamada.css');

    if (!$model->isNewRecord) {
        ?>
        <div class="formulario" Style="width:70%;">
            <fieldset class="visivel">
                <legend class="legendaDiscreta"> Navegação </legend>   
                <div class='tabelaListagemItensWrapper' style="width:180px;margin-left:40%;">
                    <a href="<?php echo '../../capitulo/index?yt0=Consultar&Capitulo%5Brelatorio_fk%5D=' . $model->id; ?>">
                        <img src="<?php echo Yii::app()->request->baseUrl . "/themes/" . Yii::app()->params["tema"] . "/img/seta3.png"; ?>" border="0" align="left"></a>
                    &nbsp; &nbsp;                 
                    <a href="<?php echo '../../capitulo/index?yt0=Consultar&Capitulo%5Brelatorio_fk%5D=' . $model->id; ?>">
                        Ver capítulos deste relatório</a>
                </div>
            </fieldset>
        </div>
    <?php } ?>
    <div class="formulario" Style="width: 57%;">
        <?php
        $form = $this->beginWidget('GxActiveForm', array(
            'id' => 'relatorio-form',
            'enableAjaxValidation' => false,
        ));
        ?>
        <?php echo $form->errorSummary($model); ?>
        
        <fieldset class="visivel">
            <legend class="legendaDiscreta"> <?php echo($model->isNewRecord ? 'Adicionar' : 'Atualizar'); ?> -  <?php echo $model->label(); ?></legend>
            <div class='row'>
                <div class='label'><?php echo $form->labelEx($model, 'id'); ?></div>
                <div class='field'><?php echo $form->textField($model, 'id', array('readonly' => 'true')); ?></div>
            </div><!-- row -->

            <div class='row'>
                <div class='label'><?php echo $form->labelEx($model, 'especie_auditoria_fk'); ?></div>
                <div class='field'><?php echo $form->dropDownList($model, 'especie_auditoria_fk', GxHtml::listDataEx(EspecieAuditoria::model()->findAllAttributes(null, true)), array('style' => 'width:200px;')); ?></div>
            </div><!-- row -->
            <div style="height:5px;"></div>
            <div class='row'>
                <div class='label'><?php echo $form->labelEx($model, 'plan_especifico_fk'); ?></div>
                <?php
                	$plan_especificio = PlanEspecifico::model()->findAll('"valor_exercicio" = ' . date(Y));
                	if ($model->plan_especifico_fk){
                		$plan_especificio = array_merge($plan_especificio, PlanEspecifico::model()->findAll('id =' . $model->plan_especifico_fk));
                	}
                	$data = sizeof($plan_especificio) ? array(null => "Selecione") + GxHtml::listDataEx($plan_especificio, 'id', 'display_acao') : array(null => "Não existe planejamento específico para o exercício vigente"); 
                ?>
                <div class='field'>
                <?php 
//                 	echo $form->dropDownList($model, 'plan_especifico_fk', $data, array('style' => 'width:550px;')); 
                ?>
                <?php
                $form->widget('ext.EchMultiSelect.EchMultiSelect', array(
                		'model' => $model,
                		'dropDownAttribute' => 'plan_especifico_fk',
                		'data' => $data,
                		'dropDownHtmlOptions' => array(
                				'id' => 'plan_especifico_fk',
                		),
                		'options' => array(
								'multiple' => false,
                				'selectedList' => 1,
                				'minWidth' => '410',
								'maxWidth'=>'570',
                				'filter' => true, 
                		),
                		'filterOptions' => array(
                				'width' => 300,
                				'label' => Yii::t('application', 'Filtrar:'),
                				'placeholder' => Yii::t('application', 'digite aqui'),
                				'autoReset' => false,
                		),
                ));
                ?>
                </div>
            </div><!-- row -->
            <div style="height:5px;"></div>       
            <div class='row'>
                <div class='label'><?php echo $form->labelEx($model, 'categoria_fk'); ?></div>
                <div class='field'><?php echo $form->dropDownList($model, 'categoria_fk', GxHtml::listDataEx(Categoria::model()->findAllAttributes(null, true)), array('style' => 'width:200px;')); ?></div>
            </div><!-- row -->        
            <div style="height:5px;"></div>        
            <div class='row'>
                <div class='label'><?php echo $form->labelEx($model, 'diretoria_fk'); ?></div>
                <div class='field'>
                    <?php
                    $relatorio_diretoria_dados = CHtml::listData(UnidadeAdministrativa::model()->findAll(array("condition" => '"diretoria" = true', 'order' => 'sigla')), 'id', 'sigla');
                    $form->widget('ext.EchMultiSelect.EchMultiSelect', array(
                        'model' => $model_relatorio_diretoria,
                        'dropDownAttribute' => 'diretoria_fk',
                        'data' => $relatorio_diretoria_dados,
                        'dropDownHtmlOptions' => array(
                            'id' => 'diretoria_fk',
                            'multiple' => true,
                        ),
                        'options' => array(
                            'selectedList' => 7,
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
                    if (is_array($relatorio_diretoria)) {
                        echo "\n <script type='text/javascript'> { \n";
                        foreach ($relatorio_diretoria as $vetor) {
                            echo "$(\"#diretoria_fk option[value='" . $vetor->diretoria_fk . "']\").attr('selected', 'selected'); \n";
                        }
                        echo "}</script>";
                    }


                    // marca no select múltiplo as ações que vieram do $_POST
                    $relatorio_diretoria2 = $_POST['RelatorioDiretoria']['diretoria_fk'];
                    if (sizeof($relatorio_diretoria2) > 0) {
                        echo "\n <script type='text/javascript'> { \n";
                        foreach ($relatorio_diretoria2 as $vetor) {
                            echo "$(\"#diretoria_fk option[value='" . $vetor . "']\").attr('selected', 'selected'); \n";
                        }
                        echo "}</script>";
                    }
                    ?>

                </div>
            </div><!-- row -->              
            <div style="height:5px;"></div>
            <div class='row'><!-- Unidade Auditada -->
                <div class='label'><?php echo $form->labelEx($model, 'unidade_administrativa_fk'); ?></div>
                <div class='field'>
                    <?php
                    $relatorio_sureg_dados = CHtml::listData(UnidadeAdministrativa::model()->findAll(array("condition" => '"sureg" = true', 'order' => 'sigla')), 'id', 'sigla');
                    $form->widget('ext.EchMultiSelect.EchMultiSelect', array(
                        'model' => $model_relatorio_sureg,
                        'dropDownAttribute' => 'unidade_administrativa_fk',
                        'data' => $relatorio_sureg_dados,
                        'dropDownHtmlOptions' => array(
                            'id' => 'unidade_administrativa_fk',
                            'multiple' => true,
                        ),
                        'options' => array(
                            'selectedList' => 5,
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
                    if (is_array($relatorio_sureg)) {
                        echo "\n <script type='text/javascript'> { \n";
                        foreach ($relatorio_sureg as $vetor) {
							if (!$vetor->sureg_secundaria){
                            	echo "$(\"#unidade_administrativa_fk option[value='" . $vetor->unidade_administrativa_fk . "']\").attr('selected', 'selected'); \n";
                            }
                        }
                        echo "}</script>";
                    }


                    // marca no select múltiplo as ações que vieram do $_POST
                    $relatorio_sureg2 = $_POST['RelatorioSureg']['unidade_administrativa_fk'];
                    if (sizeof($relatorio_sureg2) > 0) {
                        echo "\n <script type='text/javascript'> { \n";
                        foreach ($relatorio_sureg2 as $vetor) {
							if (!$vetor->sureg_secundaria){
                            	echo "$(\"#unidade_administrativa_fk option[value='" . $vetor . "']\").attr('selected', 'selected'); \n";
                            }
                        }
                        echo "}</script>";
                    }
                    ?>

                </div>
            </div><!-- row -->    
            <div style="height:5px;"></div>
            <div class='row'><!-- Unidade Auditada -->
                <div class='label'><?php echo $form->labelEx($model, 'sureg_secundaria'); ?></div>
                <div class='field'>
                    <?php
                    $relatorio_sureg_dados = CHtml::listData(UnidadeAdministrativa::model()->findAll(array("condition" => '"sureg" = true', 'order' => 'sigla')), 'id', 'sigla');
                    $form->widget('ext.EchMultiSelect.EchMultiSelect', array(
                        'model' => $model_relatorio_sureg,
                        'dropDownAttribute' => 'sureg_secundaria',
                        'data' => $relatorio_sureg_dados,
                        'dropDownHtmlOptions' => array(
                            'id' => 'sureg_secundaria',
                            'multiple' => true,
                        ),
                        'options' => array(
                            'selectedList' => 5,
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
                    if (is_array($relatorio_sureg)) {
                        echo "\n <script type='text/javascript'> { \n";
                        foreach ($relatorio_sureg as $vetor) {
							if ($vetor->sureg_secundaria){
                            	echo "$(\"#sureg_secundaria option[value='" . $vetor->unidade_administrativa_fk . "']\").attr('selected', 'selected'); \n";
                            }
                        }
                        echo "}</script>";
                    }


                    // marca no select múltiplo as ações que vieram do $_POST
                    $relatorio_sureg2 = $_POST['RelatorioSureg']['sureg_secundaria'];
                    if (sizeof($relatorio_sureg2) > 0) {
                        echo "\n <script type='text/javascript'> { \n";
                        foreach ($relatorio_sureg2 as $vetor) {
							if ($vetor->sureg_secundaria){
                            	echo "$(\"#sureg_secundaria option[value='" . $vetor . "']\").attr('selected', 'selected'); \n";
                            }
                        }
                        echo "}</script>";
                    }
                    ?>

                </div>
            </div><!-- row -->   
            <div style="height:5px;"></div>
            <div class='row'>
                <div class='label'><?php echo $form->labelEx($model, 'auditor_fk'); ?></div>
                <div class='field'>
                    <?php
                    $relatorio_auditor_dados = CHtml::listData(Usuario::model()->with('perfilFk')->findAll(array('condition' => 'perfil_fk='.Perfil::model()->find("nome_interno = 'SIAUDI_AUDITOR'")->id, 'order' => 'nome_usuario')), 'id', 'nome_usuario');
                    $form->widget('ext.EchMultiSelect.EchMultiSelect', array(
                        'model' => $model_relatorio_auditor,
                        'dropDownAttribute' => 'auditor_fk',
                        'data' => $relatorio_auditor_dados,
                        'dropDownHtmlOptions' => array(
                            'id' => 'auditor_fk',
                            'multiple' => true,
                        ),
                        'options' => array(
                            'selectedList' => 3,
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

                    if (is_array($relatorio_auditor)) {
                        echo "\n <script type='text/javascript'> { \n";
                        foreach ($relatorio_auditor as $vetor) {
                            echo "$(\"#auditor_fk option[value='" . $vetor->usuario_fk . "']\").attr('selected', 'selected'); \n";
                        }
                        echo "}</script>";
                    }

                    // marca no select múltiplo as ações que vieram do $_POST
                    $relatorio_auditor2 = $_POST['RelatorioAuditor']['auditor_fk'];
                    if (sizeof($relatorio_auditor2) > 0) {
                        echo "\n <script type='text/javascript'> { \n";
                        foreach ($relatorio_auditor2 as $vetor) {
                            echo "$(\"#auditor_fk option[value='" . $vetor . "']\").attr('selected', 'selected'); \n";
                        }
                        echo "}</script>";
                    }
                    ?>

                </div>
            </div><!-- row -->                      
            <div style="height:5px;"></div>        

            <div class='row'>
                <div class='label'><?php echo $form->labelEx($model, 'gerente_fk'); ?></div>
                <div class='field'>
                    <?php
                    $relatorio_gerente_dados = CHtml::listData(Usuario::model()->listaGerentes($relatorio_gerente), 'id', 'nome_usuario');
                    $form->widget('ext.EchMultiSelect.EchMultiSelect', array(
                        'model' => $model_relatorio_gerente,
                        'dropDownAttribute' => 'gerente_fk',
                        'data' => $relatorio_gerente_dados,
                        'dropDownHtmlOptions' => array(
                            'id' => 'gerente_fk',
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

                    if (is_array($relatorio_gerente)) {
                        echo "\n <script type='text/javascript'> { \n";
                        foreach ($relatorio_gerente as $vetor) {
                            echo "$(\"#gerente_fk option[value='" . $vetor->usuario_fk . "']\").attr('selected', 'selected'); \n";
                        }
                        echo "}</script>";
                    }

                    // marca no select múltiplo as ações que vieram do $_POST
                    $relatorio_gerente2 = $_POST['RelatorioGerente']['gerente_fk'];
                    if (sizeof($relatorio_gerente2) > 0) {
                        echo "\n <script type='text/javascript'> { \n";
                        foreach ($relatorio_gerente2 as $vetor) {
                            echo "$(\"#gerente_fk option[value='" . $vetor . "']\").attr('selected', 'selected'); \n";
                        }
                        echo "}</script>";
                    }
                    ?>
                </div>
            </div><!-- row -->
            <div style="height:5px;"/></div>    
    <div class='row'>
        <div class='label'><?php echo $form->labelEx($model, 'relatorio_riscopos'); ?></div>
        <div class='field'>
    <?php
    $acao_risco_pos_dados = CHtml::listData(RiscoPos::model()->findAll(), 'id', 'nome_risco');
    $form->widget('ext.EchMultiSelect.EchMultiSelect', array(
        'model' => $model,
        'dropDownAttribute' => 'relatorio_riscopos',
        'data' => $acao_risco_pos_dados,
        'dropDownHtmlOptions' => array(
            'id' => 'relatorio_riscopos',
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
    $relatorio_risco_pos = RelatorioRiscoPos::model()->findAllByAttributes(array('relatorio_fk' => $model->id));
    if (is_array($relatorio_risco_pos)) {
        echo "\n <script type='text/javascript'> { \n";
        foreach ($relatorio_risco_pos as $vetor) {
            echo "$(\"#relatorio_riscopos option[value='" . $vetor->risco_pos_fk . "']\").attr('selected', 'selected'); \n";
        }
        echo "}</script>";
    }

    // marca no select múltiplo as ações que vieram do $_POST
    $relatorio_risco_pos2 = $_POST['Relatorio']['relatorio_riscopos'];
    if (sizeof($relatorio_risco_pos2) > 0) {
        echo "\n <script type='text/javascript'> { \n";
        foreach ($relatorio_risco_pos2 as $vetor) {
            echo "$(\"#relatorio_riscopos option[value='" . $vetor . "']\").attr('selected', 'selected'); \n";
        }
        echo "}</script>";
    }
    ?>
            <?php
            echo CHtml::ajaxSubmitButton(
                    'Inserir Risco', $this->createUrl('/RiscoPos/AdminModalAjax'), array(
                'dataType' => 'html',
                'success' => "function (data) { 
	                                        $('#cadastrar_risco_pos').html(data);
	                                        $('#dialog_cadastrar_risco_pos').dialog('open');
	                                    }"
                    ), array(
                'id' => 'bt_cadastrar_risco_pos',
//                'value' => 'Inserir Risco',
                'class' => 'botaoInserir',
                    )
            );
            ?>
            <?php
            $this->beginWidget('application.common.extensions.jui.EDialog', array(
                'name' => 'dialog_cadastrar_risco_pos',
                'htmlOptions' => array('title' => 'Cadastrar Risco Pós Identificado'),
                'options' => array(
                    'autoOpen' => false,
                    'modal' => true,
                    'title' => 'Cadastrar Risco Pós Identificado',
                    'width' => 800,
                    'height' => 'auto'
                ),
                'buttons' => array(
                    "Salvar" => 'function(){
                                        bValido = validaDadosModal();
                                        if(bValido){
                                            $(this).dialog("close"); 
                                            salvar();
                                        }
                                }',
                    "Fechar" => 'function(){$(this).dialog("close");}'
                ),
                'UseBundledStyleSheet' => false
            ));
            echo '<div id="cadastrar_risco_pos"></div>';
            $this->endWidget('application.common.extensions.jui.EDialog');
            ?>            
            <?php
            echo CHtml::ajaxSubmitButton('ButtonName', Yii::app()->createUrl('/RiscoPos/SalvarDadosModalAjax'), array(
                'type' => 'POST',
                'data' => 'js:obtemDadosModal()',
                'success' => "js:function(data){   
                                atualizaComboRiscoPos(data);
                        }"
                    ), array('class' => 'someCssClass', 'hidden' => 'true'));
            ?>
        </div>
    </div><!-- row -->      
    <div style="height:5px;"/></div>
    <div class='row'>
        <div class='label'><?php echo $form->labelEx($model, 'area'); ?></div>
        <div class='field'>
    <?php
    $relatorio_area = (sizeof($relatorio_area) > 0) ? CHtml::listData($relatorio_area, 'unidade_administrativa_fk', 'sigla') : array();
    $form->widget('ext.EchMultiSelect.EchMultiSelect', array(
        'model' => $model_relatorio_area,
        'dropDownAttribute' => 'unidade_administrativa_fk',
        'data' => $relatorio_area,
        'dropDownHtmlOptions' => array(
            'id' => 'area_fk',
            'multiple' => true,
        ),
        'options' => array(
            'selectedList' => 5,
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

    if (is_array($relatorio_area)) {
        echo "\n <script type='text/javascript'> { \n";
        foreach ($relatorio_area as $vetor) {
            echo "$(\"#area_fk option[value='" . $vetor->unidade_administrativa_fk . "']\").attr('selected', 'selected'); \n";
        }
        echo "}</script>";
    }
    ?>
            <?php
            echo CHtml::ajaxSubmitButton(
                    'Importar Área(s)', $this->createUrl('/Relatorio/AbrirModalAreaAjax'), array(
                'dataType' => 'html',
                'success' => "function (data) { 
	                                        $('#selecionar_area').html(data);
	                                        $('#dialog_selecionar_area').dialog('open');
	                                    }"
                    ), array(
                'id' => 'bt_selecionar_area',
                'class' => 'botaoInserir',
                    )
            );
            ?>  
            <?php
            $this->beginWidget('application.common.extensions.jui.EDialog', array(
                'name' => 'dialog_selecionar_area',
                'id' => 'dialog_selecionar_area',
                'htmlOptions' => array('title' => 'Unidade Administrativa (Lotação)'),
                'options' => array(
                    'autoOpen' => false,
                    'modal' => true,
                    'title' => 'Unidade Administrativa (Lotação) - Área',
                    'width' => 800,
                    'height' => 600
                ),
                'buttons' => array(
                    "Concluído" => 'function(){BotaoConcluidoArea();}',
                    "Fechar" => 'function(){$(this).dialog("close");}'
                ),
                'UseBundledStyleSheet' => false,
            ));
            echo '<div id="selecionar_area"></div>';
            $this->endWidget('application.common.extensions.jui.EDialog');
            ?>             
        </div>
    </div><!-- row -->
    <div style="height:5px;"/></div>
    <div class='row'>
        <div class='label'><?php echo $form->labelEx($model, 'setor'); ?></div>
        <div class='field'>
    <?php
    $relatorio_setor = sizeof($relatorio_setor) > 0 ? CHtml::listData($relatorio_setor, 'unidade_administrativa_fk', 'sigla') : array();
    $form->widget('ext.EchMultiSelect.EchMultiSelect', array(
        'model' => $model_relatorio_setor,
        'dropDownAttribute' => 'unidade_administrativa_fk',
        'data' => $relatorio_setor,
        'dropDownHtmlOptions' => array(
            'id' => 'setor_fk',
            'multiple' => true,
        ),
        'options' => array(
            'selectedList' => 5,
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

    if (is_array($relatorio_setor)) {
        echo "\n <script type='text/javascript'> { \n";
        foreach ($relatorio_setor as $vetor) {
            echo "$(\"#setor_fk option[value='" . $vetor->unidade_administrativa_fk . "']\").attr('selected', 'selected'); \n";
        }
        echo "}</script>";
    }
    ?>
            <?php
            echo CHtml::ajaxSubmitButton(
                    'Importar Setor(es)', $this->createUrl('/Relatorio/AbrirModalSetorAjax'), array(
                'dataType' => 'html',
                'success' => "function (data) { 
	                                        $('#selecionar_setor').html(data);
	                                        $('#dialog_selecionar_setor').dialog('open');
	                                    }"
                    ), array(
                'id' => 'bt_selecionar_setor',
                'class' => 'botaoInserir',
                    )
            );
            ?>  
            <?php
            $this->beginWidget('application.common.extensions.jui.EDialog', array(
                'name' => 'dialog_selecionar_setor',
                'htmlOptions' => array('title' => 'Unidade Administrativa (Lotação)'),
                'options' => array(
                    'autoOpen' => false,
                    'modal' => true,
                    'title' => 'Unidade Administrativa (Lotação) - Setor',
                    'width' => 800,
                    'height' => 600
                ),
                'buttons' => array(
                    "Concluído" => 'function(){BotaoConcluidoSetor();}',
                    "Fechar" => 'function(){$(this).dialog("close");}'
                ),
                'UseBundledStyleSheet' => false,
            ));
            echo '<div id="selecionar_setor"></div>';
            $this->endWidget('application.common.extensions.jui.EDialog');
            ?>             
        </div>
    </div><!-- row -->
    <div style="height:15px;"/></div>
    <div class='row'>
        <div class='label'><?php echo $form->labelEx($model, 'nucleo'); ?></div>
        <div class='field'>
    <?php
    $accountStatus = array('1' => 'Sim', '0' => 'Não');
    echo $form->radioButtonList($model, 'nucleo', $accountStatus, array('separator' => '&nbsp&nbsp&nbsp&nbsp'));
    ?>
        </div>
    </div><!-- row -->
    <div style="height:5px;"></div>        
    <div class='row'>
        <div class='label'><?php echo $form->labelEx($model, 'descricao_introducao'); ?></div>
        <Table border="0" cellpadding="0 cellspacing=0"><tr><td>
    <?php echo $form->textArea($model, 'descricao_introducao', array('cols' => '80', 'rows' => '5', 'style' => 'margin-left:300px;', 'class' => 'tinymce_advanced')); ?></div>
                </td></tr></table>
        <!-- row -->
    </fieldset>
    <p class="note">
    <?php echo Yii::t('app', 'Fields with'); ?>
        <span class="required">*</span>
    <?php echo Yii::t('app', 'are required'); ?>.
    </p>
    <div class="rowButtonsN1">
        <input type="hidden" name="inserir_capitulo" id="inserir_capitulo" value="0">
        <input type="button" name="text" value="Gravar e inserir capítulo" id="form_submit" onclick="envia_relatorio();" class="botao" />

    <?php echo GxHtml::submitButton(Yii::t('app', 'Gravar e sair'), array('class' => 'botao')); ?>
    <?php echo CHtml::link(Yii::t('app', 'Cancel'), $this->createUrl('Relatorio/index'), array('class' => 'imitacaoBotao')); ?>
    </div>
        <?php $this->endWidget(); ?>
    </div><!-- form -->
    <?php } ?>    
