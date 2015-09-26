var PAGE_NAMES = "musician,writer,coder";

function onLoad() {

//    setupPages(PAGE_NAMES);

//    preloadImages();

    // Link mouse interaction
//    repeatForAllPages(function(i,n) {
//        var link = document.getElementById("link"+i);
//        link.onclick= bindFunction(displayPage, i);
//        link.onmouseover = bindFunction(navMouseover, i);
//        link.onmouseout = bindFunction(navMouseout, i);
//        link.onmousedown = navMousedown;
//    });

    var timeout = 0;
    var pageToLoad = 0;

    // Get page from URL or from Cookie
    var page = getQueryString().page;
    if ( !page ) {
        page = getCookie("page");
    }

    if ( page ) {
        onSelect(page);
        navigateTo(page);
        timeout = 2000;

//        // Specified page, no fade-in
//        document.getElementById("logo").className = "logo";
//        repeatForAllPages(function(i,n) {
//            var img = document.getElementById("linkImage"+i);
//            img.src="images/"+n+".gif";
//            img.style.display = "inline";
//        });
//        pageToLoad = getPageNumber(page);

    } else {

        // Root page, fade-in
//        document.getElementById("logo").className = "logo-fadein";
//        repeatForAllPages(function(i,n) {
//            var img = document.getElementById("linkImage"+i);
//            img.src="images/"+n+"-fadein.gif";
//            img.style.display = "inline";
//        });
//	    timeout = 5000;

    }

    // Load content and open specified page
    setTimeout(function() {
        preloadContent();
//        if ( pages[pageToLoad] ) {
//            displayPage(pageToLoad);
//        }
    }, timeout);

}

function triggerLogoAnimation() {
    document.getElementById("logo").src = "images/barrimason-logo-fadein.gif";
}

function cancelLogoAnimation() {
    document.getElementById("logo").src = "images/barrimason-logo.gif";
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

var currentSide = "";

function navigateTo(side) {

    var leftContentStyle = "content-frame inactive";
    var rightContentStyle = "content-frame inactive";

    var leftPicStyle = "sidebar left";
    var rightPicStyle = "sidebar right";

    var leftLinkStyle = "navlink left";
    var rightLinkStyle = "navlink right";


    if ( side!==currentSide ) {
        if ( currentSide!=="" ) {
            document.getElementById("link-" + currentSide).src = "images/" + getName(currentSide) + "-out.gif";
        }
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
    if ( side==="" ) {
        document.getElementById("navbar").className = "navbar";
    } else {
        document.getElementById("navbar").className = "navbar active";
    }

    document.getElementById("content-left").className = leftContentStyle;
    document.getElementById("content-right").className = rightContentStyle;

    document.getElementById("link-left").className = leftLinkStyle;
    document.getElementById("link-right").className = rightLinkStyle;

    document.getElementById("pic-left").className = leftPicStyle;
    document.getElementById("pic-right").className = rightPicStyle;

    currentSide = side;
}


// Content Pages

var pages = {};
var pageCount = 0;
var selectedPage = 0;

function displayPage(i) {
    if ( openInNewTab ) {
        openInNewTab = false;
        if ( pages[i] ) {
            window.open("/" + pages[i])
        }
    } else {
        preloadContent();
        if ( pages[i] && i!==selectedPage ) {
            if ( pages[selectedPage] ) {
                document.getElementById("content"+selectedPage).className = "content-frame";
                document.getElementById("linkImage"+selectedPage).src = "images/"+pages[selectedPage]+"-out.gif";
            }
            document.getElementById("content"+i).className = "content-frame active";
            document.getElementById("linkImage"+i).src = "images/"+pages[i]+"-selected.gif";
            selectedPage = i;
            setCookie("page",pages[selectedPage]);
        }
    }
}


function getPageNumber(name) {
    for (var property in pages) {
        if (pages.hasOwnProperty(property)) {
            if ( pages[property[0]] === name ) {
                return property[0];
            }
        }
    }
    return 0;
}

function setupPages(names) {
    var nameArray = names.split(",");
    for ( var i=0; i<nameArray.length; i++ ) {
        pages[i+1] = nameArray[i];
        pageCount++;
    }
}


// Link Rollovers

function navMouseover(i) {
    if ( i != selectedPage ) {
        document.getElementById("linkImage"+i).src = "images/" + pages[i] + "-over.gif";
    }
}

function navMouseout(i) {
    if ( i != selectedPage ) {
        document.getElementById("linkImage"+i).src = "images/" + pages[i] + "-out.gif";
    }
}


// CTRL+click feature

var openInNewTab = false;

function navMousedown(e) {
    if ( e.ctrlKey ) {
        openInNewTab = true;
    }
}


// Preload

var contentLoaded = false;

function preloadImages() {
	if (document.images) {
	    repeatForAllPages(function(i,n) {
		    loadImage("images/" + n + ".gif");
		    loadImage("images/" + n + "-selected.gif");
		    loadImage("images/" + n + "-over.gif");
		    loadImage("images/" + n + "-out.gif");
	    });
	}
}

function preloadContent() {
    if ( !contentLoaded ) {
//	    repeatForAllPages(function(i,n) {
//            document.getElementById("content"+i).src = n+".html";
//        });
        document.getElementById("content-left").src="musician.html";
        document.getElementById("content-right").src="coder.html";

        contentLoaded = true;
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

function repeatForAllPages(f) {
    for ( var i=1; i<=pageCount; i++ ) {
        f(i, pages[i]);
    }
}
