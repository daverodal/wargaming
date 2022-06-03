<template>
    <div class="units-component">
        <unit-component class="spinny" :class="isMoving(unit) ? 'on-top' : ''" v-if="!myfilter || unit.forceId === myfilter"
                        v-for="(unit,key) in myunits" :key="key" :unit="unit"></unit-component>

      <map-symbol class="spinny"  v-for="(unit,key) in myunits" :key="'ghost-hex'+key" :mapsymbol="{x: unit.x - 18, y:unit.y - 16, image: isSpinny(unit) ? 'YellowRowHex.svg' : normalHex}"></map-symbol>



      <unit-component class="ghost" v-for="(unit,key) in myghosts" :key="'ghost'+key" :unit="unit"></unit-component>

        <map-symbol :class="isSpinny(unit) ? 'spinny' : '' "  v-for="(unit,key) in filteredGhosts" :key="'ghost-hex'+key" :mapsymbol="{x: unit.x - 18, y:unit.y - 16, image: isSpinny(unit) ? 'YellowRowHex.svg' : normalHex}"></map-symbol>
    </div>
</template>

<script type="text/javascript">
    export default{
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
        isSpinning(){
          return (unit) => {
            if(unit.isSpinny === true){
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