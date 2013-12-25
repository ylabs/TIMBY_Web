<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Admin controller for the security log
 *
 * @author 		Team TIMBY
 * @website		http://timby.org
 * @package 	PyroCMS
 */
class Admin extends Admin_Controller
{
	protected $section = 'dashboard';

	public function __construct()
	{
		parent::__construct();

		// Load all the required models
		$this->load->model('timbysecurity/security_log_m');

        // Language
        $this->lang->load('timbysecurity');
	}

	/**
	 * List all items
	 */
	public function index()
	{
        $total_rows = $this->security_log_m->count_all();
        $pagination = create_pagination('admin/timbysecurity/index', $total_rows);

        // here we use MY_Model's get_all() method to fetch everything
        $items = $this->security_log_m
            ->order_by('id', 'desc')
            ->limit($pagination['limit'], $pagination['offset'])
            ->find_all();

		// Build the view with sample/views/admin/items.php
		$this->template
			->title($this->module_details['name'])
			->set('items', $items)
            ->set('pagination', $pagination)
			->build('admin/index');
	}

    public function bulkactions()
    {
        // Nothing at the moment
        redirect('timbysecurity/index');
    }
}
