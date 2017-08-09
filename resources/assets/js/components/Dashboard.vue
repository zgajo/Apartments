<template>
  <v-app id="example-2" standalone>
    <v-navigation-drawer persistent  :mini-variant.sync="mini" v-model="drawer" overflow>
      <v-list class="pa-0">
        <v-list-tile avatar tag="div">
          <v-list-tile-avatar>
            <img src="http://icon-icons.com/icons2/625/PNG/512/big-smile-smiley_icon-icons.com_57417.png" />
          </v-list-tile-avatar>
          <v-list-tile-content>
            <v-list-tile-title>{{this.$auth.getUsername()}}</v-list-tile-title>
          </v-list-tile-content>
          <v-menu id="space" bottom left offset-y origin="top right" transition="v-scale-transition">
            <v-btn icon="icon" slot="activator" class="white--text">
              <v-icon>more_vert</v-icon>
            </v-btn>
            <v-list>
              <v-list-item >
                <v-list-tile>
                  <v-list-tile-title class="blue-grey--text" @click="odjava()">Odjava</v-list-tile-title>
                </v-list-tile>
              </v-list-item>
            </v-list>
          </v-menu>
        </v-list-tile>
      </v-list>
      <v-list>
          <v-list-tile  :router="true" to="/" value="true" tag="li">
            <v-list-tile-action>
              <v-icon light >home</v-icon>
            </v-list-tile-action>
            <v-list-tile-content>
              <v-list-tile-title class="white--text" >Početna</v-list-tile-title>
            </v-list-tile-content>
          </v-list-tile>
          <v-list-tile  :router="true" to="/statistika/rezervacije" value="true" tag="li">
            <v-list-tile-action>
              <v-icon light style="    color: #1976d2" >equalizer</v-icon>
            </v-list-tile-action>
            <v-list-tile-content>
              <v-list-tile-title class="white--text" >Statistika</v-list-tile-title>
            </v-list-tile-content>
          </v-list-tile>
          <v-list-tile  :router="true" to="/stvaranje/korisnika" v-if="this.$auth.userPermisions() == 'admin'" tag="li" >
            <v-list-tile-action>
              <v-icon light style="    color: #1976d2"> person </v-icon>
            </v-list-tile-action>
            <v-list-tile-content>
              <v-list-tile-title class="white--text" >Stvaranje korisnika</v-list-tile-title>
            </v-list-tile-content>
          </v-list-tile>
      </v-list>
    </v-navigation-drawer>
    <v-toolbar fixed class="indigo darken-4" dark>
      <v-toolbar-side-icon light @click.native.stop="drawer = !drawer"></v-toolbar-side-icon>
      <v-toolbar-title></v-toolbar-title>
    </v-toolbar>
    <main>
      <v-container fluid>
        <router-view></router-view>
        <!--v-router-->
      </v-container>
    </main>
  </v-app>
</template>

<script>
import axios from 'axios';

  export default {
    name: 'Dashboard',
    
    data () {
      return {
         mini: false,
        right: null,
        drawer: true,
      }
    },
    methods:{
      odjava(){
        this.$auth.destroyToken();
        this.$router.push({path:'/login'})
      }
    }
  }
</script>
<style lang="css" scoped>
.list__tile__title {
    font-size: 13px;
    //color: #b8c7ce !important;
  }
</style>