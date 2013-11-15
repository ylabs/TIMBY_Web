<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Tagged reports model
 */
class Tagged_reports_m extends BF_Model {
    protected $table_name = "tagged_reports";
    protected $set_created	= FALSE;
    protected $set_modified = FALSE;
    protected $date_format = 'datetime';
}
