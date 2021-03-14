import Vue from "vue";
import Vuex from "vuex";
Vue.use(Vuex);

import {mapData} from "./mapData";
import {headerData} from "./headerData";
import {boxesData} from "./boxesData";
import {floatersData} from "./floatersData";
import {floatMessageData} from "./floatMessageData"
export const store = new Vuex.Store({
    strict: true,
    modules:{
        floatMessage: floatMessageData,
        mD: mapData,
        headerData: headerData,
        bd: boxesData,
        floaters: floatersData
    },
    state: {
        crt:{
            index: false,
            row: false,
            pinned: false,
            player: "",
            details: "",
            combatResult: "",
            selectedTable: Object.keys(combatResultsTable.crts)[0],
            defaultTable: Object.keys(combatResultsTable.crts)[0],
            roll: null,
            showCrt: false,
            crtSelfOpened: false,
            showDetails: false,
            crtDragged: false
        },
        timeTravel:{
           currentClick: '',
            showUndo: false
        },
        crtData: {...combatResultsTable
        },

        gameRules:{
            prevPhase: null
        },
        optionsPane:{
            showOptions: false
        }
    },
    getters:{
        currentTableName: (state) => {
            return state.crt.selectedTable;
        },
        currentTable: (state) => {
            return state.crtData.crts[state.crt.selectedTable];
        }
    },
    mutations:{
        toggleShowDetails(state){
          state.crt.showDetails = !state.crt.showDetails;
        },
        dragCrt(state){
            state.crt.crtDragged = true;
        },
        clearDragCrt(state){
            state.crt.crtDragged = false;
        },
        setShowUndo({timeTravel}, value){
          timeTravel.showUndo = value;
        },
        toggleShowUndo({timeTravel}){
            timeTravel.showUndo = !timeTravel.showUndo;
        },
        setCurrentClick({timeTravel}, click){
            timeTravel.currentClick = click;
        },
        setPrevPhase(state, phase){
          state.gameRules.prevPhase = phase;
        },
        crtSelfOpened(state, on){
            if(on === true) {
                if (state.crt.crtSelfOpened) {
                    return;
                }
                state.crt.showCrt = true;
                state.crt.crtSelfOpened = true;
            }else{
                state.crt.crtSelfOpened = false;
            }
        },
        closeCrt(state){
          state.crt.showCrt = false;
        },
        setCrt(state, crt){
            state.crt = {...state.crt, ...crt}
        }
    }
})