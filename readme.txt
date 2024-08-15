=== Custom User Avatar ===
Contributors: WPExplorer
Donate link: https://www.wpexplorer.com/donate/
Tags: user, avatar, profile, photo, gravatar
Requires at least: 4.2.0
Requires PHP: 8.0
Tested up to: 6.6
Stable Tag: 1.0
License: GPL v2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Adds a new field to the user edit screen where you can define a custom avatar.

== Description ==
This plugin simply adds a new field to the WordPress user edit screen where you can define a custom avatar for the user. The field accepts either an image attachment ID or a full URL to an image.

Custom User Avatar also includes support for multisite installations. If you define an image ID for a user avatar that was uploaded to the primary site it will display this image across all subsites.

== Installation ==

1. Go to your WordPress website admin panel
2. Select Plugins > Add New
3. Search for "Custom User Avatar"
4. Click Install
5. Activate the plugin
6. Now, whenever you edit a user you should see a new "Custom Avatar (ID or URL)" field that you can use at the bottom of the "Contact Info" section.

== Frequently Asked Questions ==

= Is the Custom User Avatar plugin Free? =
Yes. The plugin is completely free of charge under the GPL license.

= Is there a premium version? =
No.

= Will the plugin work with my theme? =
Custom User Avatar should work with any theme that is using the core WordPress functions to display your user avatars.

= How can I find an image ID? =
It's recommended to enter ID's for the custom avatar field instead of full URL's, this way if the image is ever deleted you won't end up with a broken avatar on the frontend. To find an image ID simply go to your media library and click to edit the image you want to use. Once you are on the image edit screen you will be able to locate the ID in the URL which will have the format: your-site.com/wp-admin/post.php?post={ID}

== Changelog ==

= 1.0 =

* First official release
