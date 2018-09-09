A WordPress plugin that adds a "Share on D*" button at the bottom of your posts.

Diaspora to Wordpress plugin in [WP Plugin Directory](https://wordpress.org/plugins/share-on-diaspora/)

- [Description](#description)
- [i18n](#i18n)
- [Screenshots](#screenshots)
- [Installation](#installation)
- [Frequently Asked Questions](#frequently-asked-questions)
- [Changelog](#changelog)
- [Upgrade Notice](#upgrade-notice)
- [ToDo](#todo)

# Description
This plugin adds a "Share on Diaspora" button at the bottom of your posts. Unlike other similar buttons,
this one is not tied to one single pod. Instead it allows the users to select their favorite pod from the list,
or type it directly. The button is highly customizable, allowing blog admins to set the color, size and shape in
accordance with the overall look and feel of their blogs.

# i18n
There is an ongoing effort to translate the plugin into other languages. This way, the sharing UI stays in the same language as the rest of the website.
So far, the following translations are available:

* French -- contributed by Stef20 from stef20.info/blog and Se7h
* German -- contributed by Georg Krause <mail@georg-krause.net> and JanRei
* Indonesian -- contributed by https://www.chameleonjohn.com
* Italian -- contributed by Sandro kensan <kensan@kensan.it> from www.kensan.it
* Japanese
* Portuguese (Brazil) -- contributed by Vostok <info@diaspbr.org>
* Romanian
* Russian
* Serbo-Croatian -- contributed by Borisa Djuraskovic from Webhostinghub.com
* Spanish -- contributed by Andrew Kurtis from Webhostinghub.com and David Charte

Please note, that some translations might be incomplete and lack latest changes.

Currently, most translation efforts are concentrated on [PoEditor.com](poeditor.com). If you want to contribute a translation, or point out some errors in others' translations, check out [our project](https://poeditor.com/projects/view?id=10673).

# Screenshots
1. The "Share on D*" button under the default "Hello World"
post in "Twenty Fifteen" theme.
![](https://github.com/ciubotaru/share-on-diaspora/blob/master/assets/screenshot-1.png?raw=true)
2. Choose a Diaspora pod from the list or type it in the text field.
![](https://github.com/ciubotaru/share-on-diaspora/blob/master/assets/screenshot-2.png?raw=true)
3. Verify the text of your post, Aspects that will see it etc, and press the Post button.
![](https://github.com/ciubotaru/share-on-diaspora/blob/master/assets/screenshot-3.png?raw=true)
4. To choose from several preset color profiles, go to the Settings page, 'Color Profiles' tab.
![](https://github.com/ciubotaru/share-on-diaspora/blob/master/assets/screenshot-4.png?raw=true)
5. To customize the look of you button, go to the Settings page, 'Button options' tab.
![](https://github.com/ciubotaru/share-on-diaspora/blob/master/assets/screenshot-5.png?raw=true)
6. To upload and use own image instead of the stock button, go to the Settings
page, 'Custom Image' tab.
![](https://github.com/ciubotaru/share-on-diaspora/blob/master/assets/screenshot-6.png?raw=true)
7. To choose the pods to be included in the drop-down list, go to the Settings page, 'Pod list options' tab.
![](https://github.com/ciubotaru/share-on-diaspora/blob/master/assets/screenshot-7.png?raw=true)
8. To show/hide the button in RSS feeds, go to the Settings page, 'Misc' tab.
![](https://github.com/ciubotaru/share-on-diaspora/blob/master/assets/screenshot-8.png?raw=true)

# Installation
1. Get the latest snapshot of master branch.
2. In the `/wp-content/plugins/` directory of your WordPress installation create a subdirectory called `share-on-diaspora`.
3. Copy the contents of `trunk` into the directory you created above.
4. Activate the plugin through the 'Plugins' menu in WordPress.
5. Enjoy

# Frequently Asked Questions
## Is it another button linked to JoinDiaspora?
No. Users can reshare to any Diaspora pod: select from the list or type their own.

## I want to report a bug, request a feature or contribute code. What shall I do?
Bug reports, feature requests and real code are always welcome. Check out https://github.com/ciubotaru/share-on-diaspora or drop a line to "vitalie at ciubotaru dot tokyo".

## Does your plugin collect private information about blog visitors?
No.

## The button color scheme does not fit into my blog theme. Can I change it?
Yes. In the plugin settings panel, go to 'Button options' tab (default) and
tweak the colors, size and shape of your button. After you are done, press
'Update'.

## I want to use my own image. Is it possible?
Yes. In the plugin settings panel, go to 'Custom image' tab and upload your
image or insert the URL of an existing image (e.g. from your Media gallery).
After you are done, press 'Update'.

## I want to change the list of displayed pods. Is it possible?
Yes. You can do this on the 'Pod list options' tab in the settings panel. To 
get (or update) the list of all active pods, press the 'Retrieve' button. To 
add a pod from the list, select it with up/down keys in the 'Add a pod' textbox 
and press 'Add'. To add a custom pod, type it in the textbox and press 'Add'. 
After you've added all pods that you want to see in the dialog, press 'Update'.

## Can I get the latest list of active pods?
Yes. You can manually retrieve a fresh list of active pods on the 'Pod list 
options' tab of the settings panel. Press the 'Retrive' button to download the 
list, then press 'Update' to save changes. Now the list of active pods is 
available in the 'Add a pod' textbox. Use up/down keys to navigate the list.

Alternatively, you can install an auxiliary plugin called 
[Diaspora Podlist Updater](https://wordpress.org/plugins/diaspora-podlist-updater/) 
that automatically retrieves a fresh list of active pods.

# Changelog
## 0.7.3
* Bug-fix: updated code to fetch podlist from podupti.me

## 0.7.2
* Bug-fix: a reflected XSS in share url parameters (patch provided by Framartin@GitHub).

## 0.7.1
* Moved translations to GlotPress

## 0.7
* Bug-fix: sharing functionality in RSS feeds.
* Button in RSS feeds is made optional
* Post title is editable now

## 0.6.4
* Bug-fix: warning messages about non-compliance with strict standards.
* i18n: German translation updated.
* Minor improvements (updated FAQ and compatibility info, removed deprecated calls etc.)

## 0.6.3
* Bug-fix: the default placeholder in pod selection window did not display properly.

## 0.6.2
* Code formatting streamlined to reflect WP Coding Standards.

## 0.6.1
* Bug-fix: saved settings shouldn't be deleted on deactivation, only on 
uninstall.

## 0.6
* Added color presets
* Changed podlist update mechanism
* Added support for an auxiliary plugin (Diaspora Podlist Updater)
* Very likely added new bugs

## 0.5.7
* Updated list of active pods
* i18n: Serbo-Croatian translation added

## 0.5.6
* Updated list of active pods
* i18n: French translation added

## 0.5.5
* i18n: Italian translation added

## 0.5.4
* Updated list of active pods
* i18n: completed Japanese translation, other minor updates

## 0.5.3
* New feature (admin can add custom pods from plugin settings panel)
* i18n: minor updates in translation

## 0.5.2
* Updated the list of active pods

## 0.5.1
* Bug-fix: Button on pages (changed to posts-only)
* i18n: Portuguese (Brasil) and Romanian translations updated. A [parial]
Japanese translation added.

## 0.5
* New options (custom image)
* i18n: Portuguese (Brazil), Spanish, Russian and Romanian translations added

## 0.4.1
* Bug-fix: Button sometimes sharing wrong page

## 0.4
* New options (customizable text on the button, color change on mouseover)
* Customizable pod list on the pod selection page

## 0.3.3
* Bug-fix: bad button formatting on some themes
* Bug-fix: Missing callback function
* Bug-fix: Conflict with OpenGraph's "og:description" tag

## 0.3.2
* Bug-fix: syntax error on PHP < 5.4

## 0.3.1
* Bug-fix: function names can cause conflict

## 0.3
* Customizable button settings (text color, background color, size and rounded corners)

## 0.2
* Pod selection dialog renewed

## 0.1
* First release

# Upgrade Notice
Version 0.3.3 introduces new ways to customize you share button. The default values for these options may not match your preferences, so please make sure to check the "Share on D*" settings page and tweak them to your liking.

# ToDo
* Think about how to add a graphical color-picker to the settings page. JavaScript?
* Think how to update preview automatically. JavaScript?
