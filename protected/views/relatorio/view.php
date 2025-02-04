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
        'numero_relatorio',
        array(
            'name' => 'especieAuditoriaFk',
            'type' => 'raw',
            //'value' => $model->especieAuditoriaFk !== null ? GxHtml::link(GxHtml::encode(GxHtml::valueEx($model->especieAuditoriaFk)), array('EspecieAuditoria/view', 'id' => GxActiveRecord::extractPkValue($model->especieAuditoriaFk, true))) : null,
            'value' => $model->especieAuditoriaFk !== null ? GxHtml::valueEx($model->especieAuditoriaFk) : null,
        ),
        array(
            'name' => 'categoriaFk',
            'type' => 'raw',
            //'value' => $model->categoriaFk !== null ? GxHtml::link(GxHtml::encode(GxHtml::valueEx($model->categoriaFk)), array('Categoria/view', 'id' => GxActiveRecord::extractPkValue($model->categoriaFk, true))) : null,
            'value' => $model->categoriaFk !== null ? GxHtml::valueEx($model->categoriaFk) : null,            
        ),
        array(
                      'name'=>'descricao_introducao',
                      'type'=>'raw',     
                      'value' => str_replace('src="../','src="',$model->descricao_introducao),
                ),        

        array(
            'name' => 'diretoria_fk',
            'type' => 'raw',
            'value' => RelatorioDiretoria::model()->diretoria_por_relatorio($model->id,0),
        ),               
        array(
            'name' => 'unidade_administrativa_fk',
            'type' => 'raw',
            'value' => RelatorioSureg::model()->sureg_por_relatorio($model->id),
        ),                
        
        'valor_prazo',        
        'data_gravacao',        
        'data_relatorio',        
        'data_finalizado',
        'data_regulariza',

        array(
        'name'=>'login_relatorio',
        'value'=>Usuario::model()->usuario_por_login($model->login_relatorio),
        'type'=>'raw',                             
        ), 
        array(
        'name'=>'login_homologa',
        'value'=>Usuario::model()->usuario_por_login($model->login_homologa),
        'type'=>'raw',                             
        ), 
        array(
        'name'=>'login_finaliza',
        'value'=>Usuario::model()->usuario_por_login($model->login_finaliza),
        'type'=>'raw',                             
        ), 
        array(
            'name' => 'objetoFk',
            'type' => 'raw',
          //  'value' => $model->objetoFk !== null ? GxHtml::link(GxHtml::encode(GxHtml::valueEx($model->objetoFk)), array('objeto/view', 'id' => GxActiveRecord::extractPkValue($model->objetoFk, true))) : null,
            'value' => $model->planEspecificoFk->objetoFk !== null ? GxHtml::valueEx($model->planEspecificoFk->objetoFk) : null,                        
        ),
    ),
));
?>
