<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Timby public landing controller
 */
class Timby extends Public_Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * All items
	 */
	public function index($offset = 0)
	{
        // Landing
	}

    public function view($report_id = 0)
    {
        // View a whole report if necessary (the narrative on a committed page)
    }
}