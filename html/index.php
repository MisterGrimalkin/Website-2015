<!DOCTYPE html>
<html data-ng-app="bmapp" >

<head lang="en">

    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta charset="UTF-8">
    <meta name="robots" content="index, follow">
    <base target="_blank">

    <title>Barri Mason</title>

    <link rel="stylesheet" type="text/css" href="barrimason.css">
    <link rel="shortcut icon" href="images/bm.ico">

    <script src="barrimason.js"></script>
    <script src="http://ajax.googleapis.com/ajax/libs/angularjs/1.4.8/angular.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>

</head>

<body>

<div id="container" data-ng-controller="bmcontroller" >

    <script>
        angular.module("bmapp", []).controller("bmcontroller", buildController);
    </script>

    <div id="robotFriendlyLinks" style="display: none;">
        <?php
            include("common.php");
            $conn = open_connection("config");
            $sql = "SELECT * FROM Story ORDER BY section, rank";
            $query_result = $conn->query($sql);
            $conn->close();
            if ($query_result->num_rows > 0) {
                while($story = $query_result->fetch_assoc()) {
                    $section = strtolower($story["section"]);
                    $story_id = $story["story_id"];
                    $title = $story["title"];
                    echo "\n<a href='content.php?section=$section&storyid=$story_id'>$title</a>\n";
                }
            }
        ?>
    </div>

    <header id="logobar" class="logo">
        <img
                id="logo" src="images/barrimason-logo.gif"
                onclick="navigateTo('');"
                onmouseover="triggerLogoAnimation();"
                onmouseout="cancelLogoAnimation();"
                height="80">
    </header>

    <nav class="navbar" id="navbar" >
        <img data-ng-repeat="(side, name) in names"
             id="link-{{side}}"
             class="navlink {{side}}"
             src=""
             data-ng-src="images/{{name}}.gif"
             data-ng-mouseenter="rollover(side, 'over');"
             data-ng-mouseleave="rollover(side, 'out');"
             data-ng-mousedown="rollover(side, 'selected');"
             data-ng-click="navigateTo(side);"
             title="Shortcut Key: {{side}} cursor"
             border="0" alt="">
    </nav>

    <aside data-ng-repeat="(side, name) in names"
         id="pic-{{side}}"
         class="sidebar {{side}}">
        <img src="" data-ng-src="images/pic-{{side}}.png" width="300" height="400">
    </aside>

    <iframe data-ng-repeat="(side, name) in names"
            id="content-{{side}}"
            name="content-{{side}}"
            class="content-frame"
            data-ng-src={{"content.php?section="+names[side]}}
            frameborder="0">
    </iframe>

</div>


</body>

</html>