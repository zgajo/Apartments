import Vue from 'vue'
import Vuex from 'vuex'
import moment from 'moment'

Vue.use(Vuex)

export const store = new Vuex.Store({
  name: 'store',
  state: {
    rezervacije: [],
    detaljnijaRez: ''
  },
  mutations: {
    eventKlik(state, event){
      event.title = event.id + " CLICKED!";
      $('#calendar').fullCalendar('updateEvent', event);
    },
    setDetaljnije(state, rezervacija){
      state.detaljnijaRez = rezervacija;
    },
    dodajRezervaciju(state, rezervacija){
      state.rezervacije.push(rezervacija)
    }
  },
  actions: {
    retriveAllReservations({state}) {   //Postavaljanje rezervacije koja se pregledava detaljno

       axios.post("/rezervacije/retrive?token=" + localStorage.getItem("token"))  // Dohvat ide na svakom postavljanju 
      .then(function(response){
        state.rezervacije = response.data;
      })
      .catch(function(response){
        console.log(response)
      });
    },
    setDetaljnijaRezervacija(state, id) {   //Postavaljanje rezervacije koja se pregledava detaljno
       let rezervacija = state.getters.findRezervacija(id)
       state.commit('setDetaljnije', rezervacija)
    },
    dodajRezervaciju({commit, dispatch}, rezervacija){
      rezervacija.id=20; //Ovdje bi mi trebalo sa servera vratit zadnji id koji je unesen i njega spremiti za ovu rezervaciju
      commit('dodajRezervaciju', rezervacija)
    },
    
  },
  getters: {
      findRezervacija: (state) => (id) => {
        return state.rezervacije.find(rezervacija => rezervacija.id == id)
      },
      getDetaljnije: (state) => {
        return state.detaljnijaRez;
      },
      rezervacije: (state) => {
        return _.sortBy(state.rezervacije, ['datum_od']);
      },
      nadolazece: (state) => {
        return _.orderBy(state.rezervacije.filter(rezervacija => (moment().isSameOrBefore(rezervacija.dolazak, "day"))), ['datum_od'], ['desc'])
      },
      findGuest: (state) => (id) => {
        return state.rezervacije.find(rezervacija => rezervacija.guest_id == id)
      },
      dodajUKalendar({getters}){
        let Events = [];

      _.forEach(store.getters.rezervacije, function(rezervacija) {

           var myEvent = {
            id: rezervacija.id,
            title: rezervacija.guest + ((!rezervacija.napomena) ? '' :  ' - ' + rezervacija.napomena),
            allDay: true,
            start: new Date(moment(rezervacija.dolazak).format('YYYY, M, D')),
            end: new Date(moment(rezervacija.odlazak).add(1, 'd').format('YYYY, M, D')),
            backgroundColor: rezervacija.color, //Success (green)
            borderColor: rezervacija.color //Success (green)
          };

          Events.push(myEvent)

        });

        return Events;
    },
  },
})
