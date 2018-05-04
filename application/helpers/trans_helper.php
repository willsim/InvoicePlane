<?php

/**
 * InvoicePlane Translation Helper
 *
 * @author         InvoicePlane Developers & Contributors
 * @copyright      Copyright (c) 2012 - 2018 InvoicePlane.com
 * @license        https://invoiceplane.com/license.txt
 * @link           https://invoiceplane.com
 */

/**
 * Output a language string, supports language fallback if a string wasn't found
 *
 * @param string      $line
 * @param string      $id
 * @param null|string $default
 * @return string
 */
function trans($line, $id = null, $default = null)
{
    $CI =& get_instance();
    $lang_string = $CI->lang->line($line);

    // Fall back to default language if the current language has no translated string
    if (empty($lang_string)) {
        // Load the default language
        set_language('english');
        $lang_string = $CI->lang->line($line);
        reset_language();
    }

    // Fall back to the $line value if the default language has no translation either
    if (empty($lang_string)) {
        $lang_string = $default != null ? $default : $line;
    }

    if (!is_null($id)) {
        $lang_string = '<label for="' . $id . '">' . $lang_string . "</label>";
    }

    return $lang_string;
}

/**
 * Load the translations for the given language
 *
 * @param string $language
 * @return void
 */
function set_language($language)
{
    // Clear the current loaded language
    $CI =& get_instance();
    $CI->lang->is_loaded = [];
    $CI->lang->language = [];

    // Load system language if no custom language is set
    $default_lang = isset($CI->mdl_settings) ? $CI->mdl_settings->setting('default_language') : 'english';
    $new_language = ($language == 'system' ? $default_lang : $language);

    // Set the new language
    $CI->lang->load('ip', $new_language);
}

/**
 * Reset the langauge to the default one
 *
 * @return void
 */
function reset_language()
{
    // Clear the current loaded language
    $CI =& get_instance();
    $CI->lang->is_loaded = [];
    $CI->lang->language = [];

    // Reset to the default language
    $default_lang = isset($CI->mdl_settings) ? $CI->mdl_settings->setting('default_language') : 'english';

    $CI->lang->load('ip', $default_lang);
}

/**
 * Returns all available languages
 *
 * @return array
 */
function get_available_languages()
{
    $languages = directory_map(APPPATH . 'language', true);
    sort($languages);

    for ($i = 0; $i < count($languages); $i++) {
        $languages[$i] = str_replace(['\\', '/'], '', $languages[$i]);
    }

    return $languages;
}
