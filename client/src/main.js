import Vue from 'vue';
import VueAui from 'vue-aui';
import axios from 'axios';
import App from './components/App';
import store from './store';

Vue.config.productionTip = false;
Vue.use(VueAui);

new Promise((resolve, reject) => {
  if (window.apiKey !== undefined) {
    resolve(window.apiKey);
  }
  return axios.get('/protected/dev-api-key').then((response) => {
    resolve(response.data);
  }, (error) => {
    reject(error);
  });
}).then((key) => {
  axios.defaults.headers.common.Authorization = `Bearer ${key}`;
  /* eslint-disable no-new */
  new Vue({
    el: '#app',
    store,
    render: h => h(App),
  });
}, () => {
  const errorElem = document.createElement('h1');
  errorElem.innerText = 'Failed to retrieve apiKey';
  document.body.appendChild(errorElem);
});
