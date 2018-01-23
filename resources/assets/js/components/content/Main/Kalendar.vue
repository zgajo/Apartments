<template>
  <div class="wrapper">{{checkDodajUKalendar}}
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <!-- Main content -->
      <section class="content">
        <div class="row">
          <div class="col-md-3">
            <div class="box box-solid">
              <div class="box-header with-border">
                <h4 class="box-title">Nadolazeće rezervacije </h4>
              </div>
              <div class="box-body">
                <!-- the events -->
                <div id="external-events" >
                <!--  Lista nadolazećih rezervacija -->
                  <v-layout row justify-center>
                    <v-dialog v-model="dialog"  style="width: 100%;" scrollable>
                     <v-card horizontal  v-for="(rezervacija, i) in nadolazece" :key="i"  @click="setDetaljnijaRezervacija(rezervacija.id)" slot="activator" style="margin-bottom: 4px"  >
                      <v-card-row v-if="rezervacija.apartman == 1" img="/img/crveni.jpg" style="margin: 0"></v-card-row>
                      <v-card-column>
                        <v-card-row height="75px" :style="[{ 'background-color': rezervacija.color } ]" style="color: white">
                          <v-card-text>
                            <strong>{{rezervacija.guest}}</strong><br>
                            <span style="font-size: 13px;display: inline-block;">Dolazak: </span>
                            <div style="font-size: 13px; display: inline-block;" v-text="momentDate(rezervacija.dolazak)"></div><br>
                            <span style="font-size: 13px;display: inline-block;">Odlazak: </span>
                            <div style="font-size: 13px; display: inline-block;" v-text="momentDate(rezervacija.odlazak)"></div><br>
                            <span style="font-size: 13px;display: inline-block;">Broj noći: </span>
                            <div style="font-size: 13px;display: inline-block;" v-text="brojNoci(rezervacija)"></div><br>
                            <span style="font-size: 13px;display: inline-block;">Cijena: </span>
                            <div style="font-size: 13px;display: inline-block;" v-text="rezervacija.cijena + ' €'"></div><br>
                            <span style="font-size: 13px;display: inline-block;">Rezervirano sa: </span>
                            <div style="font-size: 13px;display: inline-block;" v-text="rezervacija.rez_preko"></div>
                          </v-card-text>
                        </v-card-row>
                      </v-card-column>
                      <v-card-row v-if="rezervacija.apartman == 2" img="/img/crni.jpg" style="margin: 0"></v-card-row>
                    </v-card>
                    <v-card>
                      <v-card-row height="380px">
                        <v-card-text>
                         <v-card-row>
                          <v-card-title>Apartman {{getDetaljnije.apartman}}</v-card-title>
                        </v-card-row>

                        <!-- Dio o gostu -->
                        <v-layout row wrap>
                          <v-list-tile-sub-title>Gost</v-list-tile-sub-title>
                          <v-text-field xs12 v-if="!getDetaljnije.tel_broj && !getDetaljnije.email"  :value="(getDetaljnije.zemlja) ? getDetaljnije.guest + ', '+ getDetaljnije.zemlja : getDetaljnije.guest" readonly required></v-text-field>

                          <v-expansion-panel v-if="getDetaljnije.tel_broj || getDetaljnije.email" >
                            <v-expansion-panel-content >
                              <div v-text="(getDetaljnije.zemlja) ? getDetaljnije.guest + ', '+ getDetaljnije.zemlja : getDetaljnije.guest"  slot="header" ></div>
                              <v-card>
                                <v-list-item v-if="getDetaljnije.tel_broj">
                                  <v-list-tile>
                                    <v-list-tile-action>
                                      <v-icon class="indigo--text">phone</v-icon>
                                    </v-list-tile-action>
                                    <v-list-tile-content>
                                      <v-list-tile-title>{{getDetaljnije.tel_broj}}</v-list-tile-title>
                                    </v-list-tile-content>
                                  </v-list-tile>
                                </v-list-item>
                                 <v-list-item v-if="getDetaljnije.email">
                                  <v-list-tile>
                                    <v-list-tile-action class>
                                      <v-icon class="indigo--text">mail</v-icon>
                                    </v-list-tile-action>
                                    <v-list-tile-content>
                                      <v-list-tile-title >{{getDetaljnije.email}}</v-list-tile-title>
                                    </v-list-tile-content>
                                  </v-list-tile>
                                </v-list-item>
                              </v-card>
                            </v-expansion-panel-content>
                          </v-expansion-panel>
                        </v-layout>
                        <!--  Kraj - Dio o gostu -->
                        <v-layout row wrap>
                          <v-text-field xs5 label="Dolazak" :value="momentDate(getDetaljnije.dolazak)" readonly required></v-text-field>
                          <v-text-field xs5  label="Odlazak" :value="momentDate(getDetaljnije.odlazak)" readonly required></v-text-field>
                        </v-layout>
                        <v-layout row wrap>
                          <v-text-field xs6  label="Sa stranice" :value="getDetaljnije.rez_preko" readonly required></v-text-field>
                          <v-text-field xs6  label="Cijena €" :value="getDetaljnije.cijena" readonly required></v-text-field>
                        </v-layout>
                        <v-layout row wrap>
                          <v-text-field xs4 label="Br. Osoba" :value="getDetaljnije.no_guests" readonly required></v-text-field>
                          <v-text-field xs4  label="Noći" :value="brojNoci(getDetaljnije)" readonly required></v-text-field>
                        </v-layout>
                        <v-layout row wrap>
                          <v-text-field xs4  label="Provizija €:" v-if="getDetaljnije.provizija" :value="getDetaljnije.provizija" readonly required></v-text-field>
                          <v-text-field xs4  label="PDV €:"  v-if="getDetaljnije.pdv" :value="getDetaljnije.pdv" readonly required></v-text-field>
                        </v-layout>
                        <v-layout row>
                          <v-text-field
                          name="input-napomena"
                          label="Napomena"
                          :value="getDetaljnije.napomena" readonly
                          multi-line
                          ></v-text-field>
                        </v-layout>
                      </v-card-text>
                    </v-card-row>
                    <v-card-row actions>
                      <v-btn class="blue--text darken-1" flat  :router="true"  v-if="this.$auth.userPermisions() != 'guest' "  :to="'/urediGosta/'+getDetaljnije.guest_id">Uredi gosta</v-btn>
                      <v-btn class="blue--text darken-1" flat  :router="true"  v-if="this.$auth.userPermisions() != 'guest' " :to="'/urediRezervaciju/'+getDetaljnije.id">Uredi rezervaciju</v-btn>
                    </v-card-row>
                  </v-card>
                </v-dialog>
              </v-layout>
              <!-- Kraj - Lista nadolazećih rezervacija -->
            </div>
          </div>
          <!-- /.box-body -->
        </div>
        <!-- /. box -->
      </div>
      <div class="col-md-9">
        <div class="box box-primary">
          <div class="box-body no-padding">
            <!-- THE CALENDAR -->
            <div id="calendar"></div>
            <div @click="dialog = true" style="display: none" id="fakeKlik">Fake klik</div>
          </div>
          <!-- /.box-body -->
        </div>
        <!-- /. box -->
      </div>
      <!-- /.col -->
      <!-- /.col -->
    </div>
    <!-- /.row -->

    <novaNarudzba @uspjesno="azurirajKalendar" v-if="this.$auth.userPermisions() != 'guest' "></novaNarudzba>

  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->
