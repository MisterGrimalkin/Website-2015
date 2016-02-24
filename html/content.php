<html>

<?php include("header.php");?>

<body>

<?php

    include("common.php");
    build();

    function parentTitle($title) {
        return wrap("script",[],"parent.document.title='$title';");
    }

    function backLink($section = "") {
        $href = "index.php";
        if ( $section != "" ) {
            $href .= "?section=$section&storyid=menu";
        }
        $img = wrapStandalone("img", ["src"=>"images2/back-small.jpg"], true);
        $anchor = wrap("a", ["id"=>"back-$section", "href"=>$href, "target"=>"_parent", "title"=>"Return to Menu (Shortcut Key: 0)"], indent($img), false, true);
        return wrap("nav", ["class"=>"backlink"], indent($anchor), false, true);
    }

    function content($content, $extraMargin = false) {
        return section($content, "content", ($extraMargin ? "margin-left: 20px;" : ""));
    }

    function sectionIndex($section) {

        $result = "";
        $navStarted = false;

        $conn = openConnection();
        $sql = "SELECT * FROM Story WHERE UPPER(section) = '" . strtoupper($section) . "' ORDER BY rank";
        $query_result = $conn->query($sql);
        $conn->close();

        if ( $query_result->num_rows > 0 ) {
            while( $story = $query_result->fetch_assoc() ) {

                if ( $story["rank"]==0 ) {

                    $result .= article($story["content"]);

                } else {

                    if ( !$navStarted ) {
                        $result .= wrapStart("nav", ["id"=>"nav-$section", "class"=>"subnav"]);
                        $navStarted = true;
                    }

                    $result .= pageLink($story);

                }

            }
        } else {

            $result .= wrap("h1", [], "No Content Found", true);

        }

        if ( $navStarted ) {
            $result .= wrapEnd("nav");
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

        return wrap("a", ["id"=>$elem_id, "href"=>$href, "target"=>"_parent", "title"=>"$title (Shortcut Key: $rank)"],
            indent(div("", "subnavlink", "background-image: url(\"$image_src\")")));

    }

    function build() {

        $section = @$_GET["section"];
        $storyid = @$_GET["storyid"];

        if ( !$section ) {

            header("Location: index.php");

        } else {
            if ( !$storyid ) {

                echo content(sectionIndex($section), true);

            } else {

                $conn = openConnection();
                $sql = "SELECT * FROM Story WHERE story_id=$storyid";
                $result = $conn->query($sql);
                $conn->close();

                $story = $result->fetch_assoc();

                if ( strtoupper($section) == strtoupper($story["section"]) ) {

                    echo parentTitle("Barri Mason - {$story['title']}") . backLink($section) . content(article($story["content"]));

                } else {

                    echo content(sectionIndex($section), true);

                }

            }
        }
    }

?>

</body>

</html>
