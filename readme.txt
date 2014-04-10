=== Easy FAQs ===
Contributors: richardgabriel, ghuger
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=V7HR8DP4EJSYN
Tags: faqs, faq widget, faq list, faq submission, frequently asked questions, knowledgebase
Requires at least: 3.0.1
Tested up to: 3.8.2
Stable tag: 1.2.2.2
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Easy FAQs is a simple-to-use plugin for adding FAQs (Frequently Asked Questions) to your WordPress Theme, using a shortcode or a widget.

== Description ==

Easy FAQs is an easy-to-use plugin that allows users to add FAQs (Frequently Asked Questions) to the sidebar, as a widget, or to embed them into a Page or Post using the shortcode.  The Easy FAQs plugin also allows you to insert a list of all FAQs or output a Single FAQ. Easy FAQs allows you to include an image with each FAQ - this is a great feature for adding a photo of the FAQ author or other related imagery.

= Easy FAQs is a great plugin for: =
* Adding an FAQ to Your Sidebar
* Adding an FAQ to Your Page
* Outputting a List of FAQs
* Displaying an Image with a FAQ
* Custom Options Allow You to Link Your FAQs to a Custom Page, Such As Linking to your FAQs Page from a Single FAQ
* Its easy to use interface allows you to manage, edit, create, and delete FAQs with no new knowledge

Easy FAQs includes options to set the URL of the Read More Link, whether or not to display the FAQ Image, and more!  You can set the URL of the FAQs read more links for many purposes - such as directing visitors to the product info page that the faq is about.  Showing an Image next to a FAQ is a great tool!

The Easy FAQs plugin is the easiest way to start adding your customer's FAQs, right now!  Click the Download button now to get started.  The Easy FAQs plugin will inherit the styling from your Theme - just install and get to work adding your faqs!

== Installation ==

This section describes how to install the plugin and get it working.

1. Upload the contents of `/easy-faqs/` to the `/wp-content/plugins/` directory
2. Activate Easy FAQs through the 'Plugins' menu in WordPress
3. Visit this address for information on how to configure the plugin: http://goldplugins.com/documentation/easy-faqs-documentation/
= Adding a New FAQ =

Adding a New FAQ is easy!  There are 3 ways to start adding a new faq

**How to Add a New FAQ**

1. Click on "+ New" -> FAQ, from the Admin Bar _or_
2. Click on "Add New FAQ" from the Menu Bar in the WordPress Admin _or_
3. Click on "Add New FAQ" from the top of the list of FAQs, if you're viewing them all.

**New FAQ Content**

You have a few things to pay attention to:

* **FAQ Title:** this content will be displayed above your FAQ - typically this is the Question that is being Answered.
* **FAQ Body:** this is the content of your FAQ.  This will be output and displayed below the FAQ Title.
* **Featured Image:** This image is shown to the left of the FAQ's title, as a 50px by 50px thumbnail.
* **FAQ Category:** This is the Category that the FAQ belongs to, if desired.  You can use this to output FAQs from specific categories only, with the shortcode.

= Editing a FAQ =

 **This is as easy as adding a New FAQ!**

1. Click on "FAQs" in the Admin Menu.
2. Hover over the FAQ you want to Edit and click "Edit".
3. Change the fields to the desired content and click "Update".

= Deleting a FAQ =

 **This is as easy as adding a New FAQ!**

1. Click on "FAQs" in the Admin Menu.
2. Hover over the FAQ you want to Delete and click "Delete".
  
  **You can also change the Status of a FAQ, if you want to keep it on file.**

