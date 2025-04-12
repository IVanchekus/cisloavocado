import { createApp } from 'vue';
import App from './App.vue';
import router from './router';
import PrimeVue from 'primevue/config';

import "./assets/reset.css";
import 'primeicons/primeicons.css'; // Иконки
import 'primeflex/primeflex.css'; // PrimeFlex
import "@/assets/fonts/fonts.scss";
import "@/assets/style.scss";

import useDefinePreset from '@/utils/useDefinePreset';
import { axiosPlugin } from '@/plugins/axios';


createApp(App)
  .use(router)
  .use(PrimeVue, { 
    theme: {
      preset: useDefinePreset(),
      options: {
        darkModeSelector: true,
      }
    }
  })
  .use(axiosPlugin)
  .mount('#app');

