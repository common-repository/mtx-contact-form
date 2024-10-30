<?php

/**
 * @author Tarchini Maurizio
 * @copyright 2011
 */

require_once 'mtx-secure-wp-load.php';

$email = strip_tags($_POST['email']);
$name = strip_tags($_POST['name']);
$message = strip_tags($_POST['message']);

$content = "Name: $name \r\n";
$content .= "email: $email \r\n";
$content .= "Message: $message";

mail(get_option('yacf_email_to'), 'New message from ' . get_bloginfo('blogname') . ' contact form', $content, 'From: Administrator<' . get_option('yacf_email_to') . '>');

if(get_option('yacf_send_confirm') == 'yes')
{
	$to = strip_tags($_POST['email']);
	$subject = str_replace('[blogname]', get_bloginfo('blogname'), get_option('yacf_confirm_subject'));
	$subject = str_replace('[name]', $name, $subject);
	$message = str_replace('[blogname]', get_bloginfo('blogname'), get_option('yacf_confirm_message'));
	$message = str_replace('[name]', $name, $message);
	
	mail($to,$subject,$message,'From: noreply');
}

echo 1;

?>