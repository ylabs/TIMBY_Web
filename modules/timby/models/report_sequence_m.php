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
}
