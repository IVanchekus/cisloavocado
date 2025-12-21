import { useAxios } from "@/plugins/axios";

const Repository = useAxios();

const resource = "users";

export default {
  list: async () => {
    return await Repository.get(`${resource}`);
  },
  create: async (data: { name: string; email: string; password: string; role_ids?: number[] }) => {
    return await Repository.post(`admin/users`, data);
  },
  syncRoles: async (id: number, role_ids: number[]) => {
    return await Repository.put(`admin/users/${id}/roles`, { role_ids });
  },
};


