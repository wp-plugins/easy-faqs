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
	
	//quicklinks
	jQuery(".faq-questions li a").click(function(){
		jQuery("#easy-faq-" + jQuery(this).parent("li").attr("id") + " h3").trigger("click");
	});
}

jQuery(initEasyFAQs);