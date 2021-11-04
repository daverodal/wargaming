<template>
    <div class="units-component">
        <unit-component v-if="!myfilter || unit.forceId === myfilter"
                        v-for="(unit,key) in myunits" :key="key" :unit="unit"></unit-component>
        <unit-component class="ghost" v-for="(unit,key) in myghosts" :key="'ghost'+key" :unit="unit"></unit-component>

        <map-symbol :class="isSpinny(unit) ? 'spinny' : '' "  v-for="(unit,key) in filteredGhosts" :key="'ghost-hex'+key" :mapsymbol="{x: unit.x - 18, y:unit.y - 16, image: isSpinny(unit) ? 'YellowRowHex.svg' : 'WhiteRowHex.svg'}"></map-symbol>
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
        filteredGhosts(){
          if(this.myghosts) {
            const list = this.myghosts.filter((unit) => {
              return !(unit.isOccupied && !this.isSpinny(unit));
            })
            return list;
          }
          return [];
        },
          isSpinny(){
            return (unit) => {
              const ret = unit.id.match(new RegExp('Hex'+unit.hexagon+'$'));
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
    .map-symbol{
        display:none;
    }
    .units-component{
        &:after{
            clear:both;
        }
    }
</style>