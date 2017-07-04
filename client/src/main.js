import Vue from 'vue';
import VueAui from 'vue-aui';
import axios from 'axios';
import Raven from 'raven-js';
import RavenVue from 'raven-js/plugins/vue';
import { getAppConfiguration } from './util';
import store from './store';
import pusher from './pusher';
import App from './components/App';

Vue.config.productionTip = false;
Vue.use(VueAui);

getAppConfiguration().then((appConfig) => {
  window.baseUrl = appConfig.base_url;
  axios.defaults.headers.common.Authorization = `Bearer ${appConfig.api_key}`;
  if (appConfig.pusher_api_key !== null) {
    pusher.initialize(
      appConfig.pusher_api_key,
      appConfig.pusher_config,
      appConfig.api_key,
      appConfig.tenant_id,
    );
  }

  if (appConfig.sentry_public_dsn !== null) {
    Raven
      .config(appConfig.sentry_public_dsn)
      .addPlugin(RavenVue, Vue)
      .install();

    console.info('Raven started');
  }

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
