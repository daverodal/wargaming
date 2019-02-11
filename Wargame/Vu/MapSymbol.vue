<template>
    <transition name="fade">
        <div v-if="showSymbol" :style="{top:mapsymbol.y + 'px', left: mapsymbol.x + 'px'}" class="map-symbols" :class="mapsymbol.class">
            <div v-if="mapsymbol.text"> {{ mapsymbol.text }} </div>
        </div>
    </transition>
</template>

<script type="text/javascript">
    export default {
        props: ['mapsymbol'],
        watch:{
            mapsymbol:{
                handler(newVal, oldVal){
                    if(newVal.change){
                        this.showSymbol = false;
                        setTimeout(this.showAgain, 30)
                    }
                    debugger
                },
                deep: true
            }
        },
        methods:{
          showAgain(){
              this.showSymbol = true;
          }
        },
        data: () => {
            return {copyMapSymbol:{},showSymbol: true}
        }
    }
</script>
<style scoped lang="scss">
    .fade-enter{
        font-size:300px;
    }
    .fade-enter-active{
        transition: font-size 3s;
    }
    .map-symbols {
        position: absolute;
        color: black;
        background-color: transparent;
        background-color: #59cb5e;
        i {
            font-size: 14px;
            transform: rotate(180deg);
        }
    }
    .Red{
        background-color:#ef7e4a;
    }
    .Blue{
        background-color: #84b5ff;
    }
</style>