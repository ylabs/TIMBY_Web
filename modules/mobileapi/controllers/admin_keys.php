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
}
