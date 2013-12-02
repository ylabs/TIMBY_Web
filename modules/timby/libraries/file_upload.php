<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Handles File upload operations (Zipped files uploaded)
 */

class File_upload {

    const upload_folder = "Zip files";
    const extract_folder = "uploads/extract";

    /**
     * Constructor
     */
    public function __construct()
    {
        // For API operations
        ci()->load->library('timby/api_handlers');
        // For file processing from the file manager
        ci()->load->library('files/files');
    }

    /**
     * Find the extraction folder
     *
     * @param array $parameters
     * @return string
     */
    private function find_extract_folder($parameters = array())
    {
        return dirname(__FILE__)."/../../../../../".self::extract_folder;
    }

    /**
     * Find the zip folder
     *
     * @param array $parameters
     * @return string
     */
    private function find_zip_folder($parameters = array())
    {
        return dirname(__FILE__)."/../../../../../uploads/default/files";
    }

    /**
     * Locates the upload folder
     *
     * @param array $parameters
     * @return string
     */

    private function find_upload_folder($parameters = array())
    {
        return dirname(UPLOAD_PATH);
    }

    /**
     * Recursive remove directory
     *
     * @param $dir
     */
    private function rrmdir($dir) {
        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object != "." && $object != "..") {
                    if (filetype($dir."/".$object) == "dir")
                        $this->rrmdir($dir."/".$object);
                    else unlink   ($dir."/".$object);
                }
            }

            reset($objects);
            rmdir($dir);
        }
    }

    /**
     * Clear extraction folder
     *
     * @return bool
     */
    private function clear_extract_folder($parameters = array())
    {
        $extract_folder = $this->find_extract_folder();

        if(is_dir($extract_folder))
        {
            $objects = scandir($extract_folder);

            foreach($objects as $object)
            {
                if(is_dir($extract_folder."/".$object))
                    if($object != "." && $object != "..")
                        $this->rrmdir($extract_folder."/".$object);
                else if(is_file($extract_folder."/".$object))
                    if($object != "." && $object != "..")
                        unlink($extract_folder."/".$object);
            }

            return true;
        }
        else
        {
            // Create it
            mkdir($extract_folder);
            return true;
        }
    }

    /**
     * Process a folder with unzipped items
     *
     * @param $extraction_folder
     * @param array $parameters
     * @return bool
     */
    private function process_unzipped_files($extraction_folder, $parameters = array())
    {
        $objects = scandir($extraction_folder);

        foreach($objects as $object)
        {
            if(is_dir($extraction_folder."/".$object))
            {
                $folder_path = $extraction_folder."/".$object;
                $folder_objects = scandir($folder_path);

                foreach($folder_objects as $folder_object)
                {
                    if($folder_object == "db.xml")
                    {
                        $xml_content = file_get_contents($folder_path."/".$folder_object);
                        $xml_objects = simplexml_load_string($xml_content);

                        $reports = $xml_objects->report;

                        foreach($reports as $report)
                        {
                            $report_title = $report->report_title;
                            $category = $report->category;
                            $sector = $report->sector;
                            $entity = $report->entity;
                            $location = $report->location;
                            $report_objects = $report->report_objects;
                            $report_date = $report->report_date;
                            $user_id = isset($parameters["user_id"]) ? $parameters["user_id"] : 0;

                            $lat = 0;
                            $long = 0;

                            $location_array = explode(",", $location);

                            if($location_array != false)
                            {
                                $lat = trim($location_array[0]);
                                $long = trim($location_array[1]);
                            }

                            $report_api_array = array(
                                'title' => $report_title,
                                'category' => $category,
                                'sector' => $sector,
                                'entity' => $entity,
                                'user_id' => $user_id,
                                'lat' => $lat,
                                'long' => $long,
                                'report_date' => $report_date,
                            );

                            $report_id = ci()->api_handlers->create_report($report_api_array);

                            $sequence = 0;

                            foreach($report_objects->object as $report_object)
                            {
                                $item_title = trim($report_object->object_title);
                                $item_type = trim($report_object->object_type);

                                $api_media_type = "";

                                switch($item_type)
                                {
                                    case "image/jpeg":
                                        $api_media_type = "image";
                                        break;
                                    case "video/3gpp":
                                        $api_media_type = "video";
                                        break;
                                    case "audio/mpeg3":
                                        $api_media_type = "video";
                                        break;
                                    case "text/html":
                                        $api_media_type = "narrative";
                                        break;
                                    case "timby/entity":
                                        $api_media_type = "entity";
                                        break;
                                }

                                switch($item_type)
                                {
                                    case "image/jpeg":
                                    case "video/3gpp":
                                    case "audio/mpeg3":
                                        $item_media = $report_object->object_media;
                                        $file_info = pathinfo($item_media);

                                        $file_parameters = array(
                                            "sequence" => $sequence,
                                            "file_path" => $extraction_folder.$item_media,
                                            "object_type" => $api_media_type,
                                            "report_id" => $report_id["id"],
                                            "title" => $item_title,
                                            "narrative" => "",
                                            'user_id' => $user_id,
                                        );

                                        ci()->api_handlers->insert_report_object($this->find_upload_folder(), $file_parameters,
                                            array(
                                                "file_path" => $extraction_folder.'/'.$object.$item_media,
                                                "file_name" => $file_info['basename'],
                                            )
                                        );

                                        break;
                                    case "text/html":
                                    case "timby/entity":
                                        $item_narrative = $report_object->object_text;

                                        $narrative_parameters = array(
                                            "sequence" => $sequence,
                                            "file_path" => "",
                                            "file_name" => "",
                                            "object_type" => $api_media_type,
                                            "report_id" => $report_id["id"],
                                            "title" => $item_title,
                                            "narrative" => $item_narrative,
                                            'user_id' => $user_id,
                                        );

                                        ci()->api_handlers->insert_report_object($this->find_upload_folder(), $narrative_parameters);

                                        break;
                                }

                                $sequence ++;
                            }
                        }
                    }
                }
            }
        }

        return true;
    }

    private function delete_processed_files($zip_folder, $zip_files)
    {
        foreach($zip_files as $file)
        {
            ci()->files->delete_file($file->id);
        }
    }

    /**
     * Get all zip files and process them
     */
    public function get_all_files($parameters = array())
    {
        $folder_found = false;
        $folder_id = 0;

        $folder_contents = ci()->files->folder_contents();

        foreach($folder_contents["data"]["folder"] as $folder_content)
        {
            if($folder_content->name == self::upload_folder)
            {
                $folder_found = true;
                $folder_id = $folder_content->id;
            }

            if($folder_found)
            {
                break;
            }
        }

        if(!$folder_found)
        {
            // Create the folder
            $create_result = ci()->files->create_folder(0, self::upload_folder);
            $folder_id = $create_result["data"]["id"];
        }

        $zip_files = ci()->files->folder_contents($folder_id);

        $extraction_folder = $this->find_extract_folder($parameters);
        $zip_folder = $this->find_zip_folder($parameters);

        foreach($zip_files["data"]["file"] as $zip_file)
        {
            // Clear extraction folder
            $this->clear_extract_folder();

            // Extract files
            $file_path = $zip_folder."/".$zip_file->filename;
            $zip_archive = new ZipArchive;

            if($zip_archive->open($file_path) === TRUE)
            {
                $zip_archive->extractTo($extraction_folder);
                $zip_archive->close();
            }
        }

        // Process
        $this->process_unzipped_files($extraction_folder, $parameters);

        // Delete processed files
        $this->delete_processed_files($zip_folder, $zip_files["data"]["file"]);
    }
} 