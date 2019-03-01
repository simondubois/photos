
const Vue = require('vue');

const VueRouter = require('vue-router').default;
Vue.use(VueRouter);

const Vuex = require('vuex').default;
Vue.use(Vuex);

const Vue2TouchEvents = require('vue2-touch-events');
Vue.use(Vue2TouchEvents);

Vue.component('top-navbar', require('./components/TopNavbar.vue').default);
Vue.component('responsive-photo', require('./components/ResponsivePhoto.vue').default);
Vue.component('square-photo', require('./components/SquarePhoto.vue').default);

new Vue({
    el: '#app',
    router: new VueRouter({
        routes: [
            {
                path: '/',
                redirect: '/daily',
            },
            {
                path: '/random',
                component: require('./components/RandomView.vue').default,
            },
            {
                path: '/daily',
                component: require('./components/DailyView.vue').default,
            },
            {
                path: '/monthly',
                component: require('./components/MonthlyView.vue').default,
            },
            {
                path: '/yearly',
                component: require('./components/YearlyView.vue').default,
            },
            {
                path: '*',
                redirect: '/daily',
            },
        ],
    }),
    store: new Vuex.Store({
        ...require('./store.js'),
    }),
});
