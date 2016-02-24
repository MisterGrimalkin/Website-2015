<!DOCTYPE html>
<html data-ng-app="bmapp" >

<?php
    set_include_path(get_include_path().PATH_SEPARATOR.$_SERVER["DOCUMENT_ROOT"]."/includes");
    include("header.php");
?>

<body>

<div id="container" data-ng-controller="bmcontroller" >

    <script>
        angular.module("bmapp", []).controller("bmcontroller", buildController);
    </script>

    <div id="robotFriendlyLinks" style="display: none;">
        <?php
            include("common.php");
            $conn = openConnection();
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
                id="logo" src="images2/barrimason-logo.gif"
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
             data-ng-src="images2/{{name}}.gif"
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
        <img src="" data-ng-src="images2/pic-{{side}}.png" width="300" height="400">
    </aside>

    <iframe data-ng-repeat="(side, name) in names"
            id="content-{{side}}"
            name="content{{side}}"
            class="content-frame"
            data-ng-src={{"content.php?section="+names[side]}}
            frameborder="0">
    </iframe>

</div>


</body>

</html>