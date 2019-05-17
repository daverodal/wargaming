import Vue from "vue";
import Vuex from "vuex";
import {DR} from "../../DR";
Vue.use(Vuex);

import {mapData} from "./mapData";
import {headerData} from "./headerData";
import {boxesData} from "./boxesData";

export const store = new Vuex.Store({
    strict: true,
    modules:{
      mD: mapData,
      headerData: headerData,
        bd: boxesData
    },
    state: {
        crt:{
            index: false,
            row: false,
            pinned: false,
            player: "",
            details: "",
            combatResult: "",
            selectedTable: 'normal',
            roll: null,
            showCrt: false,
            crtSelfOpened: false,
            showDetails: false
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
        setCrt(state, crt){
            state.crt = {...state.crt, ...crt}
        }
    }
})