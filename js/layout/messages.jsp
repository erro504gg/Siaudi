<%@ include file="/taglib-imports.jspf" %>
<div id="mensagens">

    <logic:messagesPresent message="false">
        <logic:messagesNotPresent message="true" property="org.andromda.bpm4struts.errormessages">
            <div class="erro">
                <html:messages id="error" message="false">
                    <p>${error}</p>
                </html:messages>
            </div>
        </logic:messagesNotPresent>
    </logic:messagesPresent>
    <logic:messagesPresent message="true" property="org.andromda.bpm4struts.errormessages">
        <div class="erro">
            <html:messages id="error" message="true" property="org.andromda.bpm4struts.errormessages">
                <p>${error}</p>
            </html:messages>
        </div>
    </logic:messagesPresent>
    <logic:messagesPresent message="true" property="org.andromda.bpm4struts.warningmessages">
        <div class="alerta">
            <html:messages id="warning" message="true" property="org.andromda.bpm4struts.warningmessages">
                <p>${warning}</p>
            </html:messages>
        </div>
    </logic:messagesPresent>
    <logic:messagesPresent message="true" property="org.andromda.bpm4struts.successmessages">
        <div class="sucesso">
            <html:messages id="message" message="true" property="org.andromda.bpm4struts.successmessages">
                <p>${message}</p>
            </html:messages>
        </div>
    </logic:messagesPresent>
</div>