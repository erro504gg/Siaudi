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

$this->widget('zii.widgets.CDetailView', array(
    'data' => $model,
    'attributes' => array(
        'valor_exercicio',
        'data_log_formatado',
        'data_inicio_atividade',
        array(
        	'name'=>'numero_acao',
            'value'=>PlanEspecifico::model()->numero_acao_por_sureg($model->acaoFk,$model->valor_sureg),
       	),
        array(
            'name' => 'acaoFk',
            'type' => 'raw',
            //'value' => $model->acaoFk !== null ? GxHtml::link(GxHtml::encode(GxHtml::valueEx($model->acaoFk)), array('acao/view', 'id' => GxActiveRecord::extractPkValue($model->acaoFk, true))) : null,
            'value' => $model->acaoFk !== null ? GxHtml::valueEx($model->acaoFk) : null,            
        ),
        array(
            'name' => 'valor_sureg',
            'value' => UnidadeAdministrativa::model()->sureg_por_id($model->valor_sureg),
        ),
        array(
            'name' => 'unidade_administrativa_fk',
            'value' => UnidadeAdministrativa::model()->sureg_por_id($model->unidade_administrativa_fk),
        ),
        array(
            'name' => 'objetoFk',
            'type' => 'raw',
            //'value' => $model->objetoFk !== null ? GxHtml::link(GxHtml::encode(GxHtml::valueEx($model->objetoFk)), array('objeto/view', 'id' => GxActiveRecord::extractPkValue($model->objetoFk, true))) : null,
            'value' => $model->objetoFk !== null ? GxHtml::valueEx($model->objetoFk) : null,                        
        ),
        array(
            'name' => 'observacao_amostragem',
            'type' => 'raw',
        ),
        array(
            'name' => 'observacao_representatividade',
            'type' => 'raw',
        ),
        array(
            'name' => 'observacao_questoes_macro',
            'type' => 'raw',
        ),
        array(
            'name' => 'observacao_resultados',
            'type' => 'raw',
        ),
        array(
            'name' => 'observacao_legislacao',
            'type' => 'raw',
        ),
        array(
            'name' => 'observacao_detalhamento',
            'type' => 'raw',
        ),
        array(
            'name' => 'observacao_tecnicas_auditoria',
            'type' => 'raw',
        ),
        array(
            'name' => 'observacao_pendencias',
            'type' => 'raw',
        ),
        array(
            'name' => 'observacao_custos',
            'type' => 'raw',
        ),
        array(
            'name' => 'observacao_cronograma',
            'type' => 'raw',
        ),
    ),
));
?>

