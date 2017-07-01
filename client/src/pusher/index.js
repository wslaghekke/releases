import axios from 'axios';
import Pusher from 'pusher-js';
import environment from 'environment';
import store from '../store';
import * as tenantEvents from './tenant-events';
import * as mutationTypes from '../store/mutation-types';

export default {
  initialize(appKey, config, apiKey, tenantId) {
    config.auth.headers.Authorization = `Bearer ${apiKey}`;
    Pusher.logToConsole = environment.debug;

    const pusher = new Pusher(appKey, config);

    pusher.connection.bind('connected', () => {
      axios.defaults.headers.common['Socket-Id'] = pusher.connection.socket_id;
    });

    const channel = pusher.subscribe(`private-tenant-${tenantId}`);
    channel.bind(tenantEvents.CREATE_VERSION, (data) => {
      if (store.state.selectedProject.id === data.projectId) {
        store.commit(mutationTypes.RECEIVE_VERSION, data);
      }
    });
    channel.bind(tenantEvents.UPDATE_VERSION, (data) => {
      if (store.state.selectedProject.id === data.projectId) {
        store.commit(mutationTypes.REPLACE_VERSION, data);
      }
    });
    channel.bind(tenantEvents.DELETE_VERSION, (data) => {
      const version = store.state.allVersions.find(item => item.id === data.id);
      if (store.state.selectedProject.id === version.projectId) {
        store.commit(mutationTypes.DELETE_VERSION, version);
      }
    });
    channel.bind(tenantEvents.MOVE_VERSION, (data) => {
      const version = store.state.allVersions.find(item => item.id === data.version);
      const nextIndex = store.state.allVersions.findIndex(item => item.self === data.next);
      if (store.state.selectedProject.id.toString() === version.projectId.toString()) {
        store.commit(mutationTypes.MOVE_VERSION, [version, nextIndex]);
      }
    });
  },
};

