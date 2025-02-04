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
$perfil = strtolower(Yii::app()->user->role);
$perfil = str_replace("siaudi2", "siaudi", $perfil);
if ($perfil != "siaudi_chefe_auditoria") {
    echo "<br><br><b><center>";
    echo "Funcionalidade disponível apenas para o Chefe de Auditoria.";
    echo "</center></b>";
} else {

    if ($_POST['Relatorio']['id']) {
        $id = $_POST['Relatorio']['id'];
        $Relatorio = Relatorio::model()->findByPk($id);
        $Relatorio_sureg = RelatorioSureg::model()->sureg_por_relatorio($id);
        echo "  <br><br>
            <div align='center'><b>Relatório homologado com sucesso</b>.<br><br>
                    <b>Unidade Auditada</b>: " . $Relatorio_sureg . " <br>
                    O Relatório passou a ser identificado como:<br><br>
                    <b>Relatório de Auditoria Nº</b>: " . $Relatorio->numero_relatorio . "<br> 
                    <b>Data</b>: " . $Relatorio->data_relatorio . "<br><br>
            <a class='imitacaoBotao' href='javascript:history.back();'>Voltar</a>                        
            </div>";
    } else {

        $baseUrl = Yii::app()->baseUrl;
        $cs = Yii::app()->getClientScript();
        $cs->registerScriptFile($baseUrl . '/js/Relatorio.js');
        $form = $this->beginWidget('GxActiveForm', array(
            'action' => Yii::app()->createUrl($this->route),
            'method' => 'post',
            'id' => 'homologar'
        ));
        ?>
        <div class="formulario" Style="width: 70%;">
            <fieldset class="visivel">
                <legend class="legendaDiscreta">Homologar <?php echo $model->label(); ?> </legend>
                <div class='row'>
                    <div class='label'>ID Relatório: <span class="required">*</span></div>
                    <div class='field'><?php echo $form->dropDownList($model, 'id', Relatorio::model()->ComboRelatorioFinalizado(), array('prompt' => Yii::t('app', ' Selecione'), 'style' => 'width:200px;')); ?>
                    </div>      
            </fieldset>
            <p class="note">
        <?php echo Yii::t('app', 'Fields with'); ?>
                <span class="required">*</span>
                <?php echo Yii::t('app', 'are required'); ?>.
            </p>
            <div class="rowButtonsN1">
                <a class="imitacaoBotao" href="javascript:valida_homologacao();">Confirmar</a>
        <?php echo CHtml::link(Yii::t('app', 'Cancel'), $this->createUrl('Site/index'), array('class' => 'imitacaoBotao')); ?>
            </div>    
        </div>
        <?php
        $this->endWidget();
    }
}
?>
