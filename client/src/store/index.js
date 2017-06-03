import Vue from 'vue';
import Vuex from 'vuex';
import environment from 'environment';
import projectApi from '../api/projects';
import * as types from './mutation-types';

Vue.use(Vuex);

// initial state
const state = {
  allProjects: [],
  selectedProject: null,
  allVersions: [],
};

// getters
const getters = {
  allProjects: paramState => paramState.allProjects,
  allVersions: paramState => paramState.allVersions,
  selectedProject: paramState => paramState.selectedProject,
};

// actions
const actions = {
  getAllProjects({ commit, dispatch }) {
    return projectApi.getProjects().then((projects) => {
      commit(types.RECEIVE_PROJECTS, projects);
      if (state.selectedProject === null && projects.length > 0) {
        dispatch('setSelectedProject', projects[0].key);
      }
      return Promise.resolve(projects);
    });
  },
  setSelectedProject({ commit, dispatch }, projectKey) {
    const project = state.allProjects.find(item => item.key === projectKey);
    commit(types.SET_SELECTED_PROJECT, project);
    dispatch('getAllVersions', projectKey);
  },
  getAllVersions({ commit }, projectKey) {
    projectApi.getVersions(projectKey).then((versions) => {
      commit(types.RECEIVE_VERSIONS, versions);
    });
  },
  createVersion({ commit }, versionData) {
    versionData.project = state.selectedProject.key;
    if (versionData.startDate === '') {
      delete versionData.startDate;
    }
    if (versionData.releaseDate === '') {
      delete versionData.releaseDate;
    }
    return projectApi.createVersion(versionData).then((version) => {
      commit(types.RECEIVE_VERSION, version);
    });
  },
  deleteVersion({ commit }, version) {
    commit(types.DELETE_VERSION, version);
    return projectApi.deleteVersion(version.id).then(success => success, (error) => {
      commit(types.RECEIVE_VERSION, version);
      return Promise.reject(error);
    });
  },
};

// mutations
const mutations = {
  [types.RECEIVE_PROJECTS](paramState, projects) {
    paramState.allProjects = projects;
  },
  [types.SET_SELECTED_PROJECT](paramState, project) {
    paramState.selectedProject = project;
  },
  [types.RECEIVE_VERSIONS](paramState, versions) {
    paramState.allVersions = versions;
  },
  [types.RECEIVE_VERSION](paramState, version) {
    paramState.allVersions.push(version);
  },
  [types.DELETE_VERSION](paramState, version) {
    const index = paramState.allVersions.indexOf(version);
    if (index > -1) {
      paramState.allVersions.splice(index, 1);
    }
  },
};

export default new Vuex.Store({
  state,
  getters,
  actions,
  mutations,
  plugins: [],
  strict: environment.vuex_strict,
});
