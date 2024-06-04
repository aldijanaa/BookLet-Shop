var Constants = {
    get_api_base_url: function () {
      if (location.hostname == "localhost") {
        return "http://localhost/WEB_Projekat%20sa%20spappom/backend/";
      } else {
        return "https://sea-lion-app-edsc7.ondigitalocean.app/backend/";
      }
    },
  };