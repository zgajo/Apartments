
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
import Pocetna from './Pocetna';
import router from './router/router'; //Dohvat svih routa i postavljenih komponenta za svaku od njih
import {store} from './store/store';
import Auth from './auth/Auth';

Vue.use(Auth);

Vue.use(require('vue-pusher'), {
    api_key: '8d75855e8041f4e13e10',
    options: {
        cluster: 'eu'
    }
});

const app = new Vue({
    el: '#app',
    render: h=>h(Pocetna),
    store,
    router
});