import Vue from 'vue';
import Vuex from 'vuex';
import environment from 'environment';
import projectApi from '../api/projects';
import * as types from './mutation-types';

Vue.use(Vuex);

const recentProjects = JSON.parse(window.localStorage.getItem('recentProjects'));

// initial state
const state = {
  allProjects: [],
  selectedProject: null,
  allVersions: [],
  recentProjects: recentProjects !== null ? recentProjects : [],
};

// getters
const getters = {
  allProjects: paramState => paramState.allProjects,
  allVersions: paramState => paramState.allVersions,
  selectedProject: paramState => paramState.selectedProject,
  recentProjects: paramState => paramState.recentProjects,
};

// actions
const actions = {
  getAllProjects({ commit, dispatch }) {
    return projectApi.getProjects().then((projects) => {
      commit(types.RECEIVE_PROJECTS, projects);
      if (state.selectedProject === null && projects.length > 0) {
        let selectedProjectKey = window.localStorage.getItem('selectedProjectKey');
        if (selectedProjectKey === null) {
          selectedProjectKey = projects[0].key;
        }
        dispatch('setSelectedProject', selectedProjectKey);
      }
      return Promise.resolve(projects);
    });
  },
  setSelectedProject({ commit, dispatch }, projectKey) {
    const project = state.allProjects.find(item => item.key === projectKey);
    commit(types.SET_SELECTED_PROJECT, project);
    window.localStorage.setItem('selectedProjectKey', projectKey);
    return dispatch('getAllVersions', projectKey);
  },
  getAllVersions({ commit }, projectKey) {
    return projectApi.getVersions(projectKey).then((versions) => {
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
  editVersion({ commit }, version) {
    if (version.startDate === '') {
      delete version.startDate;
    }
    if (version.releaseDate === '') {
      delete version.releaseDate;
    }
    const oldVersion = state.allVersions.find(someVersion => someVersion.id === version.id);
    commit(types.REPLACE_VERSION, version);
    return projectApi.editVersion(version).then(success => success, (error) => {
      commit(types.REPLACE_VERSION, oldVersion);
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
    if (project !== null) {
      // If project is already in recentProjects
      const index = paramState.recentProjects.findIndex(item => item.id === project.id);
      if (index > -1) {
        paramState.recentProjects.splice(index, 1);
      }
      // Add project to start of recentProjects
      paramState.recentProjects.unshift(project);
      if (paramState.recentProjects.length >= 11) {
        paramState.recentProjects.splice(10, 1);
      }
      window.localStorage.setItem('recentProjects', JSON.stringify(paramState.recentProjects));
    }
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
  [types.REPLACE_VERSION](paramState, version) {
    const index = paramState.allVersions.findIndex(someVersion => someVersion.id === version.id);
    if (index > -1) {
      paramState.allVersions.splice(index, 1, version);
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
