import { useAxios } from "@/plugins/axios";

const Repository = useAxios();

const resource = "training-option";

export default {
  getExercises: async (hash: string) => {
    return await Repository.get(`${resource}/getExercises/${hash}`);
  },
  saveAnswers: async (answers: any, hash: string) => {
    return await Repository.post(`${resource}/saveAnswers`, { answers, hash } );
  },
  createExercise: async (data: any) => {
    return await Repository.post(`${resource}/exercise/create`, {data});
  },
  updateExercise: async (id: any, data: any) => {
    return await Repository.put(`${resource}/exercise/update/${id}`, {data});
  }
};