<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * The Category management admin landing page
 */
class Admin_Categories extends Admin_Controller
{
    protected $section = 'categories';

    public function __construct()
    {
        parent::__construct();

        // Models
        $this->load->model('categories_m');

        // Language
        $this->lang->load('timby');
    }

    /**
     * List all items
     */
    public function index()
    {
        // here we use MY_Model's get_all() method to fetch everything
        $items = $this->categories_m->find_all();

        // Build the view with sample/views/admin/items.php

        $this->template
            ->title($this->module_details['name'])
            ->set('items', $items)
            ->build('admin/categories/index');

    }
}