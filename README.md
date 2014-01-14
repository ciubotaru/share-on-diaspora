A wordpress plugin adds a "Share on D*" button at the bottom of your posts.

# Description
This plugin adds a "Share on Diaspora" button at the bottom of your posts. Unlike other similar buttons,
this one is not tied to one single pod. Instead it allowes the users to select their favorite pod from the list,
or type it directly. The button is highly customizable, allowing blog admins to set the color, size and shape in
accordance with the overall look and feel of their blogs.

# Screenshots
1. The "Share on D*" button under the default "Hello World"
post in "Twenty Thirteen" theme.
![](https://github.com/ciubotaru/share-on-diaspora/blob/master/assets/screenshot-1.png?raw=true)
2. Choose a Diaspora pod from the list or type it in the text field.
![](https://github.com/ciubotaru/share-on-diaspora/blob/master/assets/screenshot-2.png?raw=true)
3. Verify the text of your post, Aspects that will see it etc, and press the Post button.
![](https://github.com/ciubotaru/share-on-diaspora/blob/master/assets/screenshot-3.png?raw=true)
4. To customize the look of you button, go to the Settings page, 'Button options' tab.
![](https://github.com/ciubotaru/share-on-diaspora/blob/master/assets/screenshot-4.png?raw=true)
5. To choose the pods to be included in the drop-down list, go to the Settings page, 'Pod list options' tab.
![](https://github.com/ciubotaru/share-on-diaspora/blob/master/assets/screenshot-5.png?raw=true)

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
Bug reports, feature requests and real code are always welcome. Check out https://github.com/ciubotaru/share-on-diaspora or drop a line to "vitalie at ciubotaru dot tk".

## Does your plugin collect private information about blog visitors?
No.

# Changelog
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
* Add a screen shot of the settings page.
* Add plugin version number to screenshot captions.
* Add plugin description to 'readme.txt'. This will also result in tags being displayed on the plugin webpage.
* Make a cover picture for the plugin webpage.
* Reference the cover picture from Wordpress Plugin Directory in readme.md, so that it gets displayed on GitHub, too.
* Complete the FAQ and the installation info.
* Think about how to add a graphical color-picker to the settings page. JavaScript?
* Think how to update preview automatically. JavaScript?
* Find out how to upload and handle images (for custom buttons)

# Translations
It is important to providing sharing UI in the same language as the rest o the website. Currently, all translation efforts are concentrated on [poeditor.com](poeditor.com). If you want to contribute a translation, or point out some errors in others' translations, check out [https://poeditor.com/projects/view?id=10673](https://poeditor.com/projects/view?id=10673).
