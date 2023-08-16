import Vue from 'vue'
import VueRouter from 'vue-router'
import VueMeta from 'vue-meta'

import routes from './routes'

Vue.use(VueRouter)
Vue.use(VueMeta, {
    // The component option name that vue-meta looks for meta info on.
    keyName: 'page',
})

const router = new VueRouter({
    routes,
    mode: 'history',
    scrollBehavior(to, from, savedPosition) {
        if (savedPosition) {
            return savedPosition
        } else {
            return { x: 0, y: 0 }
        }
    },
})

// Before each route evaluates...
router.beforeEach((routeTo, routeFrom, next) => {

    const publicPages = ['/login', '/register', '/forgot-password', '/update_password'];
    const authpage = !publicPages.includes(routeTo.path);
    let token = localStorage.getItem('token');

    if (authpage && !token) {
        return next('/login');
    }


    next();
})

router.beforeResolve(async(routeTo, routeFrom, next) => {
    // Create a `beforeResolve` hook, which fires whenever
    // `beforeRouteEnter` and `beforeRouteUpdate` would. This
    // allows us to ensure data is fetched even when params change,
    // but the resolved route does not. We put it in `meta` to
    // indicate that it's a hook we created, rather than part of
    // Vue Router (yet?).
    try {
        // For each matched route...
        for (const route of routeTo.matched) {
            await new Promise((resolve, reject) => {
                // If a `beforeResolve` hook is defined, call it with
                // the same arguments as the `beforeEnter` hook.
                if (route.meta && route.meta.beforeResolve) {
                    route.meta.beforeResolve(routeTo, routeFrom, (...args) => {
                        // If the user chose to redirect...
                        if (args.length) {
                            // If redirecting to the same route we're coming from...
                            // Complete the redirect.
                            next(...args)
                            reject(new Error('Redirected'))
                        } else {
                            resolve()
                        }
                    })
                } else {
                    // Otherwise, continue resolving the route.
                    resolve()
                }
            })
        }
        // If a `beforeResolve` hook chose to redirect, just return.
    } catch (error) {
        return
    }

    // If we reach this point, continue resolving the route.
    next()
})

export default router