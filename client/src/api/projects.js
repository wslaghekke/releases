import axios from 'axios';

axios.defaults.headers.common.Authorization = `Bearer ${window.apiKey}`;

/**
 * @returns {Promise}
 */
function getProjects() {
  return axios.get('/api/project').then(response => response.data);
}

/**
 * @param {string} key
 * @returns {Promise}
 */
function getVersions(key) {
  return axios.get(`/api/project/${key}/versions`).then(response => response.data);
}

function createVersion(versionData) {
  return axios.post('/api/version', versionData).then(response => response.data);
}

function deleteVersion(id) {
  return axios.delete(`/api/version/${id}`).then(response => response.data);
}

export default {
  getProjects,
  getVersions,
  createVersion,
  deleteVersion,
};
