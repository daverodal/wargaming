<template>
    <div :class="colorClass" :style="{top: y, left: x}" class="circle-wrapper">
        <span class="value-wrapper">{{amount.armies[amount.owner] || 0 }}<span v-if="amount.casualities > 0">x{{amount.casualities}}</span></span>
    </div>
</template>

<script>
    import {mapGetters} from "vuex";

    export default {
        name: "CasualityCircle",
        data() {
            return {
                value: 5
            }

        },
        props: ['amount', 'theKey'],
        computed: {
            ...mapGetters(['isSmallMap']),
            circleUrl(){
                const color = 'white';
                return '/assets/map-symbols/' + color + 'CircleArrow.svg';
            },
          colorClass(){
              if(this.amount.owner == 0){
                return 'white';
              }
              return this.amount.owner == 1  ? 'blue': 'red'
          },
            x(){
                let scale = this.isSmallMap ? .7 : 1;
                let findKey = this.theKey;
                let foundBox = null;
                let borderBoxes = this.$store.state.borderBoxes;
                let i;
                for(i in borderBoxes){
                  if(borderBoxes[i].key === findKey){
                    foundBox = borderBoxes[i];
                    break;
                  }
                }
                // foundBox = this.amount;
                if(foundBox){
                  return foundBox.x - 16;
                }
                // return scale * (this.$store.state.boxes[this.command.from].x + this.$store.state.boxes[this.command.to].x)/2;
            },
            y(){
                let scale = this.isSmallMap ? .7 : 1;


              let findKey = this.theKey;
              let foundBox = null;
              let borderBoxes = this.$store.state.borderBoxes;
              let i;
              for(i in borderBoxes){
                if(borderBoxes[i].key === findKey){
                  foundBox = borderBoxes[i];
                  break;
                }
              }
              // foundBox = this.amount;
              if(foundBox){
                return foundBox.y - 30.4;
              }
                // return scale*(this.$store.state.boxes[this.command.from].y + this.$store.state.boxes[this.command.to].y)/2;
            },
            diffX(){
                return this.$store.state.boxes[this.command.to].x - this.$store.state.boxes[this.command.from].x
            },
            diffY(){
                return this.$store.state.boxes[this.command.from].y - this.$store.state.boxes[this.command.to].y;
            },
            // angle(){
            //     const ret =  90 - Math.atan2(this.diffY , this.diffX) * (180 / Math.PI) ;
            //     return 'rotate('+ret+'deg)';
            // }
        }
    }
</script>

<style lang="scss" scoped>
    .circle-wrapper{
        position:absolute;
        border-radius: 100%;
        height: 40px;
        width:40px;
      margin-top:15px;
      text-align: center;
        .value-wrapper{
            font-size:22px;
          line-height:40px;
        }
      &.red{
        background-color: #ff4500;
      }
      &.blue{
        background-color: #00e7ff;
      }
      &.white{
        background-color: white;
      }
    }
</style>