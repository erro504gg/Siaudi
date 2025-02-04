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
<?php echo $this->renderPartial('/layouts/_dialogo_view'); ?>

<?php
$this->renderPartial('_search', array(
    'model' => $model,
));
?>
        <?php if (!is_null($dados)): ?><div class="tabelaListagemItensWrapper">
        <div class="tabelaListagemItens">
            <?php
            $this->widget('zii.widgets.grid.CGridView', array(
                'id' => 'relatorio-grid',
                'dataProvider' => $dados,
                'summaryCssClass' => 'sumario',
                'enableSorting'=>false,
                'columns' => array(
                    'id',
                  //  'numero_relatorio',
                  
                    array(
                        'name' => 'especie_auditoria_fk',
                        'value' => 'GxHtml::valueEx($data->especieAuditoriaFk)',
                        'filter' => GxHtml::listDataEx(EspecieAuditoria::model()->findAllAttributes(null, true)),
                    ),	
                    array(
                        'name' => 'categoria_fk',
                        'value' => '$data->categoriaFk',
                       // 'filter' => GxHtml::listDataEx(Categoria::model()->findAllAttributes(null, true)),
                    ),
                      array(
                      'name'=>'diretoria_fk',
                      'value'=>'RelatorioDiretoria::model()->diretoria_por_relatorio($data->id)',
                      ),   
                      array(
                      'name'=>'unidade_administrativa_fk',
                      'value'=>'RelatorioSureg::model()->sureg_por_relatorio($data->id)',
                      ),
					array(
                        'class' => 'application.components.MyButtonColumn',
                        'deleteConfirmation' => "js:'Deseja remover o Relatorio '+$(this).parent().parent().children(':first-child').text()+'?'",
                        'updateButtonUrl' => 'Yii::app()->createUrl("Relatorio/admin",  array("id" => $data->id) )',
                        'template' => '{buttomCapitulo} {view} {update} {delete}',                        
                        'buttons' => array(
                            'buttomCapitulo' => array(
                                'label' => 'Mostrar Capítulos cadastrados para este Relatório',
                                'url' => 'Yii::app()->createUrl("/capitulo/index?yt0=Consultar&Capitulo%5Brelatorio_fk%5D=".$data->id)',
                                'imageUrl' => Yii::app()->request->baseUrl . "/themes/" . Yii::app()->params["tema"] . "/img/subitens.png",

                                                             
                            ),
                        )                        
                    )
                )
                    )
            );
            ?>
        </div>
<?php endif; ?>
</div>
