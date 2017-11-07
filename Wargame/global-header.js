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
import {initialize, x} from "./wargame-helpers";
var DR = window.DR;
var zoomed = false;
// $.ajaxSetup({
//     headers: {
//         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//     }
// });
window.zoomed = zoomed;

document.addEventListener("DOMContentLoaded",function(){


    var DR = window.DR;

    if (!DR) {
        DR = {};
    }
    DR.$ = $;
    console.log("$ Registered");
    DR.globalZoom = 1;
    DR.playerNameMap = ["Zero", "One", "Two", "Three", "Four"];

    DR.players = ["observer", DR.playerOne, DR.playerTwo, DR.playerThree, DR.playerFour];
    DR.crtDetails = false;
    DR.showArrows = false;


    var $panzoom = $('#gameImages').panzoom({
        cursor: "normal", animate: true, maxScale: 2.0, minScale: .3, onPan: function (e, panzoom, e2, e3, e4) {

            var event = e;
            var xDrag;
            var yDrag;
            if (event.type === 'touchmove') {
                // xDrag = Math.abs(event.touches[0].clientX - DR.clickX);
                // yDrag = Math.abs(event.touches[0].clientY - DR.clickY);
                // if (xDrag > 40 || yDrag > 40) {
                //     // DR.dragged = true;
                // }
            } else {
                // xDrag = Math.abs(event.clientX - DR.clickX);
                // yDrag = Math.abs(event.clientY - DR.clickY);
                // if (xDrag > 4 || yDrag > 4) {
                //     // DR.dragged = true;
                // }
            }
            // DR.dragged = true;
        },
        onZoom: function (e, p, q) {
            DR.globalZoom = q;
            var out = DR.globalZoom.toFixed(1);

            $("#zoom .defaultZoom").html(out);
        },
        onEnd: function(a,b,c,d,e){

            var xDrag = Math.abs(a.clientX - DR.clickX);
            var yDrag = Math.abs(a.clientY - DR.clickY);

            if (xDrag > 4 || yDrag > 4) {
                DR.dragged = true;
            }


        },
        onStart: function(a,b,c,d,e){
            DR.dragged = false;
            DR.clickX = c.clientX;
            DR.clickY = c.clientY;



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
    x.fetch(0);

    console.log(DR.exported);

});

var state = {
    x: 0,
    y: 0,
    scale: 1
};
var oX, oY;

document.addEventListener("DOMContentLoaded",initialize);

// Copyright (c) 2009-2011 Mark Butler
// This program is free software; you can redistribute it
// and/or modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation;
// either version 2 of the License, or (at your option) any later version.

// main classes for wargame

export {DR}