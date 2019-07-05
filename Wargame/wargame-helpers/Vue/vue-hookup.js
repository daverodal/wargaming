window._ = require('lodash');
import Vue from "vue";
import {
    counterClick,
    doitKeypress,
    doitSaveGame,
    mapClick,
    nextPhaseMouseDown,
    playAudio,
    toggleFullScreen
} from "../global-funcs";
import {store} from "./store/store";
import "./global-vue-header";
import {DR} from '../DR'
import VueResource from 'vue-resource';
import "../jquery.panzoom";
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
            rowSvg: {x: 0, y: 0},
            why: "Why not!",
            header: "",
            message: "",
            x: 200,
            y: 900
        },
        computed: {
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

    window.floaters = new Vue({
        el: "#floaters",
        data:{
            header: "",
            message: "",
            x: 0,
            y: 0
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
                    return;
                }
                if(id === 'showObc'){
                    this.showObc = !this.showObc;
                    this.commonRules = false;
                    this.showTec = false;
                    this.rules = false;
                    return;
                }
                if(id === 'showTec'){
                    this.showTec = !this.showTec;
                    this.rules = false;
                    this.commonRules = false;
                    this.showObc = false;
                    return;
                }
                if(id === 'all'){
                    this.show.units.submenu = false;
                    this.show.units.deployBox = false;
                    this.show.units.deadpile = false;
                    this.show.units.exitBox = false;
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
        if(evt.key === 'c'){
            window.clickThrough.clearCombat();
        }
    });
});
