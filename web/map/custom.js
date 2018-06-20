////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// jQuery
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
var resizeId;
var lastModal;
var customizerEnabled = 1;
var defaultColor;
var originalNavigationCode;
var navigationIsTouchingBrand;
var responsiveNavigationTriggered = false;
// Viewport ------------------------------------------------------------------------------------------------------------

var viewport = (function() {
    var viewPorts = ['xs', 'sm', 'md', 'lg'];

    var viewPortSize = function() {
        return window.getComputedStyle(document.body, ':before').content.replace(/"/g, '');
    };

    var is = function(size) {
        if ( viewPorts.indexOf(size) == -1 ) throw "no valid viewport name given";
        return viewPortSize() == size;
    };

    var isEqualOrGreaterThan = function(size) {
        if ( viewPorts.indexOf(size) == -1 ) throw "no valid viewport name given";
        return viewPorts.indexOf(viewPortSize()) >= viewPorts.indexOf(size);
    };

    // Public API
    return {
        is: is,
        isEqualOrGreaterThan: isEqualOrGreaterThan
    }

})();


////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// On Load
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Functions
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function heroSectionHeight(){

    if( $(".hero-section").length > 0 ){
        if( viewport.is('xs') ){
            $(".map-wrapper").height( $(window).height() - 25 );
            $(".hero-section").height( $(".hero-section .map-wrapper").height() +  $(".hero-section .search-form").height() + $(".hero-section .results").height() + 40 );
            $(".has-background").css( "min-height", $(window).height() - $("#page-header").height() + "px" );
        }
        else {
            if( $("body").hasClass("navigation-fixed") ){
                $(".hero-section.full-screen").height( $(window).height() - $("#page-header nav").height() );
                $(".hero-section .map-wrapper").css( "height", "100%" );
            }
            else {
                $(".hero-section.full-screen").height( $(window).height() - $("#page-header").height() );
                $(".hero-section .map-wrapper").css( "height", "100%" );
                if( $(".map-wrapper").length > 0 ){
                    reloadMap();
                }
            }
        }
        if( !viewport.is('xs') ){
            $(".search-form.vertical").css( "top", ($(".hero-section").height()/2) - ($(".search-form .wrapper").height()/2) );
        }
    }

}

//  Transfer "img" into CSS background-image

function bgTransfer(){
    //disable-on-mobile
    if( viewport.is('xs') ){

    }
    $(".bg-transfer").each(function() {
        $(this).css("background-image", "url("+ $(this).find("img").attr("src") +")" );
    });
}

function ratingPassive(element){
    $(element).find(".rating-passive").each(function() {
        for( var i = 0; i <  5; i++ ){
            if( i < $(this).attr("data-rating") ){
                $(this).find(".stars").append("<figure class='active fa fa-star'></figure>")
            }
            else {
                $(this).find(".stars").append("<figure class='fa fa-star'></figure>")
            }
        }
    });
}

function socialShare(){
    var socialButtonsEnabled = 1;
    if ( socialButtonsEnabled == 1 ){
        $('head').append( $('<link rel="stylesheet" type="text/css">').attr('href', 'assets/css/jssocials.css') );
        $('head').append( $('<link rel="stylesheet" type="text/css">').attr('href', 'assets/css/jssocials-theme-minima.css') );
        // $.getScript( "assets/js/jssocials.min.js", function( data, textStatus, jqxhr ) {
        //     $(".social-share").jsSocials({
        //         shares: ["twitter", "facebook", "googleplus", "linkedin", "pinterest"]
        //     });
        // });
    }
}

function initializeFitVids(){
    if ($(".video").length > 0) {
        $(".video").fitVids();
    }
}

function initializeOwl(){
    if( $(".owl-carousel").length ){
        $(".owl-carousel").each(function() {

            var items = parseInt( $(this).attr("data-owl-items"), 10);
            if( !items ) items = 1;

            var nav = parseInt( $(this).attr("data-owl-nav"), 2);
            if( !nav ) nav = 0;

            var dots = parseInt( $(this).attr("data-owl-dots"), 2);
            if( !dots ) dots = 0;

            var center = parseInt( $(this).attr("data-owl-center"), 2);
            if( !center ) center = 0;

            var loop = parseInt( $(this).attr("data-owl-loop"), 2);
            if( !loop ) loop = 0;

            var margin = parseInt( $(this).attr("data-owl-margin"), 2);
            if( !margin ) margin = 0;

            var autoWidth = parseInt( $(this).attr("data-owl-auto-width"), 2);
            if( !autoWidth ) autoWidth = 0;

            var navContainer = $(this).attr("data-owl-nav-container");
            if( !navContainer ) navContainer = 0;

            var autoplay = $(this).attr("data-owl-autoplay");
            if( !autoplay ) autoplay = 0;

            var fadeOut = $(this).attr("data-owl-fadeout");
            if( !fadeOut ) fadeOut = 0;
            else fadeOut = "fadeOut";

            if( $("body").hasClass("rtl") ) var rtl = true;
            else rtl = false;

            $(this).owlCarousel({
                navContainer: navContainer,
                animateOut: fadeOut,
                autoplaySpeed: 2000,
                autoplay: autoplay,
                autoheight: 1,
                center: center,
                loop: loop,
                margin: margin,
                autoWidth: autoWidth,
                items: items,
                nav: nav,
                dots: dots,
                autoHeight: true,
                rtl: rtl,
                navText: []
            });

            if( $(this).find(".owl-item").length == 1 ){
                $(this).find(".owl-nav").css( { "opacity": 0,"pointer-events": "none"} );
            }

        });
    }
}

function trackpadScroll(method) {
    if( method == "initialize" ){
        if( $(".results-wrapper").find("form").length ) {
            if( !viewport.is('xs') ){
                $(".results-wrapper .results").height( $(".results-wrapper").height() - $(".results-wrapper .form")[0].clientHeight );
            }
        }
    }
    else if ( method == "recalculate" ){
        setTimeout(function(){
            if( $(".tse-scrollable").length ){
                $(".tse-scrollable").TrackpadScrollEmulator("recalculate");
            }
        }, 5000);
    }
}

// Do after resize

function doneResizing(){
    var $equalHeight = $('.container');
    for( var i=0; i<$equalHeight.length; i++ ){
        equalHeight( $equalHeight );
    }
    responsiveNavigation();
    heroSectionHeight();
}

// Responsive Navigation

function responsiveNavigation(){

    if( viewport.is('xs') ){

        $("body").addClass("nav-btn-only");

        if( $("body").hasClass("nav-btn-only") && responsiveNavigationTriggered == false ){
            responsiveNavigationTriggered = true;
            $(".primary-nav .has-child").children("a").attr("data-toggle", "collapse");
            $(".primary-nav .has-child").find(".nav-wrapper").addClass("collapse");
            $(".mega-menu .heading").each(function(e) {
                $(this).wrap("<a href='" + "#mega-menu-collapse-" + e + "'></a>");
                $(this).parent().attr("data-toggle", "collapse");
                $(this).parent().addClass("has-child");
                $(this).parent().attr("aria-controls", "mega-menu-collapse-"+e);
            });
            $(".mega-menu ul").each(function(e) {
                $(this).attr("id", "mega-menu-collapse-"+e);
                $(this).addClass("collapse");
            });
        }
    }
    else {
        navigationIsTouchingBrand = false;
        responsiveNavigationTriggered = false;
        $("body").removeClass("nav-btn-only");
        $(".primary-nav").html("");
        $(".primary-nav").html(originalNavigationCode);
    }
}

function equalHeight(container){
    if( !viewport.is('xs') ){
        var currentTallest = 0,
            currentRowStart = 0,
            rowDivs = new Array(),
            $el,
            topPosition = 0;

        $(container).find('.equal-height').each(function() {
            $el = $(this);
            //var marginBottom = $el.css("margin-bottom").replace("px", "");
            //console.log( $el.css("margin-bottom").replace("px", "") );
            $($el).height('auto');
            topPostion = $el.position().top;
            if (currentRowStart != topPostion) {
                for (currentDiv = 0 ; currentDiv < rowDivs.length ; currentDiv++) {
                    rowDivs[currentDiv].height(currentTallest);
                }
                rowDivs.length = 0; // empty the array
                currentRowStart = topPostion;
                currentTallest = $el.height();
                rowDivs.push($el);
            } else {
                rowDivs.push($el);
                currentTallest = (currentTallest < $el.height()) ? ($el.height()) : (currentTallest);
            }
            for (currentDiv = 0 ; currentDiv < rowDivs.length ; currentDiv++) {
                rowDivs[currentDiv].height(currentTallest);
            }
        });
    }
}

// Rating --------------------------------------------------------------------------------------------------------------

function rating(element){
    var ratingElement =
            '<span class="stars">'+
                '<i class="fa fa-star s1" data-score="1"></i>'+
                '<i class="fa fa-star s2" data-score="2"></i>'+
                '<i class="fa fa-star s3" data-score="3"></i>'+
                '<i class="fa fa-star s4" data-score="4"></i>'+
                '<i class="fa fa-star s5" data-score="5"></i>'+
                '<i class="fa fa-star s6" data-score="6"></i>'+
                '<i class="fa fa-star s7" data-score="7"></i>'+
                '<i class="fa fa-star s8" data-score="8"></i>'+
                '<i class="fa fa-star s9" data-score="9"></i>'+
                '<i class="fa fa-star s10" data-score="10"></i>'+
                '</span>'
        ;
    if( !element ) { element = ''; }
    $.each( $(element + ' .star-rating'), function(i) {
        $(this).append(ratingElement);
        if( $(this).hasClass('active') ){
            $(this).append('<input readonly hidden="" name="score_' + $(this).attr('data-name') +'" id="score_' + $(this).attr('data-name') +'">');
        }
        // If rating exists
        var rating = $(this).attr('data-rating');
        for( var e = 0; e < rating; e++ ){
            var rate = e+1;
            console.log("a");
            $(this).children('.stars').children( '.s' + rate ).addClass('active');
        }
    });

    var ratingActive = $('.star-rating.active i');

    ratingActive.mouseenter(function() {
        for( var i=0; i<$(this).attr('data-score'); i++ ){
            var a = i+1;
            $(this).parent().children('.s'+a).addClass('hover');
        }
    })
    .mouseleave(function() {
        for( var i=0; i<$(this).attr('data-score'); i++ ){
            var a = i+1;
            $(this).parent().children('.s'+a).removeClass('hover');
        }
    });

    ratingActive.on('click', function(){
        $(this).parents(".star-rating").find("input").val( $(this).attr('data-score') );
        $(this).parent().children('.fa').removeClass('active');
        for( var i=0; i<$(this).attr('data-score'); i++ ){
            var a = i+1;
            $(this).parent().children('.s'+a).addClass('active');
        }
        return false;
    });
}

// Read more -----------------------------------------------------------------------------------------------------------

function initializeReadMore(){

    $.ajax({
        type: "GET",
        url: "assets/js/readmore.min.js",
        success: readMoreCallBack,
        dataType: "script",
        cache: true
    });

    function readMoreCallBack(){
        var collapseHeight;
        var $readMore = $(".read-more");
        if( $readMore.attr("data-collapse-height") ){
            collapseHeight =  parseInt( $readMore.attr("data-collapse-height"), 10 );
        }else {
            collapseHeight = 55;
        }
        $readMore.readmore({
            speed: 500,
            collapsedHeight: collapseHeight,
            blockCSS: 'display: inline-block; width: auto; min-width: 120px;',
            moreLink: '<a href="#" class="btn btn-primary btn-xs btn-light-frame btn-framed btn-rounded">More<i class="icon_plus"></i></a>',
            lessLink: '<a href="#" class="btn btn-primary btn-xs btn-light-frame btn-framed btn-rounded">Less<i class="icon_minus-06"></i></a>'
        });
    }
}

function fixedNavigation(state){
    if( state == true ){
        $("body").addClass("navigation-fixed");
        var headerHeight = $("#page-header").height();
        $("#page-header").css("position", "fixed");
        $("#page-content").css({
            "-webkit-transform" : "translateY(" + headerHeight + "px)",
            "-moz-transform"    : "translateY(" + headerHeight + "px)",
            "-ms-transform"     : "translateY(" + headerHeight + "px)",
            "-o-transform"      : "translateY(" + headerHeight + "px)",
            "transform"         : "translateY(" + headerHeight + "px)"
        });
    }
    else if( state == false ) {
        $("body").removeClass("navigation-fixed");
        $("#page-header").css("position", "relative");
        $("#page-content").css({
            "-webkit-transform" : "translateY(0px)",
            "-moz-transform"    : "translateY(0px)",
            "-ms-transform"     : "translateY(0px)",
            "-o-transform"      : "translateY(0px)",
            "transform"         : "translateY(0px)"
        });
    }
}

//  Show element after desired time ------------------------------------------------------------------------------------

if( !viewport.is('xs') ){
    var messagesArray = [];
    $("[data-toggle=popover]").popover({
        template: '<div class="popover" role="tooltip"><div class="close"><i class="fa fa-close"></i></div><div class="arrow"></div><h3 class="popover-title"></h3><div class="popover-content"></div></div>'
    });
    $(".popover .close").live('click',function () {
        $(this).closest(".popover").popover("hide");
    });
    $("[data-show-after-time]").each(function() {
        var _this = $(this);
        setTimeout(function(){
            if( _this.attr("data-toggle") == "popover" ){
                _this.popover("show");
            }
            else {
                for( var i=0; i < messagesArray.length; i++ ){
                    $(messagesArray[i]).css("bottom", parseInt( $(messagesArray[i]).css("bottom") ) + _this.context.clientHeight + 10 );
                }
                messagesArray.push(_this);
                _this.addClass("show");
                if( _this.attr("data-close-after-time") ){
                    setTimeout(function(){
                        closeThis();
                    }, _this.attr("data-close-after-time") );
                }
            }
        }, _this.attr("data-show-after-time") );
        $(this).find(".close").on("click",function () {
            closeThis();
        });
        function closeThis(){
            _this.removeClass("show");
            setTimeout(function(){
                _this.remove();
            }, 400 );
        }
    });

}

//  Show element when scrolled desired amount of pixels ----------------------------------------------------------------

$("[data-show-after-scroll]").each(function() {
    var _this = $(this);
    var scroll = _this.attr("data-show-after-scroll");
    var offsetTop = $(this).offset().top;
    $(window).scroll(function() {
        var currentScroll = $(window).scrollTop();
        if (currentScroll >= scroll) {
            _this.addClass("show");
        }
        else {
            _this.removeClass("show");
        }
    });
});