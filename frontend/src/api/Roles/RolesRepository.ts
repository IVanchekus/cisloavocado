import { useAxios } from "@/plugins/axios";

const Repository = useAxios();

export default {
  listWithPermissions: async () => {
    return await Repository.get(`admin/roles`);
  },
  syncPermissions: async (id: number, permission_ids: number[]) => {
    return await Repository.put(`admin/roles/${id}/permissions`, { permission_ids });
  },
};


