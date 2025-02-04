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
<html>
<head>
<link rel="stylesheet" type="text/css"
	href="<?php echo Yii::app()->request->baseUrl; ?>/themes/<?php echo Yii::app()->params['tema'] ?>/css/estiloGeral.css" />
<link rel="stylesheet" type="text/css"
	href="<?php echo Yii::app()->request->baseUrl; ?>/themes/<?php echo Yii::app()->params['tema'] ?>/css/estiloEspecifico.css" />
<link rel="stylesheet" type="text/css"
	href="<?php echo Yii::app()->request->baseUrl; ?>/themes/estilo_yii.css" />
<script type="text/javascript">
function carregaRecomendacao(key)
{
  valor = $("#recomendacao" + key).html();
  $('#Recomendacao_descricao_recomendacao_ifr').contents().find('#tinymce').html(valor);
  $('#dialog_importar_recomendacao').dialog('close');
} 
</script>
</head>
<body>
        
	<div class="row">
		<form>
                        
			<div class="formulario" style="width: auto">
                            <?= $this->renderPartial('/layouts/_msg'); ?>
				<fieldset>
					<div class="row">
						<fieldset class="visivel" style="float: left;">
							<legend>Recomendação Padrão</legend>
							<div class="row">
								<div class="tabelaListagemItensWrapper">
									<div class="tabelaListagemItens">
										<table id="row">
											<thead>
												<tr>
													<th width="auto"><div align="center">Recomendações</div>
													</th>
													<th><div align="center">Selecionar</div>
													</th>
												</tr>
											</thead>
											<tbody>
											<?php
                                                                                            $model_recomendacao_padrao=  RecomendacaoPadrao::model()->findAll('1=1 ORDER BY id');
                                                                                            $baseUrl = Yii::app()->baseUrl;
                                                                                            if (sizeof($model_recomendacao_padrao)>0){
                                                                                                    foreach ($model_recomendacao_padrao as $key=>$recomandacao_padrao){
                                                                                                            echo '<tr class="odd">';
                                                                                                            echo "<td id='recomendacao".$key."'>";
                                                                                                            echo $recomandacao_padrao;
                                                                                                            echo "</td>";
                                                                                                            echo "<td align='center' valign='top'>";
//                                                                                                            echo '<a href="javascript:carregaRecomendacao(&apos;'.$recomandacao_padrao.'&apos;);">';
                                                                                                            echo '<a href="javascript:carregaRecomendacao('.$key.');">';
                                                                                                            echo "<img src='$baseUrl/images/btn_confirm.png'>";
                                                                                                            echo '</a>';
                                                                                                            echo "</td>";
                                                                                                            echo "</tr>";
                                                                                                    }
                                                                                            }
											?>
											</tbody>
										</table>
									</div>
									<!-- fecha a <div class="tabelaListagemItens"> -->
								</div>
							</div>
						</fieldset>
					</div>
				</fieldset>
			</div>
		</form>
	</div>
</body>
</html>
