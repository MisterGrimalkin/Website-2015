<?php

function load_properties($path) {
    $properties = array();
    $filename = $path."/connection.properties";
    if ( file_exists($filename) ) {
        $propfile = fopen($filename, "r");
        while ( !feof($propfile) ) {
            $prop = explode("=", fgets($propfile));
            $properties[trim($prop[0])] = trim($prop[1]);
        }
        fclose($propfile);
    } else {
        throw new Exception("Could not open connection properties file");
    }
    return $properties;
}

function open_connection($path) {
    try {
        $properties = load_properties($path);
        $conn = new mysqli(
            $properties["host"],
            $properties["username"],
            $properties["password"],
            $properties["database"]
        );
        if ($conn->connect_error) {
            throw new Exception("Database connection failed: " . $conn->connect_error);
        }
    } catch ( Exception $e ) {
        error_log($e->getMessage());
        die("<i><h1>Database Connection Failed!</h1>ERROR-CODE: 54321 (Server Responded: 'I really cannot deal with this sh*t right now')</i>");
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

