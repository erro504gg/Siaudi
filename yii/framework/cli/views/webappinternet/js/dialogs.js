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
