import { createApp } from 'vue';
import App from './App.vue';
import router from './router';
import PrimeVue from 'primevue/config';
import { ToastService } from 'primevue';

import "./assets/reset.css";
import 'primeicons/primeicons.css'; // Иконки
import 'primeflex/primeflex.css'; // PrimeFlex
import "@/assets/fonts/fonts.scss";
import "@/assets/style.scss";
import useDefinePreset from '@/utils/useDefinePreset';

import { axiosPlugin } from '@/plugins/axios';
import { createPinia } from 'pinia';

const pinia = createPinia();

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
  .use(ToastService)
  .use(axiosPlugin)
  .use(pinia)
  .mount('#app');

