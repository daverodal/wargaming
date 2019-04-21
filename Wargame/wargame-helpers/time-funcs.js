import {DR} from './DR'
import {syncObj as x } from './Vue/syncObj';
export function clickBack(){
    x.timeTravel = true;
    if (x.current) {
        x.current.abort();
    }
    var click = DR.currentClick;
    click--;
    x.fetch(click);
}

export function phaseBack(){
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

}


export function phaseSurge(){
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
}

export function playerTurnBack(){
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

}

export function playerTurnSurge(){
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
}


export function clickSurge(){
    var click = DR.currentClick;
    click++;
    x.fetch(click);
}

export function timeBranch(){
    x.timeTravel = true;
    x.timeBranch = true;
    if (x.current) {
        x.current.abort();
    }
    var click = DR.currentClick;
    x.fetch(click);
    $("#TimeWrapper .WrapperLabel").click();
}


export function timeLive(){
    $("#TimeWrapper .WrapperLabel").click();
    x.timeTravel = false;
    x.fetch(0);
}
