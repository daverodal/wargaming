<template>
    <div class="units-component">
        <unit-component v-if="!myfilter || unit.forceId === myfilter"
                        v-for="(unit,key) in myunits" :key="key" :unit="unit"></unit-component>
        <unit-component class="ghost" v-for="(unit,key) in myghosts" :key="'ghost'+key" :unit="unit"></unit-component>

        <map-symbol :class="isSpinny(unit) ? 'spinny' : '' " :style="{display: unit.isOccupied && !isSpinny(unit) ? 'none': 'block', zIndex: 1}" v-for="(unit,key) in myghosts" :key="'ghost-hex'+key" :mapsymbol="{x: unit.x - 18, y:unit.y - 16, image: isSpinny(unit) ? 'YellowRowHex.svg' : 'WhiteRowHex.svg'}"></map-symbol>
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
          isSpinny(){
            return (unit) => {
              const ret = unit.id.match(new RegExp('Hex'+unit.hexagon+'$'));
              if(ret === null){
                return false;
              }
              debugger;
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