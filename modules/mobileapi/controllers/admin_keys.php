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
	}

	/**
	 * List all items
	 */
	public function index()
	{

		// Build the view with sample/views/admin/items.php
		$this->template
			->title($this->module_details['name'])
			->build('admin/keys/index');
	}

    public function create()
    {
        $post_vars = $this->input->post();

        if($post_vars)
        {
            redirect('admin/mobileapi/keys/index');
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
            redirect('admin/mobileapi/keys/index');

        $post_vars = $this->input->post();

        if($post_vars)
        {
            redirect('admin/mobileapi/keys/index');
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
            redirect('admin/mobileapi/keys/index');
    }
}
