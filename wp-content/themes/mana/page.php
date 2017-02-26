<?php
get_header();

the_post();

include file_require(get_template_directory() .'/template-page.php');

get_footer();
?>