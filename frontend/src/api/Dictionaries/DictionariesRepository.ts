import { useAxios } from "@/plugins/axios";

const Repository = useAxios();

export default {
  roles: async () => {
    return await Repository.get(`dictionaries/roles`);
  },
  permissions: async () => {
    return await Repository.get(`dictionaries/permissions`);
  },
};


