<?php

/*
  Plugin Name: Fluency Media Content Architecture Plugin
  Plugin URI: http://fluencymedia.com
  Description: WordPress module to manage custom taxonomies and post types on Fluency Media site
  Author: Laurent R.O. Stanevich
  Version: 0.0.1
  Author URI: http://fluencymedia.com
 */

// Custom taxonomies need to be registered first, so that the custom objects can be linked to them later
include_once dirname(__FILE__) . '/fm-content-custom-taxonomies.php';

include_once dirname(__FILE__) . '/fm-content-cp-citation.php';
include_once dirname(__FILE__) . '/fm-content-cp-data-point.php';
include_once dirname(__FILE__) . '/fm-content-tools-html-inc.php';
include_once dirname(__FILE__) . '/fm-content-nav-widget.php';

function signOffText() {
    return 'Thank you so much for reading! And remember to subscribe to our RSS feed. ';
}

function findNote() {
    $args = array(
        'relationship' => 'AND',
        'post_type' => 'notes',
        'tax_query' => array(
            'taxonomy' => 'sidebars',
            'field' => 'title',
            'terms' => 'epidemiology',
            'operator' => 'IN'
        )
    );

    $myposts = get_posts($args);

    $outString = '<ul>';

    foreach ($myposts as $post) :
        $outString .= '<li>' . $post->post_title . '</li>';
    endforeach;
    $outString .= '</ul>';

    return $outString;
}

add_shortcode('signoff', 'signOffText');

add_shortcode('notes', 'findNote');
?>