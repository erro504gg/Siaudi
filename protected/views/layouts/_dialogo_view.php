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
<?php Yii::app()->getClientScript()->registerScriptFile(Yii::app()->request->baseUrl . '/js/dialogs.js'); ?>

<?php
$this->beginWidget('application.common.extensions.jui.EDialog', array(
    'name' => 'dialog_visualizar',
    'htmlOptions' => array('title' => 'Visualizar'),
    'options' => array(
        'autoOpen' => false,
        'modal' => true,
        'title' => 'Visualizar Registro',
        'height' => 400,
        'width' => 600,
    ),
    'buttons' => array(
        "Fechar" => 'function(){$(this).dialog("close");}',
    ),
    'theme' => 'base',
        )
);
?>
<span id="visualizar_reg"> </span>
<?php $this->endWidget('application.common.extensions.jui.EDialog'); ?>


<?php
$this->beginWidget('application.common.extensions.jui.EDialog', array(
    'name' => 'dialog_excluir',
    'htmlOptions' => array('title' => 'Excluir Registro'),
    'options' => array(
        'autoOpen' => false,
        'modal' => true,
        'title' => 'Confirma a exclusão do registro',
        'height' => 400,
        'width' => 600,
    ),
    'buttons' => array(
        "Excluir" => 'function(){
           $.post($("#ctrl_excluir").val(), { id:$("#id_excluir").val()  },
               function(data) {
                    $("#dialog_excluir").dialog("close");
                    $.fn.yiiGridView.update($("#id_grid_excluir").val());
                    $("#mensagens").html(data);
           });
        }',
        "Fechar" => 'function(){$(this).dialog("close");}',
    ),
    'theme' => 'base',
        )
);
?>

<?php echo GxHtml::hiddenField('id','', array('id'=>'id_excluir')) ?>
<?php echo GxHtml::hiddenField('ctrl_excluir','', array('id'=>'ctrl_excluir')) ?>
<?php echo GxHtml::hiddenField('ctrl_excluir','', array('id'=>'id_grid_excluir')) ?>
<span id="excluir_reg"></span>

<?php $this->endWidget('application.common.extensions.jui.EDialog'); ?>
