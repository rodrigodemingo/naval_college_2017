<?php

global $current_sidebar, $layout_sidebar;

echo '<div class="col-xs-12 col-md-3 col-lg-3 col-sm-4"><div id="sidebar" class="sidebar_area '. $layout_sidebar .'_sidebar">';

if (function_exists('dynamic_sidebar'))
    dynamic_sidebar($current_sidebar);

echo '</div></div>';
?>