
<fieldset>
    <table width="960" style="margin-top:-23px;">
        <tr>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>
                <div class="tabelaListagemItensWrapper" style="width:940px">
                    <div style="margin-left:10px; margin-top:10px; margin-bottom:10px; margin-right:10px; background-color:#ded8d8; ">
                        <div class="formulario" style="width: auto;">
                            <fieldset>
                                <div class="row">
                                    <h3>&nbsp;</h3>
                                    <fieldset style="width: 800px;">
                                        <div class="row">
                                            <!-- CONTENT -->
                                            <?php
                                            $this->renderPartial('_search');
                                            ?>
                                        </div>
                                    </fieldset>  
                                </div> 
                            </fieldset>
                        </div>
                    </div> <!--fecha formulario -->
                </div>
            </td>
        </tr>
    </table>                   
</fieldset>
<?php if (!is_null($dados)): ?>
    <!-- MONTA A GRIDVIEW -->
    <div class="tabelaListagemItensWrapper">
        <div class="tabelaListagemItens">
            <?php
            $this->widget('zii.widgets.grid.CGridView', array(
                'id' => 'controller-grid',
                'dataProvider' => $dados,
                'summaryCssClass' => 'sumario',
                'columns' => array(
                    'campo1',
                    'campo2',
                    array(
                        'class' => 'application.components.MyButtonColumn',
                        'deleteConfirmation' => "js:'Deseja remover o Cbos '+$(this).parent().parent().children(':first-child').text()+'?'",
                        'updateButtonUrl' => 'Yii::app()->createUrl("Site/admin/".$data->USU_CodCbos."?".$_SERVER["QUERY_STRING"])',
                        'template' => '{view}',
                    )
                )));
            ?>
        </div>
    </div>
<?php endif; ?>

