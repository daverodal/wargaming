<template>
  <div>  <span class="food"> {{resource.food || 0}}</span> <span class="energy">{{resource.energy || 0}}</span> <span class="min"> {{resource.materials || 0}}</span></div>

</template>

<script>
export default {
name: "Resource",
  props:['resource']
}
</script>

<style lang="scss" scoped>
.min{
  background-size:16px;
  background-repeat: no-repeat;
  background-image: url('./Mine.svg');
  padding-left:20px;
}
.food{
  background-size:16px;
  background-repeat: no-repeat;
  background-image: url('./Bowl.svg');
  padding-left:20px;
}
.energy   {
  background-size:16px;
  background-repeat: no-repeat;
  background-image: url('./Bolt.svg');
  padding-left:15px;
}
</style>