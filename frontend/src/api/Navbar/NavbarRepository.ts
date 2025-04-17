import { useAxios } from "@/plugins/axios";

const Repository = useAxios();

const resource = "navs";

export default {
  getNavs: async () => {
    return await Repository.get(`${resource}`);
  },
};