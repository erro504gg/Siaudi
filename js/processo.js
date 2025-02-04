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
    $('#div_origem_externa').hide();
    $('#div_origem_interna').hide();

    if($('#DocProc_origem').val() == 0){
        $('#div_origem_interna').show();
        $('#DocProc_origem_externa_fk').val('');
        $('#div_origem_externa').val('').hide();
    }else{
        $('#div_origem_externa').show();
        $('#DocProc_origem_interna_fk').val('');
        $('#div_origem_interna').val('').hide();
    }
    
    $('#DocProc_origem').change(function(){   
        if($(this).val() == 0){
            $('#div_origem_interna').show();
            $('#DocProc_origem_externa_fk').val('');
            $('#div_origem_externa').val('').hide();
        }else{
            $('#div_origem_externa').show();
            $('#DocProc_origem_interna_fk').val('');
            $('#div_origem_interna').val('').hide();
        }
    });

    $('#DocProc_origem').change(function(){
        $('#origem_externa').val('');
        $('#DocProc_origem_externa_fk').val('');
        $('#origem_interna').val('');
        $('#DocProc_origem_interna_fk').val('');
    }),
    $('#DocProc_tipo_interessado1').change(function(){
        if ($(this).val() != ''){
            $('#interessado1').attr('disabled', false);
        } else{
            $('#interessado1').attr('disabled', true);
        }
        //$('#interessado1').val('');
        $('#DocProc_interessado1_fk').val('');
        $('#interessado1').focus();
    }),
    $('#DocProc_tipo_interessado2').change(function(){
        if ($(this).val() != ''){
            $('#interessado2').attr('disabled', false);
        } else{
            $('#interessado2').attr('disabled', true);
        }
        //$('#interessado2').val('');
        $('#DocProc_interessado2_fk').val('');
        $('#interessado2').focus();
    })
});