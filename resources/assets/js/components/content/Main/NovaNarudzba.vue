<template>
	<v-layout row justify-center>
		<v-dialog v-model="nova" fullscreen transition="v-dialog-bottom-transition" :overlay=false>
			<v-btn floating class="indigo fixedButton"  slot="activator">
				<v-icon light>add</v-icon>
			</v-btn>
			<v-card >
				<v-card-row>
					<v-toolbar light>
						<v-btn icon="icon" @click.native="nova = false" light>
							<v-icon>close</v-icon>
						</v-btn>
						<v-toolbar-title>Nova rezervacija</v-toolbar-title>
						<v-btn light flat @click.native="novaRezervacija()">Save</v-btn>
					</v-toolbar>
				</v-card-row>
				<!-- Obavijest o poljima  -->
			    <v-snackbar
			      :timeout="timeout"
			      :top="'top' === 'top'"
			      :success="context === 'success'"
			      :warning="context === 'warning'"
			      v-model="snackbar"
			    >
			      {{ snackbar_text }}
			      <v-btn light flat @click.native="snackbar = false">Close</v-btn>
			    </v-snackbar>


				<v-container fluid  @keyup.enter="novaRezervacija()">
					<v-layout row wrap class="dark--text pl-3 pr-3">
		               	<v-flex xs4 sm4 md2>
				           <v-radio label="Novi gost" v-model="chosenGuestType" value="novi" primary dark hide-details></v-radio>
				        </v-flex>
				        <v-flex xs4 sm4 md2>
				           <v-radio label="Stari gost" v-model="chosenGuestType" value="stari" success dark hide-details></v-radio>
				        </v-flex>
		            </v-layout>


					<!--  Stari gost  -->
		            <v-layout v-if="chosenGuestType == 'stari'" row wrap class="pl-3 pr-3">
				        <v-flex xs6>
					        <v-menu offset-y>
					            <v-text-field
						              label="Pronađi starog gosta"
						              slot="activator"
						              v-model="potrazi_gosta"
						            ></v-text-field>

							      <v-list xs12 >
							        <v-list-item @click="odabirStarogGosta(guest)" v-for="guest in people" :key="guest">
							          <v-list-tile>
							            <v-list-tile-title >{{ guest.guest }}</v-list-tile-title>
							          </v-list-tile>
							        </v-list-item>
							      </v-list>
							</v-menu>
					    </v-flex>
					    <v-flex xs4>
					    	<v-text-field
				              label="Odabrani gost"
				              v-model="stari_gost.guest"
				              disabled
				            ></v-text-field>
					    </v-flex>
					    <v-flex xs2>
					    	<v-icon dark title="Poništi odabir" @click="removeStariGost" class="pa-3" style="cursor: pointer" >delete forever</v-icon>
					    </v-flex>
		            </v-layout>

					<v-layout v-if="chosenGuestType == 'stari'  && stari_gost" row wrap class="pl-3 pr-3">
						<!--  Ime i prezime, zemlja -->
						<v-flex xs6>
							<v-text-field required
							label="Ime i prezime"
							v-model="stari_gost.guest"
							disabled
							></v-text-field>
						</v-flex>
						<v-flex xs6>
							<v-text-field
							label="Zemlja"
							v-model="stari_gost.zemlja"
							disabled
							></v-text-field>
						</v-flex>
					</v-layout>
					<v-layout v-if="chosenGuestType == 'stari'  && stari_gost" row wrap class="pl-3 pr-3">
						<!--  Kontakt podaci -->
						<v-flex xs6>
							<v-text-field
							label="Kontakt broj"
							v-model="stari_gost.tel_broj"
							disabled
							></v-text-field>
						</v-flex>
						<v-flex xs6>
							<v-text-field
							label="Email"
							v-model="stari_gost.email"
							disabled
							></v-text-field>
						</v-flex>
					</v-layout>
					<!-- END  Stari gost  -->
		              

		            <!--  Novi gost  -->
					<v-layout v-if="chosenGuestType == 'novi'" row wrap class="pl-3 pr-3">
						<!--  Ime i prezime, zemlja -->
						<v-flex xs6>
							<v-text-field required
							label="Ime i prezime"
							v-model="gost"
							></v-text-field>
						</v-flex>
						<v-flex xs6>
							<v-text-field
							label="Zemlja"
							v-model="zemlja"
							></v-text-field>
						</v-flex>
					</v-layout>
					<v-layout v-if="chosenGuestType == 'novi'" row wrap class="pl-3 pr-3">
						<!--  Kontakt podaci -->
						<v-flex xs6>
							<v-text-field
							label="Kontakt broj"
							v-model="kontakt"
							></v-text-field>
						</v-flex>
						<v-flex xs6>
							<v-text-field
							label="Email"
							v-model="email"
							></v-text-field>
						</v-flex>
					</v-layout>
					<!-- END Novi gost  -->

					<!--  Datum dolaska, odlaska  -->
					<v-layout row wrap class="pl-3 pr-3">
					<!--  Datumi -->
						<v-flex  xs12 sm6 md6 >
							<v-menu
					          lazy
					          :close-on-content-click="true"
					          v-model="prikaziDolazak"
					          transition="v-scale-transition"
					          offset-y
					          :nudge-left="40"
					        >
					          <v-text-field required
					            slot="activator"
					            label="Datum dolaska"
					            v-model="formattedDO"
					            prepend-icon="event"
					            readonly
					          ></v-text-field>
					          <v-date-picker  :allowed-dates="allowedDatesperiodDolazak" v-model="datum_dolaska" :months="months" :days="days" :date-format="date => { return momentDate(date) }"
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
					          :nudge-left="40"
					        >
					          <v-text-field required
					            slot="activator"
					            label="Datum odlaska"
					            v-model="formattedOD"
					            prepend-icon="event"
					            readonly
					          ></v-text-field>
					          <v-date-picker  :allowed-dates="allowedDatesperiodOdlazak" v-model="datum_odlaska"  :months="months" :days="days"  :date-format="date => { return momentDate(date) }"
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
					<!--  KRAJ Datum dolaska, odlaska  -->

					<!--  Podaci Rezervacije  -->
					<v-layout row wrap class="pl-3 pr-3">
					<!--  Stranica i cijena -->
						<v-flex xs6>
							<v-text-field required
							label="Rezervirano preko"
							v-model="rez_preko"
							></v-text-field>
						</v-flex>
						<v-flex xs6>
							<v-text-field required
							label="Cijena €"
							v-model="cijena"
							 preffix="€"
							></v-text-field>
						</v-flex>
						<v-flex xs6>
							<v-text-field
							label="Provizija €"
							v-model="getProvizija"
							 preffix="€"
							></v-text-field>
						</v-flex>
						<v-flex xs6>
							<v-text-field
							label="PDV €"
							v-model="getPdv"
							 preffix="€"
							></v-text-field>
						</v-flex>
					</v-layout>
					<v-layout row wrap class="pl-3 pr-3">
					<!--  Apartman i broj osoba  -->
						<v-flex xs6>
							<v-select required
				              v-bind:items="apartmani"
				              v-model="odabraniAp"
				              label="Apartman"
				              class="input-group--focused"
				              dark
				              item-value="text"
				            ></v-select>
						</v-flex>
						<v-flex xs6>
							<v-text-field required
							label="Broj osoba"
							v-model="brOsoba"
							></v-text-field>
						</v-flex>
					</v-layout>
					<!-- KRAJ Podaci Rezervacije  -->

					<v-layout row wrap class="pl-3 pr-3">
						<v-flex  xs12 sm6 md6 >
				            <v-text-field
				              v-model="napomena"
				              label="Napomena"
				              multi-line
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
							          <v-list-tile :style="{backgroundColor: item.class, color: item.fontColor}"  style="color: white">
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
				</v-container>
			</v-card>
		</v-dialog>
	</v-layout>
