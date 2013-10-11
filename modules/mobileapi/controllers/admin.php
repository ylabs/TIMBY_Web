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
}