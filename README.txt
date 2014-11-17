=== Plugin Name ===
Contributors: doytch
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_xclick&business=mark%2edeutsch%40utoronto%2eca&lc=US&item_name=Coffee%20Donation%20%3d%29&amount=10%2e00&currency_code=USD&button_subtype=services&bn=PP%2dBuyNowBF%3abtn_buynowCC_LG%2egif%3aNonHosted
Tags: feature list, features, check list, list, shortcode, table
Requires at least: 3.9
Tested up to: 4.0
Stable tag: 1.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Create reusable feature or check lists that you can add to any post or page.

== Description ==

Create reusable feature or check lists that you can add to any post or page. Great for houses, hotels, cars, products,
and anytime you want to display a set of items that each have a subset of a group of features. 

Simply:

1. Create a list that consists of items organised into various groups.
1. In your post, insert the [efl_feature_list] shortcode where you want the list.
1. Select the feature list you want from the metabox below the post editor.
1. Set the value for each feature. This lets you create one list and reuse it across
multiple posts, changing only the text or checkbox for each feature.

Other features are planned for the future, so let me know of any requests via the Support forum. I provide paid support
and custom development via my company as well. Email me at admin@qedev.com with any critical or larger requests.

== Installation ==

You don't really need to do anything special to install this plugin. Just:

1. Download using the button above, unzip and place the folder in your wp-content/plugins/ folder. Alternately, 
install using Wordpress' built-in plugin installer.
1. Activate the plugin through the Plugins menu in WordPress
1. Follow the instructions in the Description tab to add, insert, and configure feature lists.

== Frequently Asked Questions ==

= How do I style the feature list? =

The feature lists are simple &lt;table&gt; and &lt;input&gt; elements so your theme's CSS will affect the styling. I've included
classes for the elements that you can create selectors for as well. If you need any custom styling and you don't want
to worry about this, contact me at admin@qedev.com and we can discuss any jobs you need done.

= How do I remove a feature group? =

On the Edit Feature List page, simply remove all the features from the feature group you wish to delete.

== Screenshots ==

1. Feature lists are created using simple tables. In this example, two feature groups are being created with various
features in each group.
2. Lists are selected and customised using the metabox below the post editor, and inserted into posts and pages using a shortcode.
3. The feature list displayed under the WordPress Twenty Twelve theme. Styling is controlled via your theme.

== Changelog ==

= 1.1 =
* Features can now have their values be text as well as check boxes. The text is set on the Post page just like checkboxes.
* Feature lists previously defined will no longer work. I apologise for the inconvenience but these and future improvements
required moving to a more flexible, incompatible underlying architecture. I thought it was best to do this now rather than
down the line.
* 78% more love was added to the plugin.

= 1.0 =
* Initial release
