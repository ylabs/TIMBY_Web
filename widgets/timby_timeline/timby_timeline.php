<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Widget_timby_timeline extends Widgets {

    public $title = 'TIMBY Timeline Control Widget';
    public $description =  'Creates the TIMBY timeline control.';
    public $author = 'Team TIMBY';
    public $website = 'http://timby.com/';
    public $version = '1.0';

    const min_time_statement = "SELECT MIN(item_date) as item_date FROM dashboard";
    const max_time_statement = "SELECT MAX(item_date) as item_date FROM dashboard";

    public $fields = array(
        array(
            'field'   => 'num_ticks',
            'label'   => 'Number of ticks',
            'rules'   => 'required'
        )
    );

    public function run($options)
    {
        $this->load->library('timby/cartodb');
        $number_of_ticks = intval($options['num_ticks']);

        $cartodb_user_name = Settings::get('cartodb_user_name');
        $cartodb_api_key = Settings::get('cartodb_api_key');

        $min_date_time = json_decode($this->cartodb->call_sql_api($cartodb_user_name,
            $cartodb_api_key, self::min_time_statement));

        $max_date_time = json_decode($this->cartodb->call_sql_api($cartodb_user_name,
            $cartodb_api_key, self::max_time_statement));

        // May come in handy (someday):
        // $min_date_date = new DateTime($min_date_time->rows[0]->item_date);
        // $max_date_date = new DateTime($max_date_time->rows[0]->item_date);
        // $difference = $min_date_date->diff($max_date_date);

        $min_date_timestamp = strtotime($min_date_time->rows[0]->item_date);
        $max_time_timestamp = strtotime($max_date_time->rows[0]->item_date);
        $date_diff = $max_time_timestamp - $min_date_timestamp;

        $total_number_of_days = ceil($date_diff/ (60 * 60 * 24));

        if($number_of_ticks > $total_number_of_days)
            $number_of_ticks = $total_number_of_days;

        if($number_of_ticks > 0)
            $step_interval = ceil($total_number_of_days / $number_of_ticks);
        else
            $step_interval = 0;

        return array (
            'number_of_ticks' => $number_of_ticks,
            'total_number_of_days' => $total_number_of_days,
            'min_date_timestamp' => $min_date_timestamp,
            'max_date_timestamp' => $max_time_timestamp,
            'step_interval' => $step_interval,
        );
    }

    public function save($options)
    {
        return $options;
    }
} 