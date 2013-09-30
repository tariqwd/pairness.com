$.pnotify.defaults.styling = "bootstrap";

/*function centerdiv(el)
{
    el.css("position","fixed");
    el.css("top", ($(window).height() / 2) - ((el.outerHeight() / 2)+100));
    el.css("left", ($(window).width() / 2) - (el.outerWidth() / 2));
	return el;
}*/

function centerdiv1(el)
{
    el.css("position","fixed");
    el.css("top", "50%");
    el.css("left","50%");
	el.css("margin-top", "-110px");
    el.css("margin-left","-215px");
	el.css(" width", "600px");
    el.css(" height","400px");
	return el;
}

function centerdiv2(el)
{
    el.css("position","fixed");
    el.css("top", "50%");
    el.css("left","50%");
	el.css("margin-top", "-0px");
    el.css("margin-left","-230px");
	el.css(" width", "600px");
    el.css(" height","400px");
	return el;
}

function centerdiv(el)
{
    el.css("position","fixed");
    el.css("top", "50%");
    el.css("left","50%");
	el.css("margin-top", "-200px");
    el.css("margin-left","-350px");
	el.css(" width", "600px");
    el.css(" height","400px");
	return el;
}

function changegender(ep){

	window.location = "search.php?action=explore&gender="+ep;
}

$(document).ready(function(){
	centerdiv( $('.not-wrapper') );
});

$(document).ready(function(){
	centerdiv1( $('.not-wrapper') );
});

$(document).ready(function(){
	centerdiv2( $('.not-wrapper') );
});

$(document.documentElement).keyup(function (event) {
	if (event.keyCode == 27) {
		$(".not-wrapper").css("display","none");	
	} 
});

$("body").on("click", ".close-wrapper", function (e) {
	$(".not-wrapper").css("display","none");
});

$( ".login-mini" ).click(function(e) {
	
	centerdiv1( $('.not-wrapper') );
	$(".not-wrapper").css("display","none");
	$(".not-wrapper").css("display","block");
	$('.not-wrapper').load('./assets/template/sub/login.html');	
	centerdiv1( $('.not-wrapper') );
	$(document).attr('title', 'Login');
	$('#login-form input').focus();

});

$( ".message-mini" ).click(function(e) {
	centerdiv1( $('.not-wrapper') );
	$(".not-wrapper").css("display","none");	
	$(".not-wrapper").css("display","block");
	var recordid = $(this).attr("id");
	//alert(recordid);
	$('.not-wrapper').load('./message.php?recordid='+recordid);	
	centerdiv1( $('.not-wrapper') );
	$(document).attr('title', 'Message Detail');
});


$(".image-submit").click(function(e){
	e.preventDefault();
	if($(".op1").val()!='' && $(".op1").val()!=''){
		$("#main-form").submit();
	}else{
		$.pnotify({
					styling: 'bootstrap',		
					type: 'error',
					title: 'Failed!',
					text: 'Fields cant be empty!'
					});
	}
		
});
	
$( "#register-mini" ).click(function(e) {
	$(".not-wrapper").css("display","none");	
	$(".not-wrapper").css("display","block");
	e.preventDefault();
	$('.not-wrapper').load('./assets/template/register.coz');
	centerdiv2( $('.not-wrapper') );	
	location.hash = 'register';
	$(document).attr('title', 'Register');

});

	
$("body").on("click", ".user-mini", function (e) {
	$(".user-opt").css("display","block");
});

$(".user-opt").on('mouseleave',function(){
        $(".user-opt").css("display","none");
});


$("body").on("click", ".button-purge", function (e) {
	e.preventDefault();
	$.ajax({
		type: "POST",
		url: "./ajax/do-purge.php",
        data: 'y=1',
        dataType: "json"
        }) .always(function(data) { 
				if(data.s===1){
					$.pnotify({
					type: 'success',
					styling: 'bootstrap',
					title: 'Success',
					width: "50%",
					text: data.v
					}); 					
				}else{
					$.pnotify({
					styling: 'bootstrap',		
					type: 'error',
					title: 'Failed!',
					text: data.v
					});
			
				}
		});
});	
	
$("body").on("click", ".register-submit", function (e) {
	e.preventDefault();
	var datastring = $("#register-form").serialize();
	$.ajax({
		type: "POST",
		url: "./ajax/do-register.php",
        data: datastring,
        dataType: "json"
        }) .always(function(data) { 
				if(data.s==1){
					$.pnotify({
					type: 'success',
					styling: 'bootstrap',
					title: 'Success',
					hide: true,
					width: "31%",
					text: data.v
					});
				centerdiv( $('.not-wrapper').css("display","none") ); 
				$("input[type=image]").attr("disabled", "disabled");					
				}else{
					$.pnotify({
					styling: 'bootstrap',		
					type: 'error',
					title: 'Failed!',
					text: data.e
					});
			
				}
		});
});	
	

