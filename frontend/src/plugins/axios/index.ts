import axios from "axios";
import { App, provide } from "vue";

const axiosPlugin = {
  install: (app: App) => {
    axios.defaults.withCredentials = true;

    axios.defaults.baseURL = import.meta.env.VITE_BACKEND_URL;

    // Выставить CSRF-токен
    axios.get("/sanctum/csrf-cookie");

    app.config.globalProperties.$axios = axios;

    provide("$axios", axios);
  },
};

const useAxios = () => {
  return axios;
};

export { axiosPlugin, useAxios };
