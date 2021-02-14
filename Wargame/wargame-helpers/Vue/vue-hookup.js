import MapComponent from "./MapComponent";

window.axios = require('axios');

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * Next we will register the CSRF Token as a common header with Axios so that
 * all outgoing HTTP requests automatically have it attached. This is just
 * a simple convenience so we don't have to attach every token manually.
 */

let token = document.querySelector('meta[name="csrf-token"]');


if (token) {
    // window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    // console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}

window._ = require('lodash');
import Vue from "vue";
import {
    counterClick,
    doitKeypress,
    doitSaveGame,
    mapClick,
    nextPhaseMouseDown,
    playAudio, playAudioBuzz, playAudioLow,
    toggleFullScreen
} from "@markarian/wargame-helpers";
Vue.component('map-component', MapComponent);

import {store} from "./store/store";
import "./global-vue-header";
import "../jquery.panzoom";
import { doPostRequest } from "../doAxiosPostRequest";
import {DR} from '@markarian/wargame-helpers'
DR.doPostRequest = doPostRequest;
let myDR = DR;
import VueResource from 'vue-resource';
import "@markarian/wargame-helpers";
window.vueStore = store;
Vue.use(VueResource);
document.addEventListener("DOMContentLoaded",function(){

    window.crt = new Vue({
        el: '#crt-drag-wrapper .vue-wrapper',
        store,
        computed:{
            currentClick(){
                return this.$store.state.timeTravel.currentClick;
            },
            showCrt(){
                return this.$store.state.crt.showCrt;
            }
        },
        methods:{
            myFirstOne(){
            }
        },
        data:{
            crtOptions: {}
        }
    })

    window.undo = new Vue({
        el: '#undo-drag-wrapper .vue-wrapper',
    store,
        computed:{
            currentClick(){
                return this.$store.state.timeTravel.currentClick;
            },
            showUndo(){
               return this.$store.state.timeTravel.showUndo;
             }
        },
        methods:{
            myFirstOne(){
            }
        },
        data:{
            myName: 'david'
        }
    })
    window.topVue = new Vue({
        el: '#gameImages',
        store,
        data:{
            xunits:[],
            xunitsMap: {},
            mapSymbols: [],
            specialEvents: [],
            specialHexes: [],
            rowSvg: {x: 0, y: 0}
        },
        computed: {
            mirror(){
              return this.$store.state.mD.mirror;
            },
            units(){
              return this.$store.state.mD.dispUnits;
            },
            untisMap(){
                return this.$store.state.mD.dispUnitsMap;
            },
            mapUrl(){
                return this.$store.state.mD.mapUrl;
            },
            moveUnits(){
                return this.$store.state.mD.moveUnits;
            }
        },
        methods:{
            unitClick(e){
                counterClick(e);
            },
            getUnit(id){
                _.forEach(this.units, (mapUnit) =>{
                    if(mapUnit.id == id){
                        return mapUnit;
                    }
                })
                return {};
            },
            mapClick(event){
                mapClick(event);
            },
            pushedKey(e,a,b){
            }
        }

    });

    window.floatersMessages = new Vue({
        el: "#floatMessageContainer",
        store,
        data:{
            messages:[]
        }
    });

    window.clickThrough = new Vue({
        el: "#header",
        store,
        computed:{
            crtOpen(){
              return this.$store.state.crt.showCrt;
            },
            selectedTable(){
                return this.$store.state.getters.currentTable;
            },
            headerVictory(){
                return this.$store.state.headerData.victory;
            },
            headerStatus(){
                return this.$store.state.headerData.status;
            },
            combatStatus(){
                return this.$store.state.headerData.combatStatus;
            },
            headerTopStatus(){
                return this.$store.state.headerData.topStatus;
            },
            headerLog(){
                if(this.$store.state.headerData.log.length === 0){
                    return "<li>No Log Events</li>";
                }
                return this.$store.state.headerData.log;
            },
            allMyBoxes(){
                return this.$store.getters['bd/allBoxes'];
            },
            dynamic(){
                return this.$store.state.headerData.dynamicButtons;
            }
        },
        data:{
            headerPlayer: '',
            submenu: false,
            log: false,
            rules: false,
            commonRules: false,
            showTec: false,
            showObc: false,
            showExRules: false,
            menu: false,
            info: false,
            undo: false,
            debug: false,
            bugMessage: '',
            crtClass: 'normalCrt',
            crtOptions: {},
            dynamicButtons:{
            },
            show:{
                units:{
                    submenu:false,
                    deployBox: false,
                    deadpile: false,
                    exitBox: false
                }
            }
        },
        methods:{
            clearMenus(toggleMenu = false){

                let save;
                if(toggleMenu){
                    save = this[toggleMenu];
                }
                this.menu = false;
                this.rules = false;
                this.info = false;
                this.log = false;
                this.submenu = false;
                if(toggleMenu){
                    this[toggleMenu] = !save;
                }
            },
            toggleUndo(){
              this.$store.commit('toggleShowUndo');
            },
            shiftClick(){
                this.dynamicButtons.shiftKey = !this.dynamicButtons.shiftKey;
                DR.shiftKey = !DR.shiftKey;
                this.$store.commit('headerData/setDynamicButton', {id: 'shiftKey', value: DR.shiftKey});
            },
            clearCombat(){

                doitKeypress(67);
            },
            fullScreen(){
                toggleFullScreen()
            },
            bugReport(){
                this.debug = !this.debug;
            },
            saveBugReport(){
                doitSaveGame(this.bugMessage);
                this.bugMessage = '';
                this.bugReport();
            },
            events(){
              this.$store.commit('floaters/toggle');
            },
            nextPhase(evt){
                console.log(evt);
                nextPhaseMouseDown();
            },
            // menuClick(id){
            //     headerVue.menuClick(id);
            // },
            didDrag(){
                DR.dragged = true;
            },
            showArrows(){
                if (!DR.showArrows) {
                    $("#arrowButton").html("hide arrows");
                    DR.showArrows = true;
                    $('#arrow-svg .unit-path').show();
                } else {
                    $("#arrowButton").html("show arrows");
                    DR.showArrows = false;
                    $('#arrow-svg .unit-path').hide();
                }
            },
            mute(){
                if (!mute) {
                    $("#muteButton").html("un-mute");
                    muteMe();

                } else {
                    $("#muteButton").html("mute");
                    unMuteMe();
                    playAudio();
                }
            },
            zoom(){
                DR.globalZoom = 1.0;
                $("#zoom .defaultZoom").html(DR.globalZoom.toFixed(1));
                DR.$panzoom.panzoom('reset');
            },
            changeCrt(){
                this.$store.commit('setCrt',{showCrt: !this.$store.state.crt.showCrt})
            },
            clickCrt(){
            },  wheelo(e){
            },
            unitClick(e){
                counterClick(e);
            },
            menuClick(id){
                if(id === 'rules'){
                    this.commonRules = !this.commonRules;
                    this.rules = false;
                    this.showTec = false;
                    this.showObc = false;
                    this.showExRules = false;
                    return;
                }
                if(id === 'showObc'){
                    this.showObc = !this.showObc;
                    this.commonRules = false;
                    this.showTec = false;
                    this.rules = false;
                    this.showExRules = false;
                    return;
                }
                if(id === 'showExRules'){
                    this.showExRules = !this.showExRules;
                    this.commonRules = false;
                    this.showTec = false;
                    this.rules = false;
                    this.showObc = false;
                    return;
                }
                if(id === 'showTec'){
                    this.showTec = !this.showTec;
                    this.rules = false;
                    this.commonRules = false;
                    this.showObc = false;
                    this.showExRules = false;
                    return;
                }
                if(id === 'all'){
                    this.show.units.submenu = false;
                    this.show.units.deployBox = false;
                    this.show.units.deadpile = false;
                    this.show.units.exitBox = false;
                    this.showExRules = false;
                    return;
                }
                this.show.units[id] = !this.show.units[id];
                this.show.units.submenu = false;
            }

        }
    })
    // window.headerVue = new Vue({
    //     el: '#secondHeaderr',
    //     data:{
    //         deployBox: [],
    //         deadpile: [],
    //         exitBox: [],
    //         notUsed: [],
    //         show:{
    //             units:{
    //                 submenu:false,
    //                 deployBox: false,
    //                 deadpile: false,
    //                 exitBox: false
    //             }
    //         }
    //     },
    //     methods:{
    //         wheelo(e){
    //         },
    //         unitClick(e){
    //             counterClick(e);
    //         },
    //         menuClick(id){
    //             if(id === 'all'){
    //                 this.show.units.submenu = false;
    //                 this.show.units.deployBox = false;
    //                 this.show.units.deadpile = false;
    //                 this.show.units.exitBox = false;
    //                 return;
    //             }
    //             this.show.units[id] = !this.show.units[id];
    //             this.show.units.submenu = false;
    //         }
    //     }
    //
    // });
    document.addEventListener('keyup', function(evt) {
        const indx = "xdcms".indexOf(evt.key);
        if(indx >= 0){
            doitKeypress(event.keyCode);
            return;
        }
        if(evt.key.match(/^Arrow/)){
            doitKeypress(event.keyCode);
            return;
        }
        if(evt.key === 'Escape'){
            doitKeypress(evt.keyCode)
        }
    });
});
