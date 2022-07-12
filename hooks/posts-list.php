<?php

$actions = [
    'breakdance_posts_list_before_loop',
    'breakdance_posts_list_before_post',
    'breakdance_posts_list_after_image',
    'breakdance_posts_list_inside_wrap_start',
    'breakdance_posts_list_after_title',
    'breakdance_posts_list_after_meta',
    'breakdance_posts_list_after_tax',
    'breakdance_posts_list_after_content',
    'breakdance_posts_list_inside_wrap_end',
    'breakdance_posts_list_after_post',
    'breakdance_posts_list_after_loop',
    'breakdance_posts_list_after_pagination'
];

foreach ($actions as $action) {
    add_action($action, function ($actionData) use ($action) {
        echo $action."<br />";
    });
}
