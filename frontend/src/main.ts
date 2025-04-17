import { createApp } from "vue";
import App from "./App.vue";
import router from "./router";
import PrimeVue from "primevue/config";
import { ToastService } from "primevue";

import "./assets/reset.css";
import "primeicons/primeicons.css"; // Иконки
import "primeflex/primeflex.css"; // PrimeFlex
import "@/assets/fonts/fonts.scss";
import "@/assets/style.scss";
import useDefinePreset from "@/utils/useDefinePreset";

import { axiosPlugin } from "@/plugins/axios";
import { createPinia } from "pinia";
import useNavs from "./utils/useNavs";

const pinia = createPinia();

const app = createApp(App);
  
(async () => {
  app.use(PrimeVue, {
    theme: {
      preset: useDefinePreset(),
      options: {
        darkModeSelector: true,
      },
    },
  });
  app.use(ToastService);
  app.use(axiosPlugin);
  app.use(pinia);

  await useNavs();
  app.use(router);
  await router.isReady();

  app.mount("#app");
})();
