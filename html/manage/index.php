<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Content Management</title>
    <link rel="stylesheet" type="text/css" href="../barrimason.css">
</head>

<body>

<?php

include("../common.php");

$conn = open_connection("../config");

$storyId = $_GET["storyid"];
if ( !$storyId ) {
    build_menu($conn);
} elseif ( strtoupper($storyId)=="NEW" ) {
    echo "NEW STORY";
} else {
    load_story($conn, $storyId);
}

$conn->close();


function build_menu($conn) {
    $sql = "SELECT * FROM Story ORDER BY section, rank";
    $result = $conn->query($sql);
    echo "<ul>";
    if ($result->num_rows > 0) {
        $section = "";
        while($row = $result->fetch_assoc()) {
            $id = $row["story_id"];
            $title = $row["title"];
            $rank = $row["rank"];
            if ( $section != $row["section"] ) {
                // New section
                if ( $section != "" ) {
                    echo "<li><a href='index.php?section=$section&storyid=new'>New...</a></li>";
                    echo "</ul>";
                }
                $section = $row["section"];
                $link = $section;
                if ( $rank==0 ) {
                    $link = "<a href='index.php?storyid=$id'>$section</a>";
                }
                echo "<li>$link</li><ul>";
            }
            if ( $rank>0 ) {
                echo "
                <li><a href='index.php?storyid=$id'>$title</a></li>
                ";
            }
        }
        if ( $section != "" ) {
            echo "<li><a href='index.php?section=$section&storyid=new'>New...</a></li>";
        }
        echo "</ul>";
    }
    echo "</ul>";
}

function load_story($conn, $storyId) {
    $sql = "SELECT * FROM Story WHERE story_id=$storyId";
    $result = $conn->query($sql);
    $text = "No Results";
    if ($result->num_rows == 1) {
        while($row = $result->fetch_assoc()) {
            $text = $row["content"];
        }
    }
    echo "<div style='overflow: auto;'>";
    echo "<div style='font-family: courier; font-size: 12px; overflow: auto; position: relative; width: calc(50% - 20px); float: left; background-color: white; color: black; padding: 10px;'>"
    . str_replace("\n", "<br>", htmlspecialchars($text))
    . "</div>";
    echo "<div style='overflow: auto; width: calc(50% - 20px); float: right; padding: 10px;'>$text</div>";
    echo "</div>";
}

?>

</body>
</html>