export default {
  formatErrorResponse(responseJson) {
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
  },
};
