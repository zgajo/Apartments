<template>
	<div class="mdl-grid demo-content">


   <kalendar></kalendar>


</div>
</template>



<script>
import 'kalendar/dist/css/kalendar.css'
import Kalendar from './Kalendar'


export default {
  name: 'Home',
  mounted(){
      this.$store.dispatch('retriveAllReservations')
  },
  components:{
    Kalendar
  },
}




</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>



</style>

