<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Timby public landing controller
 */
class Timby extends Public_Controller
{
	public function __construct()
	{
		parent::__construct();

        $this->load->model('timby/reports_m');
        $this->load->model('timby/categories_m');
	}

	/**
	 * All items
	 */
	public function index()
	{
        // Nothing to do on controller landing
        show_404();
	}

    public function view($report_id = 0)
    {
        if(Settings::get('reports_are_public') == "true")
        {
            $report = $this->reports_m->find_by('id', $report_id);
            $entites = array();
            $category = false;
            $report_post = "";

            if($report)
            {
                $report_entities = $this->reports_m->entities()->find_all_by(array('report_id' => $report_id));

                if($entites)
                {
                    foreach($report_entities as $entity)
                    {
                        $entites[] = $entity->entity;
                    }
                }

                $report_posts = $this->reports_m->posts()->find_by('report_id', $report_id);

                if($report_posts)
                {
                    $report_post = $report_posts->post;
                }

                $category = $this->categories_m->find_by('id', $report->category);
            }

            $this->load->view('timby/timby/view', array('report' => $report, 'entities' => $entites,
                'category' => $category, "report_post" => $this->parser->parse_string($report_post, array(), true)));
        }
        else
        {
            show_404();
        }
    }
}