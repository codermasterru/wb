<?php

if (!function_exists('mb_str_replace')) {

    function mb_str_replace($needle, $tex_replace, $haystack)
    {
        return implode($tex_replace, mb_split($needle, $haystack));
    }
}
