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
                'id' => 'plan-especifico-grid',
                'dataProvider' => $dados,
                'summaryCssClass' => 'sumario',
                'columns' => array(
                    'valor_exercicio',
                    'data_log_formatado',  
                    
                    array(
                      'name'=>'numero_acao',
                      'value'=>'PlanEspecifico::model()->numero_acao_por_sureg($data->acaoFk,$data->valor_sureg)',
                      ),
                    array(
                      'name'=>'acao_fk',
                      'value'=>'GxHtml::valueEx($data->acaoFk)',
                      'filter'=>GxHtml::listDataEx(Acao::model()->findAllAttributes(null, true)),
                      ),
                      array(
                      'name'=>'valor_sureg',
                      'value'=>'UnidadeAdministrativa::model()->sureg_por_id($data->valor_sureg)',
                      ),                    
                    
                      array(
                      'name'=>'unidade_administrativa_fk',
                      'value'=>'UnidadeAdministrativa::model()->sureg_por_id($data->unidade_administrativa_fk)',
                      ),  		
                       array(
			'name'=>'objeto_fk',
			'value'=>'GxHtml::valueEx($data->objetoFk)',
			'filter'=>GxHtml::listDataEx(Objeto::model()->findAllAttributes(null, true)),
		     ),                  
                    
                    
                    /*
                      'id_usuario_log',
                      'valor_sureg',
                      'observacao_questoes_macro',
                      'observacao_resultados',
                      'observacao_legislacao',
                      'observacao_detalhamento',
                      'observacao_tecnicas_auditoria',
                      'observacao_pendencias',
                      'observacao_custos',
                      'observacao_cronograma',
                      array(
                      'name'=>'acao_fk',
                      'value'=>'GxHtml::valueEx($data->acaoFk)',
                      'filter'=>GxHtml::listDataEx(Siaudi.acao::model()->findAllAttributes(null, true)),
                      ),
                     */
                    array(
                        'class' => 'application.components.MyButtonColumn',
                            'deleteConfirmation' => "js:'Deseja remover o PlanEspecifico '+$(this).parent().parent().children(':first-child').text()+'?'",
                            'updateButtonUrl' => 'Yii::app()->createUrl("PlanEspecifico/admin",  array("id" => $data->id) )',                                                                                    
                        'template' => '{buttomPDF} {view} {update} {delete}',
                        'buttons' => array(
                            'buttomPDF' => array(
                                'label' => 'Gerar PDF',
                                'url' => 'Yii::app()->createUrl("planEspecifico/ExportarPDFAjax/",array("id" => $data->id))',
                                'imageUrl' => Yii::app()->request->baseUrl . "/themes/" . Yii::app()->params["tema"] . "/img/pdf.png",
                                'options' => array('id' => "buttonDetalhar" ),
                                                             
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
