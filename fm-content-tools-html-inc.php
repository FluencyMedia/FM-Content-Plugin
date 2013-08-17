<?php

add_shortcode('html-inc', 'htmlInclude');

function htmlInclude($atts) {
    extract(shortcode_atts(array(
                "incfile" => 'test.html',
                "incdir" => 'test',
                    ), $atts));

    // TODO 1: Add error-checking to filepath / filepath strings
    // TODO 2: Add simple ability to determine the current server's domain, and work down to a fixed directory from there
    // TODO 3: Add the ability to manage the "parent" file path through a WP Admin UI panel
    $strIncFileName = 'http://lairbob.com/essays-html/' . $incdir . '/' . $incfile;

    $strIncFile = file_get_contents($strIncFileName);

    // Apply any other shortcodes that are embedded within the content
    $strIncFile = do_shortcode($strIncFile);
    // $strIncFile = wpautop($strIncFile);

    // Explode the include string into an array of individual lines
    $expIncFile = explode("\n", $strIncFile);

    // Reassemble the array of lines back into a single string, eventually applying custom logic
    $outIncFile = "";
    for ($i = 0; $i < count($expIncFile); $i++) {
        $outIncFile = $outIncFile . "\n" . $expIncFile[$i]; //write value by index
    }

    return $outIncFile;
}

?>