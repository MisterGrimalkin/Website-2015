<html>
<head lang="en">

    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta charset="UTF-8">
    <meta name="robots" content="index, follow">
    <base target="_blank">

    <title>Barri Mason</title>

    <link rel="stylesheet" type="text/css" href="barrimason.css">

    <script src="http://ajax.googleapis.com/ajax/libs/angularjs/1.4.8/angular.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
    <script src="barrimason.js"></script>

</head>

<body>

<?php

    function open_connection() {
        $host = "barrimason.com";
        $database = "MAS003_A";
        $username = "barrimason";
        $password = "#X5mdp13";

        $conn = new mysqli($host, $username, $password, $database);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        return $conn;
    }

    function mainIndex() {
        return "
        <a href='content.php?section=musician' target='_self'>Musician</a>
        <a href='content.php?section=coder' target='_self'>Coder</a>
        ";
    }

    function subNav($content) {
        return "<nav class='subnav'>$content</nav>";
    }

    function pageLink($story) {
        $section = strtolower($story["section"]);
        $id = $story["story_id"];
        $href = "index.php?section=$section&storyid=$id";
        $image_src = $story["image"];
        return "
        <a href='$href' target='_parent' accesskey='$accesskey'>
            <div class='subnavlink' style='background-image: url(\"$image_src\");'>
            </div>
        </a>
        ";
    }

    function pageLink_old($section, $page, $accesskey) {
        $href = "index.php?section=$section&page=$page";
        $image_src = "../images/$section"."_$page.jpg";
        return "
        <a href='$href' target='_parent' accesskey='$accesskey'>
            <div class='subnavlink' style='background-image: url(\"$image_src\");'>
            </div>
        </a>
        ";
    }

    function backLink($section = "") {
        $href = "index.php";
        if ( $section != "" ) {
            $href .= "?section=$section&storyid=menu";
        }
        return "
            <nav class='backlink'>
                <a href='$href' target='_parent'>
                    <img src='images/back.jpg'/>
                </a>
            </nav>
        ";
    }

    function content($content, $extramargin = false) {
        $result = "<div class='content'";
        if ( $extramargin ) {
            $result .= " style='margin-left: 20px;'";
        }
        $result .=">$content</div>";
        return $result;
    }

    function sectionIndex($section) {
        $result = "<nav class='subnav'>";

        $conn = open_connection();

        $sql = "SELECT * FROM Story WHERE UPPER(section) = '" . strtoupper($section) . "' ORDER BY rank";
        $query_result = $conn->query($sql);
        if ($query_result->num_rows > 0) {
            while($row = $query_result->fetch_assoc()) {
                $result .= pageLink($row);
            }
        } else {
            $result .= "No Stories Found<br>";
        }

        $conn->close();
        return $result."</nav>";
    }

    function sectionIndex_old($section) {
        $result = "<nav class='subnav'>";
        $files = getFilenames("content");
        $ak = 1;
        foreach ($files as $file) {
            $name = explode(".", $file)[0];
            $bits = explode("_", $name);
            if ( count($bits)>1 ) {
                $file_section = $bits[0];
                if ( $file_section==$section ) {
                    $file_page = $bits[1];
                    $result .= pageLink($file_section, $file_page, substr($file_page, 0, 1));
                }
            }
        }
        return $result."</nav>";
    }

    function getFilenames($dir) {
        $result = array();
        $iter = new DirectoryIterator($dir);
        foreach ($iter as $file) {
            if (!$file->isDot()) {
                $name = explode(".", $file)[0];
                $result[] = $name;
            }
        }
        asort($result);
        return $result;
    }

    function build() {
        $section = $_GET["section"];
        $storyid = $_GET["storyid"];

        if ( $section=="" ) {
            echo mainIndex();
        } else {
            if ( !$storyid ) {
                echo content(
                    file_get_contents("content/$section.html")
                    .
                    sectionIndex($section)
                , true);
            } else {
                $conn = open_connection();

                $sql = "SELECT * FROM Story WHERE story_id=" . $storyid;

                $result = $conn->query($sql);

                $story = $result->fetch_assoc();
                $content = $story["content"];
                if ( strtoupper($section) == strtoupper($story["section"]) ) {
                    echo
                        backLink($section)
                        .
                        content($content);
                } else {
                    echo content(
                    file_get_contents("content/$section.html")
                    .
                    sectionIndex($section)
                    , true);
                }

                $conn->close();
            }
        }
    }

    build();

?>


</body>

</html>
