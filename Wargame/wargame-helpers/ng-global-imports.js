import "angular"
import "angular-sanitize"
import "angular-modal-service"
import "angular-right-click"
import 'jquery-ui-bundle';
import { GameController } from "./game-controller";
export { GameController }
import {DR} from "./DR";
import initialize from "./initialize";


document.addEventListener("DOMContentLoaded",function() {
    initialize();
    // fixHeader();
});

DR.globalZoom = 1;
DR.playerNameMap = ["Zero", "One", "Two", "Three", "Four"];

DR.players = ["observer", DR.playerOne, DR.playerTwo, DR.playerThree, DR.playerFour];
DR.crtDetails = false;
DR.showArrows = false;
DR.$ = $;
document.addEventListener("DOMContentLoaded",function() {

    var $panzoom = $('#gameContainer').panzoom({
        cursor: "normal", animate: true, maxScale: 2.0, minScale: .3, onPan: function (e, panzoom, e2, e3, e4) {
        },
        onZoom: function (e, p, q) {
            DR.globalZoom = q;
            DR.doingZoom = true;
            var out = DR.globalZoom.toFixed(1);

            $("#zoom .defaultZoom").html(out);
        },
        onEnd: function (a, b, c, d, e) {

            let clientX = a.clientX;
            let clientY = a.clientY;
            if (a.originalEvent.changedTouches) {
                clientX = a.originalEvent.changedTouches[0].clientX;
                clientY = a.originalEvent.changedTouches[0].clientY;
            }

            var xDrag = Math.abs(clientX - DR.clickX);
            var yDrag = Math.abs(clientY - DR.clickY);

            if (xDrag > 20 || yDrag > 20) {
                DR.dragged = true;
            } else {
                if (DR.doingZoom !== true && a.originalEvent.changedTouches) {
                    if (a.target.id === 'arrow-svg') {
                        mapClick(a.originalEvent);
                    } else {
                        const now = Date.now() - 0;
                        const time = now - DR.startTime;
                        if (time > 600) {
                            a.ctrlKey = false;
                            rotateUnits(a, a.target.parentElement);
                        } else {
                            counterClick(a);
                        }
                    }
                }
            }

            DR.doingZoom = false;
            return false;
        },
        onStart: function (a, b, c, d, e) {

            DR.doingZoom = false;

            DR.dragged = false;
            DR.startTime = Date.now() - 0;
            if (c.changedTouches) {
                DR.clickX = c.changedTouches[0].clientX;
                DR.clickY = c.changedTouches[0].clientY;
            } else {
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
});