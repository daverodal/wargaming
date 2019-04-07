export const mapData = {
    namespaced : true,
    state: {
        unitsMap:{},
        hexesMap:{},
        mapUrl: mapUrl,
        trueRows: false,
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
            let locId = unit.id;
            if(typeof locId === 'string'){
                locId = locId.replace(/Hex.*/,'Hex')
            }else{
                return;
            }
            unit.showOff = true;
            unit.opac = 1;
            _.forEach(unit.pathToHere,(path)=>{
                let unit = state.moveMap[path]
                unit.opac = 1
                unit.showOff = true;
            });
        },
        clearPath(state){
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
        }
    }
};