<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * The entity management admin landing page
 */
class Admin_Entities extends Admin_Controller
{
    protected $section = 'entities';

    public function __construct()
    {
        parent::__construct();

        // Models
        $this->load->model('entities_m');

        // Language
        $this->lang->load('timby');
    }

    /**
     * List all items
     */
    public function index()
    {
        // here we use MY_Model's get_all() method to fetch everything
        $items = $this->entities_m->find_all();

        // Build the view with sample/views/admin/items.php
        $this->template
            ->title($this->module_details['name'])
            ->set('items', $items)
            ->build('admin/entities/index');

    }

    public function create()
    {
        $post_vars = $this->input->post();

        unset($post_vars["btnAction"]);

        if($post_vars)
        {
            $category_id = $this->entities_m->insert($post_vars);

            Events::trigger('report_entity_created', array('category_id' => $category_id,
                'user_id' => $this->current_user->id), 'array');

            redirect(site_url("admin/timby/entities"));
        }
        else
        {
            $this->template
                ->title($this->module_details['name'])
                ->build('admin/entities/create');
        }
    }

    public function edit($entity_id = 0)
    {
        if($entity_id == 0)
        {
            redirect(site_url("admin/timby/entities"));
        }

        $post_vars = $this->input->post();

        unset($post_vars["btnAction"]);

        if($post_vars)
        {
            $this->entities_m->update($entity_id, $post_vars);

            Events::trigger('report_entity_edited', array('entity' => $entity_id,
                'user_id' => $this->current_user->id), 'array');

            redirect(site_url("admin/timby/entities"));
        }
        else
        {
            $item = $this->entities_m->find($entity_id);

            $this->template
                ->title($this->module_details['name'])
                ->set('item', $item)
                ->set('entity_id', $entity_id)
                ->build('admin/entities/edit');
        }
    }

    public function delete($entity_id = 0)
    {
        $this->entities_m->delete($entity_id);

        Events::trigger('report_category_deleted', array('entity_id' => $entity_id,
            'user_id' => $this->current_user->id), 'array');

        redirect(site_url("admin/timby/entities"));
    }
} 