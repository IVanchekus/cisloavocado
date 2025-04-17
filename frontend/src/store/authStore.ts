import AuthRepository from "@/api/Auth/AuthRepository";
import { defineStore } from "pinia";

export const useAuthStore = defineStore("auth", {
  state: () => ({
    isAuth: false,
    isCheckedAuth: false,
    user: {},
  }),
  actions: {
    async login() {
      try {
        const { data } = await AuthRepository.user();
        this.user = data;
        this.isAuth = true;
      } catch (error) {
        console.log(error);
      } finally {
        this.checkAuth();
      }
    },
    async logout() {
      try {
        await AuthRepository.logout();
        this.isAuth = false;
        this.user = {};
      } catch (error) {
        console.log(error);
      }
    },
    checkAuth() {
      this.isCheckedAuth = true;
    },
  },
});
