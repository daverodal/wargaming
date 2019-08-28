<template>
    <div @click="close" v-if="isVis" class="background">

        <div class="cool-box">
            <div class="close" @click="close">X</div>
            <h1> Events</h1>
            <div class="your-messages">
                <div class="your-message" v-for="msg in messageQueue" v-html="msg"></div>
            </div>
            <h2>Previous events <i @click.stop="showPrev = !showPrev" :class="angleType" class="fa"></i></h2>
            <div v-if="showPrev" class="your-messages" v-html="prevLogs"></div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "FlashMessages",
        data: ()=>{
            return {message: null, messageQueue: [], showPrev: false}
        },
        mounted(){
          // this.messageQueue = [...this.messages];
          //   if(this.messageQueue.length > 0){
          //       this.message = this.messageQueue.shift();
          //       this.startTick();
          //   }
        },
        computed:{
            angleType(){

              if(this.showPrev){
                  return 'fa-angle-down'
              }  else{
                  return 'fa-angle-right';
              }
            },
          isVis(){
              return this.$store.state.floaters.isVis;
          },
            prevLogs(){
              return this.$store.state.headerData.log;
            }
        },
        props:['messages'],
        watch:{
            messages(){
                this.messageQueue = [...this.messages];
                if(this.messageQueue.length > 0){
                    this.showPrev = false;
                    this.$store.commit('floaters/show');
                    // this.message = this.messageQueue.shift();
                    // this.startPulseOn();
                    setTimeout(()=>{
                        this.$store.commit('floaters/hide');
                    }, 2000)
                }
            }
        },
        methods:{
            close(){
              this.$store.commit('floaters/hide');
            },
            startPulseOn(){
                setTimeout(this.endPulseOn, 30);
            },
            endPulseOn(){
              this.message = null;
              setTimeout(this.fadeCompleted, 3000);
            },
            fadeCompleted(){
                if(this.messageQueue.length > 0){
                    this.message = this.messageQueue.shift();
                    console.log(this.message);
                    this.startPulseOn();
                }else{
                    this.message = null;
                }
            }
        }
    }
</script>

<style lang="scss" scoped>
    h1{
        font-size:25px;
        font-weight: 500;
        margin-top: 0px;
    }
    h2{
        font-size:18px;
        font-weight: 500;
        margin-top:0px;
    }
    .background{
        z-index: 19;
        position:fixed;
        top:0;
        left:0;
        right:0;
        bottom:0;
        background-color: rgba(0,0,0,.3);
    }
    .your-messages{
        list-style: none;
        padding-left:20px;
        .your-message{
            margin-bottom: 10px;
        }
    }
    .flashMessage{
    }
    .fade-enter{
        opacity: 1;
    }
    .fade-enter-active{
    }

    .fade-leave{

    }
    .fade-leave-active{
        opacity:0;
        transition: opacity 3s;
    }

    .cool-box {
        position:fixed;
        font-size: 22px;
        background: rgba(255, 255, 255, .9);
        border: 1px solid #333;
        border-radius: 15px;
        margin: 40px;
        padding: 20px;
        box-shadow: 10px 10px 10px rgba(20, 20, 20, .7);
        top: 10px;
        width:50%;
        left: 25%;
        margin: 0 auto;
        max-height: 90%;
        overflow-y: scroll;
    }




</style>