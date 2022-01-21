<template>
  <div @mouseover="showPath(unit)" @mouseleave="clearPath" :id="unit.id" @contextmenu="rightClick($event, unit)" @click.stop="unitClick" class="unit" :style="wrapperStyle">
    <img class="dir-heading" :style="{transform: course}"  :src="'/assets/unit-images/blackArrow'+unit.maxMove+'.svg'">

    <div :style="unitStyle" :class="unit.nationality">
      <div class="fa" :class="{ 'fa-bullseye': unit.spotted}"></div>
      <div class="unitOdds" :class="this.unit.oddsColor? this.unit.oddsColor: ''">{{unitOdds}}</div>
      <div class="shadow-mask" :class="{shadowy: unit.shadow}"></div>
      <div class="upper-left">{{unit.strength}} </div> <div class="upper-right">{{unit.gunRange}}</div>
      <img v-for="theta in thetas" :style="{transform: theta}" class="counter arrow" src="/assets/unit-images/short-red-arrow-md.png">
      <div class="counter-wrapper">{{unit.name}}</div>
      <div class="lower-left">{{unit.defenseStrength}} </div>
    </div>

  </div>
</template>

<script>
    import {counterClick} from "@markarian/wargame-helpers";
    import {rotateUnits} from "../../wargame-helpers/Vue/global-vue-helper";
    import {mapMutations} from 'vuex';
    import "./Images/USS_Evans_(DD-552)_in_the_Gulf_of_Mexico_1943.jpg";
    export default {
        name: "UnitComponent",
        props:["unit"],
        computed:{
          course(){
            debugger;
            return "rotate("+this.unit.facing * 60 +"deg)  scale(.55,.55) translateY(-45px)";
          },
          wrapperStyle(){
            return  {
              borderWidth: '0px',
              display:this.showMe,
              opacity:this.unit.opac,
              left:this.unit.x + 'px',
              top:this.unit.y + 'px',
              boxShadow: this.cBoxShadow,
              zIndex: 0
            }
          },
            unitStyle(){
                return  {
                    display:this.showMe,
                    opacity:this.unit.opac,
                    borderColor: this.cBorderColor,
                    borderStyle: this.unit.borderStyle,
                    left:0,
                    top:0,
                    boxShadow: this.cBoxShadow,
                    zIndex: 0,
                  position: 'absolute',
                  width:'38px',
                    height:'38px'
                }
            },
            rawUnitNumbers(){
                var move = this.unit.maxMove - this.unit.moveAmountUsed;
                move = move.toFixed(2);
                move = move.replace(/\.00$/, '');
                move = move.replace(/(\.[1  -9])0$/, '$1');
                var str = this.unit.strength;
                var symb = " - ";
                return str + symb + move;
            },

            unitNumbers(){
                var move = this.unit.maxMove - this.unit.moveAmountUsed;
                move = move.toFixed(2);
                move = move.replace(/\.00$/, '');
                move = move.replace(/(\.[1  -9])0$/, '$1');
                var str = this.unit.strength;


                var reduced = this.unit.isReduced;
                var reduceDisp = "<span class='unit-info'>";
                if (reduced) {
                    reduceDisp = "<span class='unit-info reduced'>";
                }
                var symb = this.unit.supplied !== false ? " - " : " <span class='reduced'>u</span> ";
//        symb = "-"+unit.defStrength+"-";
                var html = reduceDisp + str + symb + move + "</span>";
                return html;






                let center = ' ? ';
                if(this.unit.tried){
                    center = ' - ';
                }
                return str + center + move;
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
            courseImage(){
              return "./Images/blackArrow.svg"
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
                let len = this.rawUnitNumbers.length;
                return 'infoLen'+len;
            }
        },
        data:()=>{
            return {zIndex: 3}
        },
        methods:{
            ...mapMutations('mD',['clearPath','showPath']),
            rightClick(e, a,b, c,d){
                if(e.metaKey){
                    return;
                }
                e.preventDefault();
                rotateUnits(e, a);
            },
            unitClick(e){
                counterClick(e, this.unit.id);
            },
            mOver(){
                this.showPath(this.unit)
            },
            mouseleave(){
                this.clearPath();
            }
        }
    }
    /*
    <template>
    <div @mouseover="showPath(unit)" @mouseleave="clearPath" :id="unit.id" @contextmenu="rightClick($event, unit)" @click.stop="unitClick" class="unit" :style="unitStyle">
      <img class="heading" :style="{transform: course}"  :src="'/assets/unit-images/blackArrow'+unit.maxMove+'.svg'">

      <div style="width:30px;height:30px;"></div>
      <div :class="unit.nationality">
        <div class="unitOdds" :class="this.unit.oddsColor? this.unit.oddsColor: ''">{{unitOdds}}</div>
        <div class="shadow-mask" :class="{shadowy: unit.shadow}"></div>
      <div class="upper-left">{{unit.strength}} </div> <div class="upper-right">{{unit.gunRange}}</div>
      <img v-for="theta in thetas" :style="{transform: theta}" class="counter arrow" src="/assets/unit-images/short-red-arrow-md.png">
        <div class="counter-wrapper">{{unit.class}}</div>
      <div class="lower-left">{{unit.defenseStrength}} </div> <div class="lower-right">{{unit.torpedoStrength === 0 ? 'x' : unit.torpedoStrength}}</div>
       </div>

    </div>
</template>
     */
</script>

<style scoped  lang="scss">
    @import "../../wargame-helpers/Vue/scss/vue-unit";
    @import "../../wargame-helpers/Vue/scss/vue-mixins";

    .fa{
      position: absolute;
      bottom: 0px;
      left:33%;
      color:red;
      background: white;
    }
    .unit .unit-numbers{
        &.infoLen7{
            letter-spacing: -.6px;
        }
        &.infoLen8{
            font-size: 10px;
            letter-spacing: -.8px;
        }
    }
    .ghost{
        opacity: 0;
    }
    .unit .counter-wrapper{
      margin: 12px auto 0 auto;
      width: 100%;
      font-size:11px;
      text-align: center;
      font-size:5px;
    }
    .upper-left{
      font-size:10px;
      position:absolute;
      top:0;
      left:0px;
    }
    .upper-right{
      font-size:10px;
      position:absolute;
      right:0;
      top:0;
    }
    .lower-left{
      font-size:10px;
      position:absolute;
      bottom:0;
      left:0px;
    }
    .lower-right{
      font-size:10px;
      position:absolute;
      right:0;
      bottom:0;
    }
    .heading{
      background-image: url('./Images/blackArrow.svg');
    }
    .dir-heading{
      position: absolute;
      pointer-events: none;
      top: 0px;
      width: 100%;
      height: 100%;
    }
</style>