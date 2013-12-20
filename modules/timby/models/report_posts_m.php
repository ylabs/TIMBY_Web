<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class report_posts_m extends BF_Model {
    protected $table_name = "report_posts";
    protected $set_created	= TRUE;
    protected $set_modified = TRUE;
    protected $date_format = 'datetime';
} 