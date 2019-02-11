import {DR} from "../global-header";

export function clickBack(){
    let x = DR.sync;
    x.timeTravel = true;
    if (x.current) {
        x.current.abort();
    }
    var click = DR.currentClick;
    click--;
    x.fetch(click);
}

export function phaseBack(){
    let x = DR.sync;
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
    let x = DR.sync;
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
    let x = DR.sync;
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
    let x = DR.sync;
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
    let x = DR.sync;
    var click = DR.currentClick;
    click++;
    x.fetch(click);
}

export function timeBranch(){
    let x = DR.sync;
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
    let x = DR.sync;
    $("#TimeWrapper .WrapperLabel").click();
    x.timeTravel = false;
    x.fetch(0);
}
