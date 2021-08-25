import Vue from 'vue'
import VueRouter from 'vue-router'

Vue.use(VueRouter)

const router = new VueRouter({
    mode: 'history',
    base: '/',
    routes: [
        {
            path: '/',
            name: 'dashboard',
            component: require('./pages/Dashboard/Index').default,
        },
        {
            path: '/orders/:code/summary',
            name: 'order-summary',
            component: require('./pages/Orders/Summary').default
        }
    ]
})

export default router
