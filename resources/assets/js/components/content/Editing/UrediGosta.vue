<template>
    <v-layout row  class="ma-3 " >{{dohvatiGosta}}
		<v-flex xs12 >
			<v-card>
				<v-toolbar style="z-index: 0 !important;" class="white--text indigo" light>
				<v-toolbar-title>Ažuriranje gosta</v-toolbar-title>
				</v-toolbar>
				<v-card class="grey lighten-4 elevation-0">
					<v-card-text>
						<v-container fluid>

							<v-layout row wrap>
								<v-flex xs12 sm6 md3>
									<v-text-field
									name="input-3"
									label="Gost"
									v-model="guest.guest"
									></v-text-field>
								</v-flex>
								<v-flex xs12 sm6 md3>
									<v-text-field
									name="input-3"
									label="Zemlja"
									v-model="guest.zemlja"
									></v-text-field>
								</v-flex>
								<v-flex xs12 sm6 md3>
									<v-text-field
									name="input-3"
									label="Email"
									v-model="guest.email"
									></v-text-field>
								</v-flex>
								<v-flex xs12 sm6 md3>
									<v-text-field
									name="input-3"
									label="Kontakt"
									v-model="guest.tel_broj"
									></v-text-field>
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
								<v-btn class="blue--text darken-1" @click.native="dohvatiGosta" v-if="!guest.guest" flat>Dohvati gosta</v-btn>
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

	export default {

		name: 'UrediGosta',

		data () {
			return {
				guest_id: this.$route.params.guest_id,
				guest: {
					guest: null,
					zemlja: null,
					email: null,
					tel_broj: null
				},
				uspjesno: false, neuspjesno: false
			};
		},
		computed:{
			dohvatiGosta(){
				if(this.$store.getters.rezervacije.length) this.guest = this.$store.getters.findGuest(this.guest_id)
			},
		},
		methods: {
			spremi(){
				this.uspjesno = false;
		        this.neuspjesno = false;
				axios.post('/guests/update/'+this.guest_id+"?token="+this.$auth.getToken(), {
					guest: this.guest.guest,
					zemlja: this.guest.zemlja,
					email: this.guest.email,
					tel_broj: this.guest.tel_broj,
				})
				.then(response => {
					console.log(response)
					this.$store.dispatch('retriveAllReservations')
					this.uspjesno = true;

				})
				.catch(response => {
					console.log(response)
					this.neuspjesno = true;
				})
			}
		}
	};
</script>

<style lang="css" scoped>
</style>