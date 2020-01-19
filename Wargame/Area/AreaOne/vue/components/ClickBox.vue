<template>
    <div  @click="select" class="label-box" :class="{green: box.owner == 1, red: box.owner == 2, selected: isSelected}" :style="{top: box.y, left: box.x}">
        {{box.name}}
         <span> {{box.armies[1] || 0}} {{box.armies[2] || 0}}
         </span>
        <div v-if="open">
            This is the clickedy part
        </div>
    </div>
</template>

<script>
    export default {
        name: "ClickBox",
        props: ['box'],
        data: () => {
            return {open: false}
        },
        computed: {
          isSelected(){
              if(this.box.id === this.$store.state.selected){
                  return true;
              }
              return false;
            }
        },
        methods:{
            clicked(){
            },
            select(){
                if(this.isSelected){
                    this.$store.commit('selected', null);
                }else{
                    this.$store.commit('selected', this.box.id);
                }
            }
        }
    }
</script>

<style lang="scss" scoped>
    div{
        border: 3px solid transparent;
    }
    .green{
        background:lime;
    }
    .red{
        background: orangered;
    }
    .selected{
        border-color: yellow;
    }
</style>