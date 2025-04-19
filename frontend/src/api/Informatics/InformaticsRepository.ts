import { useAxios } from "@/plugins/axios";

const Repository = useAxios();

const resource = "informatics";

export default {
  getAllExercises: async () => {
    return await Repository.get(`${resource}/exercises`);
  },
};