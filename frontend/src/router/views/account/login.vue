<script>
import Layout from "../../layouts/auth";

import appConfig from "@/app.config";
import { mapActions, mapGetters, mapState } from "vuex";

/**
 * Login component
 */
export default {
  page: {
    title: "Login",
    meta: [
      {
        name: "description",
        content: appConfig.description,
      },
    ],
  },
  components: {
    Layout,
  },
  data() {
    return {
      submitted: false,
      data: {
        username: "",
        password: "",
      },
      usernameError: "",
      passwordError: "",
    };
  },
  
  computed: {
    ...mapState("auth", ["status"]),
    ...mapGetters("auth", ["loginStatus", "login_errors"]),
  },
  methods: {
     ...mapActions({
      LogInFunction: "auth/login",
    }),
    // Try to log the user in with the username
    // and password they provided.
    tryToLogIn() {
       if (this.data.username == "") {
        this.usernameError = "Please enter a Username";
        return;
      } else {
        this.usernameError = "";
      }
      if (this.data.password == "") {
        this.passwordError = "Please enter a Password";
        return;
      } else {
        this.passwordError = "";
      }
      this.LogInFunction(this.data);      
    },
    resetErrors(){
      this.passwordError ="";
      this.usernameError ="";
    },
  },
  mounted() {},
};
</script>

<template>
  <Layout>
    <div class="row justify-content-center">
      <div class="col-md-8 col-lg-6 col-xl-5">
        <div class="card overflow-hidden">
          <div style="background-color: blue">
            <div class="row">
              <div class="col-7">
                <div class="text-white p-4">
                  <h5 class="text-white">Welcome Back !</h5>
                  <p>Sign in to continue.</p>
                </div>
              </div>
              <div class="col-5 align-self-end">
                <img
                  src="@/assets/images/profile-img.png"
                  alt
                  class="img-fluid"
                />
              </div>
            </div>
          </div>
          <div class="card-body pt-0">
            <div>
              <router-link tag="a" to="/">
                <div class="avatar-md profile-user-wid mb-4">
                  <span class="avatar-title rounded-circle bg-light">
                    <img src="@/assets/images/logo.png" alt height="34" />
                  </span>
                </div>
              </router-link>
            </div>
            <!-- <b-alert
              v-model="isAuthError"
              variant="danger"
              class="mt-3"
              dismissible
              >{{ authError }}</b-alert
            > -->
            <!-- <div
              v-if="notification.message"
              :class="'alert ' + notification.type"
            >
              {{ notification.message }}
            </div> -->

            <b-form class="p-2" @submit.prevent="tryToLogIn">
              <b-form-group
                class="mb-3"
                id="input-group-1"
                label="Username"
                label-for="input-1"
              >
                <b-form-input
                  id="input-1"
                  v-model="data.username"
                  type="text"
                  @input="resetErrors()"
                  placeholder="Enter Username"
                  :class="{ 'is-invalid': submitted && $v.data.username.$error }"
                ></b-form-input>
                <div class="text-danger" v-if="usernameError">
                  <em>{{ usernameError }}</em>
                </div>
              </b-form-group>
              <b-form-group
                class="mb-3"
                id="input-group-2"
                label="Password"
                label-for="input-2"
              >
                <b-form-input
                  id="input-2"
                  v-model="data.password"
                   @input="resetErrors()"
                  type="password"
                  placeholder="Enter password"
                  :class="{ 'is-invalid': submitted && $v.data.password.$error }"
                ></b-form-input>
                <div class="text-danger" v-if="passwordError">
                  <em>{{ passwordError }}</em>
                </div>
              </b-form-group>
              <div class="mt-3 d-grid text-white">
                <b-button type="submit" style="background-color: blue; border-color: #blue;" class="btn-block"
                  >Log In
                  <b-spinner
                    v-if="loginStatus"
                    variant="light"
                    small
                    role="status"
                  ></b-spinner
                ></b-button
                >
              </div>
              <div class="mt-4 text-center">
                <b-alert v-if="login_errors" show variant="danger">
                  <b>{{ login_errors }}</b>
                </b-alert>
              </div>
            </b-form>
          </div>
          <!-- end card-body -->
        </div>
        <!-- end card -->
        <!-- end row -->
      </div>
      <!-- end col -->
    </div>
    <!-- end row -->
  </Layout>
</template>

<style lang="scss" module></style>
