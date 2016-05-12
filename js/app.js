$(document).ready(function() { 
    $('.processing').hide();
    $('form').show();
    $('form').submit(function(e) { 
        $(this).fadeOut(1000);
    	$('.processing').fadeIn(); 
    	//alert('Please Keep Your Browser Open!');
    });
});


            function scrolldown(){ 
                $(window).scrollTop($(document).height());
            }
