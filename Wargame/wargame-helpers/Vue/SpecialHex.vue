<template>
    <transition name="fade">
        <div v-if="showSymbol" :style="{top:specialhex.y + 'px', left: specialhex.x + 'px'}" class="special-hex" :class="[specialhex.class, firstOne]">
            <div v-if="specialhex.text"> {{ specialhex.text }} </div>
        </div>
    </transition>
</template>

<script type="text/javascript">
    export default {
        props: ['specialhex'],
        watch:{
            specialhex:{
                handler(newVal, oldVal){
                    if(newVal.change){
                        this.firstOne = false;
                        this.showSymbol = false;
                        setTimeout(this.showAgain, 30)
                    }
                },
                deep: true
            }
        },
        methods:{
          showAgain(){
              this.showSymbol = true;
          }
        },
        mounted(){
        },
        data: () => {
            return {copyMapSymbol:{},showSymbol: true, firstOne: 'first-one'}
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
    .special-hex {
        padding: 0px 5px;
        border: 3px solid #001825;
        border-radius: 10px;
        position: absolute;
        color: black;
        i {
            font-size: 14px;
            transform: rotate(180deg);
        }
        &.first-one.fade-enter{
            font-size:14px;
        }
    }
    .Red{
        background-color:#ef7e4a;
    }
    .Blue{
        background-color: #84b5ff;
    }
</style>