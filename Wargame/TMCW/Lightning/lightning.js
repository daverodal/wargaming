import Vue from "vue";
import WargameVueComponents, {SyncController} from "@markarian/wargame-vue-components";
import { globalFuncs } from "@markarian/wargame-helpers";
import VueDraggableResizable from 'vue-draggable-resizable'
// import OBCComponent from "./OBCComponent";
import UnitComponent from './UnitComponent';
Vue.use(WargameVueComponents);
Vue.component('vue-draggable-resizable', VueDraggableResizable)
Vue.component('unit-component', UnitComponent);
// Vue.component('obc-component', OBCComponent);
const syncController = new SyncController();
const xyzzy = globalFuncs;
import DeployUnitsComponent from '../../Additional/EastWest/DeployUnitsComponent'
Vue.component('deploy-units-component', DeployUnitsComponent);
import '../../wargame-helpers/Vue/vue-hookup';
