<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Keys management admin controller
 */
class Admin_Keys extends Admin_Controller
{
	protected $section = 'keys';

	public function __construct()
	{
		parent::__construct();

        // Language
        $this->lang->load('mobileapi');

        // Models
        $this->load->model('users_model');
        $this->load->model('keys_model');
	}

	/**
	 * List all items
	 */
	public function index()
	{
        $keys = $this->keys_model->find_all();
        $users = $this->users_model->order_by('name', 'asc')->find_all();

        $users_array = array();

        if($users)
        {
            foreach($users as $user)
            {
                $users_array[$user->id] = $user->name;
            }
        }

		// Build the view with sample/views/admin/items.php
		$this->template
            ->set('keys', $keys)
            ->set('users', $users_array)
			->title($this->module_details['name'])
			->build('admin/keys/index');
	}

    public function create()
    {
        $key = md5(time());
        $key_len = strlen($key);
        $key_half = ceil($key_len / 2);
        $api_key = substr($key, $key_half, $key_half - 2);

        $this->keys_model->insert(array('key' => $api_key));

        redirect('admin/mobileapi/keys/index');
    }

    public function assign_users()
    {
        $post_vars = $this->input->post();

        if($post_vars)
        {
            $this->keys_model->assign_key_to_user($post_vars['user_id'], $post_vars['key_id']);
            redirect('admin/mobileapi/keys/index');
        }
        else
        {
            redirect('admin/mobileapi/keys/index');
        }
    }

    public function delete($key_id = 0)
    {
        if($key_id == 0)
            redirect('admin/mobileapi/keys/index');

        $this->keys_model->delete($key_id);
            redirect('admin/mobileapi/keys/index');
    }
}
