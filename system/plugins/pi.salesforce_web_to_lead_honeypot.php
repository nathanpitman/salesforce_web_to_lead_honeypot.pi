<?php

$plugin_info = array(
	'pi_name'			=> 'SalesForce.com Web to Lead Honeypot',
	'pi_version'		=> '0.1',
	'pi_author'			=> 'Nathan Pitman',
	'pi_author_url'		=> 'http://ninefour.co.uk/labs/',
	'pi_description'	=> 'Adds a basic honeypot test to an embedded web to lead form',
	'pi_usage'			=> salesforce_web_to_lead_honeypot::usage()
);


class salesforce_web_to_lead_honeypot {

	var $return_data;
	var $action_url = "https://www.salesforce.com/servlet/servlet.WebToLead?encoding=UTF-8";

	function salesforce_web_to_lead_honeypot()
	{
		
		global $FNS, $TMPL;
		$honeypot_field_name = $TMPL->fetch_param('field_name');
		
		if (isset($honeypot_field_name)) {

			if ($_POST) {
		
				$human = 0;
					
				if (empty($_POST[$honeypot_field_name])) {
					$human = 1;
				}
				
				if ($human){
	
					$fields = array();
					
					foreach($_POST AS $key=>$value) {
						$fields[$key] = $value;
					}
					
					// Credit where credit is due:
					// http://infoandideas.blogspot.co.uk/2011/07/adding-captcha-with-web-to-lead-forms.html
	
					//url-ify the data for the POST
					foreach($fields as $key=>$value) {
						$fields_string .= $key.'='.$value.'&';
					}
					rtrim($fields_string,'&');
	
					//open connection
					$ch = curl_init();
					//set the url, number of POST vars, POST data
					curl_setopt($ch,CURLOPT_URL,$this->action_url);
					curl_setopt($ch,CURLOPT_POST,count($fields));
					curl_setopt($ch,CURLOPT_POSTFIELDS,$fields_string);
					//execute post
					$result = curl_exec($ch);
					//close connection
					curl_close($ch);
					
				}
			
			}
			
		} else {
			$this->return_data = "No field_name parameter specified";
		}
		
	}

	function usage()
	{
	ob_start(); 
	?>
	The SalesForce.com Web To Lead Honeypot plug-in catches a web to lead style form submission, validates the honeypot field and then pushes the data on to Salesforce assuming the honeypot is empty. If validation of the honeypot fails then the form will simply submit back to itself with no visible error.
	
Implementation is simple, add the following tag to the head of the page where your web to lead form resides:

	{exp:salesforce_web_to_lead_honeypot field_name="honeypot"}

Now remove the 'action' parameter from your web to lead form and add a new text field (you should hide this with CSS), i.e.
	
	<form method="post">
	<input type="text" name="honeypot" value="">…

Parameters:
-------------------------------------
	
	field_name = You must specify a custom honeypot field name, we advise using something that appears to be a legitimate field, i.e. 'confirm_username'.

CHANGE LOG
0.1 - Initial release

	<?php
	$buffer = ob_get_contents();
		
	ob_end_clean(); 
	
	return $buffer;
	}


} // END CLASS