<template>
    <div :style="{top:mapsymbol.y + 'px', left: mapsymbol.x + 'px'}" class="map-symbols">
        <div v-if="mapsymbol.type === 'Spotted'" class="spotted" :class="mapsymbol.class" >
            <img :src="mapSymbolsBefore +'spotted.svg'" ></img>
        </div>
        <div v-else-if="mapsymbol.image" :class="mapsymbol.class" >
            <img :src="mapSymbolsBefore + mapsymbol.image" ></img>
        </div>

    </div>
</template>

<script type="text/javascript">
    export default {
        props: ['mapsymbol'],
        computed:{
            mapSymbolsBefore(){
                return mapSymbolsBefore;
            }
        },
        data: () => {
            return {}
        },
        mounted() {
        }
    }
</script>
<style scoped lang="scss">
    .map-symbols {
        position: absolute;
        font-size:25px;

        .spotted{
            img{
                width:68px;
                margin-top:-32px;
                margin-left:-32px;
            }
        }
        color: black;;
        i {
            font-size: 25px;
            transform: rotate(180deg);
        }
    }
</style>