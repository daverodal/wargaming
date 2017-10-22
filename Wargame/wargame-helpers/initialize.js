/**
 * Created by david on 2/20/17.
 */
/**
 * Created by PhpStorm.
 * User: david
 * Date: 2/20/17
 * Time: 11:01 AM

 /*
 * Copyright 2012-2017 David Rodal

 * This program is free software; you can redistribute it
 * and/or modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation;
 * either version 2 of the License, or (at your option) any later version

 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.

 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
import {playAudio, playAudioBuzz, playAudioLow, counterClick, mapClick, doitOption, doitNext, nextPhaseMouseDown, doitKeypress, showCrtTable, fixItAll, doitSaveGame, rotateUnits, toggleFullScreen, doitCRT} from "./global-funcs";
import "jquery.panzoom";
import "jquery-ui-bundle";
export default function initialize() {
    var zoomed = window.zoomed;

    /* yuck */
    if (navigator.userAgent.match(/Android/)) {
        $('body').height($(window).height());
    }
    // setup events --------------------------------------------

//check if the image is already on cache

    $(".switch-crt").on('click', function () {
        showCrtTable(this);
    });

    DR.vintage = false;
    $("#vintageButton").click(function () {
        if (!DR.vintage) {
            $("#vintageButton").html("modern");
            $(".unit").addClass("vintage");
            DR.vintage = true;

        } else {
            $("#vintageButton").html("vintage");
            $(".unit").removeClass("vintage");
            DR.vintage = false;
        }
        $("#menuWrapper .WrapperLabel").click()
    });

    $('#crt .tableWrapper').hide();
    $('#crt .tableWrapper').first().show();
    $('#crt .switch-crt').hide();
    $('#crt .switch-crt').first().next().show();


    if ($('#image_id').prop('complete')) {
        var width = $("#gameImages #map").width();
        var height = $("#gameImages #map").height();
        $('#arrow-svg').width(width);
        $('#arrow-svg').height(height);
        $('#arrow-svg').attr('viewBox', "0 0 " + width + " " + height);
    }

    $("#map").on("load", function () {
        var width = $("#gameImages #map").width();
        var height = $("#gameImages #map").height();
        $('#arrow-svg').width(width);
        $('#arrow-svg').height(height);
        $('#arrow-svg').attr('viewBox', "0 0 " + width + " " + height);
    });

    $(".unit").on('click touchend', counterClick);


    $("#crt #odds span").on('click touchstart', function (event) {
        var col = $(event.target).attr('class');
        col = col.replace(/col/, '');
        doitCRT(col, event);
    })
    $("#gameImages").on("click", ".specialHexes", mapClick);
    $("#gameImages").on("click", "svg", mapClick);
    $("#choose-option-button").on("click", doitOption);

    $("#nextPhaseButton").on('click', nextPhaseMouseDown);
//    $("#gameImages" ).draggable({stop:mapStop, distance:15});
    $("#gameImages #map").on("click", mapClick);

    $("#header, #deadpile, #deployWrapper, #exitWrapper, #combinedWrapper").on('touchmove', function (event) {
        if ($(event.target).parents('#crt').length > 0) {
            return;
        }
        event.stopPropagation();
    });

    $("#fullScreenButton").on('click touchstart', function () {
        toggleFullScreen();
        return false;
    });

    DR.$floatMessagePanZoom = $('#floatMessage').panzoom({
        cursor: "normal", disableZoom: true, onPan: function (e, panzoom) {
            DR.floatMessageDragged = true;
        }
    });

    $("#Time").draggable();
    DR.$crtPanZoom = $('#crt').panzoom({
        cursor: "move", disableZoom: true, onPan: function (e, panzoom) {
            return;
            var xDrag;
            var yDrag;
            if (event.type === 'touchmove') {
                xDrag = Math.abs(event.touches[0].clientX - DR.clickX);
                yDrag = Math.abs(event.touches[0].clientY - DR.clickY);
                if (xDrag > 40 || yDrag > 40) {
                    // DR.dragged = true;
                }
            } else {
                xDrag = Math.abs(event.clientX - DR.clickX);
                yDrag = Math.abs(event.clientY - DR.clickY);
                if (xDrag > 4 || yDrag > 4) {
                    // DR.dragged = true;
                }
            }

            // DR.dragged = true;
        }
    });
