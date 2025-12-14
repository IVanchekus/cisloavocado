import { useAxios } from "@/plugins/axios";

const Repository = useAxios();

const resource = "users";

export default {
  list: async () => {
    return await Repository.get(`${resource}`);
  },
};


