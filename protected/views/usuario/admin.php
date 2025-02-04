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
// recupera o nome completo, pois ele está sendo cortado
// caso ultrapasse o limite da combo
if(is_numeric($model->id)){
    $nome_usuario = Usuario::model()->nome_usuario_completo($model->id);
    $nome_usuario = $nome_usuario[nome_usuario];
}
?>
<div class="formulario" Style="width: 70%;">
    <?php
    $form = $this->beginWidget('GxActiveForm', array(
        'id' => 'usuario-form',
        'enableAjaxValidation' => false,
    ));
    ?>    
    <?php echo $form->errorSummary($model); ?>
    <fieldset class="visivel">
        <legend class="legendaDiscreta"> <?php echo($model->isNewRecord ? 'Adicionar' : 'Atualizar'); ?> -  <?php echo $model->label(); ?></legend>

    <?php if(($model->scenario == 'alteraSenha')){?>
        <div class='row'>
            <div class='label'><?php echo $form->labelEx($model, 'nome_login'); ?></div>
            <div class='field'>
                <?php echo $model->nome_login;?>
            </div>
        </div><!-- row -->
        <div class='row'>
            <div class='label'><?php echo $form->labelEx($model, 'nome_usuario'); ?></div>
            <div class='field'><?php echo $model->nome_usuario; ?></div>
        </div><!-- row -->
        
    <?php }else{?>
        <div class='row'>
            <div class='label'><?php echo $form->labelEx($model, 'nome_login'); ?></div>
            <div class='field'>
                <?php
                echo $form->textField($model, 'nome_login', array('maxlength' => 50, 'size' => 35, 'class' => 'campoChave',
//                                                'ajax' => array('type' =>'POST',
//                                                                'url' => CController::createUrl('Usuario/VerificaSeLoginExisteAjax'),
//                                                                'update' => '#msg',
//                                                                'data' => 'html')
                        )
                );
                ?>
            </div>
        </div><!-- row -->
        <div class='row'>
            <div class='label'><?php echo $form->labelEx($model, 'nome_usuario'); ?></div>
            <div class='field'><?php echo $form->textField($model, 'nome_usuario', array( 'value'=>$nome_usuario, 'maxlength' => 400, 'size' => 35, 'style' => 'width:450px;')); ?></div>
        </div><!-- row -->


        <div class='row'>
            <div class='label'><?php echo $form->labelEx($model, 'cpf'); ?></div>
            <div class='field'><?php echo $form->textField($model, 'cpf', array( 'value'=>$cpf, 'maxlength' => 14, 'size' => 14)); ?></div>
        </div><!-- row -->
        
        
        <div class='row'>
            <div class='label'><?php echo $form->labelEx($model, 'funcao_fk'); ?></div>
            <div class='field'><?php echo $form->dropDownList($model, 'funcao_fk', array(null => "Selecione") + GxHtml::listDataEx(Funcao::model()->findAllAttributes(null, true, array('order' => 'nome_funcao'))), array('style' => 'width:188px', 'class' => 'campoChave')); ?></div>
        </div><!-- row -->
        
        <div class='row'>
            <div class='label'><?php echo $form->labelEx($model, 'cargo_fk'); ?></div>
            <div class='field'><?php echo $form->dropDownList($model, 'cargo_fk', array(null => "Selecione") + GxHtml::listDataEx(Cargo::model()->findAllAttributes(null, true, array('order' => 'nome_cargo'))), array('style' => 'width:188px', 'class' => 'campoChave')); ?></div>
        </div><!-- row -->
        <div class='row'>
            <div class='label'><?php echo $form->labelEx($model, 'perfil_fk'); ?></div>
            <div class='field'><?php echo $form->dropDownList($model, 'perfil_fk', array(null => "Selecione") + GxHtml::listDataEx(Perfil::model()->findAllAttributes(null, true, array('order' => 'nome_perfil'))), array('style' => 'width:188px', 'class' => 'campoChave')); ?></div>
        </div><!-- row -->
        <div class='row'>
            <div class='label'><?php echo $form->labelEx($model, 'nucleo_fk'); ?></div>
            <div class='field'><?php echo $form->dropDownList($model, 'nucleo_fk', array(null => "Selecione") + GxHtml::listDataEx(Nucleo::model()->findAllAttributes(null, true)), array('style' => 'width:188px')); ?></div>
        </div><!-- row -->
        <div class='row'>
            <div class='label'><?php echo $form->labelEx($model, 'unidade_administrativa_fk'); ?></div>
            <div class='field'>
               <?php
                $unidade_administrativa_dados = array(null => "Selecione") + CHtml::listData(UnidadeAdministrativa::model()->findAll(array("condition" => "data_extincao is null",'order' => 'sigla')), 'id', 'sigla', 'uf_fk');
                $form->widget('ext.EchMultiSelect.EchMultiSelect', array(
                    'model' => $model,
                    'dropDownAttribute' => 'unidade_administrativa_fk',
                    'data' => $unidade_administrativa_dados,
                    'dropDownHtmlOptions' => array(
                        'id' => 'unidade_administrativa_fk',
                    ),
                    'options' => array(
                        'selectedList' => 1,
                        'minWidth' => '250',
                        'filter' => true,
                        'multiple' => false,
                    ),
                    'filterOptions' => array(
                        'width' => 150,
                        'label' => Yii::t('application', 'Filtrar:'),
                        'placeholder' => Yii::t('application', 'digite aqui'),
                        'autoReset' => false,
                    ),
                ));
                ?>                 
                <?php 
                /*
                echo $form->dropDownList($model, 'unidade_administrativa_fk', array(null => "Selecione") + CHtml::listData(UnidadeAdministrativa::model()->findAll(array("condition" => '"sureg" = true', 'order' => 'sigla')), 'id', 'sigla'), array('style' => 'width:188px', 'class' => 'campoChave')); 
                 */?>
            </div>
        </div><!-- row -->
        <div class='row'>
            <div class='label'><?php echo $form->labelEx($model, 'substituto_fk'); ?></div>
            <!--<div class='field'><?php echo $form->dropDownList($model, 'substituto_fk', array(null => "Nenhum") + CHtml::listData(Usuario::model()->findAll(array('order' => 'nome_usuario')), 'id', 'nome_usuario'), array('style' => 'width:188px')); ?></div>-->
            <div class='field'><?php echo $form->dropDownList($model, 'substituto_fk', CHtml::listData(Usuario::model()->findAll(array('order' => 'nome_usuario')), 'id', 'nome_usuario'), array('prompt' => 'Nenhum', 'style' => 'width:188px')); ?></div>
        </div><!-- row -->
        
        
        <div class='row'>
            <div class='label'><?php echo $form->labelEx($model, 'email'); ?></div>
            <div class='field'><?php echo $form->textField($model, 'email', array( 'value'=>$email, 'maxlength' => 100, 'size' => 35)); ?></div>
        </div><!-- row -->
        <div class='row'>
            <div class='label'><?php echo $form->labelEx($model, 'confirmaEmail'); ?></div>
            <div class='field'><?php echo $form->textField($model, 'confirmaEmail', array( 'value'=>$confirmaEmail, 'maxlength' => 100, 'size' => 35)); ?></div>
        </div><!-- row -->
        <?php }?>
        
        <?php if(($model->isNewRecord) || ($model->scenario == 'alteraSenha')){?>
            <div class='row'>
                <div class='label'><?php echo $form->labelEx($model, 'senha'); ?></div>
                <div class='field'><?php echo $form->passwordField($model, 'senha', array( 'value'=>$senha, 'maxlength' => 100, 'size' => 35)); ?></div>
            </div><!-- row -->
            <div class='row'>
                <div class='label'><?php echo $form->labelEx($model, 'confirmaSenha'); ?></div>
                <div class='field'><?php echo $form->passwordField($model, 'confirmaSenha', array( 'value'=>$confirmaSenha, 'maxlength' => 100, 'size' => 35)); ?></div>
            </div><!-- row -->
        <?php }?>
        
        
        
        
        <? // if ($msgInconsistencia): ?>
        <div class='row'>
            <fieldset id='alerta' class="visivel" hidden="true">
                <center>
                    <div id='msg'>
                        <!--//                            $msgInconsistencia .
                        //                            $form->checkBox($model, "bolDeAcordo", array("value" => "1", "uncheckValue" => "0")) .
                        //                            "<label for='bolDeAcordo'> Estou de acordo com esta operação.*</label>";-->

                    </div>
                </center>
            </fieldset>
        </div>
        <? // endif; ?>
    </fieldset>
    <p class="note">
        <?php echo Yii::t('app', 'Fields with'); ?>
        <span class="required">*</span>
        <?php echo Yii::t('app', 'are required'); ?>.
    </p>
    <div class="rowButtonsN1">
        <?php echo GxHtml::submitButton(Yii::t('app', 'Confirm'), array('class' => 'botao')); ?>
        <?php echo CHtml::link(Yii::t('app', 'Cancel'), $this->createUrl('Usuario/index?' . $_SERVER['QUERY_STRING']), array('class' => 'imitacaoBotao')); ?>
    </div>
    <?php $this->endWidget(); ?>
