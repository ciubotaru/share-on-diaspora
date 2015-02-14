=== Plugin Name ===
Contributors: Vitalie Ciubotaru
Tags: diaspora, share, button
Requires at least: 3.2.1
Tested up to: 4.1
Stable tag: 0.6.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This plugin adds a "Share on D*" button at the bottom of your posts.

== Description ==
This plugin adds a "Share on Diaspora" button at the bottom of your posts. Unlike other similar buttons, 
this one is not tied to one single pod. Instead it allows the users to select their favorite pod from the list, 
or type it directly. The button is highly customizable, allowing blog admins to set the color, size and shape in 
accordance with the overall look and feel of their blogs.

= i18n =
There is an ongoing effort to translate the plugin into other languages.
So far, the following translations are available:

* French -- contributed by Stef20 from stef20.info/blog and Se7h
* German -- contributed by Georg Krause <mail@georg-krause.net>
* Italian -- contributed by Sandro kensan <kensan@kensan.it> from www.kensan.it
* Japanese
* Portuguese (Brazil) -- contributed by Vostok <info@diaspbr.org>
* Romanian
* Russian
* Serbo-Croatian -- contributed by Borisa Djuraskovic from Webhostinghub.com
* Spanish -- contributed by Andrew Kurtis from Webhostinghub.com and David Charte

Please note, that some translations might be incomplete and lack latest changes.

== Installation ==
1. Unpack `share-on-diaspora.zip` and upload its contents to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Enjoy

== Frequently Asked Questions ==
= Is it another button linked to JoinDiaspora? =
No. Users can reshare to any Diaspora pod: select from the list or type their
own.

= I want to report a bug, request a feature or contribute code. What shall I do? =
Bug reports, feature requests and real code are always welcome. Check out
https://github.com/ciubotaru/share-on-diaspora or drop a line to "vitalie at
ciubotaru dot tk".

= Does your plugin collect private information about blog visitors? =
No.

= The button color scheme does not fit into my blog theme. Can I change it? =
Yes. In the plugin settings panel, go to 'Button options' tab (default) and
tweak the colors, size and shape of your button. After you are done, press
'Update'.

= I want to use my own image. Is it possible? =
Yes. In the plugin settings panel, go to 'Custom image' tab and upload your
image or insert the URL of an existing image (e.g. from your Media gallery).
After you are done, press 'Update'.

= I want to change the list of displayed pods. Is it possible? =
Yes. In the plugin settings panel, go to 'Pod list options' tab and check those
pods that you want to see in the dialog. After you are done, press 'Update'.

= My pod is not in the list. How can I add it? =
I would suggest that you let me know the pod URL, so that I can add it and 
update everybody's list. Of course, you can do it yourself by adding a line 
to `pod_list_all.txt' in your plugin's directory and enabling it in the plugin
settings (see previous question).

== Screenshots ==
1. The "Share on D*" button under the default "Hello World"
post in "Twenty Thirteen" theme.
2. Choose a Diaspora pod from the list or type it in the text field.
3. Verify the text of your post, Aspects that will see it etc, and press the
Post button.
4. To choose from several preset color profiles, go to the Settings page,
'Color Profiles' tab.
5. To customize the look of you button, go to the Settings page, 'Button
options' tab.
6. To upload and use own image instead of the stock button, go to the Settings
page, 'Custom Image' tab.
7. To choose the pods to be included in the drop-down list, go to the Settings
page, 'Pod list options' tab.

== Changelog ==
= 0.6 =
* Added color presets
* Changed podlis update mechanism
* Added support for an auxiliary plugin (Diaspora Podlist Updater)
* Very likely added new bugs

= 0.5.7 =
* Updated list of active pods
* i18n: Serbo-Croatian translation added

= 0.5.6 =
* Updated list of active pods
* i18n: French translation added

= 0.5.5 =
* i18n: Italian translation added

= 0.5.4 =
* Updated list of active pods
* i18n: completed Japanese translation, other minor updates

= 0.5.3 =
* New feature (admin can add custom pods from plugin settings panel)
* i18n: minor updates in translation

= 0.5.2 =
* Updated the list of active pods

= 0.5.1 =
* Bug-fix: Button on pages (changed to posts-only)
* i18n: Portuguese (Brasil) and Romanian translations updated. A [parial]
Japanese translation added.

= 0.5 =
* New options (custom image)
* i18n: Portuguese (Brazil), Spanish, Russian and Romanian translations added

= 0.4.1 =
* Bug-fix: Button sometimes sharing wrong page

= 0.4 =
* New options (customizable text on the button, color change on mouseover)
* Customizable pod list on the pod selection page

= 0.3.3 =
* Bug-fix: bad button formatting on some themes
* Bug-fix: Missing callback function
* Bug-fix: Conflict with OpenGraph's "og:description" tag

= 0.3.2 =
* Bug-fix: syntax error on PHP < 5.4

= 0.3.1 =
* Bug-fix: function names can cause conflict

= 0.3 =
* Customizable button settings (text color, background color, size and rounded
corners)

= 0.2 =
* Pod selection dialog renewed

= 0.1 =
* First release

== Upgrade Notice ==
Version 0.3.3 introduces new ways to customize you share button. The default
values for these options may not match your preferences, so please make sure to
check the "Share on D*" settings page and tweak them to your liking.
