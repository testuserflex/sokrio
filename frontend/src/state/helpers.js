import { mapState, mapGetters, mapActions } from 'vuex'
import Vue from 'vue'

export const authComputed = {
  ...mapState('auth', {
    currentUser: (state) => state.user,
  }),
  ...mapGetters('auth', ['loggedIn']),
}

Vue.mixin({
  computed: {
    BranchData: function() {
      const data = {
        BranchId: this.$store.getters['auth/branchid'],
        Branches: this.$store.getters['auth/Branches'],
      }
      return data;
    },
    
  }
});

export const layoutComputed = {
  ...mapState('layout', {
    layoutType: (state) => state.layoutType,
    leftSidebarType: (state) => state.leftSidebarType,
    layoutWidth: (state) => state.layoutWidth,
    topbar: (state) => state.topbar,
    loader: (state) => state.loader
  })
}

export const authMethods = mapActions('auth', ['logIn', 'logOut', 'register', 'resetPassword'])

export const layoutMethods = mapActions('layout', ['changeLayoutType', 'changeLayoutWidth', 'changeLeftSidebarType', 'changeTopbar', 'changeLoaderValue'])

export const authFackMethods = mapActions('authfack', ['login', 'registeruser', 'logout'])

export const notificationMethods = mapActions('notification', ['success', 'error', 'clear'])

export const todoComputed = {
  ...mapState('todo', {
    todos: (state) => state.todos
  })
}
export const todoMethods = mapActions('todo', ['fetchTodos'])