<?php

// INIT ROUTINES

// This routine adds a handler to the "init" hook, calling the related funciton
add_action('init', 'casestudies_register');
// This module ensures that the Case Study post type is correctly registered, and all buttons and other actions are correctly labeled
function casestudies_register() {
    $labels = array(
        'name' => _x('Case Studies', 'post type general name'),
        'singular_name' => _x('Case Study', 'post type singular name'),
        'add_new' => _x('Add New', 'casestudies'),
        'add_new_item' => __('Add New Case Study'),
        'edit_item' => __('Edit Case Study'),
        'new_item' => __('New Case Study'),
        'view_item' => __('View Case Study'),
        'search_items' => __('Search Case Studies'),
        'not_found' => __('No case studies found'),
        'not_found_in_trash' => __('No case studies found in Trash'),
        'parent_item_colon' => ''
    );

    $args = array(
        'labels' => $labels,
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'query_var' => true,
        'rewrite' => true,
        'capability_type' => 'post',
        'hierarchical' => false,
        'menu_position' => null,
        'supports' => array('title', 'editor', 'thumbnail')
        // FOR REFERENCE - Original line: 'supports' => array('title', 'editor', 'thumbnail', 'excerpt')
    );

    register_post_type('casestudies', $args);
    register_taxonomy_for_object_type('engagements', 'casestudies');
    register_taxonomy_for_object_type('processes', 'casestudies');
    register_taxonomy_for_object_type('practices', 'casestudies');
}


add_action('admin_init', 'admin_init_casestudies');
function admin_init_casestudies() {
    add_meta_box("casestudy_summary-meta", "Case Study: Summary", "casestudy_summary", "casestudies", "advanced", "high");
}


// TEST CODE: Initial approach attempted to override the standard "single.php" template for Case Studies
add_filter( 'template_include', 'casestudies_register_templates' );
function casestudies_register_templates( $template ) {
    $post_types = array( 'project' );

    if ( is_post_type_archive( $post_types ) && ! file_exists( get_stylesheet_directory() . '/archive-casestudies.php' ) )
        $template = plugin_dir_path( __FILE__ ).'/templates/archive-casestudies.php';
    if ( is_singular( $post_types ) && ! file_exists( get_stylesheet_directory() . '/single-casestudy.php' ) )
        $template = plugin_dir_path( __FILE__ ).'/templates/single-casestudy.php';

    return $template;
}

// TODO: For some reason, these fields all have an extra set of blank spaces pre-pended every time they're echoed into the "textareas".
// The "trim" function that is applied at the beginning of this function prevents them from building up and becoming longer every time 
// the record is updated, but the underlying problem still needs to be addressed.
function casestudy_summary() {
    global $post;

    echo '<input type="hidden" name="casestudy_post_noncename" id="casestudy_post_noncename" value="' . wp_create_nonce( plugin_basename(__FILE__) ) . '" />';


    $custom = get_post_custom($post->ID);
    $casestudy_summary = trim($custom["casestudy_summary"][0]);
    $casestudy_results = trim($custom["casestudy_results"][0]);
    $casestudy_sellingpts = trim($custom["casestudy_sellingpts"][0]);
?>

    <div class="width_full p_box">
        <p>
            <label>Description<br>
                <textarea name="casestudy_summary" class="widefat" rows="5">
                    <?php echo $casestudy_summary; ?>
                </textarea>
            </label>
        </p>
        <p>
            <label>Results<br>
                <textarea name="casestudy_results" class="widefat" rows="5">
                    <?php echo $casestudy_results; ?>
                </textarea>
            </label>
        </p>
        <p>
            <label>Selling Points<br>
                <textarea name="casestudy_sellingpts" class="widefat" rows="5">
                    <?php echo $casestudy_sellingpts; ?>
                </textarea>
            </label>
        </p>
    </div>


<?php
}


add_action('save_post', 'casestudy_save');
function casestudy_save() {
    global $post;

    // verify this came from the our screen and with proper authorization,
    // because save_post can be triggered at other times
    if( !wp_verify_nonce( $_POST['casestudy_post_noncename'], plugin_basename(__FILE__) ) ) {
        return $post->ID;
    }

    // is the user allowed to edit the post or page?
    if( ! current_user_can( 'edit_post', $post->ID )){
        return $post->ID;
    }
    // ok, we're authenticated


    update_post_meta($post->ID, "casestudy_summary", $_POST["casestudy_summary"]);
    update_post_meta($post->ID, "casestudy_results", $_POST["casestudy_results"]);
    update_post_meta($post->ID, "casestudy_sellingpts", $_POST["casestudy_sellingpts"]);
}

add_filter("manage_edit-casestudies_columns", "casestudy_edit_columns");
function casestudy_edit_columns($columns) {
    $columns = array(
        "cb" => "<input type=\"checkbox\" />",
        "title" => "Case Study Title",
        "casestudy_summary" => "Summary",

        "engagements" => "Engagements",
        "practices" => "Practices",
        "processes" => "Processes"
    );

    return($columns);
}

add_action("manage_casestudies_posts_custom_column", "casestudy_custom_columns");
function casestudy_custom_columns($column) {
    global $post;

    $custom = get_post_custom();

    $strSummary = $custom["casestudy_summary"][0];
    // if strlen($strSummary) > 50 {
        $strSummary = substr($strSummary,0,100) . "...";
    // }

    switch ($column) {
        case "description":
            the_excerpt();
            break;
        case "casestudy_summary":
            echo $strSummary;
            break;



        case "engagements":
            echo get_the_term_list($post->ID, 'engagements', '', ', ', '');
            break;
        case "processes":
            echo get_the_term_list($post->ID, 'processes', '', ', ', '');
            break;
        case "practices":
            echo get_the_term_list($post->ID, 'practices', '', ', ', '');
            break;
    }
}

//add filter to ensure the text "Case Study", "Case Studies", etc., is displayed when user updates a book
add_filter('post_updated_messages', 'casestudy_updated_messages');

function casestudy_updated_messages( $messages ) {
  global $post, $post_ID;

  $messages['casestudies'] = array(
    0 => '', // Unused. Messages start at index 1.
    1 => sprintf( __('Case Study updated. <a href="%s">View Case Study</a>'), esc_url( get_permalink($post_ID) ) ),
    2 => __('Custom field updated.'),
    3 => __('Custom field deleted.'),
    4 => __('Case Study updated.'),
    /* translators: %s: date and time of the revision */
    5 => isset($_GET['revision']) ? sprintf( __('Case Study restored to revision from %s'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
    6 => sprintf( __('Case Study published. <a href="%s">View Case Study</a>'), esc_url( get_permalink($post_ID) ) ),
    7 => __('Case Study saved.'),
    8 => sprintf( __('Case Study submitted. <a target="_blank" href="%s">Preview Case Study</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
    9 => sprintf( __('Case Study scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview Case Study</a>'),
      // translators: Publish box date format, see http://php.net/date
      date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( get_permalink($post_ID) ) ),
    10 => sprintf( __('Case Study draft updated. <a target="_blank" href="%s">Preview Case Study</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
  );

  return $messages;
}

add_shortcode('casestudy', 'casestudy_display');



?>
