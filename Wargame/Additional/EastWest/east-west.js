import Vue from "vue";
import WargameVueComponents, {SyncController, ExpUnitsComponent as UnitsComponent} from "@markarian/wargame-vue-components";

import FlashHexagon from '../../wargame-helpers/Vue/FlashHexagon';
import EastWestHeader from "./EastWestHeader";
Vue.use(WargameVueComponents);
Vue.component('east-west-header', EastWestHeader);
import VueDraggableResizable from 'vue-draggable-resizable'
import UnitComponent from './UnitComponent';
import DeployUnitsComponent from './DeployUnitsComponent'
Vue.component('vue-draggable-resizable', VueDraggableResizable)
Vue.component('unit-component', UnitComponent);
Vue.component('units-component', UnitsComponent);
Vue.component('deploy-units-component', DeployUnitsComponent);
import {EastWestSyncController} from './EastWestSyncController.js';
const syncController = new EastWestSyncController();

import '../../wargame-helpers/Vue/vue-hookup';
