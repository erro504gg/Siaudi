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
<!-- TinyMCEEE -->
<script type="text/javascript" src='<?php if($model->id){ echo "../";}?>../js/tiny_mce/jquery.tinymce.js'></script>


<script>

	var tinyTituloCarregado = false;
	var tinyTextoCarregado = false;
	
	//Guarda a referencia ao campo selecionado para a inclusão de atributos, parametros, etc...
	var tinySelecionado = null;

	
	$().ready(function() {


		//Configurações do editor com opções reduzidas
		$('textarea.tinymce_simple').tinymce({
			// Location of TinyMCE script
			script_url : '../js/tiny_mce/tiny_mce.js',
        		language : "pt",

			// General options
			theme : "advanced",
			plugins : "media",
			//plugins : "autolink,lists,pagebreak,style,layer,advhr,advlink,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,advlist",

			// Theme options
                        theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,fontselect,fontsizeselect,|,forecolor,backcolor",
			theme_advanced_toolbar_location : "top",
			theme_advanced_toolbar_align : "left",
			theme_advanced_statusbar_location : "none",
			//theme_advanced_resizing : true,

			theme_advanced_resizing_use_cookie : false,
			theme_advanced_resizing_min_height : 10,
			

			init_instance_callback : function(ist){
				//Força o tamanho do titulo				
				$("#titulo").css("height", "35px");
				$("#titulo_tbl").css("height", "35px");
				$("#titulo_ifr").css("height", "35px");


				tinyTituloCarregado = true;
				//treeSelecionaItem(null);
			},


			setup : function(ed) {
			      ed.onClick.add(function(ed, e) {
			    	  tinySelecionado= ed;
			      });
			      
			      
			   }		

		});


		//Configurações do editor completo			
		$('textarea.tinymce_advanced').tinymce({
			// Location of TinyMCE script
			script_url : '<?php if($model->id){ echo "../";}?>../js/tiny_mce/tiny_mce.js',
	
			language : "pt",
			
	
			// General options
			theme : "advanced",
			plugins : "autolink,lists,pagebreak,style,layer,table,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,advlist,ibrowser",
	
			// Theme options
                         <?php if (!$_GET["consultar"]) { 
                            echo '
			theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,fontselect,fontsizeselect",
			theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,insertdate,inserttime,preview,|,forecolor,backcolor",
			theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,iespell,advhr,|,print", 
			theme_advanced_buttons4 : "cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,pagebreak,|,ibrowser,code",
                           '; } else { echo 'theme_advanced_buttons1 :"",'; } ?>
                        
			theme_advanced_toolbar_location : "top",
			theme_advanced_toolbar_align : "left",
			theme_advanced_statusbar_location : "bottom",
			theme_advanced_resizing : true,
			theme_advanced_path : false,
	
	
			// Drop lists for link/image/media/template dialogs
			template_external_list_url : "lists/template_list.js",
			external_link_list_url : "lists/link_list.js",
			external_image_list_url : "lists/image_list.js",
			media_external_list_url : "lists/media_list.js",
	
			theme_advanced_resizing_use_cookie : false,
			theme_advanced_resizing_min_height : 10,
			width: "400",
			height : "430",
                        

                        theme_advanced_fonts : 
                                 "Arial=arial,helvetica,sans-serif;"+
                                 "Courier New=courier new,courier;"+
                                 "Helvetica=helvetica;"+
                                 "Symbol=symbol;"+
                                 "Times New Roman=times new roman,times;",


			init_instance_callback : function(ist){
				tinyTextoCarregado = true;
				//treeSelecionaItem(null);

				//Força o tamanho do titulo				
				$("#titulo").css("height", "35px");
				$("#titulo_tbl").css("height", "35px");
				$("#titulo_ifr").css("height", "35px");

			},
			

			setup : function(ed) {
			      ed.onClick.add(function(ed, e) {
			    	  tinySelecionado= ed;			          
			      });

			      /*ed.onKeyPress.add(function(ed, e) {
			          //console.debug('Key press event: ' + e.keyCode);
			    	  destacaParametros(ed);
			      });*/

			      /*ed.onKeyUp.add(function(ed, e) {
			    	  destacaParametros(ed);
			      });*/
			      
			   }
				
		});

		
	});


	//Adiciona texto na posição do cursor
	function addParametroAtCursor(texto){

		//var bookMarkTexto = $('#texto').tinymce().selection.getBookmark();		
		//$('#texto').tinymce().selection.moveToBookmark(bookMarkTexto);
		
		if (tinySelecionado != null){
			
			//formatação do texto com background cinza
			texto= " <span style='background-color:#A0A0A0;'>(("+texto+"))</span>&nbsp;"			
			//texto = " (("+texto+")) ";
			
			tinySelecionado.selection.setContent(texto);
                            $("<a class=\"panel-tool-close\" href=\"javascript:void(0)\"></a>").appendTo(tool).bind("click",function(){
                            _18b(_188);
                            return false;
                            });
		}else
			alert("Clique no local do texto onde deseja incluir o parâmetro.");
		
	}


	function destacaParametros(tiny){

		/*tiny.formatter.register('mycustomformat', {
		   inline : 'span',
		   styles : {color : '#c0c0c0'}
		 });


		tiny.formatter.apply('mycustomformat');
		
		var parametros = new Array("ano", "mensagem");
		
		var destaque = new Array('<span style="background-color: #c0c0c0;">&shy;','&shy;</span>');		

		var texto = tiny.getContent();
			
		
		//Remove a marcação
		for (var i in destaque) {
			var regex = new RegExp(destaque[i], 'g');
			texto= texto.replace(regex, "");
		}

		//Adiciona marcação
		for (var i in parametros) {
			var regex = new RegExp("\\(\\(" + parametros[i] + "\\)\\)", "g");
			texto= texto.replace(regex, destaque[0] + "\(\(" + parametros[i] + "\)\)" + destaque[1]);
		}

		
		var bookMarkTexto = tiny.selection.getBookmark(2, true);
		tiny.setContent(texto);

		tiny.selection.moveToBookmark(bookMarkTexto);*/

	}

	
</script>
	



