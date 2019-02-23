<template>
    <div v-if="hexPos !== null" :style="{left: x, top: y} " id="FlashMessage"  class="mapFlashSymbols">
        <img :src="rowSvgImg" class="row-hex">
    </div>
</template>

<script>
    export default {
        name: "FlashHexagon",
        computed:{
          rowSvgImg(){
              return rowSvg;
          },
            x(){
              return this.hexPos.x + 'px';
            },
            y(){
              return this.hexPos.y + 'px';
            }
        },
        data: ()=>{
            return {hexPos: null}
        },
        mounted(){
        },
        props:['position'],
        watch:{
            position(){
                if(this.position === null){
                    this.hexPos = null;
                }else{
                    this.hexPos = {...this.position};
                    this.startPulseOn();
                }
            }
        },
        methods:{
            startPulseOn(){
                setTimeout(this.endPulseOn, 6000);
            },
            endPulseOn(){
              this.hexPos = null;
            }
        }
    }
</script>

<style lang="scss" scoped>
    @keyframes bigger{
        0%{
            width: 72px;
            margin-top: -31px;
            margin-left: -36px;
        }
        65%{
            opacity: 1;
        }
        100%{
            width:288px;
            margin-top:-124px;
            margin-left: -144px;
            opacity: 0;
        }
    }
    .mapFlashSymbols{
        animation: 1s 6 forwards bigger;
        .row-hex{
            width:100%;
        }
    }
</style>