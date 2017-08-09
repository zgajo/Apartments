<template>
    <v-layout row  class="ma-3 " >
		<v-flex xs12 >
			<v-card>
				<v-toolbar style="    z-index: 0 !important;" class="white--text indigo" light>
				<v-toolbar-title>Stvaranje novog korisnika</v-toolbar-title>
				</v-toolbar>
				<v-card class="grey lighten-4 elevation-0">
					<v-card-text>
						<v-container fluid>

							<v-layout row wrap>
								<v-flex xs12 sm6 md3>
									<v-text-field
									label="Ime"
									v-model="name"
									required
									></v-text-field>
								</v-flex>
								<v-flex xs12 sm6 md3>
									<v-text-field
									label="Email"
									v-model="email"
									type="email"
									required
									></v-text-field>
								</v-flex>
								<v-flex xs12 sm6 md3>
									<v-text-field
									label="Password"
									v-model="password"
									required
									></v-text-field>
								</v-flex>
								<v-flex xs12 sm6 md3>
								<v-select required
				                  label="Rezina odobrenja"
				                  v-model="permission"
				                  v-bind:items="permissions"
				                  ></v-select>
				                </v-flex>
							</v-layout>
							<v-layout row>
				                <v-alert success v-bind:value="uspjesno">
				                  Uspješno stvoren novi korisnik.
				                </v-alert>
				                <v-alert error v-bind:value="neuspjesno">
				                  Dogodila se pogreška.
				                </v-alert>
				              </v-layout>
							<v-card-row actions>
								<v-btn class="blue--text darken-1"  :router="true" :to="'/'" flat>Natrag</v-btn>
								<v-btn class="blue--text darken-1" @click.native="spremi" flat>Spremi novog korisnika</v-btn>
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

		name: 'NewUser',

		data () {
			return {
				name: '',
				email: '',
				password: '',
				permissions: ["admin", "editor", "guest"],
				permission: null,
				uspjesno: false,
				neuspjesno: false
			};
		},
		methods: {
			spremi(){
				this.uspjesno = false;
		        this.neuspjesno = false;

		        if( !this.name || !this.email || !this.password || !this.permission) {
		        	alert("Ispunite sva potrebna polja");
		        	return;
		        }

		        if(this.email.indexOf('@') < 0) {
		        	alert('Potrebno je upisati email adresu')
		        	return
		        }

				axios.post('/newUser?token='+this.$auth.getToken(), {
					name: this.name,
					email: this.email,
					password: this.password,
					permissions: this.permission
				})
				.then(response => {
					console.log(response)
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