import { useAxios } from "@/plugins/axios";
import { ILogin, IRegister } from "./AuthRepository.types";

const Repository = useAxios();

const resource = 'auth';

export default {
  login: async (data: ILogin) => {
    return await Repository.post(`${resource}/login`, data);
  },
  register: async (data: IRegister) => {
    return await Repository.post(`${resource}/register`, data);
  },
  user: async () => {
    return await Repository.get(`${resource}/user`);
  }
};