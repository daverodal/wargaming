<template>
    <div :style="{top:mapsymbol.y + 'px', left: mapsymbol.x + 'px'}" class="map-symbols">
        <div v-if="mapsymbol.text"> {{ mapsymbol.text }} </div>
        <i class="fa fa-adjust" v-else></i>
    </div>
</template>

<script type="text/javascript">
    export default {
        props: ['mapsymbol'],
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
        color: black;
        background-color: transparent;
        background-color: #59cb5e;
        i {
            font-size: 25px;
            transform: rotate(180deg);
        }
    }
</style>