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
</script>
