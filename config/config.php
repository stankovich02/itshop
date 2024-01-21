<?php
define("SEPARTOR", "&");

// Podesavanja za bazu
define("SERVER", env("SERVER"));
define("DATABASE", env("DATABASE"));
define("USERNAME", env("USERNAME"));
define("PASSWORD", env("PASSWORD"));


function env($name)
{
    // $podaci = explode("\n",file_get_contents(BASE_URL."/config/.env"));
    $open = fopen("/storage/ssd2/063/20839063/public_html/config/.env", "r");
    $data = file("/storage/ssd2/063/20839063/public_html/config/.env");
    $Value = "";
    foreach ($data as $key => $value) {
        $config = explode("=", $value);
        if ($config[0] == $name) {
            $Value = trim($config[1]); // trim() zbog \n
        }
    }
    return $Value;
}
