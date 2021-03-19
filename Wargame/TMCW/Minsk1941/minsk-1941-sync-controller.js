import {SyncController} from "../../wargame-helpers/Vue/sync-controller";
import {syncObj} from "@markarian/wargame-helpers";

import {DR} from "@markarian/wargame-helpers";
import Vue from "vue";
export class Minsk1941SyncController extends SyncController{
    constructor(){
        super();
    }

    vp(){

    }
        victory(){
        syncObj.register("victory", (victory, data) => {

            let p1 = DR.playerOne;
            let p2 = DR.playerTwo;
            let p1Class = p1.replace(/ /g, '-');
            let p2Class = p2.replace(/ /g, '-');
            let vp = victory.victoryPoints;
            p1Class =  p1Class.replace(/\//ig, '_') + 'Face';
            p2Class = p2Class.replace(/\//ig, '_') + 'Face';

            let vlabel = " Victory: <span class='" + p1Class + "'>" + p1 + " </span>" + vp[1];
            vlabel += " <span class='" + p2Class + "'>" + p2 + " </span>" + vp[2];
            vlabel += " Surrounded " + vp[3];
            vueStore.commit('headerData/victory',vlabel);
        });
    }
}