<?php

function load_properties() {
    $properties = array();
    $propfile = fopen("config/connection.properties", "r");
    while ( !feof($propfile) ) {
        $prop = explode("=", fgets($propfile));
        $properties[trim($prop[0])] = trim($prop[1]);
    }
    fclose($propfile);
    return $properties;
}

function open_connection() {
    $properties = load_properties();
    $conn = new mysqli(
        $properties["host"],
        $properties["username"],
        $properties["password"],
        $properties["database"]
    );
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}

function redirect($target) {
    echo "
    <script>
        window.location.assign('$target');
    </script>
    ";
}

?>

