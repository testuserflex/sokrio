<script>
// import axios from "axios";
import simplebar from "simplebar-vue";
import { mapGetters, mapActions } from "vuex";

import i18n from "../i18n";

/**
 * Nav-bar Component
 */
export default {
  data() {
    return {
      languages: [
        {
          flag: require("@/assets/images/flags/us.jpg"),
          language: "en",
          title: "English",
        },
        {
          flag: require("@/assets/images/flags/french.jpg"),
          language: "fr",
          title: "French",
        },
        {
          flag: require("@/assets/images/flags/spain.jpg"),
          language: "es",
          title: "Spanish",
        },
        {
          flag: require("@/assets/images/flags/chaina.png"),
          language: "zh",
          title: "Chinese",
        },
        {
          flag: require("@/assets/images/flags/arabic.png"),
          language: "ar",
          title: "Arabic",
        },
      ],
      lan: i18n.locale,
      text: null,
      flag: null,
      value: null,
    };
  },
  components: { simplebar },
  mounted() {
    this.value = this.languages.find((x) => x.language === i18n.locale);
    this.text = this.value.title;
    this.flag = this.value.flag;
  },
  computed:{
    ...mapGetters("auth", ["name","username","email","business","branch","MyUserPerm"]),
    ...mapGetters("setting", ["GeneralSettings"]),
  },
  methods: {
    ...mapActions({
      LogOut: "auth/logout",
      FetchGeneralSettings: "setting/fetchGeneralSettings",
    }),
    
    toggleMenu() {
      this.$parent.toggleMenu();
    },
    toggleRightSidebar() {
      this.$parent.toggleRightSidebar();
    },
    initFullScreen() {
      document.body.classList.toggle("fullscreen-enable");
      if (
        !document.fullscreenElement &&
        /* alternative standard method */ !document.mozFullScreenElement &&
        !document.webkitFullscreenElement
      ) {
        // current working methods
        if (document.documentElement.requestFullscreen) {
          document.documentElement.requestFullscreen();
        } else if (document.documentElement.mozRequestFullScreen) {
          document.documentElement.mozRequestFullScreen();
        } else if (document.documentElement.webkitRequestFullscreen) {
          document.documentElement.webkitRequestFullscreen(
            Element.ALLOW_KEYBOARD_INPUT
          );
        }
      } else {
        if (document.cancelFullScreen) {
          document.cancelFullScreen();
        } else if (document.mozCancelFullScreen) {
          document.mozCancelFullScreen();
        } else if (document.webkitCancelFullScreen) {
          document.webkitCancelFullScreen();
        }
      }
    },
    setLanguage(locale, country, flag) {
      this.lan = locale;
      this.text = country;
      this.flag = flag;
      i18n.locale = locale;
    },
    logoutUser() {
      this.LogOut();
      // eslint-disable-next-line no-unused-vars
      // axios.get("http://127.0.0.1:8000/api/logout").then((res) => {
      //   this.$router.push({
      //     name: "default",
      //   });
      // });
    },
  },

  created() {
    this.FetchGeneralSettings();
  },
};
</script>

<template>
  <header id="page-topbar">
    <div class="navbar-header" style="background: #3944e6;