= Outputting FAQs =
* To output a Single FAQ, place the shortcode [single_faq id="1"] in the desired area of the Page or Post Content.  If you view the List of FAQs, you can find the FAQ ID in the Table.
* To output a list of All FAQs, place the shortcode [faqs] in the desired area of the Page or Post Content.  To display more than one faq, use the shortcode [faqs count='3'], where count is the number of faqs you want displayed.  To display FAQs from a Category, use the shortcode [faqs category='your_slug'].  To control the Order of the FAQs, use the attribute [faqs order='ASC'].  To control the Order By parameter of the FAQs, use the attribute [faqs orderby='title'].  You can find more details here: http://goldplugins.com/documentation/easy-faqs-documentation/
* To display the Featured Image along with FAQs, use the attribute show_thumbs='1'.  This applies to both the single and list shortcodes.
* To control the wording of the Read More Link, use the attribute read_more_link_text='Your Text Here'.  This applies to both the single and list shortcodes.
* To control the destination of the Read More Link, use the attribute read_more_link='http://www.yahoo.com'.  This applies to both the single and list shortcodes.  **NOTE:** be sure you include http:// in your link.
* To output a FAQ in the Sidebar, use the Widgets section of your WordPress Theme, Accessible on the Appearance Menu in the WordPress Admin.  Use the Drop Down menu to select which FAQ is displayed.
* To output a Accordion Style FAQ List, use the shortcode [faqs style=accordion].  The same attributes, such as count and category, apply from above.  **NOTE:** This feature requires the Pro version of Easy FAQs: http://goldplugins.com/our-plugins/easy-faqs-details/

= Front End FAQ Submission =
* **NOTE:** This feature requires the Pro version of Easy FAQs: http://goldplugins.com/our-plugins/easy-faqs-details/
* Add the shortcode [submit_faq] to the area of the page you want your form on.
* Any submissions will be added to your FAQs list, on the back end.  Only FAQs that you choose to publish will be displayed publicly.

= Options =
* To control the destination of the "Read More" link, set the path in the FAQs Read More Link field.
* To control the wording of the "Read More" link, set the wording in the Read More Link Text field.
* To display any Featured Images that you have attached to your FAQs, check the box next to Show FAQ Image.
* To add any Custom CSS, to further modify the output of the plugin, input the CSS in the textarea labeled Custom CSS.  You do not need to include the opening or closing <style> tags, treat it like you're inside a CSS file.

== Frequently Asked Questions ==

= Help!  I need more information! =

OK!  We have a great page with some helpful information here: http://goldplugins.com/documentation/easy-faqs-documentation/

= Hey!  How do I allow my visitors to submit faqs? =

Great question!  With the Pro version of the plugin, you can do this with our front end form that is output with a shortcode!  FAQs will show up as pending on the Dashboard, for admin moderation.  Visit here to purchase the Pro version: http://goldplugins.com/our-plugins/easy-faqs-details/

= Ack!  This FAQs Plugin is too easy to use! Will you make it more complicated? =

Never!  Easy is in our name!  If by complicated you mean new and easy to use features, there are definitely some on the horizon!

== Screenshots ==

1. This is the Add New FAQ Page.
2. This is the List of FAQs - from here you can Edit or Delete a FAQ.
3. This is the Easy FAQs Settings Page.
4. This is the Easy Single FAQ Widget.
5. This is the Easy FAQs Categories Page.

== Changelog ==

= 1.2.2.2 =
* Fix: Address issue with content translation plugins and double content output.
* Fix: Update compatibility to WP 3.8.2

= 1.2.2.1 =
* Fix: Address issue with taxonomy and CPT slug conflicting when trying to view single faqs (ref #2189, thanks mralexweber!)

= 1.2.2 =
* Feature: extends shortcode to allow full control over output, including overriding global options such as image display, read more text, and read more display.
* Feature: extends widget to allow full control over output, including overriding global options such as image display, read more text, and read more display.
* Fix: address issue with single FAQ shortcode not outputting the correct FAQ.
* Fix: address issue with FAQ Read More link not displaying when global option is set.
* Fix: addresses CSS issues preventing certain styles from being applied correctly.

= 1.2.1 =
* Feature: adds ability to control Order and OrderBy parameters via the shortcode.

= 1.2 =
* Fix: update deprecated functions in widgets.
* Feature: Adds accordion style FAQ lists to Pro version.
* Update: fixes poorly structured HTML and some validation issues.

= 1.1 =
* Feature: Adds FAQ Categories and ability to list FAQs on a per category basis.

= 1.0 =
* Released!

== Upgrade Notice ==

* 1.2.2.2: Bug fixes available!