<html>
<head lang="en">

    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta charset="UTF-8">
    <meta name="robots" content="index, follow">
    <base target="_blank">

    <title>Barri Mason</title>

    <link rel="stylesheet" type="text/css" href="barrimason.css">

    <script src="barrimason.js"></script>
    <script src="http://ajax.googleapis.com/ajax/libs/angularjs/1.4.8/angular.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>

</head>

<body>

<?php

    include("common.php");
    build();


    function backLink($section = "") {
        $href = "index.php";
        if ( $section != "" ) {
            $href .= "?section=$section&storyid=menu";
        }
        return "
            <nav class='backlink'>
                <a id='back-$section' href='$href' target='_parent' title='Return to Menu (Shortcut Key: 0)'>
                    <img src='images/back-small.jpg' />
                </a>
            </nav>
        ";
    }

    function content($content, $extramargin = false) {
        $result = "\n<section class='content'";
        if ( $extramargin ) {
            $result .= " style='margin-left: 20px;'";
        }
        $result .= ">\n$content\n</section>\n";
        return $result;
    }

    function sectionIndex($section) {

        $result = "";
        $nav_started = false;

        $conn = open_connection("config");
        $sql = "SELECT * FROM Story WHERE UPPER(section) = '" . strtoupper($section) . "' ORDER BY rank";
        $query_result = $conn->query($sql);
        $conn->close();

        if ($query_result->num_rows > 0) {
            while($story = $query_result->fetch_assoc()) {

                if ( $story["rank"]==0 ) {

                    $result .= "\n<article>\n".$story["content"]."\n</article>\n";

                } else {

                    if ( !$nav_started ) {
                        $result .= "\n<nav id='nav-$section' class='subnav'>\n";
                        $nav_started = true;
                    }

                    $result .= pageLink($story);

                }

            }
        } else {

            $result .= "\n<h1>No Content Found</h1>\n";

        }

        if ( $nav_started ) {
            $result .= "\n</nav>\n";
        }

        return $result;
    }

    function pageLink($story) {

        $storyid = $story["story_id"];
        $section = strtolower($story["section"]);
        $title = $story["title"];
        $image_src = $story["image"];
        $rank = $story["rank"];

        $href = "index.php?section=$section&storyid=$storyid";

        $elem_id = "pagelink-$section-$rank";

        return "
            <a id='$elem_id' href='$href' target='_parent' title='$title (Shortcut Key: $rank)'>
                <div class='subnavlink' style='background-image: url(\"$image_src\");'></div>
            </a>
        ";
    }

    function build() {
        $section = $_GET["section"];
        $storyid = $_GET["storyid"];

        if ( $section=="" ) {

            redirect("index.php");

        } else {
            if ( !$storyid ) {

                echo content(sectionIndex($section), true);

            } else {

                $conn = open_connection("config");
                $sql = "SELECT * FROM Story WHERE story_id=" . $storyid;
                $result = $conn->query($sql);
                $conn->close();

                $story = $result->fetch_assoc();

                if ( strtoupper($section) == strtoupper($story["section"]) ) {

                    echo backLink($section) . content($story["content"]);

                } else {

                    echo content(sectionIndex($section), true);

                }

            }
        }
    }

?>


</body>

</html>
