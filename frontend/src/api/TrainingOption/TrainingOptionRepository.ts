import { useAxios } from "@/plugins/axios";

const Repository = useAxios();

const resource = "training-option";

export default {
  getExercises: async (hash: string) => {
    return await Repository.get(`${resource}/getExercises/${hash}`);
  },
  saveAnswers: async (answers: Array<{ exerciseId: number; answer: string }>, hash: string) => {
    return await Repository.post(`${resource}/saveAnswers`, { answers, hash } );
  },
  createExercise: async (data: unknown) => {
    return await Repository.post(`${resource}/exercise/create`, {data});
  },
  updateExercise: async (id: number | string, data: unknown) => {
    return await Repository.put(`${resource}/exercise/update/${id}`, {data});
  },
  getExercise: async (id: number | string) => {
    return await Repository.get(`${resource}/exercise/${id}`);
  },
  getTrainingOption: async (id: number | string) => {
    return await Repository.get(`${resource}/${id}`);
  },
  createTrainingOption: async (data: unknown) => {
    return await Repository.post(`${resource}/create`, { data });
  },
  updateTrainingOption: async (id: number | string, data: unknown) => {
    return await Repository.put(`${resource}/update/${id}`, { data });
  }
};