<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Main reports model
 */
class Report_sequence_m extends BF_Model {
    protected $table_name = "report_sequence";
    protected $set_created	= TRUE;
    protected $set_modified = TRUE;

    public function __construct()
    {
        parent::__construct();
    }

    public function insert($data = null)
    {
        $all_after = $this->where(array("sequence >" => $data["sequence"]))
            ->find_all();

        foreach($all_after as $after_item)
        {
            parent::update($after_item->id, array("sequence" => ($all_after->sequence + 1)));
        }

        return parent::insert($data);
    }

    public function update($where = null, $data = null)
    {
        $all_after = $this->where(array("sequence >" => $data["sequence"]))
            ->find_all();

        foreach($all_after as $after_item)
        {
            parent::update($after_item->id, array("sequence" => ($all_after->sequence + 1)));
        }

        return parent::update($where, $data);
    }
}
