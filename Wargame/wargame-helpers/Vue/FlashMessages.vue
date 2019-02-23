<template>
    <div>

    <transition name="fade">
        <div id="MyFlashMessage" class="flashMessage" v-if="message !== null" v-html="message"></div>
    </transition>
        <div v-for="msg in messageQueue">
            <div v-html="msg"></div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "FlashMessages",
        data: ()=>{
            return {message: null, messageQueue: []}
        },
        mounted(){
          // this.messageQueue = [...this.messages];
          //   if(this.messageQueue.length > 0){
          //       this.message = this.messageQueue.shift();
          //       this.startTick();
          //   }
        },
        props:['messages'],
        watch:{
            messages(){

                this.messageQueue = [...this.messages];
                if(this.messageQueue.length > 0){
                    this.message = this.messageQueue.shift();
                    this.startPulseOn();
                }
            }
        },
        methods:{
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

<style scoped>
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
</style>