</div><!-- form -->
<script type="text/javascript">

    $("input:[name='yt0']").on('click', function() {
        if ($('#Usuario_bolDeAcordo').length) {
            if (!($('#Usuario_bolDeAcordo').is(':checked'))) {
                alert("Para realizar a alteração das informações é necessário estar de acordo com a operação.");
                return false;
            }
        }
    });

    $('#<?= CHtml::activeId($model, "cpf");?>').mask('999.999.999-99');
    
    var id_perfil_anterior;
    var id_cargo_anterior;
    $('#Usuario_perfil_fk').on('focus', function() {
        id_perfil_anterior = $(this).val();
    });
    $('#Usuario_cargo_fk').on('focus', function() {
        id_cargo_anterior = $(this).val();
    });


    $('.campoChave').change(function() {
        var callAjax = true;
        $('.campoChave').each(function(n, element) {
            if ($(element).val() == '') {
                callAjax = false;
            }
        });
        if (callAjax) {
            var id_perfil_selecionado = $('#Usuario_perfil_fk option:selected').val();
            var nome_login = $('#Usuario_nome_login').val();
            var nome_usuario = $('#Usuario_nome_usuario').val();
            var unidade_administrativa_fk = $("#Usuario_unidade_administrativa_fk option:selected").val();
            var cargo = $("#Usuario_cargo_fk option:selected").text().toLowerCase();
            var cargo_fk = $("#Usuario_cargo_fk option:selected").val();
            var perfil = $("#Usuario_perfil_fk option:selected").text().toLowerCase();
            //PORTAL_SPB identificar quem é o responsável pela unidade
            if (($.trim(perfil) == "siaudi_cliente") || (id_perfil_anterior != id_perfil_selecionado)) {
                $.ajax({
                    type: 'POST',
                    url: '<?= CController::createUrl("Usuario/VerificaRelatorioAcessoAjax"); ?>',
                    data: {nome_login: nome_login,
                        nome_usuario: nome_usuario,
                        unidade_administrativa_fk: unidade_administrativa_fk,
                        id_perfil_anterior: id_perfil_anterior,
                        id_perfil_selecionado: id_perfil_selecionado,
                        id_cargo_selecionado: cargo_fk,
                        id_cargo_anterior: id_cargo_anterior,
                        novo_registro: '<?= $model->isNewRecord; ?>',
                        id: '<?= $model->id; ?>'
                    }
                }).done(function(data) {
                    if (data) {
                        var checkbox = '<?= $form->checkBox($model, "bolDeAcordo", array("value" => "1", "uncheckValue" => "0")) ?>' + "<label for='bolDeAcordo'> Estou de acordo com esta operação.*</label>";
                        $('#msg').html(data + checkbox);
                        $('#alerta').prop('hidden', false);
                    } else {
                        $('#msg').html(data);
                        $('#alerta').prop('hidden', true);
                    }
                });
            }
        }
    });

</script>
