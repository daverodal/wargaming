<template>
    <div class="unit-components">this is reall
        <unit-component v-if="!myfilter || unit.forceId === myfilter" v-for="(unit,key) in myunits" :key="key" :unit="unit"></unit-component>
        not fake
        <unit-component class="ghost" v-for="(unit,key) in myghosts" :key="'ghost'+key" :unit="unit"></unit-component>
        true
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