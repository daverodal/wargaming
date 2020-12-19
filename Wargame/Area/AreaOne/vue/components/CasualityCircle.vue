<template>
    <div :style="{top: y, left: x}" class="circle-wrapper">
      <img :class="colorClass" :src="circleUrl" :style="{transform: angle}" class="circle">
      <div class="value-wrapper"><span class="value-wrapper">{{amount.armies[amount.owner] || 0 }}<span v-if="amount.casualities > 0" class="cross">{{amount.casualities}}</span></span></div>
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
                const color = this.amount.owner === 0 ? 'White': (this.amount.owner === 1 ? 'Blue' : 'Red');
                return '/assets/map-symbols/' + color + 'CircleArrow.svg';
            },
          colorClass(){
              if(this.amount.owner == 0){
                return 'white';
              }
              return 'transparent';
          },
          foundBox(){
            let i;
            let foundBox = null;
            let findKey = this.theKey;
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
            return null;
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
              let trueKey = this.trueKey;
              if(trueKey === ""){
                return 0;
              }
              let fromTo = trueKey.split("@");

              let from = this.$store.state.boxes[fromTo[0]];
              let to = this.$store.state.boxes[fromTo[1]];

              return to.x - from.x
            },
            diffY(){
              let trueKey = this.trueKey;
              if(trueKey === ""){
                return 0;
              }
              let fromTo = trueKey.split("@");

              let from = this.$store.state.boxes[fromTo[0]];
              let to = this.$store.state.boxes[fromTo[1]];

              return from.y - to.y

                //return this.$store.state.boxes[this.command.from].y - this.$store.state.boxes[this.command.to].y;
            },
            trueKey(){
              let clash = this.$store.state.borderClashes[this.theKey];
              let owner = clash.owner;
              if(owner === 0){
                return "";
              }
              return this.$store.state.borderClashes[this.theKey].courses[owner];
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
    width:42px;
    transform: rotate(27deg);
  }
  .value-wrapper{
    position:absolute;
    top: 10px;
    left: 2px;
    font-size:22px;
  }
  .white{
    width:40px;
    margin-top: 13px;
  }

  .cross{
    background-size: 12px;
    background-repeat: no-repeat;
    padding-left: 12px;
    height: 12px;
    background-position-y: 5px;
    background-image: url('./cross.svg');
    &.white{
      background-image: url('./black-cross.svg');
    }
  }
}
</style>