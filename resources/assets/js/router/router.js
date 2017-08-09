import Vue from 'vue'
import VueRouter from 'vue-router';
import Dashboard from '../components/Dashboard';
import Login from '../components/Login';
import Main from '../components/content/Main/Main';
import UrediRezervaciju from '../components/content/Editing/UrediRezervaciju';
import UrediGosta from '../components/content/Editing/UrediGosta';
import NewUser from '../components/content/NewUser';
import StatistikaRezervacije from '../components/content/Statistika/Rezervacije';
import  {store} from '../store//store'
import Auth from '../auth/Auth'

Vue.use(Auth)

/** Dohvat ordera se obavlja nakon svakog postavljanja tokena **/

/*



function letRefreshToken(){
	return new Promise((resolve, reject)=>{

		var token = localStorage.getItem('token')

		axios.post('/refreshToken?token='+token)
		.then(response => {
			Vue.auth.setToken(response.data.newToken)
			resolve()
		})
		.catch(e => {
			console.log(e)

			if((e.message.indexOf('400') && (e.message.indexOf('400') != -1)) || (e.message.indexOf('401') && (e.message.indexOf('401') != -1))  ) {
				 Vue.auth.destroyToken();
				 console.log('Token invalid or expired')
				 reject('Token invalid or expired');
				 return;
			}


			setTimeout(()=>{
				if(!Vue.auth.isAuthenticated())
				{
					reject('Token invalid or expired');
				}
				else { debugger; Vue.auth.refreshToken(); resolve();  }
			},120000)
		})
	})
}

*/




export default new VueRouter({

routes:[
{
	path: '/',
	component: Dashboard,
	children: [
		{path: '/', component: Main},
		{path: '/urediRezervaciju/:reservation_id', component: UrediRezervaciju,
        beforeEnter: (to, from, next)=>{
        		if(Vue.auth.userPermisions() != 'admin' && Vue.auth.userPermisions() != 'editor') next('/');
        		if( !from.matched.length )   {	//Ako je refreshana stranica ili se ponovno pali,
	             	store.dispatch('retriveAllReservations');
	             	next();
	             }
	             else next();
	     	}

		},
		{path: '/urediGosta/:guest_id', component: UrediGosta,
        beforeEnter: (to, from, next)=>{
        		if(Vue.auth.userPermisions() != 'admin' && Vue.auth.userPermisions() != 'editor') next('/');
        		if( !from.matched.length )   {	//Ako je refreshana stranica ili se ponovno pali,
	             	store.dispatch('retriveAllReservations');
	             	next();
	             }else next();
	     	}

		},
		{path: '/stvaranje/korisnika', component: NewUser,
        beforeEnter: (to, from, next)=>{
        		if(Vue.auth.userPermisions() != 'admin') next('/');
        		if( !from.matched.length )   {	//Ako je refreshana stranica ili se ponovno pali,
	             	store.dispatch('retriveAllReservations');
	             	next();
	             }else next();
	     	}

		},
		{path: '/statistika/rezervacije', component: StatistikaRezervacije},
	],
	meta: {     // Ovo mogu koristit ako iz nekog drugog filea koristim route check, kako sam stavio u ovaj file, onda ne treba
        forAuthenticated: true       // Za goste koji su autentificirani
    },
    beforeEnter: (to, from, next)=>{
    	if(Vue.auth.isAuthenticated())
    	{
    		if( !from.matched.length )   {	//Ako je refreshana stranica ili se ponovno pali, refreshaj vrijeme trajanja tokena, nakon toga izvrši next
             	//letRefreshToken()
             	Vue.auth.refreshToken()
             	.then( (fromRefresh) => { next() } )
             	.catch((fromReject) => { if(fromReject == 'Token invalid or expired') next('/login'); } );
             }
             else next();
         } else  next('/login')

     }
 },
 {
 	path: '/login',
 	component: Login,
 	meta: {
        forNotAuthenticated: true       // Za goste koji nisu autentificirani
    },
    beforeEnter: (to, from, next)=>{

    	if(!Vue.auth.isAuthenticated())   next();
    	else next('/');

    },
}
],
  linkActiveClass: 'active',

    })