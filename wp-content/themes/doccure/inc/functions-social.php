<?php

/**
 * Return social icons from theme options.
 *
 * @since 1.0.0
 */
function doccure_get_social_icons()
{
    $social_icons = array();
    $default_plats = array(
        'facebook' => array(
            'class' => 'facebook',
            'icon_class' => 'fab fa-facebook-f',
            'link' => 'https://www.facebook.com/sharer/sharer.php?u=%%url%%',
        ),
        'twitter' => array(
            'class' => 'twitter',
            'icon_class' => 'fab fa-twitter',
            'link' => 'http://twitter.com/intent/tweet?text=%%title%%&%%url%%',
        ),
        'linkedin' => array(
            'class' => 'linkedin',
            'icon_class' => 'fab fa-linkedin-in',
            'link' => 'https://www.linkedin.com/shareArticle?mini=true&url=%%url%%&title=%%title%%&summary=%%text%%',
        ),
        'pinterest' => array(
            'class' => 'pinterest',
            'icon_class' => 'fab fa-pinterest-p',
            'link' => 'http://pinterest.com/pin/create/button/?url=%%url%%',
        ),
        'tumblr' => array(
            'class' => 'tumblr',
            'icon_class' => 'fab fa-tumblr',
            'link' => 'https://www.tumblr.com/widgets/share/tool?canonicalUrl=%%url%%&title=%%title%%&caption=%%text%%',
        ),
        'skype' => array(
            'class' => 'skype',
            'icon_class' => 'fab fa-skype',
            'link' => 'https://web.skype.com/share?url=%%url%%&text=%%text%%',
        ),
    );

    foreach ($default_plats as $key => $value) {
        $default_plats_status = doccure_get_option($key . '_share');
        $icon_link = $default_plats[$key]['link'];
        $icon_link = str_replace('%%url%%', get_permalink(), $icon_link);
        $icon_link = str_replace('%%title%%', get_the_title(), $icon_link);
        $icon_link = str_replace('%%text%%', get_the_excerpt(), $icon_link);
        if ($default_plats_status) {
            $social_icons[$key] = array(
                'class' => $default_plats[$key]['class'],
                'icon_class' => $default_plats[$key]['icon_class'],
                'link' => $icon_link,
            );
        }
    }

    return $social_icons;

}
