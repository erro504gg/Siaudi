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
        array(
            'name' => 'relatorioFk',
            'type' => 'raw',
            'value' => Item::model()->ItemRelatorio($model->id, 1),
        ),
        array(
            'name' => 'capituloFk',
            'type' => 'raw',
            'value' => $model->capituloFk !== null ? GxHtml::link(GxHtml::encode(GxHtml::valueEx($model->capituloFk)), array('capitulo/view', 'id' => GxActiveRecord::extractPkValue($model->capituloFk, true))) : null,
        ),
//'numero_item',
        'valor_reais',
        'nome_item',
        array(
            'name' => 'descricao_item',
            'type' => 'raw',
            'value' => str_replace('src="../', 'src="', $model->descricao_item),
        ),
        'data_gravacao',
        array(
            'name' => 'objetoFk',
            'type' => 'raw',
            //'value' => $model->objetoFk !== null ? GxHtml::link(GxHtml::encode(GxHtml::valueEx($model->objetoFk)), array('objeto/view', 'id' => GxActiveRecord::extractPkValue($model->objetoFk, true))) : null,
            'value' => $model->objetoFk !== null ? GxHtml::valueEx($model->objetoFk) : null,                                    
        ),
    ),
));
?>

