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
        'action' => Yii::app()->createUrl($this->route),
        'method' => 'get',
    ));
    ?>
    <fieldset class="visivel">
        <legend class="legendaDiscreta">Dados da Consulta</legend>

        <div class='row'>
            <div class='label'><?php echo $form->label($model, 'nome'); ?></div>
            <div class='field'>
                <?php echo $form->textField($model, 'nome', array('maxlength' => 80, 'size' => 35, 'style' => 'width:500px;')); ?>
            </div>
        </div>                                
        <div class='row'>
            <div class='label'><?php echo $form->label($model, 'sigla'); ?></div>
            <div class='field'>
                <?php echo $form->textField($model, 'sigla', array('maxlength' => 20, 'size' => 30, 'style' => 'width:60px;')); ?>
            </div>
        </div>                                
        <div class='row'>
            <div class='label'><?php echo $form->label($model, 'uf_fk'); ?></div>
            <div class='field'>
                <?php
                $estados = array(null => "Selecione") + UnidadeAdministrativaController::ObtemDadosUF();
                echo $form->dropDownList($model, 'uf_fk', $estados, array('style' => 'width:150px;'));
                ?>
            </div>
        </div>                                
        <div class='row'>
            <div class='label'><?php echo $form->label($model, 'subordinante_fk'); ?></div>
            <div class='field'>
                <?php echo $form->dropDownList($model, 'subordinante_fk', GxHtml::listDataEx(UnidadeAdministrativa::model()->findAll('1=1 order by sigla asc')), array('prompt' => Yii::t('app', ' Selecione'), 'style' => 'width:150px;')); ?>
            </div>
        </div>        
        <div style="height:5px;"></div>        
        <div class='row'>
            <div class='label'><?php echo $form->labelEx($model, 'caracteristica'); ?></div>
            <div class='field'>
                <?php
//                $accountStatus = array('1' => 'Sim', '0' => 'Não');
//                echo $form->radioButtonList($model, 'bolSureg', $accountStatus, array('separator' => ' '));
                echo $form->checkBox($model, 'sureg', array('value' => '1', 'uncheckValue' => '0'));
                echo " " . $form->label($model, 'sureg');
                echo "<div style='height:5px;'></div>";
                echo $form->checkBox($model, 'diretoria', array('value' => '1', 'uncheckValue' => '0'));
                echo " " . $form->label($model, 'diretoria');
                ?>
            </div>

    </fieldset>

    <div class="rowButtonsN1">


        <?php echo GxHtml::submitButton(Yii::t('app', 'Search'), array('class' => 'botao')); ?>

        <input type="reset" class='botao' value='Limpar'>

    </div>

    <?php $this->endWidget(); ?>
</div><!-- search-form -->
