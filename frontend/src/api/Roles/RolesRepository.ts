import { useAxios } from "@/plugins/axios";

const Repository = useAxios();

export default {
  listWithPermissions: async () => {
    return await Repository.get(`admin/roles`);
  },
  create: async (data: { slug: string; title_ru: string; title_en?: string | null; permission_ids?: number[] }) => {
    return await Repository.post(`admin/roles`, data);
  },
  syncPermissions: async (id: number, permission_ids: number[]) => {
    return await Repository.put(`admin/roles/${id}/permissions`, { permission_ids });
  },
};


