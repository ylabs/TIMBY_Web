<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Timby_utilities
{
    public function get_slug($string, $replace_space = "_")
    {
        $new_string = strtolower($string);
        $new_string = str_replace(" ", $replace_space, $new_string);

        return $new_string;
    }
}