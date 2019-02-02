<template>
    <div @mouseover="mOver" @mouseleave="mouseleave" :id="unit.id" @click="unitClick" class="unit" :class="unit.nationality" :style="unitStyle">
        <div class="shadow-mask" :class="{shadowy: unit.shadow}"></div>
        <div class="unit-size">xx</div>
        <img v-for="theta in thetas" :style="{transform: theta}" class="counter arrow" src="/assets/unit-images/short-red-arrow-md.png">
        <div class="counter-wrapper">
            <img class="counter" :src="'/assets/unit-images/'+unit.image" alt="">
        </div>
        <div class="unit-numbers" :class="infoLen">
            {{ unitNumbers }}
        </div>
    </div>
</template>

<script type="text/javascript">
    import {counterClick} from "../wargame-helpers/global-funcs";

    export default{
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
                    boxShadow: this.cBoxShadow
                }
            },
            unitNumbers(){
                var move = this.unit.maxMove - this.unit.moveAmountUsed;
                move = move.toFixed(2);
                move = move.replace(/\.00$/, '');
                move = move.replace(/(\.[1  -9])0$/, '$1');
                var str = this.unit.strength;


                return str + " - " + move;
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
        methods:{
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

<style scoped lang="scss">
    .unit{
        height:38px;
        width:38px;
        position:absolute;
        .unit-numbers{
            height:12px;
            margin-top:0px;
            &.infoLen8{
                letter-spacing:-.7px;
            }
        }
        .counter-wrapper{
            font-size: 9px;
            height:10px;
            img{
                width: 50%;
                margin-left:25%;
            }
        }
        .unit-size{

            font-family: sans-serif;
            font-size: 9px;
            text-align: center;
            color: black;
            height: 8px;
        }

    }
    .ghost{
        opacity:.6;
        border-color: #ccc #333 #333 #ccc;

        &:hover{
            opacity: 1;
            border-color:white !important;
        }
    }

    #boxes-wrapper .unit{
        float:left;
        position:static;
        border-color: #ccc #333 #333 #ccc;
        .shadow-mask{
            display:none;
        }

    }

</style>