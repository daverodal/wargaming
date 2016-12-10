<?php
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
?>
<script type="text/javascript">
x.register("vp", function (vp, data) {
    debugger;


var p1 = DR.playerOne.replace(/ /g, '-');
var p2 = DR.playerTwo.replace(/ /g, '-');

var p1 = 'player' + p1.replace(/\//ig, '_') + 'Face';
var p2 = 'player' + p2.replace(/\//ig, '_') + 'Face';

$("#victory").html(" Victory: <span class='" + p1 + "'>" + DR.playerOne + " </span>" + vp[1] + " Casualties <span class='" + p1 + "'>" + DR.playerOne + " </span>" + data.victory.casualties[1] + " <span class='" + p2 + "'>" + DR.playerTwo + " </span>" + data.victory.casualties[2]);
if (typeof victoryExtend === 'function') {
victoryExtend(vp, data);
}

});
</script>