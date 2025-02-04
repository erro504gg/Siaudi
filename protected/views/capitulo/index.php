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
                'id' => 'capitulo-grid',
                'dataProvider' => $dados,
                'summaryCssClass' => 'sumario',
                'enableSorting'=>false,                
                'columns' => array(
                    array(
                        'name' => 'relatorio_fk',
                        'value' => 'Capitulo::model()->CapituloRelatorio($data->id)',
                    ),
                    array(
                        'name' => 'especieauditoriaFK',
                        'value' => 'EspecieAuditoria::model()->EspecieAuditoria_Relatorio($data->relatorio_fk)',
                    ),
                      array(
                      'name'=>'unidade_administrativa_fk',
                      'value'=>'RelatorioSureg::model()->sureg_por_relatorio($data->relatorio_fk)',
                      ),                         
                    
                    'numero_capitulo',
                    'nome_capitulo',
                    /*                    
                    'descricao_capitulo',
                    'data_gravacao',
                      'numero_capitulo_decimal',
                     */
                    array(
                        'class' => 'application.components.MyButtonColumn',
                        'deleteConfirmation' => "js:'Deseja remover o Capitulo '+$(this).parent().parent().children(':first-child').text()+'?'",
                        'updateButtonUrl' => 'Yii::app()->createUrl("Capitulo/admin",  array("id" => $data->id) )',
                        'template' => '{buttomCapitulo} {view} {update} {delete}',                        
                        'buttons' => array(
                            'buttomCapitulo' => array(
                                'label' => 'Mostrar Itens cadastrados para este Capítulo',
                                'url' => 'Yii::app()->createUrl("/item/index?yt0=Consultar&Item%5Bcapitulo_fk%5D=".$data->id."&Item%5Bnumero_capitulo%5D=".$data->numero_capitulo."&Item%5Brelatorio_fk%5D=".$data->relatorio_fk)',
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
