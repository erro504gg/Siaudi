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
        'id',
//'numero_recomendacao',
        array(
            'name' => 'relatorioFk',
            'type' => 'raw',
            'value' => Recomendacao::model()->RecomendacaoRelatorio($model->id, 1),
        ),
        array(
            'name' => 'capituloFk',
            'type' => 'raw',
            'value' => Recomendacao::model()->RecomendacaoCapitulo($model->id, 1),
        ),
        array(
            'name' => 'itemFk',
            'type' => 'raw',
            'value' => $model->itemFk !== null ? GxHtml::link(GxHtml::encode(GxHtml::valueEx($model->itemFk)), array('item/view', 'id' => GxActiveRecord::extractPkValue($model->itemFk, true))) : null,
        ),
        array(
            'name' => 'unidade_administrativa_fk',
            'type' => 'raw',
            'value' => UnidadeAdministrativa::model()->sureg_por_id($model->unidade_administrativa_fk, 1),
        ),
        array(
            'name' => 'recomendacaoTipoFk',
            'type' => 'raw',
            //'value' => $model->recomendacaoTipoFk !== null ? GxHtml::link(GxHtml::encode(GxHtml::valueEx($model->recomendacaoTipoFk)), array('RecomendacaoTipo/view', 'id' => GxActiveRecord::extractPkValue($model->recomendacaoTipoFk, true))) : null,
            'value' => $model->recomendacaoTipoFk !== null ? GxHtml::valueEx($model->recomendacaoTipoFk) : null,                                    
        ),
        array(
            'name' => 'recomendacaoGravidadeFk',
            'type' => 'raw',
            //'value' => $model->recomendacaoGravidadeFk !== null ? GxHtml::link(GxHtml::encode(GxHtml::valueEx($model->recomendacaoGravidadeFk)), array('RecomendacaoGravidade/view', 'id' => GxActiveRecord::extractPkValue($model->recomendacaoGravidadeFk, true))) : null,
            'value' => $model->recomendacaoGravidadeFk !== null ? GxHtml::valueEx($model->recomendacaoGravidadeFk) : null,
        
        ),
        array(
            'name' => 'recomendacaoCategoriaFk',
            'type' => 'raw',
            //'value' => $model->recomendacaoCategoriaFk !== null ? GxHtml::link(GxHtml::encode(GxHtml::valueEx($model->recomendacaoCategoriaFk)), array('RecomendacaoCategoria/view', 'id' => GxActiveRecord::extractPkValue($model->recomendacaoCategoriaFk, true))) : null,
            'value' => $model->recomendacaoCategoriaFk !== null ? GxHtml::valueEx($model->recomendacaoCategoriaFk) : null,            
        ),
        array(
            'name' => 'recomendacaoSubcategoriaFk',
            'type' => 'raw',
            //'value' => $model->recomendacaoSubcategoriaFk !== null ? GxHtml::link(GxHtml::encode(GxHtml::valueEx($model->recomendacaoSubcategoriaFk)), array('RecomendacaoSubcategoria/view', 'id' => GxActiveRecord::extractPkValue($model->recomendacaoSubcategoriaFk, true))) : null,
            'value' => $model->recomendacaoSubcategoriaFk !== null ? GxHtml::valueEx($model->recomendacaoSubcategoriaFk) : null,
        ),
        array(
            'name' => 'descricao_recomendacao',
            'type' => 'raw',
            'value' => str_replace('src="../', 'src="', $model->descricao_recomendacao),
        ),
        'data_gravacao',
    ),
));
?>

