<?php

class site_request {

    public $visitor_ip_info = array();
    public $country_code = '';
    public $base_server_name = 'https://www.creativebug.com';
    public $local_uri = '';

    public function get_ip_visitor_data() {

        $client  = @$_SERVER['HTTP_CLIENT_IP'];
        $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
        $remote  = $_SERVER['REMOTE_ADDR'];
        $country  = "Unknown";
    
        if (filter_var($client, FILTER_VALIDATE_IP)) {
            $ip = $client;
        }
        else if (filter_var($forward, FILTER_VALIDATE_IP)) {
            $ip = $forward;
        }
        else {
            $ip = $remote;
        }

        if ($_REQUEST['debug']) {
            // An elegant way to test using theoretical IP from different countries by overriding the derived IP address in cases of machines using localhost instead of a proper IP address
            switch ($_REQUEST['debug']) {
                case 'uk': 
                    $ip = '51.155.34.25'; // Example UK (GB) IP address
                    break;
                case 'ar': 
                    $ip = '181.10.25.25 ';  // Example Argentina (AR) IP address
                    break;
                default: $ip = '192.122.209.42'; // Example US IP address
            }
        }

        // Call API with derived IP address and get country and other data back.
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "http://www.geoplugin.net/json.gp?ip=".$ip);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        $ip_data_in = curl_exec($ch); // string
        curl_close($ch);
    
        $ip_data = json_decode($ip_data_in,true);
        $ip_data = str_replace('&quot;', '"', $ip_data);
        
        $this->visitor_ip_info = $ip_data; // Set $visitor_ip_info var with returned data
        
        return 0;
    }

    public function set_country_code() {
        if ($this->visitor_ip_info['geoplugin_countryCode']) {
            $this->country_code = $this->visitor_ip_info['geoplugin_countryCode'];
        }
        return 0;
    }

    public function set_url() {
        // This is where we build the URL based on the derived country code. 
        //TODO: We could add a switch statement here or a hashtable to route countries to servers if we wanted to send groups of counties to larger area servers if we dont have a server for each country, as the above assumes.
        
        if ($this->visitor_ip_info['geoplugin_countryCode'] && strtoupper($this->visitor_ip_info['geoplugin_countryCode']) !== 'US') {
            $this->base_server_name = 'https://' . strtolower($this->country_code) . '.creativebug.com';
        } else if (strtoupper($this->visitor_ip_info['geoplugin_countryCode']) === 'US'){
            // Leave default unchanged. This evaluation is here to preserve the default else option. A switch statement could be used for this block instead.
        } else {
            // We could also send this to default www.creativebug.com if you prefer
            return '<p>Country code not found, user request is: ' . $domain = $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'] . '</p>';
        }
        return 0;
    }

    public function redirect() {
        // A little Javascript is needed here as we are redirecting the request after output is written.
        $redir = '<script type="text/javascript">';
        $redir .= 'window.location = "' . $this->base_server_name . '"';
        $redir .= '</script>';
        echo $redir;
        die();
    }

    public function showdata() {
        $showdata = var_dump($_REQUEST);
        $showdata = var_dump ($this->visitor_ip_info);
        return $showdata;
    }

    public function block_proxys() {
        $proxy_headers = array(
            'HTTP_VIA',
            'HTTP_X_FORWARDED_FOR',
            'HTTP_FORWARDED_FOR',
            'HTTP_X_FORWARDED',
            'HTTP_FORWARDED',
            'HTTP_CLIENT_IP',
            'HTTP_FORWARDED_FOR_IP',
            'VIA',
            'X_FORWARDED_FOR',
            'FORWARDED_FOR',
            'X_FORWARDED',
            'FORWARDED',
            'CLIENT_IP',
            'FORWARDED_FOR_IP',
            'HTTP_PROXY_CONNECTION'
        );
        foreach($proxy_headers as $x){
            if (isset($_SERVER[$x])) die("You are using a proxy!");
        }
    }
}
?>