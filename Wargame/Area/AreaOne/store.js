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
        borderBoxes: {},
        mode: 'select',
        commands: [],
        builds:[],
        buildLocations: {},
        resources: [],
        phase: null,
        combatants: [],
        battles: [],
        playersReady: [],
        casualities: {},
        borderClashes: {},
        smallMap: false,
        hovered: null,
        beacon: null,
        log: []
    },
    getters:{
        getLog(state){
            return state.log;
        },
        beacon(state){
            return state.beacon;
        },
        hovered(state){
            return state.hovered;
        },
        playerIds(state){
          if(state.user){
              let ids = [];
              state.players.forEach((item, index) => {
                  if(item == state.user){
                      ids.push(index);
                  }
              })
            return ids;
          }  else {
              return [];
          }
        },
        isSmallMap(state){
            return state.smallMap
        },
        totalArmies(state){
            let ret = [0,0,0]
          for(let property in state.boxes){
              if(state.boxes[property].armies[1]){
                  console.log("Armies ");
                  console.log(state.boxes[property].armies[1])
                  ret [1] += state.boxes[property].armies[1]
              }
              if(state.boxes[property].armies[2]){
                  console.log("Armies ");
                  console.log(state.boxes[property].armies[1])
                  ret [2] += state.boxes[property].armies[2]
              }
          }
          console.log(ret);
          return ret;
        },
        casualities(state){
            return state.casualities;
        },
        borderClashes(state){
            return state.borderClashes;
        },
        buildLocations(state){
            return state.buildLocations;
        },
        showWait(state, getters){
            let wait = false;
            state.playersReady.forEach(item => {
                const pIds = [...getters.playerIds]
                if(pIds.includes(item.id) && item.ready){
                    wait = true;
                }
            });
            return wait;
        },
        playersReady(state){
            return state.playersReady;
        },
        battles(state){
            return state.battles;
        },
        isProduction(state){
          return state.phase == PRODUCTION_PHASE;
        },
        getPF(state){

            const resources = state.resources;
            const pf = resources.map((user, index) => {
                    const min = Math.min(user.food, user.energy, user.materials);
                    return {pf: min, food: user.food - min, energy: user.energy - min, materials: user.materials - min}
            })
            return pf;
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
        ownsArea(state, getters){
            if(getters.isSelected){
                if(state.players[getters.selectedBox.owner] === state.user){
                    return true
                }
            }
            return false
        },
        resourcesHere(state, getters){
          if(getters.isSelected){
              switch(getters.selectedBox.terrainType){
                  case 'forest':
                      return {materials: 1};
                  case 'desert':
                      return {energy: 2};
                  case 'pasture':
                      return {food: 1};
                  case 'water':
                      return {energy: 1};
                  case 'mountain':
                      return {materials: 2};
                  case 'field':
                      return {food: 2};
              }
          }
          return {};
        },
        hasArmiesHere(state, getters){
            if(getters.ownsArea === false){
                return false;
            }
            let selectedBox = getters.selectedBox;
            let owner = selectedBox.owner;
            return selectedBox.armies[owner] > 0;
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
                    return "Command";
                case RESULTS_PHASE:
                    return "Results";
                case PRODUCTION_PHASE:
                    return "Production";
            }
        }
    },
    mutations:{
        setLog(state, payload){
          state.log = [...payload];
        },
        setHovered(state, payload){
            state.hovered = payload;
        },
        unsetHovered(state){
            state.hovered = null;
        },
        setBeacon(state, payload){
            state.beacon = payload;
        },
        unsetBeacon(state){
            state.beacon = null;
        },
        setPlayersReady(state, payload){
          state.playersReady = payload;
        },
        setSmallMap(state, payload){
            state.smallMap = payload;
        },
        setCaualities(state, payload){
            state.casualities = payload;
        },
        setBorderClashes(state, payload){
            state.borderClashes = payload;
        },
        setBattles(state, payload){
          state.battles = payload;
        },
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
        setBorders(state, payload){
            state.borderBoxes = {...payload};
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
          state.buildLocations = {};
          state.selected = null;
          state.selectedNeighbor = null;
        },
        produceUnit(state){
            state.builds = [...state.builds, {selected: state.selected, playerId: state.selectedPlayer}];
            let curBuilds = state.buildLocations[state.selected] || 0;
            state.buildLocations[state.selected] = curBuilds + 1;
            state.buildLocations = {...state.buildLocations};
            state.resources[state.selectedPlayer].food--;
            state.resources[state.selectedPlayer].energy--;
            state.resources[state.selectedPlayer].materials--;
        },
        deleteProduction(state, payload){
            const builds = [...state.builds];
            const loc = builds[payload];
            state.buildLocations[loc.selected]--;
            state.buildLocations = {...state.buildLocations};
            builds.splice(payload, 1);
            state.builds = [...builds];
            state.resources[state.selectedPlayer].food++;
            state.resources[state.selectedPlayer].energy++;
            state.resources[state.selectedPlayer].materials++;
            state.beacon = null;
        },
        deleteCommand(state, payload){
            const command = state.commands[payload];

            state.boxes[command.from].armies[command.playerId] += command.amount;
            const commands = [...state.commands];
            commands.splice(payload, 1);
            state.commands = [...commands];
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