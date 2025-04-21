import { useAxios } from "@/plugins/axios";

const Repository = useAxios();

const resource = "training-option";

export default {
  getExercises: async (hash: string) => {
    return await Repository.get(`${resource}/getExercises/${hash}`);
  },
  saveAnswers: async (answers: any, hash: string) => {
    return await Repository.post(`${resource}/saveAnswers`, { answers, hash } );
  }
};