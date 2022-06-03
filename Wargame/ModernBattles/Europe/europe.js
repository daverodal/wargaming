import Vue from "vue";
import WargameVueComponents from "@markarian/wargame-vue-components";
import ExpUnitsComponent from "./ExpUnitsComponent";
import DeployUnitsComponent from '../../Additional/EastWest/DeployUnitsComponent'
Vue.component('deploy-units-component', DeployUnitsComponent);
Vue.use(WargameVueComponents);
import VueDraggableResizable from 'vue-draggable-resizable'
import {SyncController} from "./sync-controller";
import UnitComponent from './ModernUnitComponent';
// import UnitsComponent from '../../wargame-helpers/Vue/ExpUnitsComponent'

Vue.component('vue-draggable-resizable', VueDraggableResizable)

Vue.component('unit-component', UnitComponent);
Vue.component('units-component', ExpUnitsComponent );
// import {SyncController} from "../../wargame-helpers/Vue/sync-controller";
const syncController = new SyncController();
syncController.  renderCrtDetails = (combat) => {
    var atk = combat.attackStrength;
    var def = combat.defenseStrength;
    var div = atk / def;
    var ter = combat.terrainCombatEffect;
    var combatCol = combat.index + 1;
    var oddsDisp;

    let xyz = vueStore.getters.currentTable;
    const selectedTable = vueStore.state.crt.selectedTable;
    oddsDisp = "";
    if(!Number.isInteger(combat.index)) {
        var html = "<div id='crtDetails'>No attackers selected</div>"
        return html;

    }
    const crtHeader = vueStore.getters.currentTable.header;
    oddsDisp = crtHeader[combat.index] || "< " + crtHeader[0];
    div = div.toFixed(2);
    var html = "<div id='crtDetails'>" + combat.combatLog + "</div>";
    /*+ atk + " - Defender " + def + " = " + diff + "</div>";*/
    return html;
}
import '../../wargame-helpers/Vue/vue-hookup';