//
//    DR.$crtPanZoom = $('#Time').panzoom({cursor: "move", disableZoom: true, onPan: function(e, panzoom){
//        var xDrag;
//        var yDrag;
//        if(event.type === 'touchmove'){
//            xDrag = Math.abs(event.touches[0].clientX - DR.clickX);
//            yDrag = Math.abs(event.touches[0].clientY - DR.clickY);
//            if(xDrag > 40 || yDrag > 40){
//                DR.dragged = true;
//            }
//        }else {
//            xDrag = Math.abs(event.clientX - DR.clickX);
//            yDrag = Math.abs(event.clientY - DR.clickY);
//            if(xDrag > 4 || yDrag > 4){
//                DR.dragged = true;
//            }
//        }
//
//        DR.dragged = true;
//    }});

    $("#muteButton").click(function () {
        if (!mute) {
            $("#muteButton").html("un-mute");
            lib.muteMe();

        } else {
            $("#muteButton").html("mute");
            unMuteMe();
            playAudio();
        }
    });
    $("#arrowButton").click(function () {
        if (!DR.showArrows) {
            $("#arrowButton").html("hide arrows");
            DR.showArrows = true;
            $('#arrow-svg .unit-path').show();
        } else {
            $("#arrowButton").html("show arrows");
            DR.showArrows = false;
            $('#arrow-svg .unit-path').hide();
        }
    });
    $('.unit:not(.clone)').hover(function (event) {
        $(".unitPath" + this.id).addClass('hover');
    }, function (event) {
        $(".unitPath" + this.id).removeClass('hover');
    });


    $('.unit').bind('contextmenu', function (e) {
        return rotateUnits(e, this);

    });
    // end setup events ----------------------------------------


    var Player = 'Markarian';

    $("#TimeWrapper .WrapperLabel").click(function () {
        $("#TimeWrapper").css('overflow', 'visible');
    });
    $(".dropDown .WrapperLabel").click(function () {

        $(this).parent().siblings(".dropDown, #crtWrapper").children('div').hide();
        $('.dropDownSelected').removeClass('dropDownSelected');

        $(this).next().toggle({
            effect: "blind", direction: "up", complete: function () {
                if ($(this).is(":visible")) {
                    $(this).parent().children('h4').addClass('dropDownSelected');
                } else {
                    $(this).parent().children('h4').removeClass('dropDownSelected');
                    $(this).parent().parent().parent('.dropDown').children('div').hide();
                    $(this).parent().children('h4').removeClass('dropDownSelected');

                }
            }
        });

    });

    $("#jumpWrapper .WrapperLabel").click(function () {

        $("#crt").hide({effect: "blind", direction: "up"});
        $("#gameContainer").css("margin", 0);
        $("#gameImages").css({zoom: .3, overflow: "visible"});
        // TODO: make this more modern (transform)
        $("#gameImages").css({MozTransform: "translate(-33%, -33%) scale(.3)"});
        $("html, body").animate({scrollTop: "0px"});


        $("#gameImages").css('left', 0);
        $("#gameImages").css('top', 0);
        zoomed = true;
    });
    $("#crtWrapper .WrapperLabel .goLeft").click(function () {
        $("#crtWrapper").animate({left: 0}, 300);
        $("#crt").animate({left: "0px", top: 26}, 300);
        DR.$crtPanZoom.panzoom('reset', {animate: false});


        return false;
    });
    $("#crtWrapper .WrapperLabel .goRight").click(function () {
        var wrapWid = $("#crtWrapper").css('width').replace(/px/, "");
        var crtWid = $("#crt").css('width').replace(/px/, "");
        crtWid = crtWid - wrapWid + 40;
        var moveLeft = $("body").css('width').replace(/px/, "");
        $("#crtWrapper").animate({left: moveLeft - wrapWid}, 300);
        $("#crt").animate({left: 0 - crtWid, top: 26}, 300);
        DR.$crtPanZoom.panzoom('reset', {animate: false});

        return false;
    });
    $(".close").on('click touchstart', function () {
        $(this).parent().hide({effect: "blind", direction: "up"});
    })
    $("#crtWrapper .WrapperLabel").click(function () {
        $("#crtWrapper").css('overflow', 'visible');
        DR.$crtPanZoom.panzoom('reset');

        $("#crt").toggle({effect: "blind", direction: "up"});
    });

    var up = 0;
    $("#hideShow").click(function () {
        up ^= 1;
        $("#deadpile").toggle({effect: "blind", direction: "up", complete: fixHeader});
        $(this).parent().parent('#unitsWrapper').find(".WrapperLabel").click();
        fixHeader();
        return;
        var howFar;
        if (up) {
            howFar = 30;
            $("#content").animate({marginTop: howFar + "px"}, "slow");
        } else {
            howFar = 50;
            $("#content").animate({marginTop: howFar + "px"}, "slow");

        }
    });
    $("#showExited").click(function () {
        up ^= 1;
        $("#exitWrapper").toggle({effect: "blind", direction: "up", complete: fixHeader});
        $(this).parent().parent().find(".WrapperLabel").click();
        fixHeader();
        return;
    });


    $("#showNotUsed").click(function () {
        up ^= 1;
        $("#notUsedWrapper").toggle({effect: "blind", direction: "up", complete: fixHeader});
        $(this).parent().parent().find(".WrapperLabel").click();
        fixHeader();
        return;
    });


    $("#showDeploy").click(function () {
        up ^= 1;
        $("#deployWrapper").toggle({effect: "blind", direction: "up", complete: fixHeader});
        $(this).parent().parent().find(".WrapperLabel").click();
        fixHeader();
        return;
    });
    $("#closeAllUnits").click(function () {
        $(".unit-wrapper").hide({effect: "blind", direction: "up", complete: fixHeader});
        $("#units").hide({effect: "blind", direction: "up", complete: fixHeader});
        $("#unitsWrapper .WrapperLabel").removeClass('dropDownSelected');
        fixHeader();
    });

    $("#showCombined").click(function () {
        up ^= 1;
        $("#combinedWrapper").toggle({effect: "blind", direction: "up", complete: fixHeader});
        $(this).parent().parent().find(".WrapperLabel").click();
        fixHeader();
        return;
    });


    $("#submit-bug-report").click(function(event){
        doitSaveGame($("#bug-report-message").val());
        $(".bug-report").toggle();
        return false;

    });


    $("#cancel-bug-report").click(function(event){
        $(".bug-report").toggle();
        event.stopPropagation();
        return false;
    });

    $("body").keydown(function (e) {

        var code = e.keyCode || e.which;
        if(event.target.id === "bug-report-message"){
            return;
        }
        doitKeypress(code);
    });

    $("body #bug-report-message").keypress(function(event){
        return true;
    });

    $("#forceMarchEvent").on('click', function () {
        doitKeypress(77);
    });

    $("#debug").on('click', function () {
        $("#bug-report-message").val('')
        $(".bug-report").toggle();
    });

    $("#splitEvent").on('click', function () {
        doitKeypress(115);
    });

    $("#determinedAttackEvent").on('click', function () {
        doitKeypress(100);
    });

    $("#combineEvent").on('click', function () {
        doitKeypress(99);
    });

    $("#clearCombatEvent").on('click', function () {
        doitKeypress(67);
    });

    $("#showHexes").on('click', function () {
        DR.showHexes = !DR.showHexes;
        if(DR.showHexes){
            $("#showHexes").addClass('negative');
        }else{
            $("#showHexes").removeClass('negative');
        }
        if(DR.showHexes){
            $('.range-hex').addClass('hovering');
        }else{
            $('.range-hex').removeClass('hovering');
        }
    });

    $("#showHexes1").on('click', function () {
        DR.showHexes1 = !DR.showHexes1;
        if(DR.showHexes1){
            $("#showHexes1").addClass('negative');
        }else{
            $("#showHexes1").removeClass('negative');
        }
        if(DR.showHexes1){
            $('.range-hex.forceId1').addClass('hovering');
        }else{
            $('.range-hex.forceId1').removeClass('hovering');
        }
    });

    $("#showHexes2").on('click', function () {
        DR.showHexes2 = !DR.showHexes2;
        if(DR.showHexes2){
            $("#showHexes2").addClass('negative');
        }else{
            $("#showHexes2").removeClass('negative');
        }
        if(DR.showHexes2){
            $('.range-hex.forceId2').addClass('hovering');
        }else{
            $('.range-hex.forceId2').removeClass('hovering');
        }
    });

    $("#shiftKey").on('click', function () {
        DR.shiftKey = !DR.shiftKey;
        $("#shiftKey").toggleClass('swooshy', DR.shiftKey);
    });

    $("#zoom .defaultZoom").on('click', function () {
        DR.globalZoom = 1.0;
        $("#zoom .defaultZoom").html(DR.globalZoom.toFixed(1));
        DR.$panzoom.panzoom('reset');
    });

    $('#map').on("load",function () {
        // fixItAll();
    });

    $("#crtDetailsButton").on('click touchend', function (e) {
        e.stopPropagation();
        $('#crtDetails').toggle(function () {
            DR.crtDetails = $(this).css('display') == 'block';
        });
        return false;
    });

    $("#phaseClicks").on("click", ".phaseClick", function () {
        x.timeTravel = true;
        if (x.current) {
            x.current.abort();
        }
        var click = $(this).attr('data-click');
        DR.currentClick = click;
        x.fetch(click);
    });
    $("#click-back").click(function () {
        x.timeTravel = true;
        if (x.current) {
            x.current.abort();
        }
        var click = DR.currentClick;
        click--;
        x.fetch(click);
    });
    $("#phase-back").click(function () {
        x.timeTravel = true;
        if (x.current) {
            x.current.abort();
        }
        var click = DR.currentClick - 0;
        var clicks = DR.clicks;
        var backSearch = clicks.length - 1;
        while (backSearch >= 0) {
            if (clicks[backSearch] <= click) {
                break;
            }
            backSearch--;
        }
        var gotoClick = clicks[backSearch] - 1;
        if (gotoClick < 2) {
            gotoClick = 2;
        }
        x.fetch(gotoClick);

    });

    $("#phase-surge").click(function () {
        x.timeTravel = true;
        if (x.current) {
            x.current.abort();
        }
        var click = DR.currentClick - 0;
        var clicks = DR.clicks;
        var forwardSearch = 0;

        while (forwardSearch < clicks.length) {
            if (clicks[forwardSearch] > (click + 1)) {
                break;
            }
            forwardSearch++;
        }
        var gotoClick = clicks[forwardSearch] - 1;
        if (gotoClick < 2) {
            gotoClick = 2;
        }
        x.fetch(gotoClick);

    });

    $("#player-turn-back").click(function () {
        x.timeTravel = true;
        if (x.current) {
            x.current.abort();
        }
        var click = DR.currentClick - 0;
        var clicks = DR.playTurnClicks;
        var backSearch = clicks.length - 1;
        while (backSearch >= 0) {
            if (clicks[backSearch] <= click) {
                break;
            }
            backSearch--;
        }
        var gotoClick = clicks[backSearch] - 1;
        if (gotoClick < 2) {
            gotoClick = 2;
        }
        x.fetch(gotoClick);

    });

    $("#player-turn-surge").click(function () {
        x.timeTravel = true;
        if (x.current) {
            x.current.abort();
        }
        var click = DR.currentClick - 0;
        var clicks = DR.playTurnClicks;
        var forwardSearch = 0;

        while (forwardSearch < clicks.length) {
            if (clicks[forwardSearch] > (click + 1)) {
                break;
            }
            forwardSearch++;
        }
        var gotoClick = clicks[forwardSearch] - 1;
        if (gotoClick < 2) {
            gotoClick = 2;
        }
        x.fetch(gotoClick);

    });

    $("#click-surge").click(function () {
        var click = DR.currentClick;
        click++;
        x.fetch(click);
    });

    $("#timeBranch").click(function () {
        x.timeTravel = true;
        x.timeBranch = true;
        if (x.current) {
            x.current.abort();
        }
        var click = DR.currentClick;
        x.fetch(click);
        $("#TimeWrapper .WrapperLabel").click();
    });

    $("#timeFork").click(function () {
        x.timeTravel = true;
        x.timeFork = true;
        if (x.current) {
            x.current.abort();
        }
        var click = DR.currentClick;
        x.fetch(click);
        $("#TimeWrapper .WrapperLabel").click();
    });

    $("#phaseClicks").on("click", ".realtime", function () {
        x.timeTravel = false;
        x.fetch(0);
    })
    $("#timeLive").click(function () {
        $("#TimeWrapper .WrapperLabel").click();
        x.timeTravel = false;
        x.fetch(0);
    });
    $("#showHexNums").on('click', function () {
        var src = $("#map").attr('src');
        if (src.match(/HexNumbers/)) {
            src = src.replace(/HexNumbers/, "");
        } else {
            src = src.replace(/Small/, "HexNumbersSmall");
        }
        $("#map").attr('src', src);
    });
    $(window).resize(fixItAll);

}

