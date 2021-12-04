<template>
  <div>
    <div v-html="date + message">that is the way it goes</div>
  </div>
</template>

<script>
export default {
  name: "EastWestHeader.vue",
  props: ['message', 'scenario'],
  computed:{
    date(){
      if(this.scenario === 'barbarossa'){
        return ['Jun 41', 'Jul 41', 'Aug 41', 'Sep 41', 'Oct 41 mud', 'Nov 41 snow','Dec 41 snow','Jan 42 snow' ][this.turn - 1]
      }
      if(this.scenario === 'stalingrad'){
        return ['Jul 42', 'Aug 42', 'Sep 42', 'Oct 42 mud', 'Nov 42 snow','Dec 42 snow','Jan 43 snow', 'Feb 43 snow' ][this.turn - 1]
      }
      if(this.scenario === 'zitadelle'){
        return ['Jul 43', 'Aug 43', 'Sep 43', 'Oct 43 mud', 'Nov 43 snow','Dec 43 snow','Jan 44 snow', 'Feb 44 snow', 'Mar 44 mud' ][this.turn - 1]
      }
    },
    turn(){
      return this.$store.state.headerData.turn;
    }
  }
}
</script>

<style scoped>

</style>