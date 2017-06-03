import Vue from 'vue';
import VueAui from 'vue-aui';
// import VueAui from './components/aui/VueAui';
import App from './components/App';
import store from './store';

Vue.config.productionTip = false;
Vue.use(VueAui);

/* eslint-disable no-new */
new Vue({
  el: '#app',
  store,
  render: h => h(App),
});
