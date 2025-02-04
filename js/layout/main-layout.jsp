<%@ include file="/taglib-imports.jspf" %>

<html:html lang="true">

    <head>
        <title>
        	<bean:message key="siscoe.sigla"/> - <bean:message key="siscoe.titulo"/>
        </title>
        
        <!-- Define encoding -->
        <meta http-equiv="Content-Type" content="text/html; charset:ISO-8859-1" />

	<!-- Folhas CSS -->	
        <link rel="stylesheet" type="text/css" media="screen" href="<html:rewrite page="/styles/estiloGeral.css"/>"></link>
        <link rel="stylesheet" type="text/css" media="screen" href="<html:rewrite page="/styles/estiloEspecifico.css"/>"></link>
        <link rel="stylesheet" type="text/css" media="all" href="<html:rewrite page="/layout/default-calendar.css"/>"/>
		<!--[if IE]>
			<link rel="stylesheet" type="text/css" media="screen" href="<%=request.getContextPath()%>/styles/estiloIE.css" />
			<style>
	           * html body {behavior:url("<%=request.getContextPath()%>/styles/csshover.htc");}
	       </style> 			
		<![endif]-->        
        <!-- Scripts do menu -->
	<script language="javascript" src="<html:rewrite page="/scripts/lib_scripts.js"/>"></script>
	<script language="javascript" src="<html:rewrite page="/scripts/menu.js"/>"></script>
        
        <script type="text/javascript">
			var isMostrarAlertaFinalizacaoSessao = false;
			var mostrouAlertaFinalizacaoSessao = false;
		
			function exibirCampo(campo) {
			  campo.style.visibility = 'visible'
			  campo.style.display = ''
			}

			function esconderCampo(campo) {
			  campo.style.visibility = 'hidden'
			  campo.style.display = 'none'
			}

		</script>	
        
        <!-- Scripts default do andromda ... TODO ver se sao necessarios-->
        <script type="text/javascript" language="Javascript1.1" src="<html:rewrite page="/layout/layout-common.js"/>"></script>
        <script type="text/javascript" language="Javascript1.1" src="<html:rewrite page="/layout/key-events.js"/>"></script>
        
        <script language="javascript"> 
        	function iniciaOnLoad() {  } 
        </script> 

        <tiles:insert attribute="style" flush="true"/>
        <tiles:insert attribute="javascript" flush="true"/>
    </head>

    <body onload="iniciaOnLoad()">
	<div id="geral">
		<!-- Cabecalho do sistema -->
		<tiles:insert attribute="cabecalho" flush="true"/>			
		
		<!-- Menu Principal -->
		<tiles:insert attribute="menu" flush="true"/>
		
		<!-- Conteudo -->
		<div id="wrapperConteudo" onmouseover="hideNiv2()">
			<div id="conteudo">

				<div id="pageTitle">
					<h1><tiles:insert attribute="title" flush="true"/></h1>
					<h2><tiles:insert attribute="subtitle" flush="true"/></h2>					
					<div id="menuContexto">
						<tiles:insert attribute="contexto" flush="true"/>
					</div>										
				</div>
				
				<tiles:insert attribute="messages" flush="true"/>				
				<tiles:insert attribute="body" flush="true"/>							

			</div>
		</div>
		<!-- fim conteudo -->
		
	</div>
	<!-- fim div geral-->
	
	<script language="javascript" >
			var campo = document.getElementById("cronometro");
			var campo_div = document.getElementById("cronometro_div");
			var hoje = new Date();
			var futuro = new Date(hoje);
			futuro.setMinutes(hoje.getMinutes() + 15);
			
			function startCountdown()
			{
				hoje = new Date();
				var ss = parseInt((futuro - hoje) / 1000);  
				var mm = parseInt(ss / 60);  
				ss = ss - (mm * 60);  
				var faltam = '';  
				faltam += (toString(mm).length) ? mm+' min e ' : '';  
				faltam += ss+' seg';   
				
				if (mm+ss > 0) 
				{
					 campo.innerHTML = faltam;       
					 setTimeout("startCountdown()",500);  
					 if(mm == 2 && ss == 0){
					 	if(isMostrarAlertaFinalizacaoSessao && !mostrouAlertaFinalizacaoSessao){
							mostrouAlertaFinalizacaoSessao = true;
							var campoMensagemSessao = document.getElementById('campoMensagemSessao');
							if(campoMensagemSessao != undefined){
								scroll(0,0);
								exibirCampo(campoMensagemSessao);
							}
						}
					}
				} else {
					campo.innerHTML="";
					campo_div.innerHTML="Sessão expirada!"; 
					var campoMensagemSessao = document.getElementById('campoMensagemSessao');
					if(campoMensagemSessao != undefined){
						esconderCampo(campoMensagemSessao);
					}
				}
			}
			
			startCountdown();
		</script>
</body>       


</html:html>