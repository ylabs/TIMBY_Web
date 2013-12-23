<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Widget_timby_js extends Widgets {
    public $title = 'TIMBY JavaScript Initialization Widget';
    public $description =  'Initializes all variabled needed by the TIMBY theme.';
    public $author = 'Team TIMBY';
    public $website = 'http://timby.com/';
    public $version = '1.0';

    public function run()
    {
        $this->load->model('timby/categories_m');
        $this->load->model('timby/sectors_m');

        $all_categories = $this->categories_m->order_by('category')->find_all();
        $all_sectors = $this->sectors_m->order_by('sector')->find_all();

        return (array('categories' => $all_categories, 'sectors' => $all_sectors));
    }
} 