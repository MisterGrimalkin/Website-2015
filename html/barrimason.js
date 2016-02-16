
////////////////////////
// Angular Controller //
//  & Initialisation  //
////////////////////////

function buildController($scope) {

    $scope.names = names;
    $scope.navigateTo = navigateTo;
    $scope.rollover = rollover;

    preloadImages();

    $(function() {

        adjustForOrientation(true);
        $(window).bind("orientationchange", function(e){
            adjustForOrientation(false);
        });

        initShortcuts();
        $(window).keydown(processShortcut);

        var section = getQueryString().section;
        var side = getSide(section);
        var page = getQueryString().storyid;

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

/////////////////
// Orientation //
/////////////////

function adjustForOrientation(instant) {
    if ( Math.abs(window.orientation)==90 ) {
        $("#logobar").attr("class", "logo "+(instant?"instant":"")+"hidden")
        $("#content-left").css("height", "115%"); //hacky!
        $("#content-right").css("height", "115%"); //hacky!
    } else {
        $("#logobar").attr("class", "logo");
        $("#content-left").css("height", "100%");
        $("#content-right").css("height", "100%");
    }
}

////////////////////////
// Keyboard Shortcuts //
////////////////////////

var shortcuts = {};

function initShortcuts() {
    registerShortcut(37, function(){ navigateTo("left"); });        // CURSOR LEFT
    registerShortcut(39, function(){ navigateTo("right"); });       // CURSOR RIGHT

    registerShortcut(33, function(){ scrollContentBy(-300); });     // PAGE UP
    registerShortcut(38, function(){ scrollContentBy( -40); });     // CURSOR UP
    registerShortcut(40, function(){ scrollContentBy(  40); });     // CURSOR DOWN
    registerShortcut(34, function(){ scrollContentBy( 300); });     // PAGE DOWN

    registerShortcut(36, function(){ scrollContentTop(); });        // HOME
    registerShortcut(35, function(){ scrollContentBottom(); });     // END

    for ( var i=0; i<10; i++ ) {
        registerShortcut(48+i, followPageLink(i));                  // TOP NUMBER KEYS
        registerShortcut(96+i, followPageLink(i));                  // NUMERICAL PAD KEYS
    }
}

function registerShortcut(key, func) {
    shortcuts[key] = func;
}

function processShortcut(e) {
    func = shortcuts[e.which];
//    console.log(e.which);
    if ( func ) {
        func();
    }
}

function followPageLink(n) {
    return function() {
        var frame = $("#content-"+currentSide);
        if ( frame ) {
            var target = (n==0? "back-"+names[currentSide] : "pagelink-"+names[currentSide]+"-"+n);
            var link = frame.contents().find("body #"+target).get();
            if ( link[0] ) {
                window.location.assign(link[0].href);
            }
        }
    }
}

///////////////
// Scrolling //
///////////////

function scrollContentTop() {
    $("#content-"+currentSide).contents().find(".content").scrollTop(0);
}

function scrollContentBy(n) {
    $("#content-"+currentSide).contents().find(".content").scrollTop(
        $("#content-"+currentSide).contents().find(".content").get(0).scrollTop + n
    );
}

function scrollContentBottom() {
    $("#content-"+currentSide).contents().find(".content").scrollTop(10000);  // bit hacky
}

//////////////////////////////
// Animations and Rollovers //
//////////////////////////////

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

////////////////
// Navigation //
////////////////

var currentSide = "";

function openPage(side, storyid) {
    if ( storyid!=="menu" ) {
        $("#content-"+side).attr("src", "content.php?section="+names[side]+"&storyid="+storyid);
    }
}

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

    if ( instant ) {
        $("#link-"+side).attr("src", "images/" + names[side] + "-active.gif");
    } else {
        $("#link-"+side).attr("src", "images/" + names[side] + "-selected.gif");
    }

    var active = ( instant ? "instant" : "" ) + "active";

    if ( side!==currentSide ) {
        if ( side==="left") {
            leftLinkStyle = "navlink left " + active;
            leftPicStyle = "sidebar left " + active;
            leftContentStyle = "content-frame active left";
        } else if ( side==="right" ) {
            rightLinkStyle = "navlink right " + active;
            rightPicStyle = "sidebar right " + active;
            rightContentStyle = "content-frame active right";
        }
    } else {
        $("#link-"+side).attr("src", "images/" + names[side] + "-out.gif");
        side = "";
    }

    if ( side==="left" ) {
        $("#navbar").attr("class", "navbar " + active);
        $("#link-left").css("opacity", 1);
        $("#link-right").css("opacity", 0.2);
    }
    else if ( side==="right" ) {
        $("#navbar").attr("class", "navbar " + active);
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

////////////////
// Side Names //
////////////////

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

/////////////
// Preload //
/////////////

function preloadImages() {

	if (document.images) {

        loadImage("images/barrimason-logo-animation.gif");

        for ( side in names ) {
            loadImage("images/"+names[side]+".gif");
            loadImage("images/"+names[side]+"-active.gif");
            loadImage("images/"+names[side]+"-selected.gif");
            loadImage("images/"+names[side]+"-over.gif");
            loadImage("images/"+names[side]+"-out.gif");
        }

        loadImage("images/back-small.jpg");

	}

}

function loadImage(url) {
    img = new Image();
    img.src = url;
    return img;
}

////////////////////
// URL Parameters //
////////////////////

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
