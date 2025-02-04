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
<script type="text/javascript" src="js/RelatorioSaida2.js"></script>
<script type="text/javascript">
     $(function()
    {
        
        <?php 
            $data = array('Relatorio' => array('tipo_relatorio' => 2)); //Trazer somente os relatório homologados 
            echo(CHtml::ajax(array
            (
                'type' => 'POST',
                'data' => $data,
                'url' => array('RelatorioSaida/CarregaRelatorioSaidaAjax'),
                'update' => '#Relatorio_id',
            )));
        ?>
    });    
</script>

<div class="formulario" Style="width: 70%;">

    <?php
    $form = $this->beginWidget('GxActiveForm', array(
        'action' => Yii::app()->createUrl($this->route),
        'method' => 'get',
        'id' => 'relatorioSaida'
    ));
    ?>
    <fieldset class="visivel">
        <legend class="legendaDiscreta">Consultar -  <?php echo $model->label(); ?></legend>  
        <div class='row'>
            <div class='label'><?php echo $form->labelEx($model, 'relatorio_fk'); ?> *</div>
            <div class='field'>
                <?php
                echo $form->dropDownList($model, 'id', array(), array(
                    'style' => 'width:200px;',
                    'onchange' => 'valida()'
                ));
                ?>
            </div> 
        </div>
    </fieldset>

    <p class="note">
        <b><?php echo Yii::t('app', 'Fields with'); ?> <span class="required">*</span> <?php echo Yii::t('app', 'are required'); ?>.</b>
    </p>
    <br><br>
    <font size="2">
    <b>OBS.:   NÃO É PERMITIDO SALVAR, COPIAR OU IMPRIMIR O RELATÓRIO DE AUDITORIA.</b><br>
    TODAS AS OPERAÇÕES REALIZADAS NO SISTEMA SÃO AUDITADAS.</font>

    <?php $this->endWidget(); ?>
</div><!-- search-form -->



