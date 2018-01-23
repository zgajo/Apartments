<template>

    <div id="earnings_by_month">
        <canvas id="monthEarnings" ></canvas>
    </div>
  
</template>

<script>
export default {

  name: 'EarningsByMonth',

  props: ['earnings', 'pdv', 'provizija', 'profit','months'],
  watch:{
    earnings: function(value){
        
        document.getElementById("monthEarnings").remove();
        document.getElementById("earnings_by_month").insertAdjacentHTML('beforeend', '<canvas id="monthEarnings" ></canvas>');

        this.setLineChart()
    }
  },
  methods: {
    setLineChart(){
      let earnings = this.earnings;
      let months = this.months;
      let pdv = this.pdv;
      let provizija = this.provizija;
      let profit = this.profit;
      
      var ctx = document.getElementById("monthEarnings");
      let myChart = new Chart(ctx, {
          type: 'line',
          data: {
              labels: months,
              datasets: [{
                  label: 'Uplaćeno',
                  data: earnings,
                  backgroundColor: [
                      'rgba(54, 162, 235, 0.2)',
                  ],
                  borderColor: [
                      'rgba(54, 162, 235, 1)',
                  ],
                  borderWidth: 1
              },
              {
                  label: 'Pdv',
                  data: pdv,
                  backgroundColor: [
                      'rgba(54, 50, 235, 0.2)',
                  ],
                  borderColor: [
                      'rgba(54, 50, 235, 1)',
                  ],
                  borderWidth: 1
              },
              {
                  label: 'Provizija',
                  data: provizija,
                  backgroundColor: [
                      '#42f4c5',
                  ],
                  borderColor: [
                      '#129975',
                  ],
                  borderWidth: 1
              },
              {
                  label: 'Profit',
                  data: profit,
                  backgroundColor: [
                      '#ffffb3',
                  ],
                  borderColor: [
                      '#e6e600',
                  ],
                  borderWidth: 1
              }
              ]
          },
          options: {
              scales: {
                  yAxes: [{
                      ticks: {
                          beginAtZero:true
                      }
                  }]
              }
          }
      });
    }
  }
};




</script>

<style lang="css" scoped>
</style>