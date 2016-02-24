<?php

//////////////
// Database //
//////////////

function loadProperties() {
    $properties = array();
    $filename = $_SERVER['DOCUMENT_ROOT']."/../config/connection.properties";
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

function openConnection() {
    try {
        $properties = loadProperties();
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


/////////
// DOM //
/////////

function div($content, $class="", $style="") {
    if ( $content ) {
        return wrap("div", asAttributes($class, $style), $content, false, true);
    } else {
        return wrap("div", asAttributes($class, $style), $content, true, true);
    }
}

function section($content, $class="", $style="") {
    return wrap("section", asAttributes($class, $style), $content);
}

function article($content, $class="", $style="") {
    return wrap("article", asAttributes($class, $style), $content);
}

function wrap($element, $attributes, $content, $inline=false, $tight=false) {
    $extras = compileAttributes($attributes);
    $i = $inline ? "" : "\n";
    $t = $tight ? "" : "\n";
    return "$t<$element$extras>$i$content$i</$element>$t";
}

function wrapStandalone($element, $attributes = [], $tight=false) {
    $extras = compileAttributes($attributes);
    $t = $tight ? "" : "\n";
    return "$t<$element$extras />$t";
}

function wrapStart($element, $attributes = []) {
    $extras = compileAttributes($attributes);
    return "\n<$element$extras>\n";
}

function wrapEnd($element) {
    return "\n</$element>\n";
}

function indent($content, $chars=4) {
    $indent = str_repeat(" ", $chars);
    return $indent . str_replace("\n", "\n$indent", $content);
}

function compileAttributes($attributes) {
    $extras = "";
    foreach ( $attributes as $name=>$value ) {
        $extras .= " $name='$value'";
    }
    return $extras;
}

function asAttributes($class, $style) {
    $result = [];
    if ( $class ) {
        $result["class"] = $class;
    }
    if ( $style) {
        $result["style"] = $style;
    }
    return $result;
}