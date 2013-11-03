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

    public function create()
    {
        $post_vars = $this->input->post();

        unset($post_vars["btnAction"]);

        if($post_vars)
        {
            $this->categories_m->insert($post_vars);
            redirect(site_url("admin/timby/categories"));
        }
        else
        {
            $this->template
                ->title($this->module_details['name'])
                ->build('admin/categories/create');
        }
    }

    public function edit($category_id = 0)
    {
        if($category_id == 0)
        {
            redirect(site_url("admin/timby/categories"));
        }

        $post_vars = $this->input->post();

        unset($post_vars["btnAction"]);

        if($post_vars)
        {
            $this->categories_m->update($category_id, $post_vars);
            redirect(site_url("admin/timby/categories"));
        }
        else
        {
            $item = $this->categories_m->find($category_id);

            $this->template
                ->title($this->module_details['name'])
                ->set('item', $item)
                ->set('category_id', $category_id)
                ->build('admin/categories/edit');
        }
    }

    public function delete($category_id = 0)
    {
        $this->categories_m->delete($category_id);
        redirect(site_url("admin/timby/categories"));
    }
}