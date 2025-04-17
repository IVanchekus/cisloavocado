import { defineStore } from 'pinia';

export const useGlobalStore = defineStore('global', {
  state: () => ({
    isLoading: false
  }),
  actions: {
    changeLoading(value: boolean) {
      this.isLoading = value;
    },
  }
})