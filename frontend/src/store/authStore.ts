import AuthRepository from "@/api/Auth/AuthRepository";
import router from "@/router";
import { defineStore } from "pinia";

type TRole = {
  id: number;
  slug: string;
  title: Record<string, string>;
};

export const useAuthStore = defineStore("auth", {
  state: () => ({
    isAuth: false,
    isCheckedAuth: false,
    user: {},
  }),
  getters: {
    roles: (state): Array<TRole> => {
      const u: any = state.user as any;
      return (u?.roles ?? []) as Array<TRole>;
    },
    roleSlugs(): string[] {
      return this.roles.map((r) => r.slug);
    },
    hasRole() {
      return (slug: string) => this.roleSlugs.includes(slug);
    },
    hasAnyRole() {
      return (slugs: string[]) => slugs.some((s) => this.roleSlugs.includes(s));
    },
  },
  actions: {
    async login() {
      try {
        const { data } = await AuthRepository.user();
        this.user = data;
        this.isAuth = true;
      } catch {
        console.log('Unauthorized');
      } finally {
        this.checkAuth();
      }
    },
    async logout() {
      try {
        await AuthRepository.logout();
        this.isAuth = false;
        this.user = {};
        router.push({ name: "login" });
      } catch (error) {
        console.log(error);
      }
    },
    checkAuth() {
      this.isCheckedAuth = true;
    },
  },
});
