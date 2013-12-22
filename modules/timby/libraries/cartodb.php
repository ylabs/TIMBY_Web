<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class cartodb {

    /**
     * Construct the SQL URL
     *
     * @param $account
     * @param $api_key
     * @param $statement
     * @return mixed|string
     */

    private function construct_sql_url($account, $api_key, $statement)
    {
        $cartodb_url = "http://{account}.cartodb.com/api/v2/sql?q={statement}&api_key={api_key}";
        $cartodb_url = str_replace("{account}", $account, $cartodb_url);
        $cartodb_url = str_replace("{statement}", urlencode($statement.";"), $cartodb_url);
        $cartodb_url = str_replace("{api_key}", $api_key, $cartodb_url);

        return $cartodb_url;
    }

    /**
     * Call the SQL API
     *
     * @param $account
     * @param $api_key
     * @param $statement
     * @return mixed
     */

    public function call_sql_api($account, $api_key, $statement)
    {
        $url = $this->construct_sql_url($account, $api_key, $statement);

        $json_curl = curl_init();
        curl_setopt($json_curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($json_curl, CURLOPT_URL, $url);
        $json = curl_exec($json_curl);
        curl_close($json_curl);

        return $json;
    }
} 