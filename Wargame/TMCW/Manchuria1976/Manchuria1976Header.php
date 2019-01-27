<?php
/**
 *
 * Copyright 2012-2015 David Rodal
 * User: David Markarian Rodal
 * Date: 3/8/15
 * Time: 5:48 PM
 *
 *  This program is free software; you can redistribute it
 *  and/or modify it under the terms of the GNU General Public License
 *  as published by the Free Software Foundation;
 *  either version 2 of the License, or (at your option) any later version
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
?>
<script type="text/javascript">
function renderUnitNumbers(unit, moveAmount) {

var move = unit.maxMove - unit.moveAmountUsed;
if (moveAmount !== undefined) {
move = moveAmount - 0;
}
move = move.toFixed(2);
move = move.replace(/\.00$/, '');
move = move.replace(/(\.[1-9])0$/, '$1');
var str = unit.strength;
var reduced = unit.isReduced && unit.class !== 'gorilla' && unit.class !== 'supply' && unit.class !== 'militia';
var reduceDisp = "<span class='unit-info'>";
    if (reduced) {
        reduceDisp = "<span class='unit-info reduced'>";
    }
    var symb = unit.supplied !== false ? " - " : " <span class='reduced'>u</span> ";
    var html = reduceDisp + str + symb + move + "</span>";
    return html;


}
/* Victory */
x.register("victory", function(victory){
    $ = DR.$;
    if(victory.histogram) {
        $('.cas-container').empty();
        $('.cas-container').append('<div><div class="cas-row">Turn</div><div class="cas-row">Player 1</div><div class="cas-row">Player 2</div></div>');

        for (var i in victory.histogram) {
            if (i == 0) {
                continue;
            }
            var iVal = i - 0;
            var disp = '<div><div class="cas-row"> ' + iVal + '</div><div class="cas-row">' + victory.histogram[i][1] + '</div><div class="cas-row">' + victory.histogram[i][2] + '</div></div>';
            $('.cas-container').append(disp);
        }
    }
});

x.register("vp", function (vp, data) {


    var p1 = DR.playerOne.replace(/ /g, '-');
    var p2 = DR.playerTwo.replace(/ /g, '-');

    var p1 = 'player' + p1.replace(/\//ig, '_') + 'Face';
    var p2 = 'player' + p2.replace(/\//ig, '_') + 'Face';

    $("#victory").html(" Victory: <span class='" + p1 + "'>" + DR.playerOne + " </span>" + vp[1] + " <span class='" + p2 + "'>" + DR.playerTwo + " </span>" + vp[2] + "" + " Chinese Casualities " + data.victory.chineseCasualities);
    if (typeof victoryExtend === 'function') {
        victoryExtend(vp, data);
    }

});

</script>