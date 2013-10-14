<?php defined('BASEPATH') or exit('No direct script access allowed');

class user_keys_model extends BF_Model {
    protected $table_name = "api_user_keys";
    protected $set_created	= FALSE;
    protected $set_modified = FALSE;

    public function __construct()
    {
        parent::__construct();
    }
}