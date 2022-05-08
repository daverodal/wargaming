window.axios = require('axios');

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
import {
    counterClick,
    doitKeypress,
    doitSaveGame,
    mapClick,
    nextPhaseMouseDown,
    playAudio, playAudioBuzz, playAudioLow,
    toggleFullScreen
} from "@markarian/wargame-helpers";
window._ = require('lodash');
import { globalFuncs } from "@markarian/wargame-helpers";

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
export function hookup(Vue){
    Vue.use(VueResource);

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
    window.floatMessage = new Vue({
        el: '#float-message-drag-wrapper .vue-wrapper',
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
            headerTurn(){
                return this.$store.state.headerData.turn;
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
            showSvg: false,
            bugMessage: '',
            crtClass: 'normalCrt',
            isMuted: false,
            crtOptions: {},
            dynamicButtons:{
            },
            show:{
                units:{
                    submenu:false,
                    deployWrapper: false,
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
            useDetermined(){
                doitKeypress(68);
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
                nextPhaseMouseDown();
            },
            // menuClick(id){
            //     headerVue.menuClick(id);
            // },
            didDrag(){
                DR.dragged = true;
            },
            showArrows(){
                if (!this.showSvg) {
                    DR.showArrows = true;
                    $('#arrow-svg .unit-path').show();
                } else {
                    DR.showArrows = false;
                    $('#arrow-svg .unit-path').hide();
                }
                this.showSvg = !this.showSvg;
            },
            mute(){
                if (!this.isMuted) {
                    globalFuncs.muteMe();

                } else {
                    globalFuncs.unMuteMe();
                    playAudio();
                }
                this.isMuted = !this.isMuted;
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
                this.clearMenus();
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
                    this.show.units.deployWrapper = false;
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
    document.addEventListener('keyup', function(evt) {
        const indx = "xdcmsl".indexOf(evt.key);
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
}
