<template>
<div>
  <v-layout row wrap class="ma-3">
      <v-flex xs6>
            <v-subheader>Odabrana godina</v-subheader>
      </v-flex>
      <v-flex xs6>
      <v-select
         v-bind:items="years"
         v-model="selectedYear"
        label="Select"
         dark
         single-line
         auto
       ></v-select>
  </v-layout>
<v-layout row wrap class="ma-3">
  		  <v-flex xs12 md6 class="pa-2">
            <v-list-tile-title  class="mb-1 ml-4"> Broj rezervacija po mjesecu </v-list-tile-title>
            <v-divider class="mb-3"></v-divider>
  			   <NoMonthResLine v-if="noReservationsByMonth" :months="months" :reservationsByMonth="noReservationsByMonth"></NoMonthResLine>
  	    </v-flex>
      	<v-flex xs12  md6   class="pa-2" >
          <v-list-tile-title class="mb-1  ml-4"> Zarada po mjesecu </v-list-tile-title>
          <v-divider class="mb-3" ></v-divider>
	        <EarningsByMonth v-if="monthlyEarnings" :months="months" :pdv="monthlyPdv" :provizija="monthlyProvizija" :earnings="monthlyEarnings" :profit="monthlyProfit"></EarningsByMonth>
      	</v-flex>
</v-layout>
<v-layout row wrap class="pa-3">
      	<v-flex xs12  md6  class="pa-2" >
          <v-list-tile-title class="mb-1  ml-4"> Godišnja zarada </v-list-tile-title>
          <v-divider class="mb-3"></v-divider>
	        <Earnings :pdv="godisnjiPdv" :cijena="godisnjaCijena" :provizija="godisnjaProvizija"></Earnings>
      	</v-flex>
      	<v-flex xs12 md6 class="pa-2" >
          <v-list-tile-title class="mb-1  ml-4"> Broj rezervacija po načinu rezerviranja </v-list-tile-title>
          <v-divider class="mb-3"></v-divider>
          <RezerviranoSa :rezSa="rez_sa"></RezerviranoSa>
      	</v-flex>
</v-layout> 
</div>
</template>

<script>
import Chart from "chart.js";
import NoMonthResLine from "./Grafovi/NoMonthResLine";
import Earnings from "./Grafovi/Earnings";
import EarningsByMonth from "./Grafovi/EarningsByMonth";
import RezerviranoSa from "./Grafovi/RezervacijeSa";

export default {
  name: "Rezervacije",
  components: {
    NoMonthResLine,
    Earnings,
    EarningsByMonth,
    RezerviranoSa
  },
  data() {
    return {
      reservationsByYear: null,
      noReservationsByMonth: [],
      months: [
        "Sij",
        "Velj",
        "Ožu",
        "Tra",
        "Svi",
        "Lip",
        "Srp",
        "Kol",
        "Ruj",
        "Lis",
        "Stu",
        "Pro"
      ],
      godisnjiPdv: null,
      godisnjaCijena: null,
      godisnjaProvizija: null,
      monthlyEarnings: [],
      monthlyPdv: [],
      monthlyProvizija: [],
      monthlyProfit: [],
      showNoReservationsByMonth: false,
      rez_sa: null,
      years: [2017],
      selectedYear: new Date().getFullYear()
    };
  },
  watch: {
    reservationsByYear: function(value) {
      this.getMonthReservations();
    },
    selectedYear: function(newVal, oldVal) {
      if (oldVal && newVal != oldVal) {
        this.getReservationsByYear(newVal);
      }
    }
  },
  mounted() {
    let thisYear = new Date().getFullYear();

    while (this.years[0] != thisYear) {
      this.years.push(thisYear);
      thisYear -= 1;
    }

    this.getReservationsByYear(this.selectedYear);
  },
  methods: {
    getReservationsByYear(year) {
      axios
        .post("/statistika/byYear/" + year + "?token=" + this.$auth.getToken())
        .then(response => {
          this.reservationsByYear = response.data;
        })
        .catch(err => {
          console.log(err);
        });
    },
    getMonthReservations() {
      let months = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12];
      let monthlyReservations = [];
      let monthlyLength = [];

      let activeReservations = this.reservationsByYear.filter(
        reservation => reservation.status == "Aktivno"
      );
      let canceledReservations = this.reservationsByYear.filter(
        reservation => reservation.status == "Otkazano"
      );

      this.godisnjaCijena = _.sumBy(activeReservations, function(reservations) {
        return reservations.cijena;
      }).toFixed(2); // Ukupna cijena
      this.godisnjiPdv = _.sumBy(activeReservations, function(reservations) {
        return reservations.pdv;
      }).toFixed(2); // Ukupan pdv
      this.godisnjaProvizija = _.sumBy(activeReservations, function(
        reservations
      ) {
        return reservations.provizija;
      }).toFixed(2); // Ukupan provizija

      //  Postavljanje svih rezervacija po mjesecima
      _.forEach(months, month => {
        monthlyReservations.push(
          activeReservations.filter(
            reservation => moment(reservation.dolazak).format("M") == month
          )
        );
      });

      /*  This works, but bellow is my solution.

      this.rez_sa = _(activeReservations)
      .countBy('rez_preko')
      .map((count, rez_preko) => ({ rez_preko, count }))
      .value();
      */

      this.rez_sa = null;

      let temp = {};
      for (let r of activeReservations) {
        temp[r.rez_preko] ? (temp[r.rez_preko] += 1) : (temp[r.rez_preko] = 1);
      }

      this.rez_sa = temp;

      this.noReservationsByMonth = this.monthlyEarnings = null;
      this.noReservationsByMonth = [];
      this.monthlyEarnings = [];

      //  Earnings, pdv, provizija by month
      _.forEach(monthlyReservations, monthy => {
        
        this.noReservationsByMonth.push(monthy.length); // Number of reservations by month

        this.monthlyEarnings.push(
          _.sumBy(monthy, function(monthEarn) {
            return monthEarn.cijena;
          }).toFixed(2)
        ); // Primljeno po mjesecu
        

        this.monthlyPdv.push(
          _.sumBy(monthy, function(monthEarn) {
            return monthEarn.pdv ? monthEarn.pdv : 0;
          }).toFixed(2)
        ); // PDV po mjesecu


        this.monthlyProvizija.push(
          _.sumBy(monthy, function(monthEarn) {
            return monthEarn.provizija ? monthEarn.provizija : 0 ;
          }).toFixed(2)
        ); // Provizija po mjesecu

        
        this.monthlyProfit.push(
          _.sumBy(monthy, function(monthEarn) {
            monthEarn.cijena = monthEarn.cijena || 0;
            monthEarn.provizija = monthEarn.provizija || 0;
            monthEarn.pdv = monthEarn.pdv || 0 ;
            return monthEarn.cijena - monthEarn.provizija - monthEarn.pdv;
          }).toFixed(2)
        ); // Provizija po mjesecu
        
      });


      

    }
  }
};
</script>

<style lang="css" scoped>

</style>