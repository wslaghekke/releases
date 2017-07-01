import axios from 'axios';

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

function editVersion(versionData) {
  return axios.put(`/api/version/${versionData.id}`, versionData).then(response => response.data);
}

function moveVersion(version, nextVersion) {
  let versionData;
  if (typeof nextVersion !== 'undefined' && nextVersion !== null) {
    versionData = {
      after: nextVersion.self,
    };
  } else {
    versionData = {
      position: 'First',
    };
  }
  return axios.post(`/api/version/${version.id}/move`, versionData).then(response => response.data);
}

export default {
  getProjects,
  getVersions,
  createVersion,
  deleteVersion,
  editVersion,
  moveVersion,
};
