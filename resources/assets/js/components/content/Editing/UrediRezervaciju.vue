<template>
 <v-layout row class="ma-3 ">{{dohvatRezervacije}}
    <v-flex xs12 >
      <v-card>
        <v-toolbar  style="z-index: 0 !important;" class="white--text indigo" light>
        <v-toolbar-title>Ažuriranje gosta</v-toolbar-title>
        </v-toolbar>
        <v-card class="grey lighten-4 elevation-0">
          <v-card-text>
            <v-container fluid>

              <v-layout row>
                <v-flex xs6>
                  <v-select required
                  name="input-3"
                  label="Apartman"
                  v-model="reservation.apartman"
                  v-bind:items="apartmani"
                  ></v-select>
                </v-flex>
                <v-flex xs6>
                  <v-text-field  required
                  name="input-3"
                  label="Rezervirano preko"
                  v-model="reservation.rez_preko"
                  ></v-text-field>
                </v-flex>
              </v-layout>
              <v-layout row wrap>
                    <!--  Datumi -->
                    <v-flex  xs12 sm6 md6 >
                      <v-menu
                      lazy
                      :close-on-content-click="true"
                      v-model="prikaziDolazak"
                      transition="v-scale-transition"
                      offset-y
                      :nudge-left="-20"
                      >
                      <v-text-field required
                      slot="activator"
                      label="Datum dolaska"
                      v-model="formattedDO"
                      prepend-icon="event"
                      readonly
                      ></v-text-field>
                      <v-date-picker   :allowed-dates="allowedDatesperiodDolazak"  :months="months" :days="days"  v-model="reservation.dolazak" :date-format="date => { return momentDate(date) }"
                        :formatted-value.sync="formattedDO"  actions no-title scrollable actions>
                        <template scope="{ save, cancel }">
                          <v-card-row actions>
                            <v-btn flat primary @click.native="prikaziDolazak=false">Cancel</v-btn>
                          </v-card-row>
                        </template>
                      </v-date-picker>
                    </v-menu>
                  </v-flex>
                  <v-flex  xs12 sm6 md6 >
                    <v-menu
                    lazy
                    :close-on-content-click="true"
                    v-model="prikaziOdlazak"
                    transition="v-scale-transition"
                    offset-y
                    :nudge-left="-20"
                    >
                    <v-text-field required
                    slot="activator"
                    label="Datum odlaska"
                    v-model="formattedOD"
                    prepend-icon="event"
                    readonly
                    ></v-text-field>
                    <v-date-picker  :allowed-dates="allowedDatesperiodOdlazak" v-model="reservation.odlazak"  :months="months" :days="days"  :date-format="date => { return momentDate(date) }"
                      :formatted-value.sync="formattedOD" no-title scrollable actions>
                      <template scope="{ save, cancel }">
                        <v-card-row actions>
                          <v-btn flat primary @click.native="prikaziOdlazak=false">Cancel</v-btn>
                        </v-card-row>
                      </template>
                    </v-date-picker>
                  </v-menu>
                </v-flex>
               </v-layout>  
              <v-layout row>
                <v-flex xs6>
                  <v-text-field  required
                  name="input-3"
                  label="Cijena"
                  v-model="reservation.cijena"
                  ></v-text-field>
                </v-flex>
                <v-flex xs6>
                  <v-text-field
                  label="Provizija €"
                  v-model="getProvizija"
                   suffix="€"
                  ></v-text-field>
                </v-flex>
              </v-layout>
              <v-layout row>
                <v-flex xs6>
                  <v-text-field
                  label="PDV €"
                  v-model="getPdv"
                   suffix="€"
                  ></v-text-field>
                </v-flex>
                <v-flex xs6>
                  <v-text-field required
                  name="input-3"
                  label="Broj osoba"
                  v-model="reservation.no_guests"
                  ></v-text-field>
                </v-flex>
              </v-layout>
              <v-layout row wrap>

                <v-flex  xs12 sm6 md6 >
                  <v-text-field
                  name="input-3"
                  label="Napomena"
                  v-model="reservation.napomena"
                  ></v-text-field>
                </v-flex>

                <v-flex  xs12 sm6 md6 >
                     <v-menu 
                    origin="center center"
                    transition="v-scale-transition"
                    bottom
                  >
                    <v-btn   slot="activator">
                      Odabir boje
                    </v-btn>
                    <v-list>
                      <v-list-item v-for="item in pickColor" :key="item"  @click="postavljenaBoja = item.class">
                        <v-list-tile :style="{backgroundColor: item.class, color: item.fontColor}"  >
                          <v-list-tile-title >{{ item.boja }}</v-list-tile-title>
                        </v-list-tile>
                      </v-list-item>
                    </v-list>
                  </v-menu>
                  <v-btn v-if="postavljenaBoja" :style="{backgroundColor: postavljenaBoja}" style="color: white">
                      Odabrana boja - " {{postavljenaBoja}} "
                    </v-btn>
                </v-flex>
                
              </v-layout>
              <v-layout row>
                <v-flex xs6>
                  <v-select required
                  name="input-3"
                  label="Status"
                  v-model="reservation.status"
                  v-bind:items="statusi"
                  ></v-select>
                </v-flex>
              </v-layout>
              <v-layout row>
                <v-alert success v-bind:value="uspjesno">
                  Uspješno ažurirani podaci gosta.
                </v-alert>
                <v-alert error v-bind:value="neuspjesno">
                  Dogodila se pogreška.
                </v-alert>
              </v-layout>
              <v-card-row actions>
                <v-btn class="blue--text darken-1"  :router="true" :to="'/'" flat>Natrag</v-btn>
                <v-btn class="blue--text darken-1" @click.native="spremi" flat>Spremi izmjene</v-btn>
              </v-card-row>
            </v-container>
          </v-card-text>
        </v-card>
      </v-card>
    </v-flex>
  </v-layout>
