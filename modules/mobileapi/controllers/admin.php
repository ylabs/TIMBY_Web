<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * User management admin controller
 */
class Admin extends Admin_Controller
{
    protected $section = 'users';

    public function __construct()
    {
        parent::__construct();
    }

    private function form_validation()
    {
        // Perform form validation
    }

    /**
     * List all items
     */
    public function index()
    {

        // Build the view with sample/views/admin/items.php
        $this->template
            ->title($this->module_details['name'])
            ->build('admin/users/index');
    }

    public function create()
    {
        $post_vars = $this->input->post();

        if($post_vars)
        {
            redirect('admin/mobileapi/index');
        }
        else
        {
            $this->template
                ->title($this->module_details['name'])
                ->build('admin/users/create');
        }
    }

    public function edit($user_id = 0)
    {
        if($user_id == 0)
            redirect('admin/mobileapi/index');

        $post_vars = $this->input->post();

        if($post_vars)
        {
            redirect('admin/mobileapi/index');
        }
        else
        {
            $this->template
                ->title($this->module_details['name'])
                ->set('user_id', $user_id)
                ->build('admin/users/edit');
        }
    }

    public function delete($user_id = 0)
    {
        if($user_id == 0)
            redirect('admin/mobileapi/index');
    }
}