<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8"/>
        <title>Max Perez</title>
    </head>
    <body>

        <?php 
        require './include.php';

        $sr = new site_request(); 
        $sr->get_ip_visitor_data(); // Get country and other info about user from their IP address.
        $sr->set_country_code(); // We could call this directly from get_ip_visitor_data, but this is more transparent for this demo.
        
        // Block redirects and VPN
        if ($_REQUEST['block_proxys']) {
            $sr->block_proxys(); 
        }

        $url_status = $sr->set_url(); // restrict and redirect user to that country coded server.

        // Add "redirect=true" to url to see the user actually get routed the (fictional) country specific server site.
        if ($_REQUEST['redirect']) {
            $sr->redirect();
        }

        // Display some output to show visitors from different countries being redirected to local servers.
        echo "<br><hr>Visitor IP addr: " . $sr->visitor_ip_info['geoplugin_request'];
        echo "<br>Country code derived from IP addr: " . $sr->country_code;
        echo "<br>Redirect to: " . $sr->base_server_name;
        echo '<p>url is: ' . $domain = $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'] . '</p>';
        if ($url_status) {
            echo $url_status;
        }
        echo '<hr>';

        //Add "showdata=true" to url to see additional underlying visitor IP information.
        if ($_REQUEST['showdata']) {
            echo '<pre>';
            $sr->showdata();
            echo '</pre>';
        }
        ?>
 
    </body>
</html>
