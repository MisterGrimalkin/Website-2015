<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Test</title>

    <link rel="stylesheet" type="text/css" href="../barrimason.css">

</head>
<body>

<?php

    $host = "barrimason.com";
    $database = "MAS003_A";
    $username = "barrimason";
    $password = "#X5mdp13";

    $conn = new mysqli($host, $username, $password, $database);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $storyId = $_GET["storyid"];

    if ( !$storyId ) {
        $sql = "SELECT * FROM Story ORDER BY section";
        $result = $conn->query($sql);
        $section = "";
        echo "<ul>";
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                if ( $section != $row["section"] ) {
                    if ( $section != "" ) {
                        echo "</ul>";
                    }
                    $section = $row["section"];
                    echo "<li>$section</li><ul>";
                }
                $id = $row["story_id"];
                $title = $row["title"];
                echo "
                    <li><a href='index.php?storyid=$id'>$title</a></li>
                ";
            }
        }
        echo "</ul>";
    } else {
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