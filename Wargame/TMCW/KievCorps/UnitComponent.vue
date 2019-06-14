<template>
    <div @mouseover="showPath(unit)" @mouseleave="clearPath" :id="unit.id" @contextmenu="rightClick($event, unit)" @click.stop="unitClick" class="unit" :class="unit.nationality" :style="unitStyle">
        <div class="unitOdds" :class="this.unit.oddsColor? this.unit.oddsColor: ''">{{unitOdds}}</div>
        <div class="steps">
            <div v-for="i in unit.steps" class="step"></div>
            <div v-for="i in (unit.origSteps - unit.steps)" class="empty step"></div>
        </div>
        <div class="shadow-mask" :class="{shadowy: unit.shadow}"></div>
        <div class="unit-size">{{ unit.name }}</div>
        <img v-for="theta in thetas" :style="{transform: theta}" class="counter arrow" src="/assets/unit-images/short-red-arrow-md.png">
        <div class="counter-wrapper">
            <img class="counter" :src="'/assets/unit-images/'+unit.image" alt="">
            <span class="unit-desig">{{ unit.unitDesig }}</span>
        </div>
        <div class="unit-numbers" v-html="unitNumbers" :class="infoLen">
        </div>
    </div>

</template>

<script>
    import {counterClick} from "../../wargame-helpers/global-funcs";
    import {rotateUnits} from "../../wargame-helpers/Vue/global-vue-helper";
    import {mapMutations} from 'vuex';
    export default {
        name: "UnitComponent",
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
</script>

<style scoped  lang="scss">
    @import "../../wargame-helpers/Vue/scss/vue-unit";
    @import "../../wargame-helpers/Vue/scss/vue-mixins";
    .unit .unit-numbers{
        &.infoLen7{
            letter-spacing: -.4px;
        }
        &.infoLen8{
            font-size: 10px;
            letter-spacing: -.6px;
        }
    }
    .ghost{
        opacity: 0;
    }
</style>