</template>



<script>
import moment from 'moment'

  export default {

    name: 'UrediRezervaciju',

    data () {
      return {
        reservation_id: this.$route.params.reservation_id,
          reservation: {
            no_guests:null,
            dolazak:null,
            color:null,
            apartman:null,
            cijena:null,
            id:null,
            napomena:null,
            odlazak:null,
            pdv: null,
            provizija: null,
            rez_preko: null,
            status: null,
          },
          apartmani: [1, 2],
          prikaziOdlazak: false,
          prikaziDolazak: false,
          periodOdlazak: {
              min: null,
              max: null
            },
            allowedDatesperiodOdlazak: null,
            periodDolazak: {
              min: null,
              max: null
            },
            allowedDatesperiodDolazak: null,
          statusi: ['Aktivno' , 'Otkazano'],
          pickColor: [
            {boja: 'Crvena', class: '#dd4b39', fontColor: '#fff' }, 
            {boja: ' #e16151', class: ' #e16151', fontColor: '#fff' }, 
            {boja: '#e57566', class: '#e57566', fontColor: '#fff' }, 
                   
                  {boja: 'Navy', class: '#001f3f', fontColor: '#fff' }, 
                  {boja: '#003366', class: '#003366', fontColor: '#fff' },
                  {boja: ' #004080', class: ' #004080', fontColor: '#fff' }, 
                  
                  {boja: 'Obriši odabrano', class: null, fontColor: '#444' }, 
          ],
          months: ['Siječanj', 'Veljača', 'Ožujak', 'Travanj', 'Svibanj', 'Lipanj', 'Srpanj', 'Kolovoz', 'Rujan', 'Listopad', 'Studeni', 'Prosinac'],
          days: ['Nedjelja', 'Ponedjeljak', 'Utorak', 'Srijeda', 'Četvrtak', 'Petak', 'Subota'],
          uspjesno: false,
          neuspjesno: false,
      };
    },
    methods: {
      momentDate(date){
        return moment(date).format('DD.MM.YYYY')
      },
      spremi(){
        this.uspjesno = false;
        this.neuspjesno = false;
        axios.post('/rezervacije/update/'+this.reservation_id+"?token="+this.$auth.getToken(), {
            no_guests: this.reservation.no_guests,
            dolazak:this.reservation.dolazak,
            color:this.reservation.color,
            apartman:this.reservation.apartman,
            cijena:this.reservation.cijena,
            napomena:this.reservation.napomena,
            odlazak:this.reservation.odlazak,
            pdv: this.reservation.pdv,
            provizija: this.reservation.provizija,
            rez_preko: this.reservation.rez_preko,
            status: this.reservation.status,
        })
        .then(response => {
          this.uspjesno = true;

        })
        .catch(response => {
          console.log(response)
          this.neuspjesno = true;
        })
      }
    },
    computed:{
      dohvatRezervacije(){
        if(this.$store.getters.rezervacije.length) {
          this.reservation = this.$store.getters.findRezervacija(this.reservation_id);

          this.periodDolazak.min = moment(this.reservation.odlazak).add(-6, 'month')
          this.periodDolazak.max = moment(this.reservation.odlazak).add(-1, 'd')
          this.allowedDatesperiodDolazak = this.periodDolazak;

          this.periodOdlazak.min = moment(this.reservation.dolazak).add(1, 'day')
          this.periodOdlazak.max = moment(this.reservation.dolazak).add(6, 'month')
          this.allowedDatesperiodOdlazak = this.periodOdlazak;
        }
      },
      formattedDO() {
        return this.momentDate(this.reservation.dolazak)
      },
      formattedOD() {
        return this.momentDate(this.reservation.odlazak)
      },
      getProvizija: {
        get(){
           if(this.reservation.rez_preko == 'Booking' || this.reservation.rez_preko == 'booking') 
            {
              this.reservation.provizija = this.reservation.cijena * 0.15;
              return this.reservation.provizija;
            }
           return this.reservation.provizija;
        },
        set(value){
          this.reservation.provizija = value;
        }
      },
      getPdv:{
        get(){
          if(this.reservation.rez_preko == 'Booking' || this.reservation.rez_preko == 'booking') 
          {
            this.reservation.pdv = this.reservation.provizija * 0.25;
            return this.reservation.pdv
          }
          return this.reservation.pdv
        },
        set(value){
          this.reservation.pdv = value;
        }
      },
      postavljenaBoja:{
        get(){
          return this.reservation.color;
        },
        set(value){
          this.reservation.color = value
        }
      },
    }
  };
</script>
