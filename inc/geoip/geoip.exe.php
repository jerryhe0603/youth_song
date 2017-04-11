<?php
    /**/
    function return_country_code($address)
    {
        include("./inc/geoip/geoip.inc");
        $gi = geoip_open("./inc/geoip/geoip.dat",GEOIP_STANDARD);
        $country_name = geoip_country_code_by_addr($gi, $address);
        geoip_close($gi);
        return $country_name;
    }

    function return_country_name($address)
    {
        include("./inc/geoip/geoip.inc");
        $gi = geoip_open("./inc/geoip/geoip.dat",GEOIP_STANDARD);
        $country_name = geoip_country_name_by_addr($gi, $address);
        geoip_close($gi);
        return $country_name;
    }
?>