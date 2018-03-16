var db = {};
$(document).ready(function() {
  //  Helper method to convert object to friendly php string
  function objectToPHPString(data) {
    var string = "";

    for (key in data) {
      var value = data[key];
      string += `${key}=${value}&`;
    }

    return string;
  }

  // Example data input: { machineID: 'm1' }
  db.receiveInfo = function(filePath, data, type, callback) {
    // Check to see if we should make a POST or GET request
    if (type == "GET") {
      $.get(filePath, function(newData) {
        return newData;
      });
    }

    $.ajax({
      type: "POST",
      data: objectToPHPString(data),
      url: filePath
    })
      .done(function(response, textStatus, jqXHR) {
        callback(response);
      })
      .fail(function(jqXHR, textStatus, errorThrown) {
        console.error(
          "The following error occurred: " + textStatus + errorThrown
        );
        callback("");
      });
  };
});
