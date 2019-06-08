export const headerData = {
    namespaced: true,
    state: {
        victory: '',
        status: '',
        combatStatus: '',
        topStatus: '',
        log: '',
        turn: false,
        maxTurn: false,
        dynamicButtons:{
            move: false,
            showHexes: false,
            determined: false,
            shiftKey: false
        }

    },
    mutations: {
        victory(state, p) {
            state.victory = p;
        },
        status(state, p) {
            state.status = p;
        },
        combatStatus(state, p) {
            state.combatStatus = p;
        },
        topStatus(state, p) {
            state.topStatus = p;
        },
        log(state, p) {
            state.log = p;
        },
        setTurn(state, turn) {
            state.turn = turn;
        },
        setMaxTurn(state, maxTurn) {
            state.maxTurn = maxTurn;
        },
        setDynamicButton(state, {id: id, value: value}) {
            state.dynamicButtons[id] = value;
        }
    }
}