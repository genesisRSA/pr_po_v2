require('./bootstrap');

window.Vue = require('vue').default;

import { App, plugin } from '@inertiajs/inertia-vue'
import Vue from 'vue'
import Vuetify from 'vuetify'
import VuetifyMoney from "vuetify-money";

import moment from 'moment';
import VueMoment from 'vue-moment';

// Load Locales ('en' comes loaded by default)
require('moment/locale/es');

// Choose Locale
moment.locale('es');

Vue.use(VueMoment, { moment });

Vue.mixin({ methods: { route }});
Vue.use(plugin)
Vue.use(Vuetify)
Vue.use(VuetifyMoney);
export default VuetifyMoney;

const el = document.getElementById('app')

new Vue({
    vuetify: new Vuetify({
        theme: { dark: false }
    }),
  render: h => h(App, {
    props: {
      initialPage: JSON.parse(el.dataset.page),
      resolveComponent: name => require(`./Pages/${name}`).default,
    },
  }),
}).$mount(el)
