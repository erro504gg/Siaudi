<!--
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
-->

<tiles:insert definition="main.layout">

	<tiles:put name="title" type="string">
		<c:set var="titulo" value="<?php echo $sessionScope.tituloPagina;?>" />
		<c:choose>
			<c:when test="<?php echo  empty titulo;?>">
				<bean:message key="mantem.template.titulo" />
			</c:when>
			<c:otherwise>
				<bean:message key="<?php echo titulo; ?>" />
			</c:otherwise>
		</c:choose>
	</tiles:put>


	<tiles:put name="subtitle" type="string">
		<c:set var="subtitulo" value="<?php echo sessionScope.subTituloPagina;?>" />
		<c:choose>
			<c:when test="<?php echo  empty subtitulo;?>">
				<bean:message key="mantem.template.item.legenda" />
			</c:when>
			<c:otherwise>
				<bean:message key="<?php echo subtitulo;?>" />
			</c:otherwise>
		</c:choose>
	</tiles:put>

	<tiles:put name="style" type="string">
		<link rel="stylesheet" type="text/css" media="all"
			href="<html:rewrite page='/css/default-calendar.css'/>" />

	</tiles:put>

	<?php  include_once("item-template-javascript.js"); ?>

	<tiles:put name="body" type="string">

		<div id="orientacoes">
			<div class="orientacao">
				<bean:message key="mantem.template.item.dica" />
			</div>
		</div>
		
		<html:form styleId="mantemTemplateForm" action="/ConfirmaItemTemplateCSU/ConfirmaTreeViewItemTemplate" method="post">
		
			<div class="formulario" style="width: auto">
			
				<div class="row">
					<fieldSet class="visivel" style="width: 900px">
						<legend class="legendaDiscreta"> <bean:message key='mantem.template.legenda' /></legend>
				
						<div class="row">
							<b> <bean:message key="campo.template.nome" /> </b> : <?php echo form.nome;?> &nbsp;&nbsp;
							<b> <bean:message key="campo.modalidade.edital" />: </b> <?php echo form.modalidadeEdital;?> &nbsp;&nbsp;
							<b> <bean:message key="campo.tipo.modalidade.edital" />: </b> <?php echo form.tipoModalidadeEdital;?> &nbsp;&nbsp;
							<b> <bean:message key="campo.situacao" />: </b> <?php echo form.status;?>
						</div>
				
						<html:hidden name="form" property="id" styleId="id"/>
						<html:hidden name="form" property="nome" styleId="nome"/>
						<html:hidden name="form" property="valueModalidadeEdital" styleId="valueModalidadeEdital"/>
						<html:hidden name="form" property="valueTipoModalidadeEdital" styleId="valueTipoModalidadeEdital"/>
						<html:hidden name="form" property="itemsJson" styleId="itemsJson"/>
				
					</fieldSet>
				</div>	
				
			</div>
			
			
			<div class="formulario" style="width: auto">			
				
				<fieldSet class="visivel">
					<legend class="legendaDiscreta"> <bean:message key='mantem.template.item.legenda' /></legend>
					
					<table style="width:100%" border="0">
						<tr>
													
							<td style="width:300px;" valign="top" rowspan="3">
							
										
								<div id="p" class="easyui-panel" title="" style="width:300px;height:400px;padding:10px;"  
							        data-options="closable:false, collapsible:false,minimizable:false,maximizable:false" >  

										
									<div style="margin:10px;">
									
										<html:link href="javascript:void(0)" onclick="$('#treeViewItens').tree('expandAll');" styleClass="buttonLink" title="Expandir todos">
											<html:img page="/images/expand_all.gif" style="display:inline;border:0;" />
										</html:link>
											
										<html:link href="javascript:void(0)" onclick="$('#treeViewItens').tree('collapseAll');" styleClass="buttonLink" title="Recolher todos">
											<html:img page="/images/collapse_all.gif" style="display:inline;border:0;" />
										</html:link>
										
										<html:link href="javascript:void(0)" onclick="treeAppendItem($('#treeViewItens'))" styleClass="buttonLink" title="Adicionar Item">
											<html:img page="/images/adicionar_item.gif" style="display:inline;border:0;" />
										</html:link>
										
										<html:link href="javascript:void(0)" onclick="treeAppendItemFilho($('#treeViewItens'))" styleClass="buttonLink" title="Adicionar Sub-Item">
											<html:img page="/images/adicionar_subitem.gif" style="display:inline;border:0;" />
										</html:link>
										
										<html:link href="javascript:void(0)" onclick="treeRemoveItem($('#treeViewItens'))" styleClass="buttonLink" title="Remover Item">
											<html:img page="/images/excluir.gif" style="display:inline;border:0;" />
										</html:link>					
									
										<!-- a href="#" onclick="reload()">Recarregar tree</a> <br  -->
										
									</div>
									
									<hr>
									
									<ul id="treeViewItens" animate="true" lines="false" dnd="true" class="treeview_template"> </ul>
									
				
									<div id="menu_contexto" class="easyui-menu" style="width:120px;">
										<div onclick="treeAppend($('#treeViewItens'))" iconCls="icon-add">Adicionar item</div>
										<div onclick="treeRemove($('#treeViewItens'))" iconCls="icon-remove">Remover item</div>
										<div class="menu-sep"></div>
										<div onclick="expand()">Expandir</div>
										<div onclick="collapse()">Recolher</div>
									</div>
								
								</div>
											
							</td>
							
						</tr>
						
						<tr>							
							<td valign="top" style="height:100%">
								<div id="divTitulo" style="display:inline">
									<span style="font-size:11px"> <b> Titulo: </b> </span>
									<textarea name="titulo"	id="titulo" class="tinymce_simple" style="width:100%;" ></textarea>
								</div>
							</td>
						</tr>
						
						<tr>
							<td>
								<div id="divTexto" style="display:inline">
									<span style="font-size:11px"> <b> Texto: </b> </span>
									<textarea name="texto" id="texto" class="tinymce_advanced" style="width:100%" ></textarea>
								</div>
								
								<div id="divAnexo" style="display:none">
									<input type="hidden" id="anexoId"/>
									
									<html:link href="javascript:void(0)" onclick="downloadAnexo();" styleClass="buttonLink" title="Download do arquivo">
										<html:img page="/images/pdf.gif" style="display:inline;border:0;" />
										<span id="anexoNome" style="display:inline;"/>
									</html:link>

								</div>
								
								<div id="divAnexoUpload" style="display:none">									
									<div class="field">
										<span style="font-size:11px"> * Apenas arquivos no formato <b>.pdf</b> são aceitos. </span> <br> 
										<input id="arquivoAnexo" type="file" accept="application/pdf" width="700px"/>
										<input type="button" name="text" value="<bean:message key='botao.confirmar'/>" onclick="uploadAnexo()" class="botao" /> 
									</div>
								</div>
								
							</td>
							
						</tr>
						
					</table>
					
					
					<div class="rowButtonsN1">
						
						<span id="indicatorTemplate" style="display:inline">
							<img src="<%=request.getContextPath()%>/images/indicator.gif" />
						</span>
						
						<input type="button" name="text"						
							value="<bean:message key='botao.gravar/>"
							onmouseover="hints.show('gravar')" onmouseout="hints.hide()"
							id="form_submit" onclick="gravaItensJson();" class="botao" />

						<input type="button" name="text"
							value="<bean:message key='mantem.template.item.adicionar.atributo'/>"
							onmouseover="hints.show('atributo')" onmouseout="hints.hide()"
							id="form_submit" onclick="showAtributos();" class="botao" />

						<input type="button" name="text"
							value="<bean:message key='mantem.template.item.adicionar.documento'/>"
							onmouseover="hints.show('documento')" onmouseout="hints.hide()"
							id="form_submit" onclick="showDocumentoExigido();"
							class="botao" />

						<input type="button" name="text"
							value="<bean:message key="mantem.template.item.adicionar.anexo"/>"
							onmouseover="hints.show('anexo')" onmouseout="hints.hide()"
							id="form_submit" onclick="showAnexos();"
							class="botao" />

						<input type="button" name="text"
							value="<bean:message key='mantem.template.item.visualizar.modelo'/>"
							onmouseover="hints.show('visualizar')" onmouseout="hints.hide()"
							id="form_submit" 
							onclick="openWindow('<html:rewrite action=\"/RelatorioTemplateCSU/RelatorioTemplateCSU.do?command=template\"/>','Relatorio',true,false,760,540); return false;"
							class="botao" />											
					</div>
					
				</fieldSet>
				
				<div class="rowButtonsN1">
					
					<logic:present
						role="SIAUDI_ADMINISTRADOR">
						<input type="button" name="text"
							value="<bean:message key='botao.anterior'/>"
							onmouseover="hints.show('Anterior')" onmouseout="hints.hide()"
							id="form_submit" onclick="defineActionAnterior(this);" class="botao" />
					</logic:present> 
					<logic:notPresent role="SIAUDI_ADMINISTRADOR">
						<input type="button" name="text"
							value="<bean:message key='botao.anterior'/>"
							onmouseover="hints.show('Anterior_no')" onmouseout="hints.hide()"
							disabled="true" id="form_submit"
							onclick="defineActionAnterior(this);" class="botao" />
					</logic:notPresent>						
					
					<logic:present
						role="SIAUDI_ADMINISTRADOR">
						<input type="button" name="text"
							value="<bean:message key='botao.confirmar'/>"
							onmouseover="hints.show('Confirmar')" onmouseout="hints.hide()"
							id="form_submit" onclick="defineActionConfirmar(this);" class="botao" />					
					</logic:present> 
					<logic:notPresent role="SIAUDI_ADMINISTRADOR">
						<input type="button" name="text"
							value="<bean:message key='botao.confirmar'/>"
							onmouseover="hints.show('Confirmar_no')" onmouseout="hints.hide()"
							disabled="true" id="form_submit" onclick="defineActionConfirmar(this);"
							class="botao" />
					</logic:notPresent>
				</div>
				
			</div>
		
		</html:form>

		<div id="pageHelpSection">			
	
			<a href="" id="pageHelp" style="display: inline;"
				onclick="openWindow('<html:rewrite action=\"/AlteraLoteCSUHelp\"/>','onlinehelp',true,false,760,540); return false;">
					<bean:message key="online.help.href" /> 
			</a> 
			<html:img page="/layout/help.gif" style="display:inline;" /></blockquote>
		</div>

	</tiles:put>
	
	
	<?php include_once("item-template-atributos.jspf";?>
	<?php include_once("item-template-anexos.jspf";?>
	<?php include_once("item-template-documentoexigido.jspf";?>
</tiles:insert>