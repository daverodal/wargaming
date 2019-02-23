<template>
    <div @mouseover="mOver" @mouseleave="mouseleave" :id="unit.id" @contextmenu="rightClick($event, unit)" @click.stop="unitClick" class="unit" :class="unit.nationality" :style="unitStyle">
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


    </div>

</template>

<script>
    import {counterClick} from "../../wargame-helpers/global-funcs";
    import {rotateUnits} from "../../wargame-helpers/Vue/global-vue-header";

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
                var move = this.unit.maxMove - this.unit.moveAmountUsed;
                move = move.toFixed(2);
                move = move.replace(/\.00$/, '');
                move = move.replace(/(\.[1  -9])0$/, '$1');
                var str = this.unit.strength;
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
                let len = this.unitNumbers.length;
                return 'infoLen'+len;
            }
        },
        data:()=>{
            return {zIndex: 3}
        },
        methods:{
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
                let locId = this.unit.id;
                if(typeof locId === 'string'){
                    locId = locId.replace(/Hex.*/,'Hex')
                }else{
                    return;
                }
                this.unit.showOff = true;
                this.unit.opac = 1;
                _.forEach(this.unit.pathToHere,(path)=>{
                    _.forEach(topVue.moveUnits, (unit)=>{
                        if(unit.id === locId+path){
                            unit.opac = 1;
                            unit.showOff = true;
                        }
                    })
                });
            },
            mouseleave(){
                _.forEach(topVue.moveUnits, (unit)=>{
                    unit.showOff = false;
                    delete unit.opac;
                })
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
</style>