/**
 * Created by david on 2/19/17.
 */
/**
 * Created by PhpStorm.
 * User: david
 * Date: 2/19/17
 * Time: 3:21 PM

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
/* globl-vue-header */
import {rotateUnits} from './global-vue-helper';
import { syncObj } from "@markarian/wargame-helpers";
import {counterClick, fixItAll, mapClick, doitCRT} from "@markarian/wargame-helpers";
import {DR} from '@markarian/wargame-helpers'
import {store} from "./store/store";

import "@markarian/wargame-helpers";
import {clickBack,phaseBack,playerTurnBack, clickSurge, phaseSurge, playerTurnSurge, timeBranch, timeLive} from "@markarian/wargame-helpers";

/* global-vue-header 2 */
document.addEventListener("DOMContentLoaded",function(){

    DR.$ = $;
    DR.globalZoom = 1;
    DR.playerNameMap = ["Zero", "One", "Two", "Three", "Four"];

    DR.players = ["observer", DR.playerOne, DR.playerTwo, DR.playerThree, DR.playerFour];
    DR.crtDetails = false;
    DR.showArrows = false;
    DR.doingZoom = false;

    DR.$floatMessagePanZoom = $('#float-message-drag-wrapper').panzoom({cursor: "normal", disableZoom: true, onPan: function (e, panzoom) {
        console.log("Floag Pan ");
        DR.floatMessageDragged = true;
        }
    });
    let crtPanzoom = $('#crt-drag-wrapper').panzoom({
            onPan: function (e, panzoom, e2, e3, e4) {
                console.log('i pan');
            },
            onEnd(a,b,c,d,e,f){
                let vueStore = store;

                let clientX = a.clientX;
                let clientY = a.clientY;
                if(a.originalEvent.changedTouches) {
                    clientX = a.originalEvent.changedTouches[0].clientX;
                    clientY = a.originalEvent.changedTouches[0].clientY;
                }

                var xDrag = Math.abs(clientX - DR.clickX);
                var yDrag = Math.abs(clientY - DR.clickY);
                if (xDrag > 20 || yDrag > 20) {
                    vueStore.commit('dragCrt')
                    DR.dragged = true;
                }else{
                    vueStore.commit('clearDragCrt')
                    if(a.target.id.startsWith('crt-col-')){
                        let index = a.target.id.slice(8);
                        doitCRT(index-0+1);

                    }
                    let matches;
                    if(a.target.id === 'crt-details-button'){
                        vueStore.commit('toggleShowDetails')
                    }
                    if(a.target.id === 'crt-close'){
                        vueStore.commit('setCrt', {showCrt: false});
                    }
                    if(a.target.id.match(/^crt-col-/)){
                        // matches = [...a.target.id.matchAll(/^crt-col-(\d+)/)];
                        // const index = matches[0][1] - 0;
                        // doitCRT(index + 1);
                        return false;
                    }

                }
                return true;
            },
            onStart(a,b,c,d,e,f,g){
                DR.doingZoom = false;

                DR.dragged = false;
                DR.startTime = Date.now() - 0;
                if(c.changedTouches){
                    DR.clickX = c.changedTouches[0].clientX;
                    DR.clickY = c.changedTouches[0].clientY;
                }else{
                    DR.clickX = c.clientX;
                    DR.clickY = c.clientY;
                }


            }
        }
    );
    let undoPanzoom = $('#undo-drag-wrapper').panzoom({
        onPan: function (e, panzoom, e2, e3, e4) {
            console.log('i pan');
        },
        onEnd(a,b,c,d,e,f){

            let clientX = a.clientX;
            let clientY = a.clientY;
            if(a.originalEvent.changedTouches) {
                clientX = a.originalEvent.changedTouches[0].clientX;
                clientY = a.originalEvent.changedTouches[0].clientY;
            }

            var xDrag = Math.abs(clientX - DR.clickX);
            var yDrag = Math.abs(clientY - DR.clickY);
            if (xDrag > 20 || yDrag > 20) {
                DR.dragged = true;
            }else{

                switch(a.target.id){
                    case 'click-back':
                        clickBack();
                        return;
                    case 'phase-back':
                        phaseBack();
                        return;
                    case 'player-turn-back':
                        playerTurnBack();
                        return;
                    case 'click-surge':
                        clickSurge();
                        return;
                    case 'phase-surge':
                        phaseSurge();
                        return;
                    case 'player-turn-surge':
                        playerTurnSurge();
                        return;
                    case 'time-live':
                        timeLive();
                        vueStore.commit('setShowUndo', false)
                        // vueStore.state.timeTravel.showUndo = false;
                        return;
                    case 'time-branch':
                        timeBranch();
                        vueStore.commit('setShowUndo', false)

                        // vueStore.state.timeTravel.showUndo = false;
                        return;
                }
            }
        },
        onStart(a,b,c,d,e,f,g){
            DR.doingZoom = false;

            DR.dragged = false;
            DR.startTime = Date.now() - 0;
            if(c.changedTouches){
                DR.clickX = c.changedTouches[0].clientX;
                DR.clickY = c.changedTouches[0].clientY;
            }else{
                DR.clickX = c.clientX;
                DR.clickY = c.clientY;
            }


        }
        }
    );
    var $panzoom = $('#gameContainer').panzoom({
        cursor: "normal", animate: true, maxScale: 2.0, minScale: .3, onPan: function (e, panzoom, e2, e3, e4) {
        },
        onZoom: function (e, p, q) {
            DR.globalZoom = q;
            DR.doingZoom = true;
            var out = DR.globalZoom.toFixed(1);

            $("#zoom .defaultZoom").html(out);
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

            if (xDrag > 20 || yDrag > 20) {
                DR.dragged = true;
            }else{

                if(DR.doingZoom !== true && a.originalEvent.changedTouches){
                    if(a.target.id === 'arrow-svg'){
                        mapClick(a.originalEvent);
                    }else{
                        const now = Date.now() - 0;
                        const time = now - DR.startTime;
                        if(time > 600){
                            a.ctrlKey = false;
                            rotateUnits(a, a.target.parentElement);
                        }else{
                            counterClick(a);
                        }
                    }
                }
            }

            DR.doingZoom = false;
            return false;
        },
        onStart: function(a,b,c,d,e){


            DR.doingZoom = false;

            DR.dragged = false;
            DR.startTime = Date.now() - 0;
            if(c.changedTouches){
                DR.clickX = c.changedTouches[0].clientX;
                DR.clickY = c.changedTouches[0].clientY;
            }else{
                DR.clickX = c.clientX;
                DR.clickY = c.clientY;
            }
        }
    });

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

    /* Sync object, well named as x don't start fetching till everything is ready.*/
    syncObj.fetch(0);
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

    fixItAll();
    $(window).resize(fixItAll);


});

var state = {
    x: 0,
    y: 0,
    scale: 1
};
var oX, oY;

// Copyright (c) 2009-2011 Mark Butler
// This program is free software; you can redistribute it
// and/or modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation;
// either version 2 of the License, or (at your option) any later version.

// main classes for wargame

export {DR}
