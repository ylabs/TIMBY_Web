<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Main reports model
 */
class Reports_m extends BF_Model {
    protected $table_name = "reports";
    protected $set_created	= TRUE;
    protected $set_modified = TRUE;
    protected $date_format = 'datetime';

    const type_narrative = 0;
    const type_image = 1;
    const type_video = 2;
    const type_audio = 3;
    const type_entity = 4;

	public function __construct()
	{		
		parent::__construct();

        // Loading related models
        $this->load->model('timby/report_posts_m');
        $this->load->model('timby/report_sequence_m');
        $this->load->model('timby/report_narratives_m');
        $this->load->model('timby/report_multimedia_m');
        $this->load->model('timby/report_images_m');
        $this->load->model('timby/report_entities_m');

        // Libraries
        $this->load->library("timby/timby_utilities");
	}

    public function insert($data = NULL)
    {
        if(isset($data["title"]))
        {
            $data["slug"] = $this->timby_utilities->get_slug($data["title"]);
        }

        $report_id =  parent::insert($data);

        // Create a corresponding post object

        $this->posts()->insert(array(
            'report_id' => $report_id,
            'post' => 'Report post',
        ));

        return $report_id;
    }

    public function update($where = NULL, $data = NULL)
    {
        if(isset($data["title"]))
        {
            $data["slug"] = $this->timby_utilities->get_slug($data["title"]);
        }

        return parent::update($where, $data);
    }

    public function delete($id = NULL, $soft_delete = true)
    {
        $result = null;

        if($soft_delete)
        {
            $result = parent::update($id, array("deleted" => 1));
        }
        else
        {
            $result = parent::delete($id);
        }

        if(!$soft_delete)
        {
            if($result)
            {
                $this->posts()->delete_where(array('report_id' => $id));
                $this->sequence()->delete_where(array("report_id" => $id));
                $this->sequence()->delete_where(array("report_id" => $id));
                $this->narratives()->delete_where(array("report_id" => $id));
                $this->images()->delete_where(array("report_id" => $id));
                $this->multimedia()->delete_where(array("report_id" => $id));
                $this->entities()->delete_where(array("report_id" => $id));
            }
        }

        return $result;
    }

    public function get_full_report($report_id)
    {
        $report = $this->find($report_id);
        $report->objects = array();

        if($report)
        {
            $report_sequence_items = $this->sequence()
                ->order_by("sequence")
                ->find_all_by(array("report_id" => $report_id));

            if($report_sequence_items)
            {
                $item_index = 0;

                foreach($report_sequence_items as $item)
                {
                    switch($item->item_type)
                    {
                        case self::type_narrative:
                            $narrative = $this->narratives()->find($item->item_id);

                            if($narrative)
                            {
                                if($narrative->deleted == 0)
                                {
                                    $report->objects[$item_index] = new stdClass;
                                    $report->objects[$item_index]->type = $item->item_type;
                                    $report->objects[$item_index]->narrative = $narrative->narrative;
                                }
                            }

                            break;
                        case self::type_audio:
                        case self::type_video:
                            $multimedia = $this->multimedia()->find($item->item_id);

                            if($multimedia)
                            {
                                if($multimedia->deleted == 0)
                                {
                                    $report->objects[$item_index] = new stdClass;
                                    $report->objects[$item_index]->type = $item->item_type;
                                    $report->objects[$item_index]->file = $multimedia->multimedia_path;
                                }
                            }

                            break;
                        case self::type_image:
                            $image = $this->images()->find($item->item_id);

                            if($image)
                            {
                                if($image->deleted == 0)
                                {
                                    $report->objects[$item_index] = new stdClass;
                                    $report->objects[$item_index]->type = $item->item_type;
                                    $report->objects[$item_index]->file = $image->image_path;
                                }
                            }
                            break;
                    }

                    $item_index ++;
                }
            }
        }

        return $report;
    }

    public function posts()
    {
        return $this->report_posts_m;
    }

    public function sequence()
    {
        return $this->report_sequence_m;
    }

    public function narratives()
    {
        return $this->report_narratives_m;
    }

    public function images()
    {
        return $this->report_images_m;
    }

    public function multimedia()
    {
        return $this->report_multimedia_m;
    }

    public function entities()
    {
        return $this->report_entities_m;
    }
}
