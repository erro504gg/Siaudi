
$(document).ready(function(){
    //nivel 1
    $('#menuNiv1 ul li').click(function() {
        $('#menuNiv2 div').each(function() {
            $(this).hide();
        });
        $('#menuNiv3 div').each(function() {
            $(this).hide();
        });

        var id_li = $(this).attr("id");
        niv2 = $('#menuNiv2 #'+id_li);
        niv2.fadeToggle('fast');
    });

    $('#cabecalho, #conteudo').mouseover(function(){
        $('#menuNiv3 div, #correcaoMenuIE').each(function() {
            $(this).hide();
        });
    });

    //nivel 1
    $('#menuNiv2 ul li').click(function() {
        $('#menuNiv3 div, #correcaoMenuIE').each(function() {
            $(this).hide();
        });

        $("#correcaoMenuIE").show();
        var id_li = $(this).attr("id");
        niv3 = $('#menuNiv3 #'+id_li);
        niv3.fadeToggle('slow');
    });
});
