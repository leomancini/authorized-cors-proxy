<?php
    // Disable PHP output buffering to allow returning of long responses
    ob_end_clean();
    ob_implicit_flush();
    
    function fetch($url) {
        // Based on https://stackoverflow.com/a/6518125

        $options = array(
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER => false,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_ENCODING => "",
            CURLOPT_AUTOREFERER => true,
            CURLOPT_CONNECTTIMEOUT => 120,
            CURLOPT_TIMEOUT => 120,
        ); 

        $curl = curl_init($url);
        curl_setopt_array($curl, $options);
        $response = curl_exec($curl);
        curl_close($curl);

        return $response;
    }
    
    $authorizedKeysFile = file_get_contents('./authorized-keys.json');
    $authorizedKeys = json_decode($authorizedKeysFile, true);

    $authorizedKeyMatchFound = false;

    foreach ($authorizedKeys['authorized-keys'] as $authorizedKeysKey => $authorizedKeyData) {
        if ($_GET['key'] === $authorizedKeyData['key']) {
            $authorizedKeyMatchFound = true;
        }
    }

    if ($authorizedKeyMatchFound) {
        header("Access-Control-Allow-Origin: *");

        $response = fetch($_GET['url']);
        
        echo $response;
    } else {
        echo "Please provide valid access key!";
    }
?>