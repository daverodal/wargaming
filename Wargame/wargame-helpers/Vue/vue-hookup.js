window._ = require('lodash');
import {SyncController} from "./sync-controller";
import Vue from "vue";
import {counterClick, doitSaveGame, mapClick, nextPhaseMouseDown, playAudio, toggleFullScreen} from "../global-funcs";
import {store} from "./store/store";
import {DR} from "./global-vue-header";
import VueResource from 'vue-resource';
import "../jquery.panzoom";
Vue.use(VueResource);
document.addEventListener("DOMContentLoaded",function(){

    const syncController = new SyncController();
    window.topVue = new Vue({
        el: '#gameImages',
        store,
        data:{
            units:[],
            unitsMap: {},
            moveUnits: [],
            mapSymbols: [],
            specialEvents: [],
            rowSvg: {x: 0, y: 0},
            why: "Why not!",
            header: "",
            message: "",
            x: 200,
            y: 900
        },
        methods:{
            wheelo(e){
            },
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
            }
        }

    });

    window.floatersMessages = new Vue({
        el: "#floatMessageContainer",
        data:{
            messages:[]
        }
    });

    window.floaters = new Vue({
        el: "#floaters",
        data:{
            why: "Why not!",
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
            selectedTable(){
                return this.$store.state.getters.currentTable;
            },
            headerVictory(){
                return this.$store.state.headerData.victory;
            },
            headerStatus(){
                return this.$store.state.headerData.status;
            },
            headerTopStatus(){
                return this.$store.state.headerData.topStatus;
            },
            headerLog(){
                return this.$store.state.headerData.log;
            }
        },
        data:{
            headerPlayer: '',
            submenu: false,
            log: false,
            rules: false,
            menu: false,
            info: false,
            crt: false,
            undo: false,
            debug: false,
            bugMessage: '',
            crtClass: 'normalCrt',
            crtOptions: {},
            dynamicButtons:{
                move: false,
                showHexes: false,
                determined: false
            },
            deployBox: [],
            deadpile: [],
            exitBox: [],
            notUsed: [],
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
            fullScreen(){
                toggleFullScreen()
            },
            bugReport(){
                this.debug = !this.debug;
                debugger;
            },
            saveBugReport(){
                doitSaveGame(this.bugMessage);
                this.bugMessage = '';
                this.bugReport();
            },
            nextPhase(){
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
                this.crt = !this.crt;
            },
            clickCrt(){
            },  wheelo(e){
            },
            unitClick(e){
                counterClick(e);
            },
            menuClick(id){
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
});
