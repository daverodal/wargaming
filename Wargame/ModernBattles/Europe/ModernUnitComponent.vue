<template>
    <div @mouseover="showPath(unit)" @mouseleave="mouseleave" :id="unit.id" @contextmenu="rightClick($event, unit)" @click.stop="unitClick" class="unit" :class="unit.nationality" :style="unitStyle">
      <template v-if="unit.class !== 'artillery' && unit.class !== 'air'">
      <div class="unitOdds" :class="this.unit.oddsColor? this.unit.oddsColor: ''">{{unitOdds}}</div>
        <div class="shadow-mask" :class="{shadowy: unit.shadow}"></div>
        <div class="unit-size">{{ unit.name }}</div>
        <img v-for="theta in thetas" :style="{transform: theta}" class="counter arrow" src="/assets/unit-images/short-red-arrow-md.png">
        <div class="counter-wrapper">
          <img class="counter" :src="'/assets/unit-images/'+unit.image" alt="">
        </div>
        <div class="unit-numbers" :class="infoLen">
            {{ unitNumbers }}
        </div>
      </template>
      <template v-else>
        <div class="unitOdds" :class="this.unit.oddsColor? this.unit.oddsColor: ''">{{unitOdds}}</div>
        <div class="shadow-mask" :class="{shadowy: unit.shadow}"></div>
        <div class="unit-size art-top">{{ artilleryTopNumbers }}</div>
        <img v-for="theta in thetas" :style="{transform: theta}" class="counter arrow" src="/assets/unit-images/short-red-arrow-md.png">
        <div class="counter-wrapper">
          <img class="counter" :src="'/assets/unit-images/'+unit.image" alt="">
        </div>
        <div class="unit-numbers" :class="infoLen">
          {{ artilleryBottomNumbers }}
        </div>
      </template>


    </div>

</template>

<script>
    import {mapMutations} from "vuex"
    import {counterClick} from "@markarian/wargame-helpers";
    import {rotateUnits} from "../../wargame-helpers/Vue/global-vue-helper";
    export default {
        name: "NorthSouthUnitComponent",
        props:["unit"],
        computed:{
            unitStyle(){
                return  {
                    display:this.showMe,
                    opacity:this.unit.opac,
                    borderColor: this.cBorderColor,
                    borderStyle: this.unit.borderStyle,
                    left:this.unit.x + 'px',
                    top:this.unit.y + 'px',
                    boxShadow: this.cBoxShadow,
                    zIndex: this.unit.zIndex
                }
            },
            unitNumbers(){
              let extraMoves = 0;
              if(this.unit.movesAllowed > 1){
                extraMoves = 10 * (this.unit.movesAllowed - 1);
              }
                var move = this.unit.maxMove - this.unit.moveAmountUsed + extraMoves;
                move = move.toFixed(2);
                move = move.replace(/\.00$/, '');
                move = move.replace(/(\.[1  -9])0$/, '$1');
                var str = this.unit.strength;
                let center = this.unit.defStrength;
                return str + '-' +  center + '-' + move;
            },
          artilleryTopNumbers(){
            let left = this.unit.strength;
            let center = this.unit.fpf;
            let right = this.unit.range;
            return left + '-' +  center + '-' + right;
          },
          artilleryBottomNumbers(){
            let left = this.unit.defStrength;
            let move = this.unit.maxMove - this.unit.moveAmountUsed;
            move = move.toFixed(2);
            move = move.replace(/\.00$/, '');
            move = move.replace(/(\.[1  -9])0$/, '$1');
            return left + '-' + move;
          },
            unitOdds(){
                return this.unit.odds;
            },
            thetas(){
                let thetas = [];
                for(var i in this.unit.thetas){
                    thetas.push("rotate("+this.unit.thetas[i]+"deg)  scale(.55,.55) translateY(45px)")
                }
                return thetas
            },
            showMe(){
                if(this.unit.showOff){
                    return 'block';
                }
                if(this.unit.isOccupied){
                    return 'none';
                }
                return 'block';
            },
            cBorderColor(){
                if(this.unit.showOff){
                    return 'white';
                }
                return this.unit.borderColor;
            },
            cBoxShadow(){
                if(this.unit.showOff){
                    return "#333 5px 5px 5px";
                }
                return this.unit.boxShadow;
            },
            infoLen(){
                let len = this.unitNumbers.length;
                return 'infoLen'+len;
            }
        },
        data:()=>{
            return {zIndex: 3}
        },
        methods:{
            ...mapMutations('mD', ['showPath','clearPath']),
            mOver(){
                this.showPath(this.unit);
            },
            mouseleave(){
                this.clearPath();
            },
            rightClick(e, a,b, c,d){
                if(e.metaKey){
                    return;
                }
                e.preventDefault();
                rotateUnits(e, a);

            },
            unitClick(e){
                counterClick(e, this.unit.id);
            }
        }
    }
</script>

<style scoped  lang="scss">
    @import "../../wargame-helpers/Vue/scss/vue-unit";
    @import "../../wargame-helpers/Vue/scss/vue-mixins";
    @import "localColors";
    @include unitColor(southern, $southernColor)
    @include unitColor(northern, $northernColor);
    .unit{
      &.big{
        width:54px;
        height: 54px;
        .unit-size{
          font-size:13px;
          height:12px;
          &.art-top{
            font-size:15px;
            height:13px;
          }
        }
        .counter-wrapper{
          height:15px;
          font-size:14px;
        }
        .unit-numbers{
          height:18px;
          font-size:17px;
        }
      }
      .unit-numbers.infoLen7{
        font-size:10px;
        letter-spacing: -1px;
      }
      .unit-numbers.infoLen8{
        font-size:9px;
        letter-spacing: -1px;
      }
    }
    .ghost{
        opacity: 0;
    }
</style>