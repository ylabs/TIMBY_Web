<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Main categories model
 */
class Categories_m extends BF_Model {
    protected $table_name = "report_categories";
    protected $set_created	= FALSE;
    protected $set_modified = FALSE;

    public function __construct()
    {
        parent::__construct();
    }
}
