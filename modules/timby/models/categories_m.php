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

        // Libraries
        $this->load->library("timby/timby_utilities");
    }

    public function insert($data = NULL)
    {
        if(isset($data["category"]))
        {
            $data["slug"] = $this->timby_utilities->get_slug($data["category"]);
        }

        return parent::insert($data);
    }

    public function update($where = NULL, $data = NULL)
    {
        if(isset($data["category"]))
        {
            $data["slug"] = $this->timby_utilities->get_slug($data["category"]);
        }

        return parent::update($where, $data);
    }

    public function delete($id = NULL)
    {
        $this->load->model("reports_m");

        $status = parent::delete($id);

        if($status)
        {
            $reports = $this->reports_m->find_by(array("category" => $id));

            foreach($reports as $report)
            {
                $this->reports_m->delete($report->id);
            }
        }

        return $status;
    }
}
