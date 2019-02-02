<template>
    <div class="unit-components">
        <unit-component v-for="(unit,key) in myunits" :key="key" :unit="unit"></unit-component>
        <unit-component class="ghost" v-for="(unit,key) in myghosts" :key="'ghost'+key" :unit="unit"></unit-component>
    </div>
</template>

<script type="text/javascript">
    export default{
        directives:{

        },
        props:['myunits','myghosts'],
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