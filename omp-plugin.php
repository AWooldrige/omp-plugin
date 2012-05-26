<?php
/*
Plugin Name: OnMyPlate.co.uk Recipe Manager
Plugin URI: https://github.com/AWooldrige/omp-plugin
Description: Formatting for recipes on OnMyPlate.co.uk
Version: 0.1.0
Author: Alistair Wooldrige
Author URI: http://onmyplate.co.uk
License: GPLv2 or later
*/

/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

define('OMP_PLUGIN_VERSION', '0.1.0');
define('OMP_PLUGIN_URL', plugin_dir_url( __FILE__ ));

require_once('bootstrap.php');

remove_filter('the_content', 'wpautop');
remove_filter('the_content', 'wptexturize');
remove_filter('the_excerpt', 'wpautop');
remove_filter('the_excerpt', 'wptexturize');
remove_filter('the_title', 'wpautop');
remove_filter('the_title', 'wptexturize');

if(!is_admin()) {
    add_action('wp', 'ompContentFilter');
}

/**
 * Parser for the content returned by the database.
 *
 * The content returned from the database is parsed using the OMP_Parser,
 * any leftover content returned. This leftover is usually the post excerpt, and
 * any text included other than the known formats.
 *
 * @access public
 * @return void
 */
function ompContentFilter() {
    $activeComponents = array('Ingredients', 'Method', 'Tips', 'Text', 'Meta');

    global $posts;
    foreach($posts as $p) {

        $parser = new OMP_Parser_Sequential();

        try {
            $p->recipe_data = $parser->parse($p->post_content, $activeComponents);
            $p->post_content = $p->recipe_data['Text']['summary'] .
                   $p->recipe_data['Text']['other'];
            $p->post_content_filtered = $p->post_content;
        }
        catch (Exception $e) {
            $p->recipe_data = null;
            $p->post_content = 'ERROR PARSING RECIPE: ' . $e->getMessage();
            $p->post_content_filtered = $p->post_content;
        }
    }
}
