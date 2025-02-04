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

<?php $this->widget('zii.widgets.CDetailView', array(
	'data' => $model,
	'attributes' => array(
                   array(
			'name' => 'relatorio_fk',
			'type' => 'raw',
			'value' => Capitulo::model()->CapituloRelatorio($model->id,1),
			),
            
                   array(
			'name' => 'especieauditoriaFK',
			'type' => 'raw',
			'value' => EspecieAuditoria::model()->EspecieAuditoria_Relatorio($model->relatorio_fk),
			),
            
                  array(
                            'name' => 'unidade_administrativa_fk',
                            'type' => 'raw',
                            'value' => RelatorioSureg::model()->sureg_por_relatorio($model->relatorio_fk),
                        ),               
            

'numero_capitulo',
'nome_capitulo',
array(
			'name' => 'descricao_capitulo',
			'type' => 'raw',
                        'value' => str_replace('src="../','src="',$model->descricao_capitulo),
			),            
'data_gravacao',
	),
)); ?>

