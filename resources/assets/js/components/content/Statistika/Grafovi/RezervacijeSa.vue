<template>
  <canvas id="rezervirano_sa" ></canvas>
</template>

<script>
export default {

  name: 'RezerviranoSa',

  props: ['rez_sa'],
  data(){
    return{
      stranice: [],
      straniceCount: []
    }
  },
  watch:{
    rez_sa: function(value){
      this.setChart()
    }
  },
  methods: {
    setChart(){
      let rez_sa = this.rez_sa;

      var ctx = document.getElementById("rezervirano_sa");
      var myChart = new Chart(ctx, {
          type: 'horizontalBar',
          data: {
              labels: Object.keys(rez_sa) ,
              datasets: [{
                  label: 'Broj rezervacija',
                  data: Object.values(rez_sa),
                  backgroundColor: [
                      '#ff6384',
                      '#36a2eb',
                      '#ffce56',
                      '#99ff99',
                      '#ffbc70',
                      '#9b7900',
                      '#46db2b'
                  ],
                  borderWidth: 1
              }]
          },
           options: {
              scales: {
                  xAxes: [{
                      ticks: {
                          beginAtZero:true
                      }
                  }]
              },
              legend: { display: false },
              title: {
                display: true,
                text: 'Broj rezervacija sa stranica'
              }
          }
      });
    }
  }
};




</script>

<style lang="css" scoped>
</style>