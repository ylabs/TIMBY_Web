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

        // Language
        $this->lang->load('mobileapi');

        // Models
        $this->load->model('users_model');
    }

    private function form_validation()
    {
        // Perform form validation
        $config = array(
            array(
                'field'   => 'user_name',
                'label'   => 'User name',
                'rules'   => 'required'
            ),
            array(
                'field'   => 'password',
                'label'   => 'Password',
                'rules'   => 'required'
            ),
        );

        $this->form_validation->set_rules($config);
    }

    /**
     * List all items
     */
    public function index()
    {
        $users = $this->users_model->find_all();

        // Build the view with sample/views/admin/items.php
        $this->template
            ->set('users', $users)
            ->title($this->module_details['name'])
            ->build('admin/users/index');
    }

    public function create()
    {
        $post_vars = $this->input->post();

        if($post_vars)
        {
            $this->form_validation();

            if($this->form_validation->run() == TRUE)
            {
                $user_name = $post_vars['user_name'];
                $password = $post_vars['password'];

                if($this->users_model->create_user($user_name, $password))
                {
                    redirect('admin/mobileapi/index');
                }
                else
                {
                    $this->session->set_flashdata('error', lang('mobileapi:user_already_exists'));
                    redirect('admin/mobileapi/create');
                }
            }
        }

        $this->template
            ->title($this->module_details['name'])
            ->build('admin/users/create');
    }

    public function edit($user_id = 0)
    {
        if($user_id == 0)
            redirect('admin/mobileapi/index');

        $user = $this->users_model->find($user_id);

        if(!$user)
            redirect('admin/mobileapi/index');

        $post_vars = $this->input->post();

        if($post_vars)
        {
            $this->form_validation();

            if($this->form_validation->run() == TRUE)
            {
                $user_name = $post_vars['user_name'];
                $password = $post_vars['password'];

                if($this->users_model->update(array('id' => $user_id),
                    array(
                        'name' => $user_name,
                        'password' => md5($password))
                    ))
                {
                    redirect('admin/mobileapi/index');
                }
            }
        }

        $this->template
            ->title($this->module_details['name'])
            ->set('user', $user)
            ->set('user_id', $user_id)
            ->build('admin/users/edit');
    }

    public function delete($user_id = 0)
    {
        if($user_id == 0)
            redirect('admin/mobileapi/index');

        $this->users_model->delete($user_id);
        redirect('admin/mobileapi/index');
    }
}