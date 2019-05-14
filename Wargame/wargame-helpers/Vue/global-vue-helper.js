export function rotateUnits(e, that) {
    /* hi */
    if (e.ctrlKey) {
        return true;
    }
    let id = that.id;
    vueStore.commit('mD/rotateUnits', id);
    // var hex = topVue.$store.state.mD.unitsMap[id];
    // var hexesMap = topVue.$store.state.mD.hexesMap;
    // if (hexesMap[hex] && hexesMap[hex].length > 0) {
    //     var tmp = hexesMap[hex].shift();
    //     hexesMap[hex].push(tmp);
    //
    //     for (var i in hexesMap[hex]) {
    //         topVue.unitsMap[hexesMap[hex][i]].zIndex = 3 - i - 0 + 1;
    //     }
    //     return false;
    // }
    // return false;
}
