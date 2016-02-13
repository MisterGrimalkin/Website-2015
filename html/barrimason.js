var names = {
    "left": "musician",
    "right": "coder"
}

function getSide(name) {
    for ( side in names ) {
        if ( names[side]==name ) {
            return side;
        }
    }
    return null;
}

function buildController($scope) {

    $scope.names = names;
    $scope.navigateTo = navigateTo;
    $scope.rollover = rollover;

    // Constants for readability
    $scope.contentphp = "content.php?section=";
    $scope.navlink = "navlink ";
    $scope.sidebar = "sidebar ";
    $scope.link = "link-";
    $scope.pic = "pic-";
    $scope.content = "content-";
    $scope.images = "images/";
    $scope.gif = ".gif";
    $scope.png = ".png";
    $scope.html = ".html";

    preloadImages();

    // Process URL parameters
    $(function() {
        var section = getQueryString().section;
        var side = getSide(section);
        var page = getQueryString().page;
        if ( side ) {
            if ( page ) {
                rollover(side, "selected");
                openPage(side, page);
                navigateTo(side, true);
            } else {
                rollover(side, "over");
                setTimeout(function() {
                    rollover(side, "selected");
                    navigateTo(side);
                }, 500);
            }
        }
    });
}


// Animations and Rollovers

function rollover(side, state) {
    if ( side!==currentSide ) {
        $("#link-"+side).attr("src", "images/"+names[side]+"-"+state+".gif");
    }
}

function triggerLogoAnimation() {
    $("#logo").attr("src", "images/barrimason-logo-animation.gif");
}

function cancelLogoAnimation() {
    $("#logo").attr("src", "images/barrimason-logo.gif");
}


// Navigation

var currentSide = "";

function navigateTo(side, instant) {

    var leftContentStyle = "content-frame inactive";
    var rightContentStyle = "content-frame inactive";

    var leftLinkStyle = "navlink left animated";
    var rightLinkStyle = "navlink right animated";

    var leftPicStyle = "sidebar left";
    var rightPicStyle = "sidebar right";

    if ( currentSide!=="" ) {
        $("#link-"+currentSide).attr("src", "images/" + names[currentSide] + "-out.gif");
    }

    if ( side!==currentSide ) {
        if ( side==="left") {
            leftLinkStyle = "navlink left " + ( instant?"instant":"") + "active";
            leftPicStyle = "sidebar left active";
            leftContentStyle = "content-frame active left";
        }
        if ( side==="right" ) {
            rightLinkStyle = "navlink right " + ( instant?"instant":"") + "active";
            rightPicStyle = "sidebar right active";
            rightContentStyle = "content-frame active right";
        }
    } else {
        side = "";
    }

    if ( side==="left" ) {
        $("#navbar").attr("class", "navbar active");
        $("#link-left").css("opacity", 1);
        $("#link-right").css("opacity", 0.2);
    }
    else if ( side==="right" ) {
        $("#navbar").attr("class", "navbar active");
        $("#link-left").css("opacity", 0.2);
        $("#link-right").css("opacity", 1);
    } else {
        $("#navbar").attr("class", "navbar");
        $("#link-left").css("opacity", 1);
        $("#link-right").css("opacity", 1);
    }

    $("#content-left").attr("class", leftContentStyle);
    $("#content-right").attr("class", rightContentStyle);
    $("#link-left").attr("class", leftLinkStyle);
    $("#link-right").attr("class", rightLinkStyle);
    $("#pic-left").attr("class", leftPicStyle);
    $("#pic-right").attr("class", rightPicStyle);

    currentSide = side;

}

function openPage(side, page) {
    if ( page!=="menu") {
        $("#content-"+side).attr("src", "content.php?section="+names[side]+"&page="+page);
    }
}


// Preload

function preloadImages() {

	if (document.images) {

        loadImage("images/barrimason-logo-animation.gif");

        for ( side in names ) {
            loadImage("images/"+names[side]+".gif");
            loadImage("images/"+names[side]+"-selected.gif");
            loadImage("images/"+names[side]+"-over.gif");
            loadImage("images/"+names[side]+"-out.gif");
        }

        loadImage("images/back.jpg");
	}

}

function loadImage(arg) {
	if (document.images) {
		rslt = new Image();
		rslt.src = arg;
		return rslt;
	}
}


// Process URL Parameters

function getQueryString() {
    var query_string = {};
    var query = window.location.search.substring(1).split('/')[0];
    var vars = query.split("&");
    for (var i=0;i<vars.length;i++) {
        var pair = vars[i].split("=");
        if (typeof query_string[pair[0]] === "undefined") {
            query_string[pair[0]] = decodeURIComponent(pair[1]);
        } else if (typeof query_string[pair[0]] === "string") {
        var arr = [ query_string[pair[0]],decodeURIComponent(pair[1]) ];
            query_string[pair[0]] = arr;
        } else {
            query_string[pair[0]].push(decodeURIComponent(pair[1]));
        }
    }
    return query_string;
}

