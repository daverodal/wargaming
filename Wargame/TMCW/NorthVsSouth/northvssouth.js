import Vue from "vue";
import WargameVueComponents, {SyncController} from "@markarian/wargame-vue-components";

Vue.use(WargameVueComponents);
import VueDraggableResizable from 'vue-draggable-resizable'

import UnitComponent from './NorthSouthUnitComponent';
import UnitsComponent from '../../wargame-helpers/Vue/ExpUnitsComponent'

Vue.component('vue-draggable-resizable', VueDraggableResizable)

Vue.component('unit-component', UnitComponent);
Vue.component('units-component', UnitsComponent);
// import {SyncController} from "../../wargame-helpers/Vue/sync-controller";
const syncController = new SyncController();
import '../../wargame-helpers/Vue/vue-hookup';
