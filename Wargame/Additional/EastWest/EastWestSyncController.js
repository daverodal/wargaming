import {DR, syncObj} from "@markarian/wargame-helpers";
import WargameVueComponents, { SyncController } from "@markarian/wargame-vue-components";

export class EastWestSyncController extends SyncController{

    constructor(){
        super();
    }
    specialHexes() {
        syncObj.register("specialHexes", (specialHexes, data) => {
            topVue.specialEvents = [];
            topVue.specialHexes = [];
            var lab = ['unowned','<?=strtolower($forceName[1])?>','<?=strtolower($forceName[2])?>'];
            for(var i in specialHexes){

                var hexPos = i.replace(/\.\d*/g,'');
                var x = hexPos.match(/x(\d*)y/)[1];
                var y = hexPos.match(/y(\d*)\D*/)[1];
                let hex = data.specialHexesMap[i];
                hex = hex.replace(/^0/,"");
                let value = data.victory.cityValues[hex];
                console.log(i)
                console.log(data.specialHexesMap[i])
                console.log(data.victory.cityValues[data.specialHexesMap[i]])

                let mapSymbol = {x: x, y: y, text: DR.players[specialHexes[i]] + " " + value, class: DR.players[specialHexes[i]].replace(/ /g,'-'), change: false};
                if(data.specialHexesChanges[i]){
                    mapSymbol.change = true;
                }
                topVue.specialHexes.push(mapSymbol);
            }

            for(var id in data.specialHexesVictory)
            {
                // if(data.specialHexesChanges[id]){
                //     continue;
                // }
                var hexPos = id.replace(/\.\d*/g,'');
                var x = hexPos.match(/x(\d*)y/)[1];
                var y = hexPos.match(/y(\d*)\D*/)[1];
                topVue.specialEvents.push({x: x, y: y, text:data.specialHexesVictory[id], id: hexPos});
            }

            if(topVue.specialEvents.length > 0){
                setTimeout(()=>{
                    topVue.specialEvents = [];
                }, 3000);
            }
        });
    }
}