</template>

<script>
import moment from 'moment'

	export default {

		name: 'NovaNarudzba',

		data () {

			return {
				// Prikaz skocnih prozora
				nova: false,
				prikaziOdlazak: false,
				prikaziDolazak: false,
				//Podaci prilikom kreiranja nove rezervacije
				apartmani: [1,2],
				datum_odlaska: null,
				datum_dolaska: null,
				formattedDO: null,
				formattedOD: null,
				gost: null,
				zemlja: null,
				kontakt: null,
				email: null,
				odabraniAp:null,
				rez_preko: null,
				cijena: null,
				brOsoba: null,
				napomena: null,
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
				pdv: null,
				provizija: null,
				pickedColor: null,
				//Dio za obavijest
				snackbar: false,
		        context: '',
		        mode: '',
		        timeout: 6000,
		        snackbar_text: '',
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
		        chosenGuestType: 'novi',
		        people: [],
		        potrazi_gosta: '',
		        stari_gost: '',
			};
		},
		watch:{
			datum_dolaska: function(value){					//postavljanje ograničenja na odlazak
				if(value == null) {this.allowedDatesperiodOdlazak = null; return;}
				this.periodOdlazak.min= moment(value).add(1,'d');
				this.periodOdlazak.max = moment(value).add(6, 'month')
				this.allowedDatesperiodOdlazak = this.periodOdlazak
			},
			datum_odlaska: function(value){					//postavljanje ograničenja na odlazak
				if(value == null) {this.allowedDatesperiodDolazak = null; return;}
				this.periodDolazak.min= moment(value).add(-6, 'month');
				this.periodDolazak.max = moment(value).add(-1,'d');;
				this.allowedDatesperiodDolazak = this.periodDolazak
			},
			potrazi_gosta: function(){
				if(this.potrazi_gosta.length >= 3) {
					this.potraziStarogGosta();
				}else{
					this.people = '';
				}
			},
			chosenGuestType:function(){
				if(this.chosenGuestType == 'novi') {
					this.removeStariGost();
				}
			}

		},
		methods: {
			removeStariGost(){
				this.potrazi_gosta = '';
				this.stari_gost = '';
			},
			odabirStarogGosta(stari){
				this.stari_gost = stari;
			},
			potraziStarogGosta(){
				axios.get( '/find/oldGuest/' + this.potrazi_gosta + '?token='+this.$auth.getToken() )
				.then((response)=>{
					if(response.data.length) this.people = response.data;
					else this.people = '';
				})
				.catch((err)=>{
					console.log(err)
				})
			},
			momentDate(date){
				return moment(date).format('DD.MM.YYYY')
			},
			novaRezervacija(){
				// pROVJERA DA LI SU UPISANI PODACI
				if((this.datum_odlaska  == "") || (this.datum_dolaska  == "") || 
					(this.gost  == "") || (this.odabraniAp  == "") || 
					(this.rez_preko  == "") || (this.pickedColor  == null) || (this.cijena  == "") || 
					(this.brOsoba  == "") )
				{
					this.snackbar_text = "Potrebno je ispuniti sva polja"
					this.context = 'warning';
					this.snackbar = true;
					return;
				}
				else
				{
					if(this.zemlja == '') this.zemlja=null;
					if(this.kontakt == '') this.kontakt=null;
					if(this.email == '') this.email=null;
					if(this.pdv == '') this.pdv=0;
					if(this.provizija == '') this.provizija=0;

					var auth = this.$auth;

					var url = '';
					var podaci = '';

					if(this.chosenGuestType == 'novi')
					{
						url= "/rezervacije/nova";
						podaci = {
							gost: this.gost,
							zemlja: this.zemlja,
							kontakt: this.kontakt,
							email: this.email,
							dolazak: this.datum_dolaska,
							odlazak: this.datum_odlaska,
							rez_preko: this.rez_preko,
							cijena: this.cijena,
							provizija: this.provizija,
							pdv: this.pdv,
							apartman: this.odabraniAp,
							brOsoba: this.brOsoba,
							napomena: this.napomena,
							color: this.pickedColor,
						}
					}
					else if(this.chosenGuestType == 'stari' && this.stari_gost)
					{
						url= "/rezervacije/novaWthGuest";
						podaci = {
							guest_id: this.stari_gost.id,
							dolazak: this.datum_dolaska,
							odlazak: this.datum_odlaska,
							rez_preko: this.rez_preko,
							cijena: this.cijena,
							provizija: this.provizija,
							pdv: this.pdv,
							apartman: this.odabraniAp,
							brOsoba: this.brOsoba,
							napomena: this.napomena,
							color: this.pickedColor,
						}
					}
					var komponenta = this;
					axios.post(url+"?token=" + auth.getToken(), podaci)
					.then(response => {
						this.$store.dispatch('retriveAllReservations')
						this.snackbar_text = "Rezervacija je uspješno unešena"
						this.context = 'success';
						this.snackbar = true;
						
						setTimeout(this.fetchHole, 2000)
					})
					.catch(function(error){
						komponenta.snackbar_text = "pogreška prilikom spremanja u bazu"
						komponenta.context = 'warning';
						komponenta.snackbar = true;
						console.log(error)
					});
				}
			},
			fetchHole(){
				this.nova = false;
				Object.assign(this.$data, this.$options.data());		//Resetiranje data na početne postavke
				this.$emit('uspjesno')
			},
		},
		computed:{

			postavljenaBoja:{
				get(){
					return this.pickedColor;
				},
				set(value){
					this.pickedColor = value
				}
			},
			getProvizija: {
				get(){
					 if(this.rez_preko == 'Booking' || this.rez_preko == 'booking') 
					 	{
					 		this.provizija = this.cijena * 0.15;
					 		return this.cijena * 0.15;
					 	}
					 return this.provizija;
				},
				set(value){
					this.provizija = value;
				}
			},
			getPdv:{
				get(){
					if(this.rez_preko == 'Booking' || this.rez_preko == 'booking') 
					{
						this.pdv = this.getProvizija * 0.25;
						return this.getProvizija * 0.25;
					}
					return this.pdv
				},
				set(value){
					this.pdv = value;
				}
			}
		}
	};
</script>

<style lang="css" scoped>
	.fixedButton{
		position: fixed;
		bottom: 2%;
		right: 2%;
		z-index: 5;
		height: 40px;
		width: 40px;
	}
</style>