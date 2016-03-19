<?php global $force_name;
/*
Copyright 2012-2015 David Rodal

This program is free software; you can redistribute it
and/or modify it under the terms of the GNU General Public License
as published by the Free Software Foundation;
either version 2 of the License, or (at your option) any later version

This program is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   GNU General Public License for more details.

You should have received a copy of the GNU General Public License
   along with this program.  If not, see <http://www.gnu.org/licenses/>.
   */
?><!doctype html>
<html>
<head>



    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="/favicon.ico" type="image/icon">
    <script src="<?=url("js/jquery.js");?>"></script>
    <script src="<?=url("js/jquery-ui.js");?>"></script>
    <script src="<?=url("js/jquery.panzoom/dist/jquery.panzoom.js");?>"></script>
    <script src="<?=url("js/jquery.panzoom/test/libs/jquery.mousewheel.js");?>"></script>
    <script src="<?=url("js/sync.js");?>"></script>
    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>
    <link href='http://fonts.googleapis.com/css?family=Nosifer' rel='stylesheet' type='text/css'>
<link href='http://fonts.googleapis.com/css?family=Droid+Serif' rel='stylesheet' type='text/css'>
<link href='http://fonts.googleapis.com/css?family=PT+Serif' rel='stylesheet' type='text/css'>
<style type="text/css">
</style>
<script>
    var DR = {};
    var zoomed = false;
    DR.globalZoom = 1;
    DR.playerNameMap = ["Zero", "One", "Two", "Three", "Four"];

    {{ $playerThree = isset($playerThree) ? $playerThree : 3 }}
    {{ $playerFour = isset($playerFour) ? $playerFour : 4 }}

    DR.playerOne = "{{$forceName[1]}}";
    DR.playerTwo = "{{$forceName[2]}}";
    DR.playerThree = "{{$forceName[3] or ''}}";
    DR.playerFour = "{{$forceName[4] or ''}}";
    DR.players = ["observer", DR.playerOne,DR.playerTwo,DR.playerThree,DR.playerFour];

    function rotateUnits(e, that){
        if (e.ctrlKey) {
            return true;
        }
        var id = that.id;
        var x = DR.stackModel.ids[id].x;
        var y = DR.stackModel.ids[id].y;
        var units = DR.stackModel[x][y].ids;
        for (i in units) {
            var zindex = $("#" + i).css('z-index') - 0;
            $("#" + i).css({zIndex: zindex + 1});
        }
        $(that).css({zIndex: 1});
        return false;
    }

    function toggleFullScreen() {
        var doc = window.document;
        var docEl = doc.documentElement;

        var requestFullScreen = docEl.requestFullscreen || docEl.mozRequestFullScreen || docEl.webkitRequestFullScreen || docEl.msRequestFullscreen;
        var cancelFullScreen = doc.exitFullscreen || doc.mozCancelFullScreen || doc.webkitExitFullscreen || doc.msExitFullscreen;

        if(!doc.fullscreenElement && !doc.mozFullScreenElement && !doc.webkitFullscreenElement && !doc.msFullscreenElement) {
            requestFullScreen.call(docEl);
            $("#fullScreenButton i").removeClass("fa-arrows-alt").addClass("fa-compress");
        }
        else {
            cancelFullScreen.call(doc);
            $("#fullScreenButton i").addClass("fa-arrows-alt").removeClass("fa-compress");
        }
    }

    $(document).ready(function () {

        DR.crtDetails = false;
        DR.showArrows = false;

        $("#header, #deadpile, #deployWrapper, #exitWrapper, #combinedWrapper").on('touchmove', function(event){
            if($(event.target).parents('#crt').length > 0){
                return;
            }
            event.stopPropagation();
        });

        $("#fullScreenButton").on('click touchstart', function () {
            toggleFullScreen();
            return false;
        });


            var $panzoom = $('#gameImages').panzoom({cursor: "normal", animate: true, maxScale: 2.0, minScale:.3, onPan: function(e, panzoom){

                var xDrag;
                var yDrag;
                if(event.type === 'touchmove'){
                    xDrag = Math.abs(event.touches[0].clientX - DR.clickX);
                    yDrag = Math.abs(event.touches[0].clientY - DR.clickY);
                    if(xDrag > 40 || yDrag > 40){
                        DR.dragged = true;
                    }
                }else {
                    xDrag = Math.abs(event.clientX - DR.clickX);
                    yDrag = Math.abs(event.clientY - DR.clickY);
                    if(xDrag > 4 || yDrag > 4){
                        DR.dragged = true;
                    }
                }
            },
            onZoom:function(e,p,q){
                DR.globalZoom = q;
                var out = DR.globalZoom.toFixed(1);

                $("#zoom .defaultZoom").html(out);
            }});

            $panzoom.parent().on('mousewheel DOMMouseScroll MozMousePixelScroll', function (e) {
                e.preventDefault();
                var delta = e.delta || e.originalEvent.wheelDelta;

                var zoomOut = delta ? delta < 0 : e.originalEvent.deltaY > 0;

                $panzoom.panzoom('zoom', zoomOut, {
                    increment: 0.1,
                    animate: false,
                    focal: e
                });
            });
            DR.$panzoom = $panzoom;
        $('#map').load(function(){
                fixItAll();
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
        $("#phase-back").click(function(){
            x.timeTravel = true;
            if (x.current) {
                x.current.abort();
            }
            var click = DR.currentClick - 0;
            var clicks = DR.clicks;
            var backSearch = clicks.length - 1;
            while(backSearch >= 0){
                if(clicks[backSearch] <= click){
                    break;
                }
                backSearch--;
            }
            var gotoClick = clicks[backSearch] - 1;
            if(gotoClick < 2){
                gotoClick = 2;
            }
            x.fetch(gotoClick);

        });

        $("#phase-surge").click(function(){
            x.timeTravel = true;
            if (x.current) {
                x.current.abort();
            }
            var click = DR.currentClick - 0;
            var clicks = DR.clicks;
            var forwardSearch = 0;

            while(forwardSearch < clicks.length){
                if(clicks[forwardSearch] > (click + 1)){
                    break;
                }
                forwardSearch++;
            }
            var gotoClick = clicks[forwardSearch] - 1;
            if(gotoClick < 2){
                gotoClick = 2;
            }
            x.fetch(gotoClick);

        });

        $("#player-turn-back").click(function(){
            x.timeTravel = true;
            if (x.current) {
                x.current.abort();
            }
            var click = DR.currentClick - 0;
            var clicks = DR.playTurnClicks;
            var backSearch = clicks.length - 1;
            while(backSearch >= 0){
                if(clicks[backSearch] <= click){
                    break;
                }
                backSearch--;
            }
            var gotoClick = clicks[backSearch] - 1;
            if(gotoClick < 2){
                gotoClick = 2;
            }
            x.fetch(gotoClick);

        });

        $("#player-turn-surge").click(function(){
            x.timeTravel = true;
            if (x.current) {
                x.current.abort();
            }
            var click = DR.currentClick - 0;
            var clicks = DR.playTurnClicks;
            var forwardSearch = 0;

            while(forwardSearch < clicks.length){
                if(clicks[forwardSearch] > (click + 1)){
                    break;
                }
                forwardSearch++;
            }
            var gotoClick = clicks[forwardSearch] - 1;
            if(gotoClick < 2){
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
        fixHeader();
        $(window).resize(fixItAll);
    });
    function fixItAll() {
        fixHeader();

    }
    function fixHeader() {

        var winHeight = $(window).height();
        var winWidth = $(window).width();
        var mapHeight = $("#map").height();
        var mapWidth = $("#map").width();
        var containerHeight = mapHeight;
        var containerWidth = mapWidth;
        if(winWidth > mapWidth){
            containerWidth = winWidth;
        }
        if(winHeight > mapHeight){
            containerHeight = winHeight;
        }
        $("#gameImages, #gameContainer").height(containerHeight).width(containerWidth);
        $("#arrow-svg").height(mapHeight).width(mapWidth);
        DR.$panzoom.panzoom('resetDimensions');

        height = $("#crtWrapper h4").height();
        $("#bottomHeader").css("height", height);

        var headerHeight = $("#header").height();
        $("#content").css("margin-top",0);
        var bodyHeight = $(window).height();
        var bodyWidth = $(window).width();
        var deployHeight = $("#deployWrapper:visible").height();
        var deadHeight = $("#deadpile:visible").height();
//        $("#gameViewer, #gameContainer").height(bodyHeight - (deployHeight+deadHeight + 20));
        if (deadHeight) {
            deadHeight += 10 + 10 + 4 + 4;
        }
        if (deployHeight) {
            deployHeight += 10 + 10 + 4 + 4;
        } else {
            deployHeight = 0;
        }
        var height = bodyHeight - deployHeight - deadHeight - headerHeight - 40;
        var width = bodyWidth - 35;
        DR.$panzoom.panzoom('resetDimensions');

        window.scrollTo(0, 1);

//        $("#gameViewer").height(height);
//        $("#gameViewer").width(width);
    }
</script>
    @include("wargame::commonSync")
<script>
function seeMap() {
    $(".unit").css("opacity", .0);
}
function seeUnits() {
    $(".unit").css("opacity", 1.);
}
function seeBoth() {
    $(".unit").css("opacity", .3);
}
function doit() {
    var mychat = $("#mychat").attr("value");
    $.ajax({
        url: "<?=url("wargame/add/");?>",
        type: "GET",
        data: {chat: mychat},
        success: function (data, textstatus) {
            alert(data);
        }
    });
    $("#mychat").attr("value", "");
}
function doitKeypress(key) {
    var mychat = $("#mychat").attr("value");
    playAudio();
    $('body').css({cursor: "wait"});
    $(this).css({cursor: "wait"});
//    $("#"+id+"").addClass("pushed");

    $("#comlink").html('Waiting');
    $.ajax({
        url: "<?=url("wargame/poke");?>",
        type: "POST",
        data: {id: key, event: <?=KEYPRESS_EVENT?>},
        error: function (data, text, third) {
            try {
                obj = jQuery.parseJSON(data.responseText);
            } catch (e) {
//                alert(data);
            }
            if (obj.emsg) {
                alert(obj.emsg);
            }
            playAudioBuzz();
            $('body').css({cursor: "auto"});
            $(this).css({cursor: "auto"});
            $("#" + id + "").removeClass("pushed");
            $("#comlink").html('Working');
        },
        success: function (data, textstatus) {
            try {
                var success = data.success;
            } catch (e) {
//                alert(data);
            }
            if (success) {
                playAudioLow();

            } else {
                playAudioBuzz();
            }
            $('body').css({cursor: "auto"});
            $(this).css({cursor: "auto"});
//            $("#"+id+"").removeClass("pushed");


        }
    });
    $("#mychat").attr("value", "");
}
function doitCRT(id, event) {
    var mychat = $("#mychat").attr("value");
    playAudio();
    $('body').css({cursor: "wait"});
    $(this).css({cursor: "wait"});

    $("#comlink").html('waiting');
    $.ajax({
        url: "<?=url("wargame/poke");?>",
        type: "POST",
        data: {id: id, event: (event.shiftKey || DR.shiftKey) ? <?=COMBAT_PIN_EVENT;?> : <?=COMBAT_PIN_EVENT?>},
        error: function (data, text, third) {
            try {
                obj = jQuery.parseJSON(data.responseText);
            } catch (e) {
//                alert(data);
            }
            if (obj.emsg) {
                alert(obj.emsg);
            }
            playAudioBuzz();
            $('body').css({cursor: "auto"});
            $(this).css({cursor: "auto"});
            $("#comlink").html('Working');
        },
        success: function (data, textstatus) {
            try {
                var success = data.success;
            } catch (e) {
//            alert(data);
            }
            if (success) {
                playAudioLow();

            } else {
                playAudioBuzz();
            }
            $('body').css({cursor: "auto"});
            $(this).css({cursor: "auto"});
        }
    });
    $("#mychat").attr("value", "");
}
function doitUnit(id, event) {
    var mychat = $("#mychat").attr("value");
    playAudio();
    $('body').css({cursor: "wait"});
    $(this).css({cursor: "wait"});
    $("#" + id + "").addClass("pushed");

    $("#comlink").html('waiting');
    if(DR.shiftKey){
        event.shiftKey = true;
        $("#shiftKey").click();
    }
    $.ajax({
        url: "<?=url("wargame/poke");?>",
        type: "POST",
        data: {id: id, event: (event.metaKey || event.ctrlKey) ? <?=SELECT_ALT_COUNTER_EVENT;?> : (event.shiftKey || DR.shiftKey) ? <?=SELECT_SHIFT_COUNTER_EVENT;?> : <?=SELECT_COUNTER_EVENT?>},
        error: function (data, text, third) {
            try {
                obj = jQuery.parseJSON(data.responseText);
            } catch (e) {
//                alert(data);
            }
            if (obj.emsg) {
                alert(obj.emsg);
            }
            playAudioBuzz();
            $('body').css({cursor: "auto"});
            $(this).css({cursor: "auto"});
            $("#" + id + "").removeClass("pushed");
            $("#comlink").html('Working');
        },
        success: function (data, textstatus) {
            try {
                var success = data.success;
            } catch (e) {
//            alert(data);
            }
            if (success) {
                playAudioLow();

            } else {
                playAudioBuzz();
            }
            $('body').css({cursor: "auto"});
            $(this).css({cursor: "auto"});
            $("#" + id + "").removeClass("pushed");


        }
    });
    $("#mychat").attr("value", "");
}
function doitMap(x, y) {
    playAudio();

    $.ajax({
        url: "<?=url("wargame/poke");?>",
        type: "POST",
        data: {
            x: x,
            y: y,
            event: <?=SELECT_MAP_EVENT?>
        },
        success: function (data, textstatus) {
            try {
                var success = +$.parseJSON(data).success;
            } catch (e) {
//            alert(data);
            }
            if (success) {
                playAudioLow();

            } else {
                playAudioBuzz();
            }
            $('body').css({cursor: "auto"});
            $(this).css({cursor: "auto"});
        },
        error: function (data, text) {
            try {
                var success = +$.parseJSON(data).success;
            } catch (e) {
//                alert(data);
            }
            playAudioBuzz();
            $('body').css({cursor: "auto"});
            $(this).css({cursor: "auto"});
        }
    });
    return true;
}
function doitNext() {
    playAudio();

    $.ajax({
        url: "<?=url("wargame/poke");?>",
        type: "POST",
        data: {event: <?=SELECT_BUTTON_EVENT?>},
        success: function (data, textstatus) {
            try {
                var success = +$.parseJSON(data).success;
            } catch (e) {
//                alert(data);
            }
            playAudioLow();

        }, error: function (data, text) {
            try {
                alert(data.responseText);
                var success = +$.parseJSON(data).success;
            } catch (e) {
//                alert(data);
            }
            playAudioBuzz();
            $('body').css({cursor: "auto"});
            $(this).css({cursor: "auto"});
        }
    });

}


// Copyright (c) 2009-2011 Mark Butler
// This program is free software; you can redistribute it
// and/or modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation;
// either version 2 of the License, or (at your option) any later version.

// main classes for wargame

function mapMouseMove(event) {
    var tar = event.target;

    var x = event.pageX - event.target.x;
    var y = event.pageY - event.target.y;
    $("#mouseMove").html("X " + x + " Y " + y);
}
function mapStop(event) {

    $("#map").data('did-drag', true);
    event.stopPropagation();
}
function mapClick(event) {
    var didDrag = $("#map").data('did-drag');
    $("#map").data('did-drag', false);
    if (didDrag) {
        return;
    }

    var pixelX, pixelY;
    pixelX = event.pageX;
    pixelY = event.pageY;
    var p;
    p = $("#gameImages").offset();
    pixelX -= p.left;
    pixelY -= p.top;

    if (zoomed) {
        doZoom(event);
        zoomed = false;
        return;
    }
}

function changePosition(player) {
    $("#flash").html(player);
}

function doZoom(event) {

    var pixelX, pixelY;
    pixelX = event.pageX;
    pixelY = event.pageY;
    var p;
    p = $("#gameViewer").offset();
    pixelX -= p.left;
    pixelY -= p.top;

    zoomed = false;
    width = $("body").width();
    var left = (pixelX / -.3) + (width / 2);
    var viewerHeight = $("#gameViewer").height() / 2;
    var top = (pixelY / -.3) + (viewerHeight);

    if (left > 0) {
        left = 0;
    }
    if (top > 0) {
        top = 0;
    }
    // TODO: make this more modern
    $("#gameImages").css({MozTransform: "translate(0,0) scale(1.0)"});
    $("#gameImages").animate({zoom: 1.0, left: left, top: top}, 1500);
}

function counterClick(event) {
    if(event.type == 'touchend'){
        event.stopPropagation();
        event.preventDefault();
    }

    DR.clickX = DR.clickY = undefined;
    if(DR.dragged){
        return;
    }
    if(event.which === 3){
        return;
    }
    if (zoomed) {
        doZoom(event);
        return;
    }

    var didDrag = $("#map").data('did-drag');
    $("#map").data('did-drag', false);
    if (didDrag) {
        return;
    }

    if(event.altKey){
        rotateUnits(event, this);
        return;
    }
    var id;
    id = $(event.target).attr('id');
    if (!id) {
        id = $(event.target).parent().attr("id");
    }
    if (!id) {
        id = $(event.target).parent().parent().attr("id");
    }
    doitUnit(id, event);
}

function nextPhaseMouseDown(event) {
    doitNext();
}

var mute = false;

function playAudio() {
    var aud = $('.pop').get(0);
    <!--    aud.src = "-->
    if (aud && !mute) {
        aud.play();
    }

}
function playAudioLow() {
    var aud = $('.poop').get(0);
    <!--    aud.src = "-->
    if (aud && !mute) {
        aud.play();
    }

}
function unMuteMe() {
    mute = false;
    return true;
}
function muteMe() {
    mute = true;
    return true;
}
function playAudioBuzz() {
    var aud = $('.buzz').get(0);
    <!--    aud.src = "-->
    if (aud) {
        aud.play();
    }

}

function showCrtTable(mySelf){
    if($("#crt .switch-crt").length <= 1){
        return;
    }
    $("#crt .tableWrapper").hide();
    $("#crt .switch-crt").hide();
    var id = $(mySelf).attr('id');
    $('#crt .'+id).show();
    var next = $(mySelf).next();
    if(next.length){
        $(next).show();
    }else{
        $('#crt .switch-crt').first().show();
    }
}

function initialize() {

    /* yuck */
    if(navigator.userAgent.match(/Android/)){
        $('body').height($(window).height());
    }
    // setup events --------------------------------------------

//check if the image is already on cache

    $(".switch-crt").on('click', function(){
        showCrtTable(this);
    });



    $('#crt .tableWrapper').hide();
    $('#crt .tableWrapper').first().show();
    $('#crt .switch-crt').hide();
    $('#crt .switch-crt').first().next().show();


    if($('#image_id').prop('complete')){
        var width = $("#gameImages #map").width();
        var height = $("#gameImages #map").height();
        $('#arrow-svg').width(width);
        $('#arrow-svg').height(height);
        $('#arrow-svg').attr('viewBox', "0 0 " + width + " " + height);
    }

    $("#map").load(function () {
        var width = $("#gameImages #map").width();
        var height = $("#gameImages #map").height();
        $('#arrow-svg').width(width);
        $('#arrow-svg').height(height);
        $('#arrow-svg').attr('viewBox', "0 0 " + width + " " + height);
    });


    $(".unit").on('mousedown touchstart', function(e){
        DR.clickX = e.clientX;
        DR.clickY = e.clientY;
        DR.dragged = false;
    });

    $(".unit").on('mouseup touchend', counterClick);


    $("#crt #odds span").on('click touchstart', function (event) {
        var col = $(event.target).attr('class');
        col = col.replace(/col/, '');
        doitCRT(col, event);
    })
    $("#gameImages").on("click", ".specialHexes", mapClick);
    $("#gameImages").on("click", "svg", mapClick);

    $("#nextPhaseButton").on('click', nextPhaseMouseDown);
//    $("#gameImages" ).draggable({stop:mapStop, distance:15});
    $("#gameImages #map").on("click", mapClick);

    DR.$floatMessagePanZoom = $('#floatMessage').panzoom({cursor: "normal", disableZoom: true, onPan: function(e, panzoom){
        DR.floatMessageDragged = true;
    }});

    $("#Time").draggable();
    DR.$crtPanZoom = $('#crt').panzoom({cursor: "move", disableZoom: true, onPan: function(e, panzoom){
        var xDrag;
        var yDrag;
        if(event.type === 'touchmove'){
            xDrag = Math.abs(event.touches[0].clientX - DR.clickX);
            yDrag = Math.abs(event.touches[0].clientY - DR.clickY);
            if(xDrag > 40 || yDrag > 40){
                DR.dragged = true;
            }
        }else {
            xDrag = Math.abs(event.clientX - DR.clickX);
            yDrag = Math.abs(event.clientY - DR.clickY);
            if(xDrag > 4 || yDrag > 4){
                DR.dragged = true;
            }
        }

        DR.dragged = true;
    }});
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
            muteMe();

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
            $('#arrow-svg').show();
        } else {
            $("#arrowButton").html("show arrows");
            DR.showArrows = false;
            $('#arrow-svg').hide();
        }
    });
    $('.unit:not(.clone)').hover(function (event) {

        $(".unitPath" + this.id).css({opacity: 1.0});

    }, function (event) {
        $(".unitPath" + this.id).css({opacity: ''});

    });


    $('.unit').bind('contextmenu', function (e) {
        return rotateUnits(e, this);

    });
    // end setup events ----------------------------------------


    var Player = 'Markarian';

    $("#TimeWrapper .WrapperLabel").click(function(){
        $("#TimeWrapper").css('overflow', 'visible');
    });
    $(".dropDown .WrapperLabel").click(function () {
        $(this).parent().siblings(".dropDown, #crtWrapper").children('div').hide({
            effect: "blind", direction: "up", complete: function () {
                $(this).parent().children('h4').removeClass('dropDownSelected');
            }
        });

        $(this).next().toggle({
            effect: "blind", direction: "up", complete: function () {
                if ($(this).is(":visible")) {
                    $(this).parent().children('h4').addClass('dropDownSelected');
                } else {
                    $(this).parent().children('h4').removeClass('dropDownSelected');
                    $(this).parent().parent().parent('.dropDown').children('div').hide({
                        effect: "blind", direction: "up", complete: function () {
                            $(this).parent().children('h4').removeClass('dropDownSelected');
                        }
                    });
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
    $("#closeAllUnits").click(function(){
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

    fixHeader();
    $("body").keydown(function (event) {
        if(event.which == 37 || event.which == 38 || event.which == 39 || event.which == 40){
            doitKeypress(event.which);
            return false;
        }
        return true;
    });

    $("body").keypress(function (event) {
        doitKeypress(event.which);
    });

    $("#forceMarchEvent").on('click',function(){
        doitKeypress(109);
    });

    $("#determinedAttackEvent").on('click',function(){
        doitKeypress(100);
    });

    $("#clearCombatEvent").on('click',function(){
        doitKeypress(99);
    });

    $("#shiftKey").on('click',function(){
        DR.shiftKey = !DR.shiftKey;
        $("#shiftKey").toggleClass('swooshy', DR.shiftKey);
    });

    $("#zoom .defaultZoom").on('click', function () {
        DR.globalZoom = 1.0;
        $("#zoom .defaultZoom").html(DR.globalZoom.toFixed(1));
        DR.$panzoom.panzoom('reset');
    });


}

var state = {
    x: 0,
    y: 0,
    scale: 1
};
var oX, oY;
var changeScale = function (scale) {
    // Limit the scale here if you want
    // Zoom and pan transform-origin equivalent
    var scaleD = scale / state.scale;
    var currentX = state.x;
    var currentY = state.y;
    // The magic
    var x = scaleD * (currentX - oX) + oX;
    var y = scaleD * (currentY - oY) + oY;

    state.scale = scale;
    state.x = x;
    state.y = y;

    var transform = "matrix(" + scale + ",0,0," + scale + "," + x + "," + y + ")";
    //var transform = "translate("+x+","+y+") scale("+scale+")"; //same
//    view.setAttributeNS(null, "transform", transform);
    $("#gameImages").css('transform', transform);
};

function doUserZoom(event) {

    var vHeight;
    var vWidth;
    var prevWidth;
    var precision = 1;
    if (DR.globalZoom >= 1.0) {
        precision = 2;
    }
    $("#zoom .defaultZoom").html(DR.globalZoom.toPrecision(precision));

    if (event) {
        prevWidth = $("#gameImages").css('-webkit-transform-origin');
        vWidth = event.pageX - event.target.x;
        vHeight = event.pageY - event.target.y;
//        oX = vWidth;
//        oY = vHeight;
//        oX = window.innerWidth/2;
//        oY = window.innerHeight/2;
//        changeScale(DR.globalZoom);
    } else {
        var origHeight = vHeight = $('#gameViewer').height();
        var origWidth = vWidth = $('#gameViewer').width();
        vHeight /= 2;
        vWidth /= 2;
        var pos = $('#gameImages').position();
        var top = pos.top;
        vHeight -= top;
        var left = pos.left;
        vWidth -= left;
        if (vWidth > origWidth) {
            vWidth = origWidth;
        }
        if (vHeight > origHeight) {
            vHeight = origHeight;
        }
        if (vHeight < 0) {
            vheight = 0;
        }
        if (vWidth < 0) {
            vWidth = 0;
        }
    }
    $("#gameImages").css('-webkit-transform-origin', vWidth + "px " + vHeight + "px").css('transform-origin', vWidth + "px " + vHeight + "px");
    $("#gameImages").css('transform', 'scale(' + DR.globalZoom + ',' + DR.globalZoom + ')').css('-webkit-transform', 'scale(' + DR.globalZoom + ',' + DR.globalZoom + ')');
}
$(document).ready(initialize);
</script>

    <style type="text/css">
        @font-face {
            font-family: 'FontAwesome';
            src: url("../fonts/font-awesome/fontawesome-webfont.eot?v=4.5.0");
            src: url("../fonts/font-awesome/fontawesome-webfont.eot?#iefix&v=4.5.0") format("embedded-opentype"), url("../fonts/font-awesome/fontawesome-webfont.woff2?v=4.5.0") format("woff2"), url("../fonts/font-awesome/fontawesome-webfont.woff?v=4.5.0") format("woff"), url("../fonts/font-awesome/fontawesome-webfont.ttf?v=4.5.0") format("truetype"), url("../fonts/font-awesome/fontawesome-webfont.svg?v=4.5.0#fontawesomeregular") format("svg");
            font-weight: normal;
            font-style: normal; }

        .fa {
            display: inline-block;
            font: normal normal normal 14px / 1 FontAwesome;
            font-size: inherit;
            text-rendering: auto;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale; }

        /* makes the font 33% larger relative to the icon container */
        .fa-lg {
            font-size: 1.3333333333em;
            line-height: 0.75em;
            vertical-align: -15%; }

        .fa-2x {
            font-size: 2em; }

        .fa-3x {
            font-size: 3em; }

        .fa-4x {
            font-size: 4em; }

        .fa-5x {
            font-size: 5em; }

        .fa-fw {
            width: 1.2857142857em;
            text-align: center; }

        .fa-ul {
            padding-left: 0;
            margin-left: 2.1428571429em;
            list-style-type: none; }
        .fa-ul > li {
            position: relative; }

        .fa-li {
            position: absolute;
            left: -2.1428571429em;
            width: 2.1428571429em;
            top: 0.1428571429em;
            text-align: center; }
        .fa-li.fa-lg {
            left: -1.8571428571em; }

        .fa-border {
            padding: .2em .25em .15em;
            border: solid 0.08em #eee;
            border-radius: .1em; }

        .fa-pull-left {
            float: left; }

        .fa-pull-right {
            float: right; }

        .fa.fa-pull-left {
            margin-right: .3em; }

        .fa.fa-pull-right {
            margin-left: .3em; }

        /* Deprecated as of 4.4.0 */
        .pull-right {
            float: right; }

        .pull-left {
            float: left; }

        .fa.pull-left {
            margin-right: .3em; }

        .fa.pull-right {
            margin-left: .3em; }

        .fa-spin {
            -webkit-animation: fa-spin 2s infinite linear;
            animation: fa-spin 2s infinite linear; }

        .fa-pulse {
            -webkit-animation: fa-spin 1s infinite steps(8);
            animation: fa-spin 1s infinite steps(8); }

        @-webkit-keyframes fa-spin {
            0% {
                -webkit-transform: rotate(0deg);
                transform: rotate(0deg); }
            100% {
                -webkit-transform: rotate(359deg);
                transform: rotate(359deg); } }

        @keyframes fa-spin {
            0% {
                -webkit-transform: rotate(0deg);
                transform: rotate(0deg); }
            100% {
                -webkit-transform: rotate(359deg);
                transform: rotate(359deg); } }

        .fa-rotate-90 {
            filter: progid:DXImageTransform.Microsoft.BasicImage(rotation=1);
            -webkit-transform: rotate(90deg);
            transform: rotate(90deg); }

        .fa-rotate-180 {
            filter: progid:DXImageTransform.Microsoft.BasicImage(rotation=2);
            -webkit-transform: rotate(180deg);
            transform: rotate(180deg); }

        .fa-rotate-270 {
            filter: progid:DXImageTransform.Microsoft.BasicImage(rotation=3);
            -webkit-transform: rotate(270deg);
            transform: rotate(270deg); }

        .fa-flip-horizontal {
            filter: progid:DXImageTransform.Microsoft.BasicImage(rotation=0);
            -webkit-transform: scale(-1, 1);
            transform: scale(-1, 1); }

        .fa-flip-vertical {
            filter: progid:DXImageTransform.Microsoft.BasicImage(rotation=2);
            -webkit-transform: scale(1, -1);
            transform: scale(1, -1); }

        :root .fa-rotate-90,
        :root .fa-rotate-180,
        :root .fa-rotate-270,
        :root .fa-flip-horizontal,
        :root .fa-flip-vertical {
            -webkit-filter: none;
            filter: none; }

        .fa-stack {
            position: relative;
            display: inline-block;
            width: 2em;
            height: 2em;
            line-height: 2em;
            vertical-align: middle; }

        .fa-stack-1x, .fa-stack-2x {
            position: absolute;
            left: 0;
            width: 100%;
            text-align: center; }

        .fa-stack-1x {
            line-height: inherit; }

        .fa-stack-2x {
            font-size: 2em; }

        .fa-inverse {
            color: #fff; }

        /* Font Awesome uses the Unicode Private Use Area (PUA) to ensure screen
           readers do not read off random characters that represent icons */
        .fa-glass:before {
            content: ""; }

        .fa-music:before {
            content: ""; }

        .fa-search:before {
            content: ""; }

        .fa-envelope-o:before {
            content: ""; }

        .fa-heart:before {
            content: ""; }

        .fa-star:before {
            content: ""; }

        .fa-star-o:before {
            content: ""; }

        .fa-user:before {
            content: ""; }

        .fa-film:before {
            content: ""; }

        .fa-th-large:before {
            content: ""; }

        .fa-th:before {
            content: ""; }

        .fa-th-list:before {
            content: ""; }

        .fa-check:before {
            content: ""; }

        .fa-remove:before,
        .fa-close:before,
        .fa-times:before {
            content: ""; }

        .fa-search-plus:before {
            content: ""; }

        .fa-search-minus:before {
            content: ""; }

        .fa-power-off:before {
            content: ""; }

        .fa-signal:before {
            content: ""; }

        .fa-gear:before,
        .fa-cog:before {
            content: ""; }

        .fa-trash-o:before {
            content: ""; }

        .fa-home:before {
            content: ""; }

        .fa-file-o:before {
            content: ""; }

        .fa-clock-o:before {
            content: ""; }

        .fa-road:before {
            content: ""; }

        .fa-download:before {
            content: ""; }

        .fa-arrow-circle-o-down:before {
            content: ""; }

        .fa-arrow-circle-o-up:before {
            content: ""; }

        .fa-inbox:before {
            content: ""; }

        .fa-play-circle-o:before {
            content: ""; }

        .fa-rotate-right:before,
        .fa-repeat:before {
            content: ""; }

        .fa-refresh:before {
            content: ""; }

        .fa-list-alt:before {
            content: ""; }

        .fa-lock:before {
            content: ""; }

        .fa-flag:before {
            content: ""; }

        .fa-headphones:before {
            content: ""; }

        .fa-volume-off:before {
            content: ""; }

        .fa-volume-down:before {
            content: ""; }

        .fa-volume-up:before {
            content: ""; }

        .fa-qrcode:before {
            content: ""; }

        .fa-barcode:before {
            content: ""; }

        .fa-tag:before {
            content: ""; }

        .fa-tags:before {
            content: ""; }

        .fa-book:before {
            content: ""; }

        .fa-bookmark:before {
            content: ""; }

        .fa-print:before {
            content: ""; }

        .fa-camera:before {
            content: ""; }

        .fa-font:before {
            content: ""; }

        .fa-bold:before {
            content: ""; }

        .fa-italic:before {
            content: ""; }

        .fa-text-height:before {
            content: ""; }

        .fa-text-width:before {
            content: ""; }

        .fa-align-left:before {
            content: ""; }

        .fa-align-center:before {
            content: ""; }

        .fa-align-right:before {
            content: ""; }

        .fa-align-justify:before {
            content: ""; }

        .fa-list:before {
            content: ""; }

        .fa-dedent:before,
        .fa-outdent:before {
            content: ""; }

        .fa-indent:before {
            content: ""; }

        .fa-video-camera:before {
            content: ""; }

        .fa-photo:before,
        .fa-image:before,
        .fa-picture-o:before {
            content: ""; }

        .fa-pencil:before {
            content: ""; }

        .fa-map-marker:before {
            content: ""; }

        .fa-adjust:before {
            content: ""; }

        .fa-tint:before {
            content: ""; }

        .fa-edit:before,
        .fa-pencil-square-o:before {
            content: ""; }

        .fa-share-square-o:before {
            content: ""; }

        .fa-check-square-o:before {
            content: ""; }

        .fa-arrows:before {
            content: ""; }

        .fa-step-backward:before {
            content: ""; }

        .fa-fast-backward:before {
            content: ""; }

        .fa-backward:before {
            content: ""; }

        .fa-play:before {
            content: ""; }

        .fa-pause:before {
            content: ""; }

        .fa-stop:before {
            content: ""; }

        .fa-forward:before {
            content: ""; }

        .fa-fast-forward:before {
            content: ""; }

        .fa-step-forward:before {
            content: ""; }

        .fa-eject:before {
            content: ""; }

        .fa-chevron-left:before {
            content: ""; }

        .fa-chevron-right:before {
            content: ""; }

        .fa-plus-circle:before {
            content: ""; }

        .fa-minus-circle:before {
            content: ""; }

        .fa-times-circle:before {
            content: ""; }

        .fa-check-circle:before {
            content: ""; }

        .fa-question-circle:before {
            content: ""; }

        .fa-info-circle:before {
            content: ""; }

        .fa-crosshairs:before {
            content: ""; }

        .fa-times-circle-o:before {
            content: ""; }

        .fa-check-circle-o:before {
            content: ""; }

        .fa-ban:before {
            content: ""; }

        .fa-arrow-left:before {
            content: ""; }

        .fa-arrow-right:before {
            content: ""; }

        .fa-arrow-up:before {
            content: ""; }

        .fa-arrow-down:before {
            content: ""; }

        .fa-mail-forward:before,
        .fa-share:before {
            content: ""; }

        .fa-expand:before {
            content: ""; }

        .fa-compress:before {
            content: ""; }

        .fa-plus:before {
            content: ""; }

        .fa-minus:before {
            content: ""; }

        .fa-asterisk:before {
            content: ""; }

        .fa-exclamation-circle:before {
            content: ""; }

        .fa-gift:before {
            content: ""; }

        .fa-leaf:before {
            content: ""; }

        .fa-fire:before {
            content: ""; }

        .fa-eye:before {
            content: ""; }

        .fa-eye-slash:before {
            content: ""; }

        .fa-warning:before,
        .fa-exclamation-triangle:before {
            content: ""; }

        .fa-plane:before {
            content: ""; }

        .fa-calendar:before {
            content: ""; }

        .fa-random:before {
            content: ""; }

        .fa-comment:before {
            content: ""; }

        .fa-magnet:before {
            content: ""; }

        .fa-chevron-up:before {
            content: ""; }

        .fa-chevron-down:before {
            content: ""; }

        .fa-retweet:before {
            content: ""; }

        .fa-shopping-cart:before {
            content: ""; }

        .fa-folder:before {
            content: ""; }

        .fa-folder-open:before {
            content: ""; }

        .fa-arrows-v:before {
            content: ""; }

        .fa-arrows-h:before {
            content: ""; }

        .fa-bar-chart-o:before,
        .fa-bar-chart:before {
            content: ""; }

        .fa-twitter-square:before {
            content: ""; }

        .fa-facebook-square:before {
            content: ""; }

        .fa-camera-retro:before {
            content: ""; }

        .fa-key:before {
            content: ""; }

        .fa-gears:before,
        .fa-cogs:before {
            content: ""; }

        .fa-comments:before {
            content: ""; }

        .fa-thumbs-o-up:before {
            content: ""; }

        .fa-thumbs-o-down:before {
            content: ""; }

        .fa-star-half:before {
            content: ""; }

        .fa-heart-o:before {
            content: ""; }

        .fa-sign-out:before {
            content: ""; }

        .fa-linkedin-square:before {
            content: ""; }

        .fa-thumb-tack:before {
            content: ""; }

        .fa-external-link:before {
            content: ""; }

        .fa-sign-in:before {
            content: ""; }

        .fa-trophy:before {
            content: ""; }

        .fa-github-square:before {
            content: ""; }

        .fa-upload:before {
            content: ""; }

        .fa-lemon-o:before {
            content: ""; }

        .fa-phone:before {
            content: ""; }

        .fa-square-o:before {
            content: ""; }

        .fa-bookmark-o:before {
            content: ""; }

        .fa-phone-square:before {
            content: ""; }

        .fa-twitter:before {
            content: ""; }

        .fa-facebook-f:before,
        .fa-facebook:before {
            content: ""; }

        .fa-github:before {
            content: ""; }

        .fa-unlock:before {
            content: ""; }

        .fa-credit-card:before {
            content: ""; }

        .fa-feed:before,
        .fa-rss:before {
            content: ""; }

        .fa-hdd-o:before {
            content: ""; }

        .fa-bullhorn:before {
            content: ""; }

        .fa-bell:before {
            content: ""; }

        .fa-certificate:before {
            content: ""; }

        .fa-hand-o-right:before {
            content: ""; }

        .fa-hand-o-left:before {
            content: ""; }

        .fa-hand-o-up:before {
            content: ""; }

        .fa-hand-o-down:before {
            content: ""; }

        .fa-arrow-circle-left:before {
            content: ""; }

        .fa-arrow-circle-right:before {
            content: ""; }

        .fa-arrow-circle-up:before {
            content: ""; }

        .fa-arrow-circle-down:before {
            content: ""; }

        .fa-globe:before {
            content: ""; }

        .fa-wrench:before {
            content: ""; }

        .fa-tasks:before {
            content: ""; }

        .fa-filter:before {
            content: ""; }

        .fa-briefcase:before {
            content: ""; }

        .fa-arrows-alt:before {
            content: ""; }

        .fa-group:before,
        .fa-users:before {
            content: ""; }

        .fa-chain:before,
        .fa-link:before {
            content: ""; }

        .fa-cloud:before {
            content: ""; }

        .fa-flask:before {
            content: ""; }

        .fa-cut:before,
        .fa-scissors:before {
            content: ""; }

        .fa-copy:before,
        .fa-files-o:before {
            content: ""; }

        .fa-paperclip:before {
            content: ""; }

        .fa-save:before,
        .fa-floppy-o:before {
            content: ""; }

        .fa-square:before {
            content: ""; }

        .fa-navicon:before,
        .fa-reorder:before,
        .fa-bars:before {
            content: ""; }

        .fa-list-ul:before {
            content: ""; }

        .fa-list-ol:before {
            content: ""; }

        .fa-strikethrough:before {
            content: ""; }

        .fa-underline:before {
            content: ""; }

        .fa-table:before {
            content: ""; }

        .fa-magic:before {
            content: ""; }

        .fa-truck:before {
            content: ""; }

        .fa-pinterest:before {
            content: ""; }

        .fa-pinterest-square:before {
            content: ""; }

        .fa-google-plus-square:before {
            content: ""; }

        .fa-google-plus:before {
            content: ""; }

        .fa-money:before {
            content: ""; }

        .fa-caret-down:before {
            content: ""; }

        .fa-caret-up:before {
            content: ""; }

        .fa-caret-left:before {
            content: ""; }

        .fa-caret-right:before {
            content: ""; }

        .fa-columns:before {
            content: ""; }

        .fa-unsorted:before,
        .fa-sort:before {
            content: ""; }

        .fa-sort-down:before,
        .fa-sort-desc:before {
            content: ""; }

        .fa-sort-up:before,
        .fa-sort-asc:before {
            content: ""; }

        .fa-envelope:before {
            content: ""; }

        .fa-linkedin:before {
            content: ""; }

        .fa-rotate-left:before,
        .fa-undo:before {
            content: ""; }

        .fa-legal:before,
        .fa-gavel:before {
            content: ""; }

        .fa-dashboard:before,
        .fa-tachometer:before {
            content: ""; }

        .fa-comment-o:before {
            content: ""; }

        .fa-comments-o:before {
            content: ""; }

        .fa-flash:before,
        .fa-bolt:before {
            content: ""; }

        .fa-sitemap:before {
            content: ""; }

        .fa-umbrella:before {
            content: ""; }

        .fa-paste:before,
        .fa-clipboard:before {
            content: ""; }

        .fa-lightbulb-o:before {
            content: ""; }

        .fa-exchange:before {
            content: ""; }

        .fa-cloud-download:before {
            content: ""; }

        .fa-cloud-upload:before {
            content: ""; }

        .fa-user-md:before {
            content: ""; }

        .fa-stethoscope:before {
            content: ""; }

        .fa-suitcase:before {
            content: ""; }

        .fa-bell-o:before {
            content: ""; }

        .fa-coffee:before {
            content: ""; }

        .fa-cutlery:before {
            content: ""; }

        .fa-file-text-o:before {
            content: ""; }

        .fa-building-o:before {
            content: ""; }

        .fa-hospital-o:before {
            content: ""; }

        .fa-ambulance:before {
            content: ""; }

        .fa-medkit:before {
            content: ""; }

        .fa-fighter-jet:before {
            content: ""; }

        .fa-beer:before {
            content: ""; }

        .fa-h-square:before {
            content: ""; }

        .fa-plus-square:before {
            content: ""; }

        .fa-angle-double-left:before {
            content: ""; }

        .fa-angle-double-right:before {
            content: ""; }

        .fa-angle-double-up:before {
            content: ""; }

        .fa-angle-double-down:before {
            content: ""; }

        .fa-angle-left:before {
            content: ""; }

        .fa-angle-right:before {
            content: ""; }

        .fa-angle-up:before {
            content: ""; }

        .fa-angle-down:before {
            content: ""; }

        .fa-desktop:before {
            content: ""; }

        .fa-laptop:before {
            content: ""; }

        .fa-tablet:before {
            content: ""; }

        .fa-mobile-phone:before,
        .fa-mobile:before {
            content: ""; }

        .fa-circle-o:before {
            content: ""; }

        .fa-quote-left:before {
            content: ""; }

        .fa-quote-right:before {
            content: ""; }

        .fa-spinner:before {
            content: ""; }

        .fa-circle:before {
            content: ""; }

        .fa-mail-reply:before,
        .fa-reply:before {
            content: ""; }

        .fa-github-alt:before {
            content: ""; }

        .fa-folder-o:before {
            content: ""; }

        .fa-folder-open-o:before {
            content: ""; }

        .fa-smile-o:before {
            content: ""; }

        .fa-frown-o:before {
            content: ""; }

        .fa-meh-o:before {
            content: ""; }

        .fa-gamepad:before {
            content: ""; }

        .fa-keyboard-o:before {
            content: ""; }

        .fa-flag-o:before {
            content: ""; }

        .fa-flag-checkered:before {
            content: ""; }

        .fa-terminal:before {
            content: ""; }

        .fa-code:before {
            content: ""; }

        .fa-mail-reply-all:before,
        .fa-reply-all:before {
            content: ""; }

        .fa-star-half-empty:before,
        .fa-star-half-full:before,
        .fa-star-half-o:before {
            content: ""; }

        .fa-location-arrow:before {
            content: ""; }

        .fa-crop:before {
            content: ""; }

        .fa-code-fork:before {
            content: ""; }

        .fa-unlink:before,
        .fa-chain-broken:before {
            content: ""; }

        .fa-question:before {
            content: ""; }

        .fa-info:before {
            content: ""; }

        .fa-exclamation:before {
            content: ""; }

        .fa-superscript:before {
            content: ""; }

        .fa-subscript:before {
            content: ""; }

        .fa-eraser:before {
            content: ""; }

        .fa-puzzle-piece:before {
            content: ""; }

        .fa-microphone:before {
            content: ""; }

        .fa-microphone-slash:before {
            content: ""; }

        .fa-shield:before {
            content: ""; }

        .fa-calendar-o:before {
            content: ""; }

        .fa-fire-extinguisher:before {
            content: ""; }

        .fa-rocket:before {
            content: ""; }

        .fa-maxcdn:before {
            content: ""; }

        .fa-chevron-circle-left:before {
            content: ""; }

        .fa-chevron-circle-right:before {
            content: ""; }

        .fa-chevron-circle-up:before {
            content: ""; }

        .fa-chevron-circle-down:before {
            content: ""; }

        .fa-html5:before {
            content: ""; }

        .fa-css3:before {
            content: ""; }

        .fa-anchor:before {
            content: ""; }

        .fa-unlock-alt:before {
            content: ""; }

        .fa-bullseye:before {
            content: ""; }

        .fa-ellipsis-h:before {
            content: ""; }

        .fa-ellipsis-v:before {
            content: ""; }

        .fa-rss-square:before {
            content: ""; }

        .fa-play-circle:before {
            content: ""; }

        .fa-ticket:before {
            content: ""; }

        .fa-minus-square:before {
            content: ""; }

        .fa-minus-square-o:before {
            content: ""; }

        .fa-level-up:before {
            content: ""; }

        .fa-level-down:before {
            content: ""; }

        .fa-check-square:before {
            content: ""; }

        .fa-pencil-square:before {
            content: ""; }

        .fa-external-link-square:before {
            content: ""; }

        .fa-share-square:before {
            content: ""; }

        .fa-compass:before {
            content: ""; }

        .fa-toggle-down:before,
        .fa-caret-square-o-down:before {
            content: ""; }

        .fa-toggle-up:before,
        .fa-caret-square-o-up:before {
            content: ""; }

        .fa-toggle-right:before,
        .fa-caret-square-o-right:before {
            content: ""; }

        .fa-euro:before,
        .fa-eur:before {
            content: ""; }

        .fa-gbp:before {
            content: ""; }

        .fa-dollar:before,
        .fa-usd:before {
            content: ""; }

        .fa-rupee:before,
        .fa-inr:before {
            content: ""; }

        .fa-cny:before,
        .fa-rmb:before,
        .fa-yen:before,
        .fa-jpy:before {
            content: ""; }

        .fa-ruble:before,
        .fa-rouble:before,
        .fa-rub:before {
            content: ""; }

        .fa-won:before,
        .fa-krw:before {
            content: ""; }

        .fa-bitcoin:before,
        .fa-btc:before {
            content: ""; }

        .fa-file:before {
            content: ""; }

        .fa-file-text:before {
            content: ""; }

        .fa-sort-alpha-asc:before {
            content: ""; }

        .fa-sort-alpha-desc:before {
            content: ""; }

        .fa-sort-amount-asc:before {
            content: ""; }

        .fa-sort-amount-desc:before {
            content: ""; }

        .fa-sort-numeric-asc:before {
            content: ""; }

        .fa-sort-numeric-desc:before {
            content: ""; }

        .fa-thumbs-up:before {
            content: ""; }

        .fa-thumbs-down:before {
            content: ""; }

        .fa-youtube-square:before {
            content: ""; }

        .fa-youtube:before {
            content: ""; }

        .fa-xing:before {
            content: ""; }

        .fa-xing-square:before {
            content: ""; }

        .fa-youtube-play:before {
            content: ""; }

        .fa-dropbox:before {
            content: ""; }

        .fa-stack-overflow:before {
            content: ""; }

        .fa-instagram:before {
            content: ""; }

        .fa-flickr:before {
            content: ""; }

        .fa-adn:before {
            content: ""; }

        .fa-bitbucket:before {
            content: ""; }

        .fa-bitbucket-square:before {
            content: ""; }

        .fa-tumblr:before {
            content: ""; }

        .fa-tumblr-square:before {
            content: ""; }

        .fa-long-arrow-down:before {
            content: ""; }

        .fa-long-arrow-up:before {
            content: ""; }

        .fa-long-arrow-left:before {
            content: ""; }

        .fa-long-arrow-right:before {
            content: ""; }

        .fa-apple:before {
            content: ""; }

        .fa-windows:before {
            content: ""; }

        .fa-android:before {
            content: ""; }

        .fa-linux:before {
            content: ""; }

        .fa-dribbble:before {
            content: ""; }

        .fa-skype:before {
            content: ""; }

        .fa-foursquare:before {
            content: ""; }

        .fa-trello:before {
            content: ""; }

        .fa-female:before {
            content: ""; }

        .fa-male:before {
            content: ""; }

        .fa-gittip:before,
        .fa-gratipay:before {
            content: ""; }

        .fa-sun-o:before {
            content: ""; }

        .fa-moon-o:before {
            content: ""; }

        .fa-archive:before {
            content: ""; }

        .fa-bug:before {
            content: ""; }

        .fa-vk:before {
            content: ""; }

        .fa-weibo:before {
            content: ""; }

        .fa-renren:before {
            content: ""; }

        .fa-pagelines:before {
            content: ""; }

        .fa-stack-exchange:before {
            content: ""; }

        .fa-arrow-circle-o-right:before {
            content: ""; }

        .fa-arrow-circle-o-left:before {
            content: ""; }

        .fa-toggle-left:before,
        .fa-caret-square-o-left:before {
            content: ""; }

        .fa-dot-circle-o:before {
            content: ""; }

        .fa-wheelchair:before {
            content: ""; }

        .fa-vimeo-square:before {
            content: ""; }

        .fa-turkish-lira:before,
        .fa-try:before {
            content: ""; }

        .fa-plus-square-o:before {
            content: ""; }

        .fa-space-shuttle:before {
            content: ""; }

        .fa-slack:before {
            content: ""; }

        .fa-envelope-square:before {
            content: ""; }

        .fa-wordpress:before {
            content: ""; }

        .fa-openid:before {
            content: ""; }

        .fa-institution:before,
        .fa-bank:before,
        .fa-university:before {
            content: ""; }

        .fa-mortar-board:before,
        .fa-graduation-cap:before {
            content: ""; }

        .fa-yahoo:before {
            content: ""; }

        .fa-google:before {
            content: ""; }

        .fa-reddit:before {
            content: ""; }

        .fa-reddit-square:before {
            content: ""; }

        .fa-stumbleupon-circle:before {
            content: ""; }

        .fa-stumbleupon:before {
            content: ""; }

        .fa-delicious:before {
            content: ""; }

        .fa-digg:before {
            content: ""; }

        .fa-pied-piper:before {
            content: ""; }

        .fa-pied-piper-alt:before {
            content: ""; }

        .fa-drupal:before {
            content: ""; }

        .fa-joomla:before {
            content: ""; }

        .fa-language:before {
            content: ""; }

        .fa-fax:before {
            content: ""; }

        .fa-building:before {
            content: ""; }

        .fa-child:before {
            content: ""; }

        .fa-paw:before {
            content: ""; }

        .fa-spoon:before {
            content: ""; }

        .fa-cube:before {
            content: ""; }

        .fa-cubes:before {
            content: ""; }

        .fa-behance:before {
            content: ""; }

        .fa-behance-square:before {
            content: ""; }

        .fa-steam:before {
            content: ""; }

        .fa-steam-square:before {
            content: ""; }

        .fa-recycle:before {
            content: ""; }

        .fa-automobile:before,
        .fa-car:before {
            content: ""; }

        .fa-cab:before,
        .fa-taxi:before {
            content: ""; }

        .fa-tree:before {
            content: ""; }

        .fa-spotify:before {
            content: ""; }

        .fa-deviantart:before {
            content: ""; }

        .fa-soundcloud:before {
            content: ""; }

        .fa-database:before {
            content: ""; }

        .fa-file-pdf-o:before {
            content: ""; }

        .fa-file-word-o:before {
            content: ""; }

        .fa-file-excel-o:before {
            content: ""; }

        .fa-file-powerpoint-o:before {
            content: ""; }

        .fa-file-photo-o:before,
        .fa-file-picture-o:before,
        .fa-file-image-o:before {
            content: ""; }

        .fa-file-zip-o:before,
        .fa-file-archive-o:before {
            content: ""; }

        .fa-file-sound-o:before,
        .fa-file-audio-o:before {
            content: ""; }

        .fa-file-movie-o:before,
        .fa-file-video-o:before {
            content: ""; }

        .fa-file-code-o:before {
            content: ""; }

        .fa-vine:before {
            content: ""; }

        .fa-codepen:before {
            content: ""; }

        .fa-jsfiddle:before {
            content: ""; }

        .fa-life-bouy:before,
        .fa-life-buoy:before,
        .fa-life-saver:before,
        .fa-support:before,
        .fa-life-ring:before {
            content: ""; }

        .fa-circle-o-notch:before {
            content: ""; }

        .fa-ra:before,
        .fa-rebel:before {
            content: ""; }

        .fa-ge:before,
        .fa-empire:before {
            content: ""; }

        .fa-git-square:before {
            content: ""; }

        .fa-git:before {
            content: ""; }

        .fa-y-combinator-square:before,
        .fa-yc-square:before,
        .fa-hacker-news:before {
            content: ""; }

        .fa-tencent-weibo:before {
            content: ""; }

        .fa-qq:before {
            content: ""; }

        .fa-wechat:before,
        .fa-weixin:before {
            content: ""; }

        .fa-send:before,
        .fa-paper-plane:before {
            content: ""; }

        .fa-send-o:before,
        .fa-paper-plane-o:before {
            content: ""; }

        .fa-history:before {
            content: ""; }

        .fa-circle-thin:before {
            content: ""; }

        .fa-header:before {
            content: ""; }

        .fa-paragraph:before {
            content: ""; }

        .fa-sliders:before {
            content: ""; }

        .fa-share-alt:before {
            content: ""; }

        .fa-share-alt-square:before {
            content: ""; }

        .fa-bomb:before {
            content: ""; }

        .fa-soccer-ball-o:before,
        .fa-futbol-o:before {
            content: ""; }

        .fa-tty:before {
            content: ""; }

        .fa-binoculars:before {
            content: ""; }

        .fa-plug:before {
            content: ""; }

        .fa-slideshare:before {
            content: ""; }

        .fa-twitch:before {
            content: ""; }

        .fa-yelp:before {
            content: ""; }

        .fa-newspaper-o:before {
            content: ""; }

        .fa-wifi:before {
            content: ""; }

        .fa-calculator:before {
            content: ""; }

        .fa-paypal:before {
            content: ""; }

        .fa-google-wallet:before {
            content: ""; }

        .fa-cc-visa:before {
            content: ""; }

        .fa-cc-mastercard:before {
            content: ""; }

        .fa-cc-discover:before {
            content: ""; }

        .fa-cc-amex:before {
            content: ""; }

        .fa-cc-paypal:before {
            content: ""; }

        .fa-cc-stripe:before {
            content: ""; }

        .fa-bell-slash:before {
            content: ""; }

        .fa-bell-slash-o:before {
            content: ""; }

        .fa-trash:before {
            content: ""; }

        .fa-copyright:before {
            content: ""; }

        .fa-at:before {
            content: ""; }

        .fa-eyedropper:before {
            content: ""; }

        .fa-paint-brush:before {
            content: ""; }

        .fa-birthday-cake:before {
            content: ""; }

        .fa-area-chart:before {
            content: ""; }

        .fa-pie-chart:before {
            content: ""; }

        .fa-line-chart:before {
            content: ""; }

        .fa-lastfm:before {
            content: ""; }

        .fa-lastfm-square:before {
            content: ""; }

        .fa-toggle-off:before {
            content: ""; }

        .fa-toggle-on:before {
            content: ""; }

        .fa-bicycle:before {
            content: ""; }

        .fa-bus:before {
            content: ""; }

        .fa-ioxhost:before {
            content: ""; }

        .fa-angellist:before {
            content: ""; }

        .fa-cc:before {
            content: ""; }

        .fa-shekel:before,
        .fa-sheqel:before,
        .fa-ils:before {
            content: ""; }

        .fa-meanpath:before {
            content: ""; }

        .fa-buysellads:before {
            content: ""; }

        .fa-connectdevelop:before {
            content: ""; }

        .fa-dashcube:before {
            content: ""; }

        .fa-forumbee:before {
            content: ""; }

        .fa-leanpub:before {
            content: ""; }

        .fa-sellsy:before {
            content: ""; }

        .fa-shirtsinbulk:before {
            content: ""; }

        .fa-simplybuilt:before {
            content: ""; }

        .fa-skyatlas:before {
            content: ""; }

        .fa-cart-plus:before {
            content: ""; }

        .fa-cart-arrow-down:before {
            content: ""; }

        .fa-diamond:before {
            content: ""; }

        .fa-ship:before {
            content: ""; }

        .fa-user-secret:before {
            content: ""; }

        .fa-motorcycle:before {
            content: ""; }

        .fa-street-view:before {
            content: ""; }

        .fa-heartbeat:before {
            content: ""; }

        .fa-venus:before {
            content: ""; }

        .fa-mars:before {
            content: ""; }

        .fa-mercury:before {
            content: ""; }

        .fa-intersex:before,
        .fa-transgender:before {
            content: ""; }

        .fa-transgender-alt:before {
            content: ""; }

        .fa-venus-double:before {
            content: ""; }

        .fa-mars-double:before {
            content: ""; }

        .fa-venus-mars:before {
            content: ""; }

        .fa-mars-stroke:before {
            content: ""; }

        .fa-mars-stroke-v:before {
            content: ""; }

        .fa-mars-stroke-h:before {
            content: ""; }

        .fa-neuter:before {
            content: ""; }

        .fa-genderless:before {
            content: ""; }

        .fa-facebook-official:before {
            content: ""; }

        .fa-pinterest-p:before {
            content: ""; }

        .fa-whatsapp:before {
            content: ""; }

        .fa-server:before {
            content: ""; }

        .fa-user-plus:before {
            content: ""; }

        .fa-user-times:before {
            content: ""; }

        .fa-hotel:before,
        .fa-bed:before {
            content: ""; }

        .fa-viacoin:before {
            content: ""; }

        .fa-train:before {
            content: ""; }

        .fa-subway:before {
            content: ""; }

        .fa-medium:before {
            content: ""; }

        .fa-yc:before,
        .fa-y-combinator:before {
            content: ""; }

        .fa-optin-monster:before {
            content: ""; }

        .fa-opencart:before {
            content: ""; }

        .fa-expeditedssl:before {
            content: ""; }

        .fa-battery-4:before,
        .fa-battery-full:before {
            content: ""; }

        .fa-battery-3:before,
        .fa-battery-three-quarters:before {
            content: ""; }

        .fa-battery-2:before,
        .fa-battery-half:before {
            content: ""; }

        .fa-battery-1:before,
        .fa-battery-quarter:before {
            content: ""; }

        .fa-battery-0:before,
        .fa-battery-empty:before {
            content: ""; }

        .fa-mouse-pointer:before {
            content: ""; }

        .fa-i-cursor:before {
            content: ""; }

        .fa-object-group:before {
            content: ""; }

        .fa-object-ungroup:before {
            content: ""; }

        .fa-sticky-note:before {
            content: ""; }

        .fa-sticky-note-o:before {
            content: ""; }

        .fa-cc-jcb:before {
            content: ""; }

        .fa-cc-diners-club:before {
            content: ""; }

        .fa-clone:before {
            content: ""; }

        .fa-balance-scale:before {
            content: ""; }

        .fa-hourglass-o:before {
            content: ""; }

        .fa-hourglass-1:before,
        .fa-hourglass-start:before {
            content: ""; }

        .fa-hourglass-2:before,
        .fa-hourglass-half:before {
            content: ""; }

        .fa-hourglass-3:before,
        .fa-hourglass-end:before {
            content: ""; }

        .fa-hourglass:before {
            content: ""; }

        .fa-hand-grab-o:before,
        .fa-hand-rock-o:before {
            content: ""; }

        .fa-hand-stop-o:before,
        .fa-hand-paper-o:before {
            content: ""; }

        .fa-hand-scissors-o:before {
            content: ""; }

        .fa-hand-lizard-o:before {
            content: ""; }

        .fa-hand-spock-o:before {
            content: ""; }

        .fa-hand-pointer-o:before {
            content: ""; }

        .fa-hand-peace-o:before {
            content: ""; }

        .fa-trademark:before {
            content: ""; }

        .fa-registered:before {
            content: ""; }

        .fa-creative-commons:before {
            content: ""; }

        .fa-gg:before {
            content: ""; }

        .fa-gg-circle:before {
            content: ""; }

        .fa-tripadvisor:before {
            content: ""; }

        .fa-odnoklassniki:before {
            content: ""; }

        .fa-odnoklassniki-square:before {
            content: ""; }

        .fa-get-pocket:before {
            content: ""; }

        .fa-wikipedia-w:before {
            content: ""; }

        .fa-safari:before {
            content: ""; }

        .fa-chrome:before {
            content: ""; }

        .fa-firefox:before {
            content: ""; }

        .fa-opera:before {
            content: ""; }

        .fa-internet-explorer:before {
            content: ""; }

        .fa-tv:before,
        .fa-television:before {
            content: ""; }

        .fa-contao:before {
            content: ""; }

        .fa-500px:before {
            content: ""; }

        .fa-amazon:before {
            content: ""; }

        .fa-calendar-plus-o:before {
            content: ""; }

        .fa-calendar-minus-o:before {
            content: ""; }

        .fa-calendar-times-o:before {
            content: ""; }

        .fa-calendar-check-o:before {
            content: ""; }

        .fa-industry:before {
            content: ""; }

        .fa-map-pin:before {
            content: ""; }

        .fa-map-signs:before {
            content: ""; }

        .fa-map-o:before {
            content: ""; }

        .fa-map:before {
            content: ""; }

        .fa-commenting:before {
            content: ""; }

        .fa-commenting-o:before {
            content: ""; }

        .fa-houzz:before {
            content: ""; }

        .fa-vimeo:before {
            content: ""; }

        .fa-black-tie:before {
            content: ""; }

        .fa-fonticons:before {
            content: ""; }

        .fa-reddit-alien:before {
            content: ""; }

        .fa-edge:before {
            content: ""; }

        .fa-credit-card-alt:before {
            content: ""; }

        .fa-codiepie:before {
            content: ""; }

        .fa-modx:before {
            content: ""; }

        .fa-fort-awesome:before {
            content: ""; }

        .fa-usb:before {
            content: ""; }

        .fa-product-hunt:before {
            content: ""; }

        .fa-mixcloud:before {
            content: ""; }

        .fa-scribd:before {
            content: ""; }

        .fa-pause-circle:before {
            content: ""; }

        .fa-pause-circle-o:before {
            content: ""; }

        .fa-stop-circle:before {
            content: ""; }

        .fa-stop-circle-o:before {
            content: ""; }

        .fa-shopping-bag:before {
            content: ""; }

        .fa-shopping-basket:before {
            content: ""; }

        .fa-hashtag:before {
            content: ""; }

        .fa-bluetooth:before {
            content: ""; }

        .fa-bluetooth-b:before {
            content: ""; }

        .fa-percent:before {
            content: ""; }

    </style>