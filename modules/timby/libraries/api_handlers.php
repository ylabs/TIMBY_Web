<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Handles the API events
 */
class API_Handlers
{
    public function __construct()
    {
        // Models
        ci()->load->model('timby/categories_m');
        ci()->load->model('timby/reports_m');
    }

    public function create_report($post_data)
    {
        unset($post_data["approved"]);
        $status = ci()->reports_m->insert($post_data);

        if($status != false)
        {
            return array("id" => $status);
        }

        return false;
    }

    public function update_report($report_id, $post_data)
    {
        unset($post_data["approved"]);
        $status = ci()->reports_m->update($report_id, $post_data);

        if($status != false)
        {
            return array("status" => $status);
        }

        return false;
    }

    public function delete_report($post_data)
    {
        $status = ci()->reports_m->delete($post_data["report_id"]);

        if($status)
        {
            return array("status" => $status);
        }

        return false;
    }

    public function insert_report_object($upload_path, $post_data)
    {
        // We may upload heavy objects
        set_time_limit(0);

        // Upload configuration
        $config = array();
        $config['allowed_types'] = 'gif|jpg|png';
        $config['max_size']	= '100';
        $config['max_width']  = '1024';
        $config['max_height']  = '768';

        $sequence_number = $post_data["sequence"];
        $object_type = $post_data["object_type"];
        $report_id = $post_data["report_id"];
        $narrative = $post_data["narrative"];

        $do_upload = true;
        $table_to_use = "";
        $path_field_to_use = "";
        $field_to_use = "";

        switch($object_type)
        {
            case "narrative":
                $do_upload = false;
                $table_to_use = "narratives";
                $path_field_to_use = "";
                $field_to_use = "narrative";
                break;
            case "video":
                $config['upload_path'] = rtrim($upload_path, "/")."/videos";
                $table_to_use = "videos";
                $path_field_to_use = "video_path";
                $field_to_use = "video";
                break;
            case "image":
                $config['upload_path'] = rtrim($upload_path, "/")."/images";
                $table_to_use = "images";
                $path_field_to_use = "image_path";
                $field_to_use = "image";
                break;
            default:
                return false;
                break;
        }

        $upload_data = null;

        if($do_upload)
        {
            ci()->load->library('upload', $config);

            if (ci()->upload->do_upload())
            {
                $upload_data = ci()->upload->data();
            }
            else
            {
                return false;
            }

            Events::trigger('media_uploaded', array("file_name" => $config['upload_path']."/".$upload_data["file_name"]));
        }

        $object_id = false;

        if($path_field_to_use != "")
        {
            $object_id = ci()->reports_m->{$table_to_use}()->insert(
                array(
                    "{$path_field_to_use}" => $upload_data["file_name"],
                    "report_id" => $report_id,
                )
            );
        }
        else
        {
            $object_id = ci()->reports_m->{$table_to_use}()->insert(
                array(
                    "{$field_to_use}" => $narrative,
                    "report_id" => $report_id,
                )
            );
        }

        if(!$object_id)
        {
            return false;
        }

        return ci()->reports_m->report_sequence()->insert(array(
            "sequence" => $sequence_number,
            "report_id" => $report_id,
            "item_type" => $object_type,
            "item_id" => $object_id,
        ));
    }

    public function update_report_object($upload_path, $post_data)
    {
        // We may upload heavy objects
        set_time_limit(0);

        // Upload configuration
        $config = array();
        $config['allowed_types'] = 'gif|jpg|png';
        $config['max_size']	= '100';
        $config['max_width']  = '1024';
        $config['max_height']  = '768';

        $sequence_number = $post_data["sequence"];
        $object_type = $post_data["object_type"];
        $report_id = $post_data["report_id"];
        $object_id = $post_data["object_id"];
        $narrative = $post_data["narrative"];

        $old_object_id = $object_id;

        $do_upload = true;
        $table_to_use = "";
        $path_field_to_use = "";
        $field_to_use = "";

        switch($object_type)
        {
            case "narrative":
                $do_upload = false;
                $table_to_use = "narratives";
                $path_field_to_use = "";
                $field_to_use = "narrative";
                break;
            case "video":
                $config['upload_path'] = rtrim($upload_path, "/")."/videos";
                $table_to_use = "videos";
                $path_field_to_use = "video_path";
                $field_to_use = "video";
                break;
            case "image":
                $config['upload_path'] = rtrim($upload_path, "/")."/images";
                $table_to_use = "images";
                $path_field_to_use = "image_path";
                $field_to_use = "image";
                break;
            default:
                return false;
                break;
        }

        // Get the old object (To delete if necessary)

        $object_data = ci()->reports_m->{$table_to_use}()->find($object_id);

        if(!$object_data)
        {
            return false;
        }

        $upload_data = null;

        if($do_upload)
        {
            ci()->load->library('upload', $config);

            if (ci()->upload->do_upload())
            {
                $upload_data = ci()->upload->data();
            }
            else
            {
                return false;
            }

            if($object_data->{$path_field_to_use} != $upload_data["file_name"])
            {
                // Delete the old file (Clean up)
                unlink($config['upload_path']."/".$object_data->{$path_field_to_use});
            }

            Events::trigger('media_uploaded', array("file_name" => $config['upload_path']."/".$upload_data["file_name"]));
        }

        $object_id = false;

        if($path_field_to_use != "")
        {
            $object_id = ci()->reports_m->{$table_to_use}()->update(
                array("id" => $old_object_id),
                array(
                    "{$path_field_to_use}" => $upload_data["file_name"],
                    "report_id" => $report_id,
                )
            );
        }
        else
        {
            $object_id = ci()->reports_m->{$table_to_use}()->update(
                array("id" => $old_object_id),
                array(
                    "{$field_to_use}" => $narrative,
                    "report_id" => $report_id,
                )
            );
        }

        if(!$object_id)
        {
            return false;
        }

        return ci()->reports_m->report_sequence()->update(
            array("report_id" => $report_id),
            array(
                "sequence" => $sequence_number,
                "item_type" => $object_type,
                "item_id" => $object_id,
            )
        );
    }

    public function delete_report_object($upload_path, $post_data)
    {
        $object_type = $post_data["object_type"];
        $table_to_use = "";
        $object_id = $post_data["object_id"];

        $new_upload_path = rtrim($upload_path, "/")."/";
        $path_field_to_use = "";

        switch($object_type)
        {
            case "narrative":
                $table_to_use = "narratives";
                break;
            case "video":
                $table_to_use = "videos";
                $new_upload_path = $new_upload_path."videos/";
                $path_field_to_use = "video_path";
                break;
            case "image":
                $table_to_use = "images";
                $new_upload_path = $new_upload_path."images/";
                $path_field_to_use = "image_path";
                break;
            default:
                return false;
                break;
        }

        $object_data = ci()->reports_m->{$table_to_use}()->find($object_id);

        Events::trigger('pre_media_deleted', array("file_name" => $new_upload_path."/".$object_data->{$path_field_to_use}));

        unlink($new_upload_path."/".$object_data->{$path_field_to_use});
    }
}