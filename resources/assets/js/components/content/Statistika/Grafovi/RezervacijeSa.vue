<template>

  <div id="numRezFrom">
    <canvas id="rezervirano_sa" ></canvas>
  </div>
  
</template>

<script>
export default {

  name: 'RezerviranoSa',

  props: ['rezSa'],

  watch:{
    rezSa: function(value){
      
        document.getElementById("rezervirano_sa").remove();
        document.getElementById("numRezFrom").insertAdjacentHTML('beforeend', '<canvas id="rezervirano_sa" ></canvas>');

      this.setChart()
    }
  },
  methods: {
    setChart(){
      let rezSa = this.rezSa;

      var ctx = document.getElementById("rezervirano_sa");
      var myChart = new Chart(ctx, {
          type: 'bar',
          data: {
              labels: Object.keys(rezSa) ,
              datasets: [{
                  label: 'Broj rezervacija',
                  data: Object.values(rezSa),
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