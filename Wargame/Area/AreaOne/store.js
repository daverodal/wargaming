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
        commands: [],
        builds:[],
        resources: [],
        phase: null,
        combatants: []
    },
    getters:{
        isProduction(state){
          return state.phase == PRODUCTION_PHASE;
        },
        isSelected(state){
            return state.selected !== null;
        },
        pfAvailable(state, getters){
            if(getters.isProduction){
                if(getters.selectedBox.isCity){
                    if(getters.selectedBox.owner && getters.selectedBox.owner > 0){
                        let resources = state.resources[getters.selectedBox.owner];
                        let min = Math.min(resources.food, resources.materials, resources.energy);
                        return min;
                    }
                }
            }
            return 0;
        },
        getCities(state){
            let cities = [[], [],[]];

            for(let key in state.boxes){
                let box = state.boxes[key];
                if(box.isCity){
                    if(box.owner && box.owner != 0){
                        cities[box.owner].push(box.id);
                    }
                }
            }
          return cities;
        },
        selectedBox(state){
            if(state.selected !== null){
                return state.boxes[state.selected];
            }
            return {};
        },
        selectedNeighbors(state){
            if(state.selected !== null && state.phase == COMMAND_PHASE){
                return state.boxes[state.selected].neighbors;
            }
            return [];
        },
        getPhase(state){
            switch(state.phase){
                case COMMAND_PHASE:
                    return "Command Phase!";
                case RESULTS_PHASE:
                    return "Results Phase!";
                case PRODUCTION_PHASE:
                    return "Production Phase!";
            }
        }
    },
    mutations:{
        setResources(state, payload){
          state.resources = payload;
        },
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
        setPhase(state, payload){
            state.phase = payload;
        },
        setCombatants(state, payload){
            state.combatants = [...payload];
        },
        doMove(state, payload){
            state.mode = 'move';
            state.selectedNeighbor = payload;
        },
        clearTurn(state){
          state.commands = [];
          state.builds = [];
          state.selected = null;
          state.selectedNeighbor = null;
        },
        produceUnit(state){
            state.builds = [...state.builds, {selected: state.selected, playerId: state.selectedPlayer}];
            state.resources[state.selectedPlayer].food--;
            state.resources[state.selectedPlayer].energy--;
            state.resources[state.selectedPlayer].materials--;
        },
        moveCommand(state, payload){
            state.mode = 'select';
            state.boxes[state.selected].armies[state.selectedPlayer] -= payload.amount;
            state.commands = [...state.commands, {from: state.selected, to: state.selectedNeighbor, amount: payload.amount, playerId: state.selectedPlayer}];
            state.selected = null;
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