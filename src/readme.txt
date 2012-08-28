=== OnMyPlate.co.uk Recipe Manager ===
Contributors: woolie
Donate link: http://onmyplate.co.uk
Tags: food, recipe, parser, markdown
Requires at least: 3.3.1
Tested up to: 3.3.1
Stable tag: 0.1.0

Here is a short description of the plugin.  This should be no more than 150 characters.  No markup here.

== Description ==



== Installation ==

This section describes how to install the plugin and get it working.

e.g.

1. Upload the `omp-plugin` directory to your `wp-content/plugins/`
2. Ensure the Zend Framework is within `/usr/share/php/libzend-framework-php/` (in Ubuntu: `apt-get install TODO`)
2. Activate the plugin through the 'Plugins' menu in WordPress

== Frequently Asked Questions ==

= A question that someone might have =

An answer to that question.


== Screenshots ==

1. This screen shot description corresponds to screenshot-1.(png|jpg|jpeg|gif). Note that the screenshot is taken from
the directory of the stable readme.txt, so in this case, `/tags/4.3/screenshot-1.png` (or jpg, jpeg, gif)
2. This is the second screen shot

== Changelog ==

= 0.1 =
* Initial release


== Upgrade Notice ==


== config.json Configuration ==
= Active Components =

Although there may be many components within the `OMP_Parser_Component_*`
namespace, not all of them may be active. To configure the active components,
the config.json key `active-omp-parser-components` should be used.

This key references a list of components that should be used, in the order
which they should be parsed. The parser will look for a class named
`OMP_Parser_Component_<name>`, for each entry within the array.

e.g.
    "active-omp-parser-components" : [
        "Ingredients",
        "Method",
        "Tips"
    ]
