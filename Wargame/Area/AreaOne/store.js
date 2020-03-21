import Vue from "vue";
import Vuex from "vuex";
Vue.use(Vuex);


export const store = new Vuex.Store({
    strict: true,
    modules:{
    },
    state: {
        user: null,
        players: [],
        selected: null,
        selectedPlayer: null,
        selectedNeighbor: null,
        boxes: {},
        mode: 'select',
        commands: []
    },
    getters:{
        selectedBox(state){
            if(state.selected !== null){
                return state.boxes[state.selected];
            }
            return {};
        },
        selectedNeighbors(state){
            if(state.selected !== null){
                return state.boxes[state.selected].neighbors;
            }
            return [];
        }
    },
    mutations:{
        selected(state, {id, playerId}){
            state.selected = id;
            state.selectedPlayer = playerId;
        },
        setBoxes(state, payload){
            state.boxes = {...payload};
        },
        setUser(state, payload){
            state.user = payload;
        },
        setPlayers(state, payload){
            state.players = [...payload];
        },
        doMove(state, payload){
            state.mode = 'move';
            state.selectedNeighbor = payload;
        },
        clearTurn(state){
          state.commands = [];
          state.selected = null;
          state.selectedNeighbor = null;
        },
        moveCommand(state, payload){
            state.mode = 'select';
            state.boxes[state.selected].armies[state.selectedPlayer] -= payload.amount;
            state.commands = [...state.commands, {from: state.selected, to: state.selectedNeighbor, amount: payload.amount, playerId: state.selectedPlayer}]
            state.selectedNeighbor = null;
        },
        doCancel(state){
            state.mode = 'select';
            state.selectedNeighbor = null;
        }
        },
    actions: {
    }
})