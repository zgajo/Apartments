<template>
<div>
<router-view></router-view>
</div>
</template>

<script>

export default {
  name: 'Pocetna',
  mounted () {
        var channel = this.$pusher.subscribe('my-channel');
 
        channel.bind('my-event', (data) => {
          this.$store.dispatch('retriveAllReservations');
       });
    }
};
</script>
