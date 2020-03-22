<template>
    <div :style="{top: y, left: x}" class="circle-wrapper">
        <img :src="circleUrl" :style="{transform: angle}" class="circle">
        <div class="value-wrapper">{{amount}}</div>
    </div>
</template>

<script>
    export default {
        name: "MoveCircle",
        data() {
            return {
                value: 5
            }

        },
        props: ['amount', 'command'],
        computed: {
            circleUrl(){
                debugger;
                const color = this.command.playerId == 1 ? 'Green' : 'Red';
                return '/assets/map-symbols/' + color + 'CircleArrow.svg';
            },
            x(){
                return (this.$store.state.boxes[this.command.from].x + this.$store.state.boxes[this.command.to].x)/2;
            },
            y(){
                return (this.$store.state.boxes[this.command.from].y + this.$store.state.boxes[this.command.to].y)/2;
            },
            diffX(){
                return this.$store.state.boxes[this.command.to].x - this.$store.state.boxes[this.command.from].x
            },
            diffY(){
                return this.$store.state.boxes[this.command.from].y - this.$store.state.boxes[this.command.to].y;
            },
            angle(){
                debugger;
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