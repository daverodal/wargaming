<template>
    <div :style="{top: y, left: x}" class="circle-wrapper">
        <img :src="circleUrl" :style="{transform: angle}" class="circle">
        <div class="value-wrapper">{{amount}}</div>
    </div>
</template>

<script>
    import {mapGetters} from "vuex";

    export default {
        name: "MoveCircle",
        data() {
            return {
                value: 5
            }

        },
        props: ['amount', 'command'],
        computed: {
            ...mapGetters(['isSmallMap']),
            circleUrl(){
                const color = this.command.playerId == 1 ? 'Blue' : 'Red';
                return '/assets/map-symbols/' + color + 'CircleArrow.svg';
            },
            x(){
                let scale = this.isSmallMap ? .7 : 1;
                let f = this.command.from;
                let t = this.command.to;
                let temp;
                if(f > t){
                  temp = f;
                  f = t;
                  t = temp;
                }
                let findKey = f+"@"+t;
                let foundBox = null;
                let borderBoxes = this.$store.state.borderBoxes;
                let i;
                for(i in borderBoxes){
                  if(borderBoxes[i].key === findKey){
                    foundBox = borderBoxes[i];
                    break;
                  }
                }
                if(foundBox){
                  return foundBox.x - 16;
                }
                return scale * (this.$store.state.boxes[this.command.from].x + this.$store.state.boxes[this.command.to].x)/2;
            },
            y(){
                let scale = this.isSmallMap ? .7 : 1;

              let f = this.command.from;
              let t = this.command.to;
              let temp;
              if(f > t){
                temp = f;
                f = t;
                t = temp;
              }
              let findKey = f+"@"+t;
              let foundBox = null;
              let borderBoxes = this.$store.state.borderBoxes;
              let i;
              for(i in borderBoxes){
                if(borderBoxes[i].key === findKey){
                  foundBox = borderBoxes[i];
                  break;
                }
              }
              if(foundBox){
                return foundBox.y - 30.4;
              }
                return scale*(this.$store.state.boxes[this.command.from].y + this.$store.state.boxes[this.command.to].y)/2;
            },
            diffX(){
                return this.$store.state.boxes[this.command.to].x - this.$store.state.boxes[this.command.from].x
            },
            diffY(){
                return this.$store.state.boxes[this.command.from].y - this.$store.state.boxes[this.command.to].y;
            },
            angle(){
                const ret =  90 - Math.atan2(this.diffY , this.diffX) * (180 / Math.PI) ;
                return 'rotate('+ret+'deg)';
            }
        }
    }
</script>

<style lang="scss" scoped>
    .circle-wrapper{
        position:absolute;
        img{
            width:32px;
            transform: rotate(27deg);
        }
        .value-wrapper{
            position:absolute;
            top: 16px;
            left: 11px;
            font-size:22px;
        }
    }
</style>