import http from '../services/http'

const Config = {
    initialized: false,
    options_status : {
      'quote' : "",
      'invoice' : ""
    },
    units: '',
    default_dates : {
      'quote' : "",
      'invoice' : ""
    },
    tva : '',
};

  export default {
    install(Vue, options) {
       
      Vue.prototype.$getConfig =  async (key) => {

        if(!Config.initialized){
        
            const quoteConfig = await http.get("quotes/config");
            Config.options_status.quote = quoteConfig.options_status;
            Config.default_dates.quote = quoteConfig.default_dates;
            Config.units = quoteConfig.units;
            Config.tva = quoteConfig.tva;

            const invoiceConfig = await http.get("invoices/config");
            Config.options_status.invoice = invoiceConfig.options_status;
            Config.default_dates.invoice = invoiceConfig.default_dates;
            Config.initialized = true;

        }
        return Config[key];
      };
    }
  };

