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

include_once dirname(__FILE__) . '/fm-content-cp-case.php';

// include_once dirname(__FILE__) . '/fm-content-cp-data-point.php';
// include_once dirname(__FILE__) . '/fm-content-tools-html-inc.php';
// include_once dirname(__FILE__) . '/fm-content-nav-widget.php';


?>