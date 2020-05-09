<template>
        <img id="map" alt="map" :class="{mirror: mirror}" :src="src">
</template>

<script>
    export default {
        name: "MapComponent",
        props:['src'],
        computed: {
            mirror(){
                debugger;
                return this.$store.state.mD.mirror;
            }
        }
    }
</script>

<style lang="scss" scoped>
    .mirror{
        transform: rotate(180deg);
    }
</style>