import { useAxios } from "@/plugins/axios";

const Repository = useAxios();

const resource = 'auth';

export default {
  login: async (email: string, password: string) => {
    return await Repository.post(`${resource}/login`, {
      email,
      password
    });
  },
  register: async (name: string, email: string, password: string, password_confirmation: string) => {
    return await Repository.post(`${resource}/register`, {
      name,
      email,
      password,
      password_confirmation
    });
  },
  user: async () => {
    return await Repository.get(`${resource}/user`);
  }
};