function onLoad() {

    preloadImages();

    // Get side from URL
    var page = getQueryString().page;
    if ( page ) {
        onSelect(page);
        navigateTo(page);
    }

}

function getName(side) {
    var name;
    if ( side==="left" ) {
        name = "musician";
    }
    if ( side==="right" ) {
        name = "coder";
    }
    return name;
}


// Animations and Rollovers

function triggerLogoAnimation() {
    document.getElementById("logo").src = "images/barrimason-logo-animation.gif";
}

function cancelLogoAnimation() {
    document.getElementById("logo").src = "images/barrimason-logo.gif";
}

function onOver(side) {
    if ( side!==currentSide ) {
        document.getElementById("link-" + side).src = "images/" + getName(side) + "-over.gif";
    }
}

function onOut(side) {
    if ( side!==currentSide ) {
        document.getElementById("link-" + side).src = "images/" + getName(side) + "-out.gif";
    }
}

function onSelect(side) {
    if ( side!==currentSide ) {
        document.getElementById("link-" + side).src = "images/" + getName(side) + "-selected.gif";
    }
}


// Navigation

var currentSide = "";

function navigateTo(side) {

    var leftContentStyle = "content-frame inactive";
    var rightContentStyle = "content-frame inactive";

    var leftLinkStyle = "navlink left";
    var rightLinkStyle = "navlink right";

    var leftPicStyle = "sidebar left";
    var rightPicStyle = "sidebar right";

    if ( currentSide!=="" ) {
        document.getElementById("link-" + currentSide).src = "images/" + getName(currentSide) + "-out.gif";
    }

    if ( side!==currentSide ) {
        if ( side==="left") {
            leftLinkStyle = "navlink left active";
            leftPicStyle = "sidebar left active";
            leftContentStyle = "content-frame active left";
        }
        if ( side==="right" ) {
            rightLinkStyle = "navlink right active";
            rightPicStyle = "sidebar right active";
            rightContentStyle = "content-frame active right";
        }
    } else {
        side = "";
    }

    if ( side==="left" ) {
        document.getElementById("navbar").className = "navbar active";
        document.getElementById("link-left").style.opacity = 1;
        document.getElementById("link-right").style.opacity = 0.2;
    }
    else if ( side==="right" ) {
        document.getElementById("navbar").className = "navbar active";
        document.getElementById("link-left").style.opacity = 0.2;
        document.getElementById("link-right").style.opacity = 1;
    } else {
        document.getElementById("navbar").className = "navbar";
        document.getElementById("link-left").style.opacity = 1;
        document.getElementById("link-right").style.opacity = 1;
    }

    document.getElementById("content-left").className = leftContentStyle;
    document.getElementById("content-right").className = rightContentStyle;
    document.getElementById("link-left").className = leftLinkStyle;
    document.getElementById("link-right").className = rightLinkStyle;
    document.getElementById("pic-left").className = leftPicStyle;
    document.getElementById("pic-right").className = rightPicStyle;

    currentSide = side;

}


// Preload

function preloadImages() {
	if (document.images) {

        loadImage("images/barrimason-logo-animation.gif");

        loadImage("images/musician.gif");
        loadImage("images/musician-selected.gif");
        loadImage("images/musician-over.gif");
        loadImage("images/musician-out.gif");

        loadImage("images/coder.gif");
        loadImage("images/coder-selected.gif");
        loadImage("images/coder-over.gif");
        loadImage("images/coder-out.gif");

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

// Cookies

function getCookie(name) {
    var all = document.cookie;
    var pairs = all.split(";");
    for ( var i=0; i<pairs.length; i++ ) {
        var c = pairs[i].split("=");
         if ( c[0]===name ) {
            return c[1];
         }
    }
    return null;
}

function setCookie(name, value) {
    var newCookie = "";
    var found = false;
    var all = document.cookie;
    var pairs = all.split(";");
    if ( all.indexOf(name)!==-1 ) {
        for ( var i=0; i<pairs.length; i++ ) {
             var c = pairs[i].split("=");
             if ( c[0]===name ) {
                newCookie = addWithSemiColon(newCookie, c[0]+"="+value);
                found = true;
             } else {
                newCookie = addWithSemiColon(newCookie, c[0]+"="+c[1]);
             }
        }
    }
    if ( !found ) {
        newCookie = addWithSemiColon(newCookie, name+"="+value);
    }

    document.cookie = newCookie;
}

function addWithSemiColon(allCookies, newPair) {
    var newCookie = allCookies;
    if ( newCookie==="" ) {
        newCookie = newPair;
    } else {
        newCookie = newCookie + ";" + newPair;
    }
    return newCookie;
}


// Utility

function bindFunction(f, i) {
    return function() {
        f(i);
    }
}

