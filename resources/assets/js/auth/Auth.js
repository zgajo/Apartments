import moment from 'moment';
import axios from 'axios'
import {store} from '../store/store';

export default function (Vue){
	Vue.auth = {

		isAuthenticated(){
			return (this.getToken()) ? true : false;
		},

		setToken(token){
			localStorage.setItem('token', token);
			localStorage.setItem('expiration',  moment().add(1, 'h'));

			this.refreshTimer();
		},

		refreshTimer(){
			var obj = this;

			setTimeout(()=>{	//Refreshaj token za 50 min
				obj.refreshToken()
			}, 3000000)
		},

		refreshToken(){

			var token = localStorage.getItem('token')
			var obj = this;

			return new Promise((resolve, reject) => {
				axios.post('/refreshToken?token='+token)
				.then(response => {
					obj.setToken(response.data.newToken)
					resolve()
				})
				.catch(e => {
					console.log(e)

					if((e.message.indexOf('400') && (e.message.indexOf('400') != -1)) || (e.message.indexOf('401') && (e.message.indexOf('401') != -1))  ) {
						 Vue.auth.destroyToken();
						 console.log('Token invalid or expired')
						 reject('Token invalid or expired');
						 window.location = '/dash#/login';
						 return;
					}

					setTimeout(()=>{
						if(!obj.isAuthenticated()) // provjeriti token, ako je povrat null onda prekinuti sve i redirektirati na login
						{
							window.location = '/dash#/login';
							return;
						}
						obj.refreshToken();	//ako nije null, onda rekurzivno refreshToken()
					},120000)
				})
			})
		},

		getToken(){

			var token = localStorage.getItem('token')
			var expiration = localStorage.getItem('expiration')

			if(!token || !expiration) return null;

			if(moment(expiration) < moment())		// provjera da li je isteklo vrijeme trajanja
			{
				this.destroyToken();
				return null
			}
			else return token;
		},

		destroyToken(){
			localStorage.removeItem('token');
			localStorage.removeItem('expiration');
			localStorage.removeItem('permissions');
			localStorage.removeItem('user');
		},

		userPermisions(){
			return localStorage.getItem('permissions');
		},

		getUsername(){
			return localStorage.getItem('user');
		},


	}

	Object.defineProperties(Vue.prototype,{
		$auth:{
			get(){
				return Vue.auth;
			}
		}
	})


}