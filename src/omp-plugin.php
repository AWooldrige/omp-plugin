<?php
/*
Plugin Name: OnMyPlate.co.uk Recipe Manager
Plugin URI: https://github.com/AWooldrige/omp-plugin
Description: Formatting for recipes on OnMyPlate.co.uk
Version: {{VERSION}}
Author: Alistair Wooldrige
Author URI: http://onmyplate.co.uk
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
            $p->post_content = $p->recipe_data['Text']['summary'] . '<!--more--><br></br>' .
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


add_filter('pre_update_option_sticky_posts', 'ompStickyPostsFilter', 10, 2);

/**
 * When a new post is made sticky, ensure it is the only one which is.
 * I.e. Only one post can be sticky at a time.
 *
 * @param array $stickyPosts of posts which are sticky
 * @access public
 * @return array of sticky posts, which only contains the one element
 */
function ompStickyPostsFilter($new, $old) {
    return OMP_Wordpress_Utilities::oneStickyPost($old, $new);
}
