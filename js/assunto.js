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
    $(document).ready(function(){
    
    if($('#codigo_paijunio').val() == 0){
        $('#div_cod_pai').hide();
        $('#Assunto_assunto_pai').val('');
    }else{
        $('#div_cod_pai').show();
        $('#Assunto_vinculo_1').attr("checked",'checked');
    }
   
    $('#codigo_pai').live('blur',function(){
        $.getJSON('getAjaxAssunto', {
            id: $(this).val()
        },function(data) {
            if(data.id){
                $('#assunto_pai').val(data.assunto);
                $('#id_pai').val(data.id);
            }else{
                $('#assunto_pai').val(data.assunto);
            }
           
        });
    });

    $('.class_vinculo').live('change',function(){
        $('#form_cod_pai').toggle();
        $('#form_default').toggle();
    })
});