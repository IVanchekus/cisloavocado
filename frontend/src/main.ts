import { createApp } from 'vue';
import App from './App.vue';
import router from './router';
import PrimeVue from 'primevue/config';
import "./assets/reset.css";
import 'primeicons/primeicons.css'; // Иконки
import 'primeflex/primeflex.css'; // PrimeFlex
import Lara from "@primevue/themes/lara";

createApp(App)
  .use(router)
  .use(PrimeVue, { 
    theme: {
      preset: Lara,
      options: {
        darkModeSelector: 'system',
      }
    }
  })
  .mount('#app');

