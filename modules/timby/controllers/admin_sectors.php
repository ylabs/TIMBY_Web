<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * The Category management admin landing page
 */
class Admin_Sectors extends Admin_Controller
{
    protected $section = 'sectors';

    public function __construct()
    {
        parent::__construct();

        // Models
        $this->load->model('sectors_m');

        // Language
        $this->lang->load('timby');
    }

    /**
     * List all items
     */
    public function index()
    {
        // here we use MY_Model's get_all() method to fetch everything
        $items = $this->sectors_m->find_all();

        // Build the view with sample/views/admin/items.php
        $this->template
            ->title($this->module_details['name'])
            ->set('items', $items)
            ->build('admin/sectors/index');

    }

    public function create()
    {
        $post_vars = $this->input->post();

        unset($post_vars["btnAction"]);

        if($post_vars)
        {
            $sector_id = $this->sectors_m->insert($post_vars);

            Events::trigger('report_sector_created', array('sector_id' => $sector_id,
                'user_id' => $this->current_user->id), 'array');

            redirect(site_url("admin/timby/sectors"));
        }
        else
        {
            $this->template
                ->title($this->module_details['name'])
                ->build('admin/sectors/create');
        }
    }

    public function edit($category_id = 0)
    {
        if($category_id == 0)
        {
            redirect(site_url("admin/timby/sectors"));
        }

        $post_vars = $this->input->post();

        unset($post_vars["btnAction"]);

        if($post_vars)
        {
            $this->sectors_m->update($category_id, $post_vars);

            Events::trigger('report_sector_edited', array('sector_id' => $category_id,
                'user_id' => $this->current_user->id), 'array');

            redirect(site_url("admin/timby/sectors"));
        }
        else
        {
            $item = $this->sectors_m->find($category_id);

            $this->template
                ->title($this->module_details['name'])
                ->set('item', $item)
                ->set('category_id', $category_id)
                ->build('admin/sectors/edit');
        }
    }

    public function delete($category_id = 0)
    {
        $this->sectors_m->delete($category_id);

        Events::trigger('report_sector_deleted', array('sector_id' => $category_id,
            'user_id' => $this->current_user->id), 'array');

        redirect(site_url("admin/timby/sectors"));
    }
}