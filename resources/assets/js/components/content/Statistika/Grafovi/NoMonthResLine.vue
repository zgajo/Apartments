<template>

    <div id="monthResLine">
        <canvas id="monthChart"></canvas>
    </div>
  
</template>

<script>
export default {

  name: 'NoMonthResLine',

  props: ['reservationsByMonth', 'months'],
  watch:{
    reservationsByMonth: function(value){

        document.getElementById("monthChart").remove();
        document.getElementById("monthResLine").insertAdjacentHTML('beforeend', '<canvas id="monthChart" ></canvas>');

      this.setBarChart()
    }
  },
  methods: {
    setBarChart(){
      let numOfRes = this.reservationsByMonth;
      let months = this.months;
      
      var ctx = document.getElementById("monthChart");
      var myChart = new Chart(ctx, {
          type: 'line',
          data: {
              labels: months,
              datasets: [{
                  label: 'Mjesečni broj rezervacija',
                  data: numOfRes,
                  backgroundColor: [
                      'rgba(54, 162, 235, 0.2)',
                  ],
                  borderColor: [
                      'rgba(54, 162, 235, 1)',
                  ],
                  borderWidth: 1,
              }]
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