$("body").on("click", ".login-submit", function (e) {
	e.preventDefault();
	var datastring = $("#login-form").serialize();
	datastring= datastring + "&return="+ ret;
	$.ajax({
		type: "POST",
		url: "./ajax/do-login.php",
        data: datastring,
        dataType: "json"
        }) .always(function(data) { 
				if(data.s==1){
					$.pnotify({
					type: 'success',
					styling: 'bootstrap',
					title: 'Success',
					text: 'You have successfully logged in..'
					});  
					if (data.r!=''){
						window.location = baseurl+data.r+".php";
					}
					location.hash = 'home';
					$(document).attr('title', 'Home');
					$('.content').load('./assets/template/sub/home.html');
					$(".box-layout").css("display","none");	
					$(".tp-nav").css("display","none");	
					$(".loader").css("display","block");
					$(".layout").css("opacity","0.4");	
					window.setTimeout(function(){ window.location = 'home.php'; }, 3000);
				}else{
					$.pnotify({
					styling: 'bootstrap',		
					type: 'error',
					title: 'Failed!',
					text: data.e
					});
			
				}
		});
 
});

$("body").on("click", ".message-submit", function (e) {
	e.preventDefault();
	var datastring = $("#message-form").serialize();
	datastring= datastring + "&return="+ ret;
	$.ajax({
		type: "POST",
		url: "./ajax/message.php",
        data: datastring,
        dataType: "json"
        }) .always(function(data) { 
				if(data.s==1){
					$.pnotify({
					type: 'success',
					styling: 'bootstrap',
					title: 'Success',
					text: 'You have successfully logged in..'
					});  
				
					if (data.r!=''){
						window.location = baseurl+data.r+".php";
					}
					location.hash = 'home';
					$(document).attr('title', 'Home');
					$('.content').load('./assets/template/sub/home.html');
					$(".box-layout").css("display","none");	
					$(".tp-nav").css("display","none");	
					$(".loader").css("display","block");
					$(".layout").css("opacity","0.4");	
					window.setTimeout(function(){ window.location = 'home.php'; }, 3000);
				}else{
					$.pnotify({
					styling: 'bootstrap',		
					type: 'error',
					title: 'Failed!',
					text: data.e
					});
			
				}
		});
 
});

$("body").on("click", ".email-submit", function (e) {
	e.preventDefault();
	
	var datastring = $("#email-form").serialize();
	$.ajax({
		type: "POST",
		url: "./ajax/do-email.php",
        data: datastring,
        dataType: "json"
        }) .always(function(data) { 
				if(data.s==1){
					$.pnotify({
					type: 'success',
					styling: 'bootstrap',
					title: 'Success',
					hide: true,
					width: "31%",
					text: data.v
					});	
				centerdiv( $('.not-wrapper').css("display","none") );
				$("input[type=image]").attr("disabled", "disabled");					
				}else{
					$.pnotify({
					styling: 'bootstrap',		
					type: 'error',
					title: 'Failed!',
					text: data.e
					});
					
				}
		});
});	


$("body").on("click", ".s-register", function (e)  {
	$(".not-wrapper").css("display","none");	
	$(".not-wrapper").css("display","block");
	e.preventDefault();
	location.hash = 'self-registrations';
	$('.not-wrapper').load('./assets/template/sub/s-register.html');
	centerdiv( $('.not-wrapper') );
	$(document).attr('title', 'Regsiter Now!');
});

$("body").on("click", ".g-register", function (e) {
	$(".not-wrapper").css("display","none");	
	$(".not-wrapper").css("display","block");
	e.preventDefault();
	location.hash = 'family-registrations';
	$('.not-wrapper').load('./assets/template/sub/g-register.html');
	centerdiv( $('.not-wrapper') );
	$(document).attr('title', 'Regsiter Now!');
});

$("body").on("click", ".email", function (e) {
	$(".not-wrapper").css("display","none");	
	$(".not-wrapper").css("display","block");
	e.preventDefault();
	location.hash = 'send-email';
	//var tltrcd = document.getElementsByName('forid').length;
	var recordid = $(this).attr("id");
	var userid = document.getElementsByName('forid');
	var uid= userid[recordid];
	var id = uid.getAttribute('value'); 
	//alert(id);
	$('.not-wrapper').load('./email.php?id='+id);
	centerdiv( $('.not-wrapper') );
	$(document).attr('title', 'Send Email!');
});




$(function () {
    'use strict';
    $.ajax({
        url: 'content/countries.txt',
        dataType: 'json'
    }).done(function (source) {

        var countriesArray = $.map(source, function (value, key) { return { value: value, data: key }; }),
            countries = $.map(source, function (value) { return value; });

        $('#autocomplete-ajax').autocomplete({
            lookup: countriesArray,
            lookupFilter: function(suggestion, originalQuery, queryLowerCase) {
                var re = new RegExp('\\b' + $.Autocomplete.utils.escapeRegExChars(queryLowerCase), 'gi');
                return re.test(suggestion.value);
            },
            onSelect: function(suggestion) {
                $('#selction-ajax').html('You selected: ' + suggestion.value + ', ' + suggestion.data);
            },
            onHint: function (hint) {
                $('#autocomplete-ajax-x').val(hint);
            },
            onInvalidateSelection: function() {
                $('#selction-ajax').html('You selected: none');
            }
        });

        $('#autocomplete').autocomplete({
            lookup: countriesArray,
            onSelect: function (suggestion) {
                $('#selection').html('You selected: ' + suggestion.value + ', ' + suggestion.data);
            }
        });
        
        $('#autocomplete-custom-append').autocomplete({
            lookup: countriesArray,
            appendTo: '#suggestions-container'
        });

        $('#autocomplete-dynamic').autocomplete({
            lookup: countriesArray
        });
        
    });

});
