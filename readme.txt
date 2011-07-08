=== Feed2Tabs ===
Contributors: Name.ly, Namely
Donate link: http://feed2tabs.com/
Tags: atom, feed, feeds, links, rss, widget, widgets
Requires at least: 3.0
Tested up to: 3.2
Stable tag: 0.0.3
License: GPLv2 or later

Feed2Tabs allows adding widgets and shortcodes that will automatically open recent feed items in tabs with just one single click.



== Description ==

Feed2Tabs allows to add widgets and shortcodes that will automatically open recent feed items in tabs with just one single click.

For installation please see the corresponding section. It is as trivial as copying the plugin folder in your WordPress.

Once installed, there are two ways to add Feed2Tabs links: via widgets or via shortcodes.

= Widgets: =

1. Go to Appearance -> Widgets
1. Drag Feed2Tabs box to the widget holder
1. If you want to use advanced option, configure and save.
1. Else, all is ready.

Widget offers the following options:

* Title (title of the widget)
* Feed URL (feed source; leave blank to use the current blog's feed)
* Table of Contents (caption of the Table of Contents; set to `off` to hide the ToC)
* Maximum number of feed entries (limits the number of shown feed items)
* Link destination (new window or same window)
* Description (text to appear as the icon description)
* Custom API base URL (for advanced users)

= Shortcodes: =

With `[Feed2Tabs]` shortcode one can insert Feed2Tabs links directly from any post or pages. E.g.

Here is my super `[Feed2Tabs]`.

Here is my super `[Feed2Tabs title=Feed toc=off]`.

You can open 10 top sites at `[Feed2Tabs title=Delicious source=delicious.com toc=off]` with just one click.

Short codes offer the following options:

* title (default: Feed2Tabs)
* source (feed source; default: if left blank the current blog's feed will be used)
* toc (default: ToC)
* numposts (default: 10)
* target (default: _blank)
* description (text to appear as the link description)
* custom_api_base (for advanced users)

For those that want to configure custom API base and even map it on their own domain names, instructions can be found [here](http://name.ly/api/custom-api/).



== Installation ==

= As easy, as 1-2-3: =

1. Upload `feed2tabs` folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Voila!

= Usage: =

To add a widget: simply go to Appearance -> Widgets and add Feed2Tabs.

To add a link via a shortcode, insert `[Feed2Tabs]` in any post or page.

Please see more details in the Description section.



== Frequently Asked Questions ==

= Can I link to another RSS? =

*Yes*, just provide the valid URL for the feed source.

= Can I have many widgets for several different RSS feeds? =

*Yes*, you can. Just drag Feed2Tabs template box to the widget area and configure all instance accordingly.

= What feed types do you support? =

All major ones, i.e.:

* Atom
* RSS
* XML

The complete list can be found [here](http://supportedfeedtypes.feed2tabs.com/).



== Screenshots ==

1. Widget settings
2. Widget badge as embedded on the site
3. Feed entries opened in tabs
4. Inserting shortcodes
5. Shortcoded links in the post



== Changelog ==

= 0.0.1 =

* Initial version.
* Created and tested.

= 0.0.2 =

* Added a note that Wordpress 3.0 is the minimal version.

= 0.0.3 =

* Adjusted width of the setting inputs for WP 3.2.



== Upgrade Notice ==

= 0.0.1 =

This is a great plugin, give it a try.

= 0.0.2 =

Users using WP below 3.0 should upgrade.



== Translations ==

* English - [Name.ly](http://name.ly/)

If you want to translate this plugin, please read [this](http://feed2tabs.com/plugins/wordpress#translating).



== Recommendations ==

Check out the companion plugin: [Links2Tabs](http://wordpress.org/extend/plugins/links2tabs/).

They go well together like coffee and doughnuts!



== About Name.ly ==

Name.ly offers your WordPress blogs and many other services allowing to consolidate multiple sites, pages and profiles.

All on catchy domain names, like sincere.ly, thatis.me, of-cour.se, ...



