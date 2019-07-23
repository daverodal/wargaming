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
import {clickBack, phaseBack, playerTurnBack, clickSurge,phaseSurge, playerTurnSurge, timeLive, timeBranch} from "./time-funcs";
import "./jquery.panzoom";
import {DR} from "./DR";
import {syncObj as x } from './Vue/syncObj'
import fixHeader from './fix-header';
export default function initialize() {

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

    $(".unit").on('click', counterClick);


    $("#crt #odds span").on('click touchstart', function (event) {
        var col = $(event.target).attr('class');
        if(col.match(/col/)){
            col = col.replace(/col/, '');
            doitCRT(col, event);
        }
    });
    $("#gameImages").on("click touchend", "svg",(event) =>{

        mapClick(event);
        return false;
    } );
    $("#choose-option-button").on("click", doitOption);

    $("#nextPhaseButton").on('click', nextPhaseMouseDown);


    $("#header, #deadpile, #exitWrapper, #combinedWrapper").on('touchmove', function (event) {

        if (event.target.id === 'Time' || $(event.target).parents('#Time').length > 0) {
            return;
        }
        if ($(event.target).parents('#crt').length > 0) {
            return;
        }
        event.stopPropagation();
    });

    $("#fullScreenButton").on('click touch', function () {
        toggleFullScreen();
        return false;
    });

    DR.$floatMessagePanZoom = $('#floatMessage').panzoom({
        cursor: "normal", disableZoom: true, onPan: function (e, panzoom) {
            DR.floatMessageDragged = true;
        }
    });


    DR.$time = $('#Time').panzoom({
        cursor: "move", disableZoom: true, onPan: function (e, panzoom) {
        },
        onEnd: (e, panzoom) => {

            let clientX = e.clientX;
            let clientY = e.clientY;
            if(e.originalEvent.changedTouches) {
                clientX = e.originalEvent.changedTouches[0].clientX;
                clientY = e.originalEvent.changedTouches[0].clientY;
            }

            var xDrag = Math.abs(clientX - DR.clickX);
            var yDrag = Math.abs(clientY - DR.clickY);

            if (xDrag > 4 || yDrag > 4) {
                DR.dragged = true;
            }else {
                if (e.changedTouches && e.originalEvent && e.originalEvent.path && e.originalEvent.path[0]) {
                    switch (e.originalEvent.path[0].id) {
                        case 'timeBranch':
                            timeBranch();
                            break;
                        case 'phase-surge':
                            phaseSurge();
                            break;
                        case 'click-surge':
                            clickSurge();
                            break;
                        case 'player-turn-surge':
                            playerTurnSurge();
                            break;
                        case 'click-back':
                            clickBack();
                            break;
                        case 'phase-back':
                            phaseBack();
                            break;
                        case 'player-turn-back':
                            playerTurnBack();
                            break;
                        case 'timeLive':
                            timeLive();
                            break;
                    }
                }
            }
        },
        onStart: function(a,b,c,d,e){

            DR.doingZoom = false;

            DR.dragged = false;
            if(c.changedTouches){
                DR.clickX = c.changedTouches[0].clientX;
                DR.clickY = c.changedTouches[0].clientY;
            }else{
                DR.clickX = c.clientX;
                DR.clickY = c.clientY;
            }
        }
    });
    DR.$crtPanZoom = $('#crt').panzoom({
        cursor: "move", disableZoom: true, onPan: function (e, panzoom) {
        }
    });

    $('#deployBox').width(3000);
    DR.$deployBoxZoom = $('#deployBox').panzoom({
        cursor: "move", disableYAxis: true, duration: 2000, disableZoom: true, onPan: function (e, panzoom) {
            return;
        },
        onStart: function(a,b,c,d,e){

            DR.dragged = false;
            if(c.changedTouches){
                DR.clickX = c.changedTouches[0].clientX;
                DR.clickY = c.changedTouches[0].clientY;
            }else{
                DR.clickX = c.clientX;
                DR.clickY = c.clientY;
            }




        },
        onEnd: function(a,b,c,d,e){
            let clientX = a.clientX;
            let clientY = a.clientY;
            if(a.originalEvent.changedTouches) {
                clientX = a.originalEvent.changedTouches[0].clientX;
                clientY = a.originalEvent.changedTouches[0].clientY;
            }

            var xDrag = Math.abs(clientX - DR.clickX);
            var yDrag = Math.abs(clientY - DR.clickY);

            if (xDrag > 4 || yDrag > 4) {
                DR.dragged = true;
            }else{
                if(DR.doingZoom !== true && a.originalEvent.changedTouches){
                    counterClick(a);
                }
            }

            DR.doingZoom = false;
        }
    });

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
    $("#time-wrapper .WrapperLabel").click(function () {
        $("#time-wrapper").css('overflow', 'visible');
    });
    $(".dropDown .WrapperLabel").click(function () {

        $(this).parent().siblings(".dropDown, #crt-wrapper").children('div').hide();
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

    $("#crt-wrapper .wrapper-label").click(function () {
        $("#crt-wrapper").css('overflow', 'visible');
        DR.$crtPanZoom.panzoom('reset');

        $("#crt").toggle({effect: "blind", direction: "up"});
    });

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
        $("#deployWrapper").hide({effect: "blind", direction: "up"});
        $("#deadpile").hide({effect: "blind", direction: "up"});
        $("#exitWrapper").hide({effect: "blind", direction: "up"});
        $("#notUsedWrapper").hide({effect: "blind", direction: "up"});
        $("#undeadpile").hide({effect: "blind", direction: "up", complete: fixHeader});

        $("#units").hide({effect: "blind", direction: "up"});
        $("#unitsWrapper .WrapperLabel").removeClass('dropDownSelected');
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
        if(e.target.id === "bug-report-message"){
            return;
        }
        var code = e.keyCode || e.which;
        doitKeypress(code);
    });

    $("#exitEvent").on('click', function () {
        doitKeypress(88);
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
        doitKeypress(68);
    });

    $("#combineEvent").on('click', function () {
        doitKeypress(67);
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

    $("#zoom").on('click', function () {
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
        clickBack();
    });
    $("#phase-back").click(function () {
        phaseBack();
    });

    $("#phase-surge").click(function () {
        phaseSurge();
    });

    $("#player-turn-back").click(function () {
        playerTurnBack();
    });

    $("#player-turn-surge").click(function () {
        playerTurnSurge();
    });

    $("#click-surge").click(function () {
        clickSurge();
    });


    $("#timeBranch").click(function () {
       timeBranch();
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
    });
    $("#timeLive").click(function () {
        timeLive();
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

