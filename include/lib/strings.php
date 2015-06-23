<?php
	//notification email strings
	define("NEW_FAQ_SUBMISSION_SUBJECT", __("New Easy FAQ Submission on ", $this->textdomain));
	define("NEW_FAQ_SUBMISSION_BODY", __("You have received a new submission with Easy FAQs on your site, ", $this->textdomain) . get_bloginfo('name') . ".  " . __("Login and see what they had to say!", $this->textdomain));
	
	//faq submission form
	define("FAQ_FORM_ERROR_NAME", __("Please enter your name.", $this->textdomain));
	define("FAQ_FORM_ERROR_QUESTION", __("Please enter a question.", $this->textdomain));
	define("FAQ_FORM_ERROR_CAPTCHA", __("Captcha did not match.", $this->textdomain));
	define("FAQ_FORM_NAME", __("Your Name", $this->textdomain));
	define("FAQ_FORM_NAME_DESCRIPTION", __("Please let us know your name.", $this->textdomain));
	define("FAQ_FORM_QUESTION", __("Question", $this->textdomain));
	define("FAQ_FORM_QUESTION_DESCRIPTION", __("This is the question that you are asking.", $this->textdomain));
	define("FAQ_SUBMIT_QUESTION_BUTTON", __("Submit Your Question", $this->textdomain));
	
	//plugin list links
	define("FAQ_SUPPORT_TEXT", __("Get Support", $this->textdomain));
	define("FAQ_UPGRADE_TEXT", __("Upgrade to Pro", $this->textdomain));
	define("FAQ_SETTINGS_TEXT", __("Settings", $this->textdomain));
	
	//quick links
	define("FAQ_QUICK_LINKS_LABEL", __("Quick Links", $this->textdomain));