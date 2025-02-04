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

<script>
	
	

	//Verifica se o item foi atualizado na tela
	function verificaAtualizacao(pTree, node){

		if ((node.attributes.titulo != $('#titulo').tinymce().getContent()) || (node.attributes.texto != $('#texto').tinymce().getContent())){			
			populaJson(pTree, node);
		}
		
	}

	
	//Atualiza ilha JSon com os dados da tela
	function populaJson(pTree, node){

		try{
			var numeracao = getNodeNumeracao(node); 

			if (numeracao != "")
				numeracao += "&nbsp;"

			node.text = numeracao + $('#titulo').tinymce().getContent({format:'text'});  
			node.attributes.titulo = $('#titulo').tinymce().getContent(); 
			node.attributes.texto = $('#texto').tinymce().getContent(); 
			//node.attributes.numeravel = "true";
			//node.iconCls = 'icon-reload';
			pTree.tree('update', node);
			
		}catch(e){
			alert("Erro ao atualizar item " + e);
		}
	}


	//Atualiza tela com os dados JSon
	function populaTela(node){

		
		
		try{

			$('#titulo').tinymce().setContent(node.attributes.titulo);
			$('#texto').tinymce().setContent(node.attributes.texto);

		}catch(e){
			$('#titulo').tinymce().setContent("");
			$('#texto').tinymce().setContent("");

			$('#anexoId').val("");
			$('#anexoNome').html("");

		}
	}

	


	function confirmarDados(){

		var pTree = $('#treeViewItens');
		var node = pTree.tree('getSelected');
		if (node != null)
			verificaAtualizacao(pTree, node);

		//var root = pTree.tree('getRoot');
		//var data = pTree.tree('getData', root.target);

		//alert(JSON.stringify(data));
		
		//console.log(getJsonTree(pTree));
		//console.log(JSON.stringify(eval(getJsonTree(pTree))));		
		
		
		$('#itemsJson').val(getJsonTree(pTree));
		
		//console.log($('#itemsJson').val());
	}
	


	//Tratamento da strings (Aspas são caracteres delimitadores no JSon)
	function quoteStr(texto){

		texto = texto.replace(/'/g,"\\'");
		texto = texto.replace(/"/g,'\\"');

		return texto;
	}


	
	function showAtributos(){		
		$('#divAtributos').css("display", "block");//div fica invisivel para tabela não ser exibida no carregamento da tela
		$('#winAtributos').window('open');		
	}


	function showAnexos(){		
		$('#divAnexos').css("display", "block");
		$('#winAnexos').window('open');		
	}
	
	
	function showDocumentoExigido(){
		
		$('#divDocumentoExigido').css("display", "block");
		$('#winDocumentoExigido').window('open');		
	}


	//***** FUNÇÕES AJAX /////////////////////////////////

	/*Funcao recupera os itens do template.*/
	/*function recuperaItensJson(){
		//Instanciando um objeto e adicionando uma propriedade,
		//esse objeto ao ser passado para a classe java(FacadeAjax) sera convertido para um objeto do_ tipo BeanUsers.
		
		var idTemplate = "${form.id}";
			
		TemplateAjaxFacade.recuperaItensJson(idTemplate,{
			
			callback:function(itemJson){
				alert(itemJson);
			},
			//funcao que trata os erros
			errorHandler:function(errorString) {setMsgError(errorString, 'block');},
			timeout:5000
		});	

	}*/

	
	/*Funcao para gravar os itens do template.*/
	/*function gravaItensJson(){
		//Instanciando um objeto e adicionando uma propriedade,
		//esse objeto ao ser passado para a classe java(FacadeAjax) sera convertido para um objeto do_ tipo BeanUsers.
		
		$("#indicatorTemplate").css("display", "inline");		
		
		confirmarDados();
		
		var idTemplate = "${form.id}";
		var json = $('#itemsJson').val();
			
		TemplateAjaxFacade.gravaItensJson(idTemplate, json, {
			
			callback:function(resultado){

				var nodes = eval(resultado);								
				treeLoad($('#treeViewItens'), nodes);				

				
				$("#indicatorTemplate").css("display", "none");
				alert('Template salvo com sucesso.');
				
			},
			//funcao que trata os erros
			errorHandler:function(errorString) {

				alert('Erro ao gravar template ' + errorString);				
				$("#indicatorTemplate").css("display", "none");
			},
			timeout:5000
			});	

	}*/


		

	/*Uplodad dos arquivo.*/
        /*
	function uploadAnexo(){
		//Instanciando um objeto e adicionando uma propriedade,
		//esse objeto ao ser passado para a classe java(FacadeAjax) sera convertido para um objeto do_ tipo BeanUsers.
		
		var node = $('#treeViewItens').tree('getSelected');
		
		var idTemplate = "${form.id}";
		var idItemTemplate = node.id;
		var arquivo = dwr.util.getValue('arquivoAnexo');
		var nomeArquivo = $('#arquivoAnexo').val();

		if (nomeArquivo == ""){
			alert("Selecione o arquivo");
			return;
		}
		
		if ((idItemTemplate == null) || (idItemTemplate == 0)){
			alert("É necessário que o item esteja salvo para fazer o upload.");
			return;
		}

		
		
		$("#indicatorTemplate").css("display", "inline");
		
		
		
		TemplateAjaxFacade.uploadFile(idTemplate, idItemTemplate, nomeArquivo, arquivo, {
			
			callback:function(resultado){

				var nodes = eval(resultado);								
				treeLoad($('#treeViewItens'), nodes);				
	
				
				$("#indicatorTemplate").css("display", "none");			
			},
			//funcao que trata os erros
			errorHandler:function(errorString) {
				//alert('Erro ao realizar upload ' + errorString);
				alert('Erro ao realizar upload. Verifique o tipo do arquivo.');
				
				
				$("#indicatorTemplate").css("display", "none");
			},
			timeout:500000
		});	

	}


	function downloadAnexo(){

		$("#indicatorTemplate").css("display", "inline");

		var node = $('#treeViewItens').tree('getSelected');
		
		var idItemTemplate = node.id;

		
		TemplateAjaxFacade.downloadFile(idItemTemplate, {
			
			callback:function(resultado){

				$("#indicatorTemplate").css("display", "none");
				dwr.engine.openInDownload(resultado);
			
			},
			//funcao que trata os erros
			errorHandler:function(errorString) {
				
				alert('Erro ao realizar download ' + errorString);				
				$("#indicatorTemplate").css("display", "none");
			},
			timeout:500000
		});	
		

	}
	*/

</script>