</div>
<!-- ./wrapper -->
</template>



<script>
  import { mapGetters } from 'vuex'
  import { mapActions } from 'vuex'
  import novaNarudzba from './NovaNarudzba'
  import moment from 'moment'



  export default {

    name: 'Kalendar',
    data(){
      return{
        dialog:false,
      }
    },
    methods:{
      ...mapActions([
        'setDetaljnijaRezervacija',
        ]),
      momentDate(date){
        return moment(date).format('DD.MM.YYYY')
      },
      brojNoci(rez){
        return moment(rez.odlazak, 'YYYY-MM-DD').diff(moment(rez.dolazak, 'YYYY-MM-DD'), 'days');

      },
      azurirajKalendar(){
        let rezZaKal = this.dodajUKalendar;
        $('#calendar').fullCalendar( 'removeEvents' )
        $('#calendar').fullCalendar( 'addEventSource', rezZaKal )
      }
    },
    computed:{
      ...mapGetters([
        'rezervacije',
        'nadolazece',
        'getDetaljnije',
        'dodajUKalendar'
        ]),
      checkDodajUKalendar(){
        if(this.dodajUKalendar.length)
          {
            let store =   this.$store;
            let komponenta = this;
            let rezZaKal = this.dodajUKalendar;

            kreirajKalendar(store, komponenta, rezZaKal)
            checkWindowSize();
          }
      }
    },
    components: {
      novaNarudzba
    }
  };



