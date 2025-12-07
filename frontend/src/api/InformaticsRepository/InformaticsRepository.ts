import { useAxios } from "@/plugins/axios";

const Repository = useAxios();

const resource = "informatics";

export default {
  getTrainingOptions: async () => {
    return await Repository.get(`${resource}/getTrainingOptions`);
  },
  getTrainingOptionsByUser: async () => {
    return await Repository.get(`${resource}/getTrainingOptionsByUser`);
  }
};