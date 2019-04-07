<template>
    <div class="unit-components">
        <unit-component v-if="!myfilter || unit.forceId === myfilter" v-for="(unit,key) in myunits" :key="key" :unit="unit"></unit-component>
        <unit-component class="ghost" v-for="(unit,key) in myghosts" :key="'ghost'+key" :unit="unit"></unit-component>

        <map-symbol v-for="(unit,key) in myghosts" :key="'ghost-hex'+key" :mapsymbol="{x: unit.x - 16, y:unit.y - 17, image: 'whiteRowHex.svg'}"></map-symbol>
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
        }
    }
</script>
<style scoped lang="scss">
    .unit-components{
        &:after{
            clear:both;
        }
    }
</style>