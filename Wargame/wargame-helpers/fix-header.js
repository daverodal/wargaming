/**
 * Created by david on 2/19/17.
 */
/**
 * Created by PhpStorm.
 * User: david
 * Date: 2/19/17
 * Time: 4:59 PM

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
export default function fixHeader() {

    var DR = window.DR;
    var winHeight = $(window).height();
    var winWidth = $(window).width();
    var mapHeight = $("#map").height();
    var mapWidth = $("#map").width();
    var containerHeight = mapHeight;
    var containerWidth = mapWidth;

    if (winWidth > mapWidth) {
        containerWidth = winWidth;
    }
    if (winHeight > mapHeight) {
        containerHeight = winHeight;
    }
    $("#gameImages, #gameContainer").height(containerHeight).width(containerWidth);
    $("#arrow-svg").height(mapHeight).width(mapWidth);
    DR.$panzoom.panzoom('resetDimensions');

    height = $("#crtWrapper h4").height();
    $("#bottomHeader").css("height", height);

    var headerHeight = $("#header").height();
    $("#content").css("margin-top", 0);
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
