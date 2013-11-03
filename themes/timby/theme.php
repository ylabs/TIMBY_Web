<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Theme_Timby extends Theme {

    public $name = 'Timby Theme';
    public $author = 'Team Timby';
    public $author_website = 'http://www.timby.org/';
    public $website = 'http://www.timby.org/';
    public $description = 'Base theme for Timby.';
    public $version = '1.0.0';
    public $options = array(
        'api_key' => array(
            'title' => 'CartoDB API Key',
            'description'   => 'This will allow the theme to pick up the Visualizations from CartoDB',
            'default'       => 'APIKEY',
            'type'          => 'text',
            'options'       => '',
            'is_required'   => true
        ),
        'theme' => array(
            'title' => 'CartoDB default theme',
            'description'   => 'Should it be dark or light?',
            'default'       => 'dark',
            'type'          => 'radio',
            'options'       => 'dark=Dark|light=Light',
            'is_required'   => true
        ),
    );
}

/* End of file theme.php */