<script>
import appConfig from "@/app.config";
import axios from "axios";

import { notificationMethods } from "@/state/helpers";

export default {
  name: "app",
  page: {
    // All subcomponent titles will be injected into this template.
    titleTemplate(title) {
      title = typeof title === "function" ? title(this.$store) : title;
      return title ? `${title} | ${appConfig.title}` : appConfig.title;
    },
  },
  mounted() {
    // document.querySelector("html").setAttribute('dir', 'rtl')
  },
  beforeCreate() {
    this.$store.commit("auth/initiateAuth");
    let token = localStorage.getItem('token')
    if(token){
      axios.defaults.headers.common['Authorization'] = `Bearer ${token}`
      axios.defaults.headers.common['Access-Control-Allow-Origin'] = '*'
      this.$store.dispatch('auth/authenticated')
    }else{
      axios.defaults.headers.common['Authorization'] = ""
      axios.defaults.headers.common['Access-Control-Allow-Origin'] = ''
    } 
  },
  watch: {
    /**
     * Clear the alert message on route change
     */
    // eslint-disable-next-line no-unused-vars
    $route(to, from) {
      // clear alert on location change
      this.clearNotification();
    },
  },
  methods: {
    clearNotification: notificationMethods.clear,
  },
};
</script>

<template>
  <div id="app">
    <RouterView />
  </div>
</template>
