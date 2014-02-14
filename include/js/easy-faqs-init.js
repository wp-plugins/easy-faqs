var initEasyFAQs = function ()
{
	var options = {
		header: '.easy-faq-title',
		//animate: "bounceslide",
		collapsible: true,
		heightStyle: "content",
	};
	jQuery( ".easy-faqs-accordion" ).accordion(options);
};

jQuery(initEasyFAQs);