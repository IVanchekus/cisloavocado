import { useAxios } from "@/plugins/axios";

const Repository = useAxios();

const resource = "profile";

export default {
  getUserData: async (id: string) => {
    return await Repository.get(`${resource}/user-data/${id}`);
  },
  getMyExercises: async () => {
    return await Repository.get(`${resource}/exercises`);
  },
}