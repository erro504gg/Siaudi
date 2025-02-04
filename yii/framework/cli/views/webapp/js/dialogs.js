$(document).ready(function(){
    });

function carregaView(url) {

    var arrayUrl=url.split('/');
    var id = arrayUrl[arrayUrl.length-1];
 
    $.get('view/'+id, function(data) {
        $('#visualizar_reg').html(data);
        $('#dialog_visualizar').dialog('open');
    });
}; 

function carregarLoteGuia(url) {

    var arrayUrl=url.split('/');
    var id = arrayUrl[arrayUrl.length-1];
    $.get('view/'+id, function(data) {
        $('#visualizar_lote_guia').html(data);
        $('#dialog_lote_guia').dialog('open');
    });
}; 

function carregarDialogDelete(url, grid_name) {
    $('.sucesso').hide();
    $('.erro').hide();
    var arrayUrl=url.split('/');
    var id = arrayUrl[arrayUrl.length-1];
    $.get('viewAjax/'+id, function(data) {
        $('#visualizar_dialog_delete').html(data);
        $('#dialog_delete').dialog('open');
        $("#id_guias").val(id)
        $("#ctrl_confirmar").val(url)
        $("#id_grid_confirmar").val(grid_name.parents('.grid-view').attr('id'))
    });
};

function carregarDialogGlosa(url, grid_name) {
    $('.sucesso').hide();
    $('.erro').hide();
    var arrayUrl=url.split('/');
    var id = arrayUrl[arrayUrl.length-1];
    var arrayAction=url.split('?');
    var url_action = arrayAction[0];
    $.get(url, function(data) {
        $('#visualizar_dialog_glosa').html(data);
        $('#dialog_glosa').dialog('open');
        $("#id_registro").val(id)
        $("#ctrl_confirmar").val(url_action)
        $("#id_grid_confirmar").val(grid_name.parents('.grid-view').attr('id'))
    });
};
 
function viewDelete(url, grid_name) {

    var arrayUrl=url.split('/');
    var id = arrayUrl[arrayUrl.length-1];
    
    $('.ui-button:contains("Excluir")')
        .css("border", "1px solid #CD0A0A")
        .css('background','#FEF1EC').children().css("color", "#CD0A0A")
    
    $.get('view/'+id, function(data) {
        $('#excluir_reg').html(data);
        $('#dialog_excluir').dialog('open');
        $("#id_excluir").val(id)
        $("#ctrl_excluir").val(url)
        $("#id_grid_excluir").val(grid_name.parents('.grid-view').attr('id'))
    });
}; 
