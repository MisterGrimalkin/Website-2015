<!DOCTYPE html>
<html data-ng-app="bmapp" >

<head lang="en">

    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta charset="UTF-8">
    <meta name="robots" content="index, follow">
    <base target="_blank">

    <title>Barri Mason</title>

    <link rel="stylesheet" type="text/css" href="barrimason.css">
    <link rel="shortcut icon" href="bm.ico">

    <script src="http://ajax.googleapis.com/ajax/libs/angularjs/1.4.8/angular.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
    <script src="barrimason.js"></script>

</head>

<body>

<div data-ng-controller="bmcontroller" >

    <script>
        angular.module("bmapp", []).controller("bmcontroller", buildController);
    </script>

    <header class="logo">
        <img
                id="logo" src="images/barrimason-logo.gif"
                onclick="navigateTo('');"
                onmouseover="triggerLogoAnimation();"
                onmouseout="cancelLogoAnimation();"
                height="80">
    </header>

    <nav class="navbar" id="navbar" >
        <img data-ng-repeat="(side, name) in names"
             id={{link+side}}
             class={{navlink+side}}
             src=""
             data-ng-src={{images+name+gif}}
             data-ng-mouseenter="rollover(side, 'over');"
             data-ng-mouseleave="rollover(side, 'out');"
             data-ng-mousedown="rollover(side, 'selected');"
             data-ng-click="navigateTo(side);"
             border="0" alt="">
    </nav>

    <div data-ng-repeat="(side, name) in names"
         id={{pic+side}}
         class={{sidebar+side}}>
        <img src="" data-ng-src={{images+pic+side+png}}>
    </div>

    <iframe data-ng-repeat="(side, name) in names"
            id={{content+side}}
            name={{content+side}}
            class="content-frame"
            data-ng-src={{contentphp+names[side]}}
            frameborder="0">
    </iframe>

</div>

</body>

</html>