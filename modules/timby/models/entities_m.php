<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Entities_m extends BF_Model {
    protected $table_name = "report_entity";
    protected $set_created	= FALSE;
    protected $set_modified = FALSE;
    protected $date_format = 'datetime';

    public function __construct()
    {
        parent::__construct();

        // Libraries
        $this->load->library("timby/timby_utilities");
    }

    public function insert($data = NULL)
    {
        if(isset($data["entity"]))
        {
            $data["slug"] = $this->timby_utilities->get_slug($data["entity"]);
        }

        return parent::insert($data);
    }

    public function update($where = NULL, $data = NULL)
    {
        if(isset($data["entity"]))
        {
            $data["slug"] = $this->timby_utilities->get_slug($data["entity"]);
        }

        return parent::update($where, $data);
    }
} 