import Vue from "vue";
import WargameVueComponents, {SyncController, ExpUnitsComponent} from "@markarian/wargame-vue-components";
import DeployUnitsComponent from '../../Additional/EastWest/DeployUnitsComponent'
Vue.component('deploy-units-component', DeployUnitsComponent);
Vue.use(WargameVueComponents);
import VueDraggableResizable from 'vue-draggable-resizable'

import UnitComponent from './NorthSouthUnitComponent';
// import UnitsComponent from '../../wargame-helpers/Vue/ExpUnitsComponent'

Vue.component('vue-draggable-resizable', VueDraggableResizable)

Vue.component('unit-component', UnitComponent);
Vue.component('units-component', ExpUnitsComponent );
// import {SyncController} from "../../wargame-helpers/Vue/sync-controller";
const syncController = new SyncController();
import '../../wargame-helpers/Vue/vue-hookup';
