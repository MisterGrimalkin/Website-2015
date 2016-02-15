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

    include("common.php");

    function subNav($content) {
        return "<nav id='section' class='subnav'>$content</nav>";
    }

    function backLink($section = "") {
        $href = "index.php";
        if ( $section != "" ) {
            $href .= "?section=$section&storyid=menu";
        }
        return "
            <nav class='backlink'>
                <a id='back-$section' href='$href' target='_parent' title='Return to Menu (Shortcut: 0)'>
                    <img src='images/back-small.jpg' />
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
        $result = "
            <nav id='nav-$section' class='subnav'>
        ";


        $shortcuts = array();

        $conn = open_connection();

        $sql = "SELECT * FROM Story WHERE UPPER(section) = '" . strtoupper($section) . "' ORDER BY rank";
        $query_result = $conn->query($sql);
        if ($query_result->num_rows > 0) {
            while($story = $query_result->fetch_assoc()) {
                $result .= pageLink($story);
            }
        } else {
            $result .= "No Stories Found<br>";
        }

        $conn->close();
        $result .= "</nav>";

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
            <a id='$elem_id' href='$href' target='_parent' title='$title (Shortcut: $rank)'>
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
