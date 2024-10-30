<?php

/**
 * @author Tarchini Maurizio
 * @copyright 2011
 */

require_once 'mtx-secure-wp-load.php';
header('Content-type: text/javascript');
$location = WP_PLUGIN_URL.'/'.str_replace(basename( __FILE__),'',plugin_basename(__FILE__));
?>

jQuery(document).ready(function($){
			
			// modal dialog control
			$('#mtx_contact').dialog({
				modal: true,
				autoOpen: false,
				width: 800,
				height: 800,
				open: function(event, ui) { $(".ui-dialog-titlebar-close").hide(); }

			});	
			
			// modal dialog open control
			$('.mtx_contact_form_open').click(function(){
				$('#mtx_contact').dialog('open');
			})
			
			// modal dialod close control
			$('.mtx_contact_form_close').click(function(){
				$('#mtx_contact').dialog('close');	
			});
			
			// text area max chars control
			var caratteri = 140;
		    $("#mtx_remaining").append("("+  caratteri+" chars)");
		    $("#mtx_message").keyup(function(){
		        if($(this).val().length > caratteri){
		        $(this).val($(this).val().substr(0, caratteri));
		        }
		    	var restanti = caratteri -  $(this).val().length;
		    	$("#mtx_remaining").html("("+  restanti+" chars)");
			    if(restanti <= 10)
			    {
			        $("#mtx_remaining").css("color","red");
			    }
			    else
			    {
			        $("#mtx_remaining").css("color","gray");
			    }
		    });
		    
			// form validate and submit control
			$('form#mtx_contact_form').submit(function(){
				$('#mtx_contact_form label').css('color','#000');
				$('.error_info').css('color', 'red');
				$('.error_info').hide().fadeOut(1000);
				var name = $('#mtx_name').val();
				var email = $('#mtx_email').val();
				var mess = $('#mtx_message').val();
				var expr = /^[_a-z0-9+-]+(\.[_a-z0-9+-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)+$/;
				var error = 0;
				if(name=="")
				{
					$('#mtx_name').prev().css('color','red');
					error=1;
				}
				if(email=="")
				{
					$('#mtx_email').prev().css('color','red');
					error=1;
				}
				if(mess=="")
				{
					$('#mtx_message').prev().css('color','red');
					error=1;
				}
				
				if(error==1)
				{
					$('.error_info').html('All fields are required!').fadeIn(1000);
					return false;
				}
				
				if(!expr.test(email))
				{
					$('#mtx_email').prev().css('color','red');
					error=1;
					$('.error_info').html('Incorrect email address!').fadeIn(1000);
					return false;
					
				}
				
				$('.error_info').css('color', 'black');
				$('.error_info').html('Sending...').fadeIn(1000);
				var formdata = $(this).serialize();
				$.ajax({
					url: '<?php echo $location; ?>form_send.php',
					type: "POST",
            		data: formdata,
					success: function(data){
						if(data==1)
						{
							$('.error_info').css('color', 'green');
							$('.error_info').html('Message sent! Tank You').fadeIn(1000);
							$('#mtx_name, #mtx_email, #mtx_message').val('');
						}
					}	
				});
				
				return false;	
			});
		    
		});