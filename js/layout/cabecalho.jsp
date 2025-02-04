<%@ include file="/taglib-imports.jspf" %>

<!-- Cabecalho do sistema -->
		<div id="cabecalho" onmouseover="hideNiv2()">
			<div id="marca"><img src="<%=request.getContextPath() %>/images/logo.jpg"></div>
			
			<div id="wrapperMenuAuxiliar">
				<div id="acessoSistemas">
					<img id="imgE" src="<%=request.getContextPath() %>/images/c_e_sistemas.jpg" />
					<label>Outros Sistemas:</label>
					<conab:sistema />
					<img id="imgD" src="<%=request.getContextPath() %>/images/c_d_sistemas.jpg" />					
				</div>
				<div id="sair">
					<span id="cronometro_div">Sua sessão expira em: <strong><span id="cronometro" style="color:#FFFFFF;" ></span></strong></span>
					<span><%=request.getRemoteUser()%></span>
					<a href="<conab:urlJosso/>/josso/signon/logout.do?josso_back_to=<conab:urlJosso comAcessoUnico="true" />">[Sair]</a>
				</div>				
				<div id="menuAuxiliar">
					<ul>
						<li>
							<html:link action="/contato"><bean:message key="siscoe.tela.inicial.contato.legenda" /></html:link>
						</li>
						<li>
							<html:link action="/mapa"><bean:message key="siscoe.tela.inicial.mapa.legenda" /></html:link>
						</li>
						<li>
							<html:link action="/manual"><bean:message key="siscoe.tela.inicial.manual.legenda" /></html:link>
						</li>
					</ul>					
				</div>
			</div>			
		
			<div id="tituloSistema"><img src="<%=request.getContextPath() %>/images/logo_sistema.jpg" /></div>
		</div>
		<!-- Fim cabecalho -->