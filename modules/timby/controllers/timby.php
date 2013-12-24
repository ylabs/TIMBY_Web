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

    public function rss()
    {
        if(Settings::get('reports_are_public') == "true")
        {
            require dirname(__FILE__).'/../libraries/feedwriter/FeedTypes.php';

            $approved_reports = $this->reports_m
                ->order_by('id', 'desc')
                ->limit(100)
                ->find_all_by(array('approved' => 1));

            $reports_feed = new RSS2FeedWriter();

            $reports_feed->setTitle('TIMBY RSS Feed');
            $reports_feed->setLink(site_url('timby/timby/rss'));
            $reports_feed->setDescription('RSS Feeds from the TIMBY website');
            $reports_feed->setChannelElement('language', 'en-us');
            $reports_feed->setChannelElement('pubDate', date(DATE_RSS, time()));

            if($approved_reports)
            {
                foreach($approved_reports as $approved_report)
                {
                    $post = $this->reports_m->posts()->find_by('report_id', $approved_report->id);
                    $feed_item = $reports_feed->createNewItem();

                    $feed_item->setTitle($approved_report->title);
                    $feed_item->setDate(strtotime($approved_report->report_date));
                    $feed_item->setDescription($post ? $post->post : '');
                    $feed_item->addElement('author', 'TIMBY');
                    
                    $reports_feed->addItem($feed_item);
                }
            }

            $reports_feed->generateFeed();
        }
        else
        {
            show_404();
        }
    }
}