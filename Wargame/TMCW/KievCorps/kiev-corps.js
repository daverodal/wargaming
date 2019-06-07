import Vue from "vue";

import FlashHexagon from '../../wargame-helpers/Vue/FlashHexagon';
import VueDraggableResizable from 'vue-draggable-resizable'
import FloatMessage from '../../wargame-helpers/Vue/FloatMessage';
import FlashMessages from '../../wargame-helpers/Vue/FlashMessages';
import VueCrt    from '../../wargame-helpers/Vue/VueCrt';
import UnitComponent from './UnitComponent';
import UnitsComponent from '../../wargame-helpers/Vue/ExpUnitsComponent'
import Undo from '../../wargame-helpers/Vue/Undo';
import MapSymbol from '../../wargame-helpers/Vue/MapSymbol';
import SpecialHex from '../../wargame-helpers/Vue/SpecialHex';
import SpecialEvent from '../../wargame-helpers/Vue/SpecialEvent';
// import OBCComponent from './OBCComponent';
Vue.component('flash-messages', FlashMessages);
Vue.component('flash-hexagon', FlashHexagon);
Vue.component('vue-crt', VueCrt);
Vue.component('undo', Undo);
Vue.component('float-message', FloatMessage);
Vue.component('vue-draggable-resizable', VueDraggableResizable)

Vue.component('unit-component', UnitComponent);
Vue.component('units-component', UnitsComponent);
Vue.component('special-hex', SpecialHex);
Vue.component('map-symbol', MapSymbol);
// Vue.component('obc-component', OBCComponent);

Vue.component('special-event', SpecialEvent);
import {KievCorpsSyncController} from "./kiev-corps-sync-controller";
const syncController = new KievCorpsSyncController();

import '../../wargame-helpers/Vue/vue-hookup';