function checkWindowSize(){
            var calendarOption = 0;
            $('body').on('DOMNodeInserted', '#calendar .fc-view-container', function(e) {
              if(window.outerWidth < 640)
                {
                    if(calendarOption>0) return;
                    ++calendarOption;
                    console.log(calendarOption)
                    $('#calendar').fullCalendar('option', 'height', 600);
                }
            });
}


  function kreirajKalendar(store, komponenta, rezervacije){

    $(function () {

            /* initialize the calendar
            -----------------------------------------------------------------*/
            //Date for the calendar events (dummy data)
            $('#calendar').fullCalendar({
              locale: 'hr',
              header: {
                left: 'prev,next today',
                center: 'title',
                right: 'month,agendaWeek '
              },
              buttonText: {
                today: 'danas',
                month: 'mjesec',
                week: 'tjedan',
                day: 'dan'
              },
              firstDay: 1,
              monthNames :['Siječanj', 'Veljača', 'Ožujak', 'Travanj', 'Svibanj', 'Lipanj', 'Srpanj', 'Kolovoz', 'Rujan', 'Listopad', 'Studeni', 'Prosinac'],
              dayNames: ['Nedjelja', 'Ponedjeljak', 'Utorak', 'Srijeda', 'Četvrtak', 'Petak', 'Subota'],
              dayNamesShort: ['Ned', 'Pon', 'Uto', 'Sri', 'Čet', 'Pet', 'Sub'],
              events: rezervacije,
                editable: true,
                eventClick: function(event, element) {

                  element.stopPropagation();
                  // Fake klik napravim, kako bi se izmijenio vrijednost dialoga. Na dinamički kreiranim eventima mi ne mijenja vrijednost
                  $('#fakeKlik').trigger('click');
                  store.dispatch('setDetaljnijaRezervacija', event.id)
                }
            });


          });

}




</script>

<style scoped>

  #external-events{
    height: 535px; overflow: auto; 
  }

  .col-md-3{
    width:25%;
    position: relative;
    min-height: 1px;
    padding-right: 15px;
    padding-left: 15px;
    float: left;
  }

  .col-md-9{
    width:75%;
    position: relative;
    min-height: 1px;
    padding-right: 15px;
    padding-left: 15px;
    float: left;
  }

  .content-wrapper{
    min-height: 901.2px;
  }
  .layout{
    margin-right: 0px;
    margin-left: 0px;
  }

  #calendar .fc-scroller{
    height: 100% !important;
  }

  

  @media screen and (max-width: 1250px) {
    .col-md-3{
      width:35%;
    }

    .col-md-9{
      width:65%;
    }
  }

  @media screen and (max-width: 800px) {
    .col-md-3{
      width:100%;
    }

    .col-md-9{
      width:100%;
    }
  }

</style>