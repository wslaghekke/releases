import Axios from 'axios';

/**
 * @typedef {Object} AppConfiguration
 * @property {number} tenant_id Tenant id
 * @property {string} api_key JWT Api key for accessing the api
 * @property {string} base_url Base url for current JIRA Installation
 * @property {string|null} pusher_api_key Api key for pusher, or null if disabled
 * @property {Object} pusher_config Configuration for pusher client
 */

/**
 * Loads appConfiguration from global or retrieves from dev-endpoint if running in development mode
 * @returns {Promise.<AppConfiguration>}
 */
export function getAppConfiguration() {
  if (window.appConfiguration !== undefined) {
    return Promise.resolve(window.appConfiguration);
  }
  return Axios.get('/protected/dev-environment').then(response => response.data, error => error);
}

/**
 * Formats api error response into a string of HTML
 * @param responseJson
 * @returns {string}
 */
export function formatErrorResponse(responseJson) {
  let body = '';
  responseJson.errorMessages.forEach((element) => {
    body += `<p>${element}</p>`;
  });
  if (Object.keys(responseJson.errors).length > 0) {
    body += '<p><b>Fields with errors:</b><br/>';
    Object.keys(responseJson.errors).forEach((key) => {
      body += `${key}: ${responseJson.errors[key]}<br/>`;
    });
    body += '</p>';
  }
  return body;
}
