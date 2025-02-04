(function ($) {

	var opt;
	var obj;
	var sessaoExpirou = false;
	
	$.fn.cronometroSessao = function(options){
		
        var defaults={
                minutos : 0,
                segundos: 0
        };		
		
        if (typeof options == 'string') {

            var args = Array.prototype.slice.call(arguments, 1);
            
            if(options == 'isExpirouSessao'){
            	return(sessaoExpirou);
            }
            if(options == 'iniciarSessao'){
            	iniciarCronometro();
            }            
            return; 
        }
		
	return this.each(function() {
            opt = $.extend(defaults, options);
            obj = $(this);
            sessaoExpirou = false;
            iniciarCronometro();
        });		
	}
	
	function iniciarCronometro(){
                if(opt.segundos > 60){
                    opt.minutos = opt.segundos / 60;
                    opt.segundos = opt.segundos % 60;
                }
 	
		if (opt.segundos<=0){  
     		opt.segundos=60;
     		opt.minutos-=1;
		 } 
		 if (opt.minutos<=-1){ 
			 opt.segundos=0;
			 opt.segundos+=1;
			 obj.children("span").text("");
			 obj.text("Sessão expirada!");
			 sessaoExpirou = true;
		 } else{ 
			 opt.segundos-=1
			if(opt.segundos < 10) {
				opt.segundos = "0" + opt.segundos;
			} 
			 jQuery("#cronometro").text(" " + opt.minutos+"min"+opt.segundos);
		    setTimeout("jQuery('#cronometro_div').cronometroSessao('iniciarSessao'); ",1000); 
		}  
	}		 
	
}(jQuery));	

