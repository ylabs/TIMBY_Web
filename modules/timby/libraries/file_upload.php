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
        ci()->load->library('timby/api_handlers');
    }

    /**
     * Find the extraction folder
     *
     * @return string
     */
    private function find_extract_folder($parameters = array())
    {
        return dirname(__FILE__)."/../../../../../".self::extract_folder;
    }

    /**
     * Find the zip folder
     *
     * @return string
     */
    private function find_zip_folder($parameters = array())
    {
        return dirname(__FILE__)."/../../../../../uploads/default/files";
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
                            $company = $report->company;
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
                                'company' => $company,
                                'user_id' => $user_id,
                                'lat' => $lat,
                                'long' => $long,
                                'report_date' => $report_date,
                            );

                            ci()->api_hanlers->create_report($report_api_array);

                            foreach($report_objects as $report_object)
                            {
                                $item_title = trim($report_object->object_title);
                                $item_type = trim($report_object->object_type);

                                switch($item_type)
                                {
                                    case "image/jpeg":
                                    case "video/3gpp":
                                    case "audio/mpeg3":
                                        $item_media = $report_object->object_media;
                                        break;
                                    case "text/html":
                                        $item_narrative = $report_object->object_text;
                                        break;
                                }

                                // Handle the file upload
                            }
                        }
                    }
                }
            }
        }
    }

    /**
     * Get all zip files and process them
     */
    public function get_all_files($parameters = array())
    {
        ci()->load->library('files/files');

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

        $this->process_unzipped_files($extraction_folder, $parameters);
    }
} 