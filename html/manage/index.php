<html>
<head lang="en">

    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta charset="UTF-8">
    <meta name="robots" content="index, follow">
    <base target="_blank">

    <title>Barri Mason</title>

    <link rel="stylesheet" type="text/css" href="../barrimason.css">

    <script src="http://ajax.googleapis.com/ajax/libs/angularjs/1.4.8/angular.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
    <script src="../barrimason.js"></script>

</head>

<body>

<?php

    function buildMainIndex() {
        echo "
        <a href='index.php?section=musician' target='_self'>Musician</a>
        <a href='index.php?section=coder' target='_self'>Coder</a>
        ";
    }

    function startSubNav() {
        echo "<nav class='subnav'>";
    }

    function endSubNav() {
        echo "</nav>";
    }

    function buildSubNavLink($section, $page) {
        $image_src = "../images/$section"."_$page.jpg";
        echo "
        <a href='index.php?section=$section&page=$page' target='_self'>
            <div class='subnavlink' style='background-image: url(\"$image_src\");'>
            </div>
        </a>
        ";
    }

    function buildBackButton($section = "") {
        $href = "index.php";
        if ( $section != "" ) {
            $href .= "?section=$section";
        }
        echo "
        <div class='content'>
            <h2>
                <a href='$href' target='_self'>
                    <img src='../images/back.jpg'/>
                </a>
            </h2>
        ";
    }


    $section = $_GET["section"];
    if ( $section=="" ) {
        buildMainIndex();
    } else {
        $page = $_GET["page"];
        if ( $page=="" ) {
            buildBackButton();
            startSubNav();
            $dir = new DirectoryIterator("../content");
            foreach ($dir as $file) {
                if (!$file->isDot()) {
                    $name = explode(".", $file)[0];
                    $bits = explode("_", $name);
                    if ( count($bits)>1 ) {
                        $file_section = $bits[0];
                        if ( $file_section==$section ) {
                            $file_page = $bits[1];
                            buildSubNavLink($file_section, $file_page);
                        }
                    }
                }
            }
            endSubNav();
        } else {
            buildBackButton($section);
            require("../content/$section"."_$page.html");
            echo "</div>";
        }
    }
?>


</body>

</html>
