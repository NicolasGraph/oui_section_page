<?php

Txp::get('\Textpattern\Tag\Registry')->register('oui_page');

/**
 * Gets a section's page.
 *
 * @param  string $section The section
 * @return string|bool The page or FALSE on error
 */

function oui_fetch_section_page($section)
{
    static $secpages = array();
    global $thissection;

    // Try cache.
    if (isset($secpages[$section])) {
        return $secpages[$section];
    }

    // Try global set by section_list().
    if (!empty($thissection['page']) && $thissection['name'] == $section) {
        $secpages[$section] = $thissection['page'];

        return $thissection['page'];
    }

    if (empty($section)) {
        return '';
    }

    $f = safe_field("page", 'txp_section', "name = '".doSlash($section)."'");
    $secpages[$section] = $f;

    return $f;
}

// -------------------------------------------------------------

function oui_section_page($atts, $thing = null)
{
    global $thisarticle, $s, $thissection;

    extract(lAtts(array(
        'class'   => '',
        'section' => '',
        'wraptag' => '',
    ), $atts));

    if ($section) {
        $sec = $section;
    } elseif (!empty($thissection['name'])) {
        $sec = $thissection['name'];
    } elseif (!empty($thisarticle['section'])) {
        $sec = $thisarticle['section'];
    } else {
        $sec = $s;
    }

    if ($sec) {
        $out = oui_fetch_section_page($sec);

        return doTag($out, $wraptag, $class);
    }
}

// -------------------------------------------------------------

function oui_if_page($atts, $thing)
{
    global $pretext;
    extract($pretext);

    extract(lAtts(array(
        'name' => false,
        'section' => '',
    ), $atts));

    if ($section) {
        $page = oui_page(array('section' => $section));
    }

    return parse($thing, $name === false or in_list($page, $name));
}
