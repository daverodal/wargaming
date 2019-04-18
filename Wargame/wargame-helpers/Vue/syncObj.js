import {Sync} from "../Sync";
export const syncObj = new Sync(fetchUrl);
import {DR} from '../DR'
DR.sync = syncObj;
