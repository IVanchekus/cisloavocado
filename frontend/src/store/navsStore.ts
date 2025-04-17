import { defineStore } from "pinia";
import NavbarRepository from "@/api/Navbar/NavbarRepository";

export const useNavsStore = defineStore("navs", {
  state: () => ({
    navs: [],
  }),
  actions: {
    async getNavs() {
      const { data } = await NavbarRepository.getNavs();
      this.navs = data;
    },
  },
});