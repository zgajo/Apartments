<template>

  <div id="totalEarnings">
    <canvas id="earnings" ></canvas>
  </div>
  
</template>

<script>
export default {

  name: 'Earnings',

  props: ['pdv', 'provizija', 'cijena'],
  watch:{
    cijena: function(value){
      
        document.getElementById("earnings").remove();
        document.getElementById("totalEarnings").insertAdjacentHTML('beforeend', '<canvas id="earnings" ></canvas>');

        this.setDoughnutChart()
    }
  },
  methods: {
    setDoughnutChart(){

      let pdv = (parseFloat(this.pdv)).toFixed(2);
      let provizija = (parseFloat(this.provizija)).toFixed(2);
      let cijena = (parseFloat(this.cijena)).toFixed(2);
      let sum = (parseFloat(cijena-pdv-provizija)).toFixed(2);

      var ctx = document.getElementById("earnings");
      var myChart = new Chart(ctx, {
          type: 'bar',
          data: {
              labels: ["Uplaćeno",  "Pdv", "Provizija", 'Profit'],
              datasets: [{
                  label: 'Prikaz u €',
                  data: [cijena, pdv, provizija, sum],
                  backgroundColor: [
                      '#ff6384',
                      '#36a2eb',
                      '#ffce56',
                      '#99ff99'
                  ],
                  borderWidth: 1
              }]
          },
          options: {
            //showAllTooltips: true,
            legend: { display: false },
            title: {
              display: true,
              text: 'Godišnja zarada'
            },
          }
      });
    }
  }
};




</script>

<style lang="css" scoped>
</style>