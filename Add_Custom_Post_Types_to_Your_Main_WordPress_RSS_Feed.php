<?php
/* This code modifies the query to keep the default content type for blog posts “post” in the main RSS feed, and also adding new custom post types.*/
function myfeed_request($qv) {
    if (isset($qv['feed']) && !isset($qv['post_type']))
        $qv['post_type'] = array('post', 'reviews');
    return $qv;
}
add_filter('request', 'myfeed_request');
/* But what if you have five custom post types in your new project, and you only want to add three to the main RSS feed? Well that shouldn’t be any problem because we will just modify the code slightly to give you the option to only include the ones that you want.*/
function myfeed_request($qv) {
    if (isset($qv['feed']) && !isset($qv['post_type']))
        $qv['post_type'] = array('post', 'reviews');
    return $qv;
}
add_filter('request', 'myfeed_request');
?>