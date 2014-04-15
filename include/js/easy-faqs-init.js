var initEasyFAQs = function ()
{
	var options = {
		header: '.easy-faq-title',
		//animate: "bounceslide",
		collapsible: true,
		heightStyle: "content",
	};
	jQuery( ".easy-faqs-accordion" ).accordion(options);
	var options = {
		header: '.easy-faq-title',
		active: false,
		collapsible: true,
		heightStyle: "content",
	};
	jQuery( ".easy-faqs-accordion-collapsed" ).accordion(options);
};

jQuery(initEasyFAQs);