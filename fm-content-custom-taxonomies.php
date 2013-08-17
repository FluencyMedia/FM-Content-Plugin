<?php

add_action('init', 'engagements_register');
add_action('init', 'processes_register');
add_action('init', 'practices_register');

function engagements_register() {
    register_taxonomy(
            "engagements",
            "post",
            array(
                "hierarchical" => true,
                "label" => "Engagements",
                "singular_label" => "Engagement",
                "rewrite" => true,
                "show_ui" => true)
    );
}

function processes_register() {
    register_taxonomy(
            "processes",
            "post",
            array(
                "hierarchical" => true,
                "label" => "Processes",
                "singular_label" => "Process",
                "rewrite" => true,
                "show_ui" => true)
    );
}

function practices_register() {
    register_taxonomy(
            "practices",
            "post",
            array(
                "hierarchical" => true,
                "label" => "Practices",
                "singular_label" => "Practice",
                "rewrite" => true,
                "show_ui" => true)
    );
}

?>