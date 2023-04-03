<?php

if (!function_exists('activeSegment')) {
    function activeSegment($name, $segment = 1, $parent = "", $class = 'active')
    {
            if ($parent != "") {
                return (request()->segment($segment) == $name && request()->segment($segment-1) == $parent) ? $class : "";
            } else {
                return request()->segment($segment) == $name ? $class : "";
            }
    }
}



// if (!function_exists('activeSegment')) {
//     function activeSegment($name, $segment = 1, $class = 'active')
//     {
//         return request()->segment($segment) == $name ? $class : "";
//     }
// }


function money_format($value = 0, $decimal = 2, $comma = true)
{
    return ($comma) ? '₦ '.number_format($value, $decimal) : '₦ '.number_format($value, $decimal, ".", "");
}
