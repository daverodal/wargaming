import Vue from "vue";
import WargameVueComponents, {SyncController, ExpUnitsComponent as UnitsComponent} from "@markarian/wargame-vue-components";

import FlashHexagon from '../../wargame-helpers/Vue/FlashHexagon';
Vue.use(WargameVueComponents);
import VueDraggableResizable from 'vue-draggable-resizable'
import UnitComponent from './UnitComponent';
// import UnitsComponent from './ExpUnitsComponent'
Vue.component('vue-draggable-resizable', VueDraggableResizable)
Vue.component('unit-component', UnitComponent);
Vue.component('units-component', UnitsComponent);
const syncController = new SyncController();

import '../../wargame-helpers/Vue/vue-hookup';
