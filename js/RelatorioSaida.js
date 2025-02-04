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
    // ************************************************
    // Função para desabilitar o botão direito do mouse
    // ************************************************
    var message="";

    function clickIE() {
            if (document.all) {
                    (message);
                    return false;
            }
    }

    function clickNS(e) {
            if (document.layers||(document.getElementById&&!document.all)) {
                    if (e.which==2||e.which==3) {
                            (message);
                            return false;
                    }
            }
    }

    if (document.layers) {
            document.captureEvents(Event.MOUSEDOWN);
            document.onmousedown=clickNS;
    }else{
            document.onmouseup=clickNS;document.oncontextmenu=clickIE;
    }

    document.oncontextmenu=new Function("return false");

    //FUNÇÃO PARA BLOQUEAR A TECLA CTRL DO TECLADO
    function BloquearTecla(e){
            if (document.all) // Internet Explorer
                    var tecla = event.keyCode;
            else if (document.layers) // Nestcape
                    var tecla = e.which;
            else if (document.getElementById) //FireFox
                    var tecla = e.which;

            if (e.ctrlKey){ 
              return false;
            }
    }    