background: -webkit-linear-gradient(to right, #0d3868, #3944e6); 
background: linear-gradient(to right, #0d3868, #3944e6);">
      <div class="d-flex">
        <!-- LOGO -->
        <div class="navbar-brand-box text-white">
          <router-link tag="a" to="/" class="logo logo-dark">
            <span class="logo-sm">
              <img src="@/assets/images/logo.png" alt height="40" />
            </span>
            <span class="logo-lg">
              <img src="@/assets/images/logo.png" alt height="35" />
            </span>
          </router-link>

          <router-link tag="a" to="/" class="logo logo-light">
            <span class="logo-sm">
              <img src="@/assets/images/logo.png" alt height="40" />
            </span>
            <span class="logo-lg">
              <img src="@/assets/images/logo.png" alt height="35" />
            </span>
          </router-link>
        </div>

        <button
          id="vertical-menu-btn"
          type="button"
          class="btn btn-sm px-3 font-size-16 header-item text-white"
          @click="toggleMenu"
        >
          <i class="fa fa-fw fa-bars"></i>
        </button>
        
      </div>      
      <div class="d-flex">        

        <b-dropdown
          right
          menu-class="dropdown-menu-lg p-0 dropdown-menu-end"
          toggle-class="header-item noti-icon"
          variant="black"
        >
          <simplebar style="max-height: 230px">
            <a href="javascript: void(0);" class="text-reset notification-item">
              <div class="media">
                <div class="avatar-xs me-3">
                  <span
                    class="avatar-title bg-primary rounded-circle font-size-16"
                  >
                    <i class="bx bx-cart"></i>
                  </span>
                </div>
                <div class="media-body">
                  <h6 class="mt-0 mb-1">
                    {{ $t("navbar.dropdown.notification.order.title") }}
                  </h6>
                  <div class="font-size-12 text-muted">
                    <p class="mb-1">
                      {{ $t("navbar.dropdown.notification.order.text") }}
                    </p>
                    <p class="mb-0">
                      <i class="mdi mdi-clock-outline"></i>
                      {{ $t("navbar.dropdown.notification.order.time") }}
                    </p>
                  </div>
                </div>
              </div>
            </a>
            <a href="javascript: void(0);" class="text-reset notification-item">
              <div class="media">
                <img
                  src="@/assets/images/users/avatar-3.jpg"
                  class="me-3 rounded-circle avatar-xs"
                  alt="user-pic"
                />
                <div class="media-body">
                  <h6 class="mt-0 mb-1">
                    {{ $t("navbar.dropdown.notification.james.title") }}
                  </h6>
                  <div class="font-size-12 text-muted">
                    <p class="mb-1">
                      {{ $t("navbar.dropdown.notification.james.text") }}
                    </p>
                    <p class="mb-0">
                      <i class="mdi mdi-clock-outline"></i>
                      {{ $t("navbar.dropdown.notification.james.time") }}
                    </p>
                  </div>
                </div>
              </div>
            </a>
            <a href="javascript: void(0);" class="text-reset notification-item">
              <div class="media">
                <div class="avatar-xs me-3">
                  <span
                    class="avatar-title bg-success rounded-circle font-size-16"
                  >
                    <i class="bx bx-badge-check"></i>
                  </span>
                </div>
                <div class="media-body">
                  <h6 class="mt-0 mb-1">
                    {{ $t("navbar.dropdown.notification.item.title") }}
                  </h6>
                  <div class="font-size-12 text-muted">
                    <p class="mb-1">
                      {{ $t("navbar.dropdown.notification.item.text") }}
                    </p>
                    <p class="mb-0">
                      <i class="mdi mdi-clock-outline"></i>
                      {{ $t("navbar.dropdown.notification.item.time") }}
                    </p>
                  </div>
                </div>
              </div>
            </a>
            <a href="javascript: void(0);" class="text-reset notification-item">
              <div class="media">
                <img
                  src="@/assets/images/users/avatar-4.jpg"
                  class="me-3 rounded-circle avatar-xs"
                  alt="user-pic"
                />
                <div class="media-body">
                  <h6 class="mt-0 mb-1">
                    {{ $t("navbar.dropdown.notification.salena.title") }}
                  </h6>
                  <div class="font-size-12 text-muted">
                    <p class="mb-1">
                      {{ $t("navbar.dropdown.notification.salena.text") }}
                    </p>
                    <p class="mb-0">
                      <i class="mdi mdi-clock-outline"></i>
                      {{ $t("navbar.dropdown.notification.salena.time") }}
                    </p>
                  </div>
                </div>
              </div>
            </a>
          </simplebar>
          <div class="p-2 border-top d-grid">
            <a
              class="btn btn-sm btn-link font-size-14 text-center"
              href="javascript:void(0)"
            >
              <i class="mdi mdi-arrow-right-circle me-1"></i>
              {{ $t("navbar.dropdown.notification.button") }}
            </a>
          </div>
        </b-dropdown>

        <b-dropdown
          right
          variant="black"
          toggle-class="header-item"
          menu-class="dropdown-menu-end"
        >
          <template v-slot:button-content>
            <img
              class="rounded-circle header-profile-user"
              src="@/assets/images/users/user1.png"
              alt="Header Avatar"
            />
            <span class="d-none d-xl-inline-block ms-1 text-white">
              {{ name?name:"InEx User" }}
            </span>
            <i class="mdi mdi-chevron-down d-none d-xl-inline-block text-white"></i>
          </template>
          <!-- item-->
          <!-- <b-dropdown-item>
            <router-link tag="span" to="/contacts/profile">
              <i class="bx bx-user font-size-16 align-middle me-1"></i>
              {{ $t("navbar.dropdown.henry.list.profile") }}
            </router-link>
          </b-dropdown-item>
          <b-dropdown-item class="d-block" href="javascript: void(0);">
            <i class="bx bx-wrench font-size-16 align-middle me-1"></i>
            {{ $t("navbar.dropdown.henry.list.settings") }}
          </b-dropdown-item> -->
          <b-dropdown-divider></b-dropdown-divider>
          <a href="#" class="dropdown-item text-danger">
            <button class="btn btn-outline"
            @click="logoutUser()"
            >
              <i
              class="bx bx-power-off font-size-16 align-middle me-1 text-danger"
            ></i> Logout
            </button>
            
          </a>
        </b-dropdown>
      </div> 
  </div>
  </header>
</template>
