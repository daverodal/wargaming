import Vue from 'vue';
export const mapData = {
    namespaced : true,
    state: {
        unitsMap:{},
        hexesMap:{},
        dispUnits:[],
        dispUnitsMap: {},
        mapUrl: mapUrl,
        trueRows: false,
        mirror: false,
        moveUnits: [],
        moveMap: {}
    },
    getters: {
        getUnitsMaps(state) {
            const hexesMap = state.hexesMap;
            const unitsMap = state.unitsMap;
            return {hexesMap, unitsMap};
        }
    },
    mutations:{
        showPath(state, unit){
            // hovered unit is decorated via css
            _.forEach(unit.pathToHere,(path)=>{
                let pathUnit = state.moveMap[path]
                if(pathUnit){
                    pathUnit.opac = 1
                    pathUnit.showOff = true;
                }

            });
        },
        clearPath(state){
            /* hit all the unit with a showgun */
            /* approach */
            _.forEach(state.moveUnits,(unit)=>{
                unit.showOff = false;
                delete unit.opac;
            });
        },
        clearMoves(state){
            state.moveUnits = [];
            state.moveMap = {};
        },
        addUnit(state, payload){
            state.moveUnits.push(payload.unit);
            state.moveMap[payload.hex] = payload.unit;
        },
        clearUnitsMaps(state){
            // state.unitsMap = {}
            // state.hexesMap = {}
        },
        clearUnitMap({unitsMap, hexesMap}, id){
            if(unitsMap[id]){
                if(hexesMap[unitsMap[id]]){
                    hexesMap[unitsMap[id]] = hexesMap[unitsMap[id]].filter(element => element != id);
                }
                delete unitsMap[id];
            }
        },
        unitHexMapper({unitsMap, hexesMap}, {i, unit}){
            if (unitsMap[i] === undefined) {
                unitsMap[i] = unit.hexagon;
                if (hexesMap[unit.hexagon] === undefined) {
                    hexesMap[unit.hexagon] = [];
                }
                if(!hexesMap[unit.hexagon].includes(i)){
                    hexesMap[unit.hexagon].push(i);
                }
            } else {

                if (unitsMap[i] !== unit.hexagon) {
                    /* unit moved */
                    var dead = hexesMap[unitsMap[i]].indexOf(i);
                    hexesMap[unitsMap[i]].splice(dead, 1);
                    if (hexesMap[unit.hexagon] === undefined) {
                        hexesMap[unit.hexagon] = [];
                    }
                    hexesMap[unit.hexagon].push(i);
                    unitsMap[i] = unit.hexagon;
                }
            }
            if (Object.keys(hexesMap[unit.hexagon]).length) {
                let unitsHere = hexesMap[unit.hexagon];
                let sortedUnits = _.sortBy(unitsHere, o => o-0);
                unit.shift = sortedUnits.indexOf(i) * 5;
            }
        },
        dispUnits(state, units){
            state.dispUnits = units;
            state.dispUnitsMap = {};
            state.dispUnits.forEach(unit => {
                const id = unit.id;
                Vue.set(state.dispUnitsMap, id, unit );
            });
            state.dispUnits.forEach(unit => {
                const id = unit.id;
                const hex = state.unitsMap[id];
                let temp = [];
                for (var i in state.hexesMap[hex]) {
                    if(state.dispUnitsMap[state.hexesMap[hex][i]]){
                        state.dispUnitsMap[state.hexesMap[hex][i]].zIndex = 3 - i - 0 + 1;
                        temp.push(state.hexesMap[hex][i]);
                    }
                }
                state.hexesMap[hex] = temp;
            })
        },
        rotateUnits({dispUnitsMap, hexesMap, unitsMap}, id){
            var hex = unitsMap[id];
            if (hexesMap[hex] && hexesMap[hex].length > 0) {
                var tmp = hexesMap[hex].shift();
                hexesMap[hex].push(tmp);

                for (var i in hexesMap[hex]) {
                    dispUnitsMap[hexesMap[hex][i]].zIndex = 3 - i - 0 + 1;
                }
            }
        },
        decorateUnit({dispUnitsMap}, {id, key, value}){
            Vue.set(dispUnitsMap[id], key, value)
        },
        showOffMove({moveMap}, {hexId, show}){
            if(show){
                Vue.set(moveMap[hexId], 'showOff', true);
                Vue.set(moveMap[hexId], 'opac', 1);
            }else{
                Vue.set(moveMap[hexId], 'showOff', false);
                Vue.delete(moveMap[hexId], 'opac');
            }
        },
        setTrueRows(state, payload){
            state.trueRows = payload;
        },
        setMirror(state, payload){
            state.mirror = payload;
        }
    }
};