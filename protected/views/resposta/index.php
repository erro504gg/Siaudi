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
<?php  echo $this->renderPartial('/layouts/_dialogo_view'); ?>

<?php $this->renderPartial('_search', array(
    'model' => $model,
));
?>
<?php if ( !is_null($dados) ): 
     $relatorio_id = $_GET[Resposta][relatorio_fk];

    //verifica se usuário tem acesso a este follow-up
    // (pois o ID do relatório pode ser trocado no GET
    $relatorios_autorizados = Resposta::model()->FollowUp_combo();
    foreach ($relatorios_autorizados as $ids => $values){
        if ($relatorio_id==$ids){
            $acesso_autorizado=1;
        }
    }
    if (!$acesso_autorizado){
        echo "<br><Br><center><b><font face=Verdana size=2>Acesso negado.</font></b></center>";
    } else {

    // pega relatório
    $Relatorio = Relatorio::model()->findByPk($relatorio_id);
    
    // pega especie de auditoria
    $Especie_auditoria = EspecieAuditoria::model()->findByPk($Relatorio->especie_auditoria_fk);
    
    // pega auditores
    $RelatorioAuditor= RelatorioAuditor::model()->findAllByAttributes(array('relatorio_fk'=>$relatorio_id));

    // pega unidades auditadas 
    $RelatorioSureg = RelatorioSureg::model()->findAllByAttributes(array('relatorio_fk'=>$relatorio_id));
    ?>

<div class="tabelaListagemItensWrapper">
    <div class="tabelaListagemItens">
        <center>
        <table style="width:400px;" class="tabelaListagemItensYii">
            <tr> 
                <td align="right" width="150" valign="top"><b>Nº Relatório:</b></td>
                <td><?php 
					echo $Relatorio->numero_relatorio . " DE " . $Relatorio->data_relatorio;
                ?></td>
            </tr>
            <tr> 
                <td align="right" width="150" valign="top"><b>Unidade(s) Auditada(s):</b></td>
                <td><?php 
                    foreach ($RelatorioSureg as $vetor){
                        $UnidadeAdministrativa = UnidadeAdministrativa::model()->findbyAttributes(array('id'=>$vetor[unidade_administrativa_fk]));
                        echo $UnidadeAdministrativa->sigla. '<br>';
                    }
                ?></td>
            </tr>
            <tr class="odd">
                <td align="right" width="150"><b>Espécie de Auditoria:</b></td>
                <td><?php echo $Especie_auditoria->nome_auditoria;?></td>
            </tr>            
            <tr>
                <td align="right" width="150" valign="top"><b>Auditores:</b></td>
                <td><?php 
                    foreach ($RelatorioAuditor as $vetor){
                        $Auditor = Usuario::model()->findByPk($vetor[usuario_fk]);
                        echo $Auditor->nome_usuario . '<br>';
                    }
                ?>
                </td>
            </tr>                        
        </table>
        </center>
            
    <?php $this->widget('zii.widgets.grid.CGridView', array(
            'id' => 'resposta-grid',
            'dataProvider' => $dados,
            'summaryCssClass' => 'sumario',
            'columns' => array(
		'numero_relatorio',
		'numero_capitulo',
		'numero_item',
                array(
                'name'=>'numero_recomendacao',
                'value'=>'$data->numero_item.".".$data->numero_recomendacao',
                ),                
                array(
                'name'=>'qt_cliente',
                'value'=>'Resposta::model()->FollowUp_QT($data->id,"cliente")',
                ),
                array(
                'name'=>'qt_auditor',
                'value'=>'Resposta::model()->FollowUp_QT($data->id,"auditor")',
                ),          
                array(
                'name'=>'ultimo_st',
                'value'=>'Resposta::model()->FollowUp_Ultimo_Status($data->id)',
                'type'=>'raw'                    
                ),                          
                array(
                'name'=>'anexo',
                'value'=>'Resposta::model()->FollowUp_TemAnexo($data->id)',
                'type'=>'raw'                    
                ),                          
            array(
                'class' => 'application.components.MyButtonColumn',
                'template' => '{update}',
//                'updateButtonUrl' => 'Yii::app()->createUrl("Resposta/admin",  array("id" => $data->id) )',
				'updateButtonUrl' => 'Yii::app()->createUrl("resposta/admin/".$data->id."?".$_SERVER["QUERY_STRING"])',
             )
            )
          )
        );
    ?>
    </div>
<?php 
    }
endif;?>
</div>
