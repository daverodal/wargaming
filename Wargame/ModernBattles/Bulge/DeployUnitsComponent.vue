<template>
    <div class="units-component">
      <div v-if="!myfilter || unit.unit.forceId === myfilter"
         v-for="(unit,key) in deployUnits" style="float:left">
          <unit-component class="big" style="float:none" :key="unit.id" :unit="unit.unit"></unit-component>
          <div style="text-align:center">x{{ unit.count }}</div>
      </div>
    </div>
</template>

<script type="text/javascript">
    export default{
      created(){
      },
        directives:{
        },
        props:['myunits','myghosts','myfilter'],
        data:()=>{
            return{
                units: [],
                moveUnits: [],
                deployBox: [],
                deadpile: []
            }
        },
        mounted(){
        },
      computed:{
          deployUnits(){
              let unitsTypes = [];
            let unitsRet = {};
            if(this.myunits) {
              this.myunits.forEach(unit => {
                if (!unitsRet[unit.name + "@" + unit.strength + "@" + unit.defStrength + "@" + unit.maxMove + "@" + unit.forceId + "@" + unit.reinforceZone + "@" + unit.range]) {
                  unitsRet[unit.name + "@" + unit.strength + "@" + unit.defStrength + "@" + unit.maxMove + "@" + unit.forceId + "@" + unit.reinforceZone + "@" + unit.range] = {
                    unit: unit,
                    count: 0
                  };
                }
                unitsRet[unit.name + "@" + unit.strength + "@" + unit.defStrength + "@" + unit.maxMove + "@" + unit.forceId + "@" + unit.reinforceZone + "@" + unit.range].count++;
              });
            }

            return unitsRet;
          },
          normalHex(){
            if(this.$store.state.mD.trueRows){
              return 'WhiteColHex.svg';
            }
            return 'WhiteRowHex.svg';
          },
        filteredGhosts(){
          if(this.myghosts) {
            const list = this.myghosts.filter((unit) => {
              return !(unit.isOccupied && !this.isSpinny(unit));
            })
            return list;
          }
          return [];
        },
        isMoving(){
          return (unit) => {
            if(unit.status === STATUS_MOVING){
              return true;
            }
            return false;
          }
        },
          isSpinny(){
            return (unit) => {
              let id = unit.id + '';
              const ret = id.match(new RegExp('Hex'+unit.hexagon+'$'));
              if(ret === null){
                return false;
              }
              return true;
            }
          }
      }
    }
</script>
<style scoped lang="scss">
  .on-top{
    z-index: 10 !important;
  }
    .map-symbol{
        display:none;
    }
    .units-component{
        &:after{
            clear:both;
        }
    }
</style>