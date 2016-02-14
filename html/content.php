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

    function mainIndex() {
        return "
        <a href='content.php?section=musician' target='_self'>Musician</a>
        <a href='content.php?section=coder' target='_self'>Coder</a>
        ";
    }

    function subNav($content) {
        return "<nav class='subnav'>$content</nav>";
    }

    function pageLink($section, $page) {
        $href = "index.php?section=$section&page=$page";
        $image_src = "../images/$section"."_$page.jpg";
        return "
        <a href='$href' target='_parent'>
            <div class='subnavlink' style='background-image: url(\"$image_src\");'>
            </div>
        </a>
        ";
    }

    function backLink($section = "") {
        $href = "index.php";
        if ( $section != "" ) {
            $href .= "?section=$section&page=menu";
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
        $files = getFilenames("content");
        foreach ($files as $file) {
            $name = explode(".", $file)[0];
            $bits = explode("_", $name);
            if ( count($bits)>1 ) {
                $file_section = $bits[0];
                if ( $file_section==$section ) {
                    $file_page = $bits[1];
                    $result .= pageLink($file_section, $file_page);
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


    $section = $_GET["section"];
    $page = $_GET["page"];

    if ( $section=="" ) {
        echo mainIndex();
    } else {
        if ( $page=="" ) {
            echo content(
                file_get_contents("content/$section.html")
                .
                sectionIndex($section)
            , true);
        } else {
            echo
                backLink($section)
                .
                content(file_get_contents("content/$section"."_$page.html"));
        }
    }
?>


</body>

</html>
