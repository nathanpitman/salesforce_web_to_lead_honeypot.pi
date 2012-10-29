The SalesForce.com web to lead honeypot plug-in catches a web to lead style form submission, validates the honeypot field and then pushes the data on to Salesforce assuming the honeypot is empty. If validation of the honeypot fails then the form will simply submit back to itself with no visible error.
	
Implementation is simple, add the following tag to the head of the page where your web to lead form resides:

	<pre><code>{exp:salesforce_web_to_lead_honeypot field_name="honeypot"}</pre></code>

Now remove the 'action' parameter from your web to lead form and add a new text field (you should hide this with CSS), i.e.
	
	<pre><code><form method="post">
	<input type="text" name="honeypot" value=""></pre></code>

Parameters:
-------------------------------------
	
field_name = You must specify a custom honeypot field name, we advise using something that appears to be a legitimate field, i.e. 'confirm_username'.