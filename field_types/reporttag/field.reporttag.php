<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Allows articles / posts and stream clients to be linked to reports (tagged to)
 */

class Field_reporttag {
    public $field_type_slug			= 'reporttag';

    public $db_col_type				= 'text';

    public $version					= '1.0';

    public $author					= array('name'=>'Team Timby', 'url'=>'http://timby.org');

    public $custom_parameters		= array(
        'index_id',
    );

    public $plugin_return			= 'merge';

    private function init()
    {
        $this->CI->load->model('timby/reports_m');
        $this->CI->load->model('timby/tagged_reports_m');
    }

    public function form_output($params, $entry_id, $field)
    {
        $this->init();

        $all_reports = $this->CI->reports_m
            ->order_by('id', 'desc')
            ->limit(50)
            ->find_all();

        $tagged_reports = false;

        if($entry_id != null)
        {
            $tagged_reports = $this->CI->tagged_reports_m
                ->where('object_id', $entry_id)
                ->find_all();
        }

        $tagged_reports_array = array();

        if($tagged_reports)
        {
            foreach($tagged_reports as $tagged_report)
            {
                $tagged_reports_array[] = $tagged_report->report_id;
            }
        }

        $view = $this->CI->type->load_view($this->field_type_slug, 'index', array(
            'all_reports' => $all_reports,
            'tagged_reports' => $tagged_reports,
            'tagged_reports_array' => $tagged_reports_array,
        ), true);

        return $view;
    }
} 