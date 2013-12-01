<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Handles the API events
 */
class API_Handlers
{

    const type_narrative = 0;
    const type_image = 1;
    const type_video = 2;

    public function __construct()
    {
        // Models
        ci()->load->model('timby/categories_m');
        ci()->load->model('timby/sectors_m');
        ci()->load->model('timby/reports_m');
    }

    private function verify_report_owner($report_id, $user_id)
    {
        $result = ci()->reports_m->find_by(array('id' => $report_id, 'user_id' => $user_id));

        return $result;
    }

    public function get_categories()
    {
        return ci()->categories_m->find_all();
    }

    public function get_sectors()
    {
        return ci()->sectors_m->find_all();
    }

    public function create_report($post_data)
    {
        unset($post_data["approved"]);
        unset($post_data["token"]);
        unset($post_data["key"]);

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
        unset($post_data["token"]);
        unset($post_data["key"]);

        if($this->verify_report_owner($post_data["report_id"], $post_data["user_id"]))
        {
            unset($post_data["report_id"]);

            $status = ci()->reports_m->update($report_id, $post_data);

            if($status != false)
            {
                return array("status" => $status);
            }
        }

        return false;
    }

    public function delete_report($post_data)
    {
        unset($post_data["token"]);
        unset($post_data["key"]);
        $soft_delete = Settings::get('report_soft_delete') == "true" ? true : false;

        if($this->verify_report_owner($post_data["report_id"], $post_data["user_id"]))
        {
            $status = ci()->reports_m->delete($post_data["report_id"], $soft_delete);

            if($status)
            {
                return array("status" => $status);
            }
        }

        return false;
    }

    public function insert_report_object($upload_path, $post_data, $parameters = array())
    {
        // We may upload heavy objects
        set_time_limit(0);

        unset($post_data["token"]);
        unset($post_data["key"]);

        // Verify if owner
        if(!$this->verify_report_owner($post_data["report_id"], $post_data["user_id"]))
            return false;

        // Upload configuration
        $config = array();
        $config['allowed_types'] = 'gif|jpg|png|mp3|mp4';
        $config['max_size']	= 0;
        $config['max_width']  = 0;
        $config['max_height']  = 0;

        $sequence_number = $post_data["sequence"];
        $object_type = $post_data["object_type"];
        $report_id = $post_data["report_id"];
        $narrative = $post_data["narrative"];
        $title = $post_data["title"];

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
                $object_type = self::type_narrative;
                break;
            case "video":
                $config['upload_path'] = rtrim($upload_path, "/")."/default/timby/videos";
                $table_to_use = "videos";
                $path_field_to_use = "video_path";
                $field_to_use = "video";
                $object_type = self::type_video;
                break;
            case "image":
                $config['upload_path'] = rtrim($upload_path, "/")."/default/timby/images";
                $table_to_use = "images";
                $path_field_to_use = "image_path";
                $field_to_use = "image";
                $object_type = self::type_image;
                break;
            default:
                return false;
                break;
        }

        $upload_data = null;

        if($do_upload)
        {
            if(!isset($parameters["file_path"]))
            {
                ci()->load->library('upload', $config);

                if (ci()->upload->do_upload())
                {
                    $upload_data = ci()->upload->data();
                }
                else
                {
                    return ci()->upload->display_errors();
                }
            }
            else
            {
                if(!copy($parameters["file_path"], rtrim(realpath($config['upload_path']), "/")."/".$parameters["file_name"]))
                {
                    return array("message" => "Copy not successful");
                }

                $upload_data["file_name"] = rtrim(realpath($config['upload_path']), "/")."/".$parameters["file_name"];
            }
        }

        if(isset($upload_data["file_name"]))
        {
            $path_info = pathinfo($upload_data["file_name"]);
            $file_path = realpath($upload_data["file_name"]);

            if(rename($file_path, $path_info['dirname']."/".$report_id."_".$path_info['basename']))
                $upload_data["file_name"] = $report_id."_".$path_info['basename'];
        }

        // Post file upload event trigger
        Events::trigger('media_uploaded', array("file_name" => $config['upload_path']."/".$upload_data["file_name"]));

        $object_id = false;

        if($path_field_to_use != "")
        {
            $object_id = ci()->reports_m->{$table_to_use}()->insert(
                array(
                    "{$path_field_to_use}" => $upload_data["file_name"],
                    "{$field_to_use}" => json_encode(array("original_file" => $upload_data["file_name"])),
                    "title" => $title,
                    "report_id" => $report_id,
                )
            );
        }
        else
        {
            $object_id = ci()->reports_m->{$table_to_use}()->insert(
                array(
                    "{$field_to_use}" => $narrative,
                    "title" => $title,
                    "report_id" => $report_id,
                )
            );
        }

        if(!$object_id)
        {
            return false;
        }

        $sequence_result = ci()->reports_m->sequence()->insert(array(
            "sequence" => $sequence_number,
            "report_id" => $report_id,
            "item_type" => $object_type,
            "item_id" => $object_id,
        ));

        return array("object_id" => $object_id, "sequence_id" => $sequence_result);
    }

    public function update_report_object($upload_path, $post_data)
    {
        // We may upload heavy objects
        set_time_limit(0);

        unset($post_data["token"]);
        unset($post_data["key"]);

        // Upload configuration
        $config = array();
        $config['allowed_types'] = 'gif|jpg|png|mp3|mp4';
        $config['max_size']	= 0;
        $config['max_width']  = 0;
        $config['max_height']  = 0;

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
                $object_type = self::type_narrative;
                break;
            case "video":
                $config['upload_path'] = rtrim($upload_path, "/")."/default/timby/videos";
                $table_to_use = "videos";
                $path_field_to_use = "video_path";
                $field_to_use = "video";
                $object_type = self::type_video;
                break;
            case "image":
                $config['upload_path'] = rtrim($upload_path, "/")."/default/timby/images";
                $table_to_use = "images";
                $path_field_to_use = "image_path";
                $field_to_use = "image";
                $object_type = self::type_image;
                break;
            default:
                return false;
                break;
        }

        // Get the old object (To delete if necessary)

        $object_data = ci()->reports_m->{$table_to_use}()->find($object_id);

        // Verify report owner
        if(!$this->verify_report_owner($object_data->report_id, $post_data["user_id"]))
            return false;

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
                $old_object_id,
                array(
                    "{$path_field_to_use}" => $upload_data["file_name"],
                    "{$field_to_use}" => json_encode(array("original_file" => $upload_data["file_name"])),
                    "report_id" => $report_id,
                )
            );
        }
        else
        {
            $object_id = ci()->reports_m->{$table_to_use}()->update(
                $old_object_id,
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
    }

    public function delete_report_object($upload_path, $post_data)
    {
        unset($post_data["token"]);
        unset($post_data["key"]);

        $soft_delete = Settings::get('report_soft_delete') == "true" ? true : false;

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

        // Verify report owner
        if(!$this->verify_report_owner($object_data->report_id, $post_data["user_id"]))
            return false;

        if($path_field_to_use !== "")
            Events::trigger('pre_media_deleted', array("file_name" => $new_upload_path."/".$object_data->{$path_field_to_use}));

        // Do actual delete

        if(!$soft_delete)
            ci()->reports_m->sequence()->delete_object($post_data, $object_id);

        ci()->reports_m->{$table_to_use}()->delete($object_id, $soft_delete);

        if(!$soft_delete)
            if($path_field_to_use !== "")
                unlink($new_upload_path."/".$object_data->{$path_field_to_use});
    }
}