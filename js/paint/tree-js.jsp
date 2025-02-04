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
// cabecalho include file=/taglib-imports.jspf
	var treeCarregado = false;
	
	$().ready(function() {
	
		
		//TreeView
		$('ul.treeview_template').tree({
			checkbox: false,
                        dnd: <?= $_GET['consultar'] == 1? "false" : "true"; ?>,

                        url: "<? echo $baseUrl . "/Paint/CarregaPaintAjax" . "?exercicio=" . $_GET["exercicio"];?>",

			onClick:function(node){
				treeNodeClick(node);
			},
	
			onDblClick:function(node){
				$(this).tree('toggle', node.target); //expandir item
			},
	
			onBeforeSelect:function(node){				
				var nodeAnt= $(this).tree('getSelected');
				
				if (nodeAnt != null)
					return verificaAtualizacao($(this), nodeAnt);
				else
					return true;

			},
	
			onDrop:function(target, source, point){ //Drag and Drop
				ordenaTree();
			},
	
			
			onBeforeLoad:function(node, param){
				$("#indicatorTemplate").css("display", "inline");
			},
	
			
			onLoadSuccess:function(node, data){				
				ordenaTree();

				if (treeCarregado)
					node = getUltimoFilho($(this), node);

				treeSelecionaItem(node);
				treeCarregado = true;				
			}
			
			/*onContextMenu:function(e, node){
				e.preventDefault();
				//$(this).tree('select', node.target);
				$('#menu_contexto').menu('show', {
					left: e.pageX,
					top: e.pageY
				});
			}*/
		});
	
	
		//Popula o tree
                    /*var nodes = ${form.itemsJson};
                    treeLoad($('#treeViewItens'), nodes);*/
				

				
	});


	/*function treeLoad(pTree, nodes){

		//pTree.tree('append', {parent:null, data:nodes});


		pTree.tree('loadData', nodes);


		//alert("load");

	}*/


	function treeNodeClick(node){

		populaTela(node);

	}
	
	
	//Seleciona item do tree
	function treeSelecionaItem(pNode){

		//Seleciona apenas se todos os componentes da tela tiverem sido carregados completamente
		if (treeCarregado && tinyTituloCarregado && tinyTextoCarregado){

			if (pNode == null)
				pNode = $('#treeViewItens').tree('getRoot');
						
			if (pNode != null){
				$('#treeViewItens').tree('select', pNode.target);	
				treeNodeClick(pNode)
			}

			$("#indicatorTemplate").css("display", "none");
		}
	}
	
	

	function treeAppendItemFilho(pTree){
		node = pTree.tree('getSelected')
		treeAddItem(pTree, node, '', '');
	}

	function treeAppendItem(pTree){
		treeAddItem(pTree, null, '', '');
	}


	function treeAddItem(pTree, nodeParent, titulo, texto){


		var nodeAppend = "{'id':null, " +
                                        "'text':titulo," +
                                        "'attributes':{ " +
                                                     "'titulo':titulo," +
                                                     "'texto':texto," +
                                                     "'sequencia':''," +
                                                     "} " +
                                "}";




		//Posição de referencia para a inserção de um novo node
                nodeAppend = eval("([" + nodeAppend + "])");
                pTree.tree('append', {parent:((nodeParent != null) ? nodeParent.target : null), data:nodeAppend});
			
	}



	

	
	function treeRemoveItem(pTree){
		var node = pTree.tree('getSelected');
		if (confirm("Confirma a exlusão do item '" + getNodeNumeracao(node) + ' ' + getNodeTexto(node) + "' e todos os seus sub-itens?")){
			pTree.tree('remove', node.target);
			ordenaTree();
		}
	}


	function treeGetDados(pTree){
		//var root = pTree.tree('getSelected');  
		var data = pTree.tree('getData', null);  

		alert(JSON.stringify(data));
		return data;		
		//alert(data);
	}


	//Retorna o texto do node sem a numeracao
	function getNodeTexto(node){
		var texto = node.text + "";

		if (texto.indexOf("&nbsp;") != -1) 
                    texto = node.text.substring(node.text.indexOf("&nbsp;")+6);
		
		return texto;
	}
	

	//Retorna a numeração do node (se houver) sem o texto
	function getNodeNumeracao(node){

		var numeracao = "";
		
		if (node.text.indexOf("&nbsp;") != -1)
			numeracao= node.text.substring(0, node.text.indexOf("&nbsp;"));

		return numeracao;
	}

	

	function ordenaTree(){
		var pTree = $('#treeViewItens');		
		var nodes = pTree.tree('getRoots');
		
		ordenaNodes(pTree, nodes, null);
	}


	
	function ordenaNodes(pTree, nodes, nodePai){

		var numeracao = 1;
		var numeracaoTexto;
		var sequencia = 1;

               // console.debug(nodes);

		for (var i in nodes) {
                    
                        if (i== "last")
                            continue;

			var nodePaiTemp = null;
			
			//Apenas os primeiro nivel de filhos (netos, bisnetos, etc... ficam de fora pois são carregados recursivamente)
			if (nodePai != null) {
				nodePaiTemp = pTree.tree('getParent', nodes[i].target);
				
				if ((nodePai.id != nodePaiTemp.id) || (nodePai.text != nodePaiTemp.text) || (nodePai.attributes.sequencia != nodePaiTemp.attributes.sequencia))
    					continue;
			}

			
			var textoAtual = getNodeTexto(nodes[i]); 


                        //Tipo de numeração (Natural/Romano)
                        /*if ($.inArray(nodes[i].attributes.tipoItem, ["ANEXO","ANEXO_LOTE","ANEXO_ARMAZEM"]) >= 0)
                                numeracaoTexto = getValorRomano(numeracao);
                        else*/
                                numeracaoTexto = numeracao;


                        if (nodePai == null){				
                                nodes[i].text = numeracaoTexto + "&nbsp;" + textoAtual;
                        }else{
                                nodes[i].text = getNodeNumeracao(nodePaiTemp) + "." + numeracaoTexto + "&nbsp;" + textoAtual;

                        }

                        numeracao++;

                        //console.debug(i);

                        
			nodes[i].attributes.sequencia = sequencia++;
                        
			pTree.tree('update', nodes[i]);

			if (!pTree.tree('isLeaf', nodes[i].target)){				
				var nodesFilhos = pTree.tree('getChildren', nodes[i].target);
				ordenaNodes(pTree, nodesFilhos, nodes[i]) + ","; 
			}

		}

	}

        function envia_paint_json(sair){
            if (sair==1) { $('#paint_sair').val("1"); }
            if (sair==2) { $('#paint_sair').val("2"); }
            
            confirmarDados();
            
            $('#paint').val(getItensJson());
            $('#paint-form').submit();
            $('#paint_sair').val("0");
        }

        function getItensJson(){
            
            var pTree = $('#treeViewItens');

            var dadosJson = getJsonTree(pTree);
            
            //console.log(dadosJson);
            return(dadosJson);
            
        }


	function getJsonTree(pTree) {

		var nodes = pTree.tree('getRoots');

                return getJsonNodes(pTree, nodes, null);
		
	}

	function getJsonNodes(pTree, nodes, nodePai){

		var saida = "[";
		
		for (var i in nodes) {	
                    
                        if (i== "last")
                            continue;

			//Apenas os primeiro nivel de filhos (netos, bisnetos, etc... ficam de fora pois são carregados recursivamente)
			if (nodePai != null) {

				var nodePaiTemp = pTree.tree('getParent', nodes[i].target);
				
				if ((nodePai.id != nodePaiTemp.id) || (nodePai.text != nodePaiTemp.text) || (nodePai.attributes.sequencia != nodePaiTemp.attributes.sequencia))
					continue;
			}

			saida += "{"
                        saida += "'numeracao':'" + getNodeNumeracao(nodes[i]) + "',";
			saida += "'id':" + nodes[i].id + ",";
			saida += "'text':'" + quoteStr(getNodeTexto(nodes[i])) + "',";
			//saida += "'checked':" + nodes[i].checked + ",";

			if (!pTree.tree('isLeaf', nodes[i].target)){
				
				var nodesFilhos = pTree.tree('getChildren', nodes[i].target);

				saida += "'children':" + getJsonNodes(pTree, nodesFilhos, nodes[i]) + ","; 
			}

			var atrib = nodes[i].attributes;

                        //console.log(nodes[i].attributes.titulo);
			
			saida += "'attributes':{'titulo':'" + quoteStr(atrib.titulo) + "'," +
						"'texto':'" + quoteStr(atrib.texto) + "'," +
                                                "'numeracao':'" + getNodeNumeracao(nodes[i]) + "'," + 
						"'sequencia':'" + atrib.sequencia + "'," + "}";

			
			saida += (i == (nodes.length-1)) ? "}" : "},";
		}		
		
		saida += "]";
		
	    return saida;
	}




	//Retorna o ultimo filho (apenas primeiro nivel) do node pai
	function getUltimoFilho(pTree, nodePai){

		var nodes = null;
		
		if (nodePai == null)
			nodes = pTree.tree('getRoots');
		else
			nodes = pTree.tree('getChildren', nodePai.target);
		

		for (var i in nodes) {			

			var nodePaiTemp = null;
			
			//Apenas os primeiro nivel de filhos (netos, bisnetos, etc... ficam de fora pois são carregados recursivamente)
			if (nodePai != null) {
				nodePaiTemp = pTree.tree('getParent', nodes[i].target);
				
				if ((nodePai.id != nodePaiTemp.id) || (nodePai.text != nodePaiTemp.text) || (nodePai.attributes.sequencia != nodePaiTemp.attributes.sequencia))
					continue;
			}
		}

		return nodes[i];
	}




	//Transforma numero natural em romano
	function getValorRomano(numero){
		var N = parseInt(numero);
		var N1 = N;
		var Y = ""
		while (N/1000 >= 1) {Y += "M"; N = N-1000;}
		 if (N/900 >= 1) {Y += "CM"; N=N-900;}
		 if (N/500 >= 1) {Y += "D"; N=N-500;}
		 if (N/400 >= 1) {Y += "CD"; N=N-400;}
		while (N/100 >= 1) {Y += "C"; N = N-100;}
		 if (N/90 >= 1) {Y += "XC"; N=N-90;}
		 if (N/50 >= 1) {Y += "L"; N=N-50;}
		 if (N/40 >= 1) {Y += "XL"; N=N-40;}
		while (N/10 >= 1) {Y += "X"; N = N-10;}
		 if (N/9 >= 1) {Y += "IX"; N=N-9;}
		 if (N/5 >= 1) {Y += "V"; N=N-5;}
		 if (N/4 >= 1) {Y += "IV"; N=N-4;}
		while (N >= 1) {Y += "I"; N = N-1;}
		
		return Y;
	}

	
	
</script>
	



