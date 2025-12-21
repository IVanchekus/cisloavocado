<template>
  <div class="flex flex-column row-gap-3">
    <div class="admin-roles__title">Роли</div>

    <Card>
      <template #title>Создать роль</template>
      <template #content>
        <div class="flex flex-column row-gap-2" style="max-width: 720px;">
          <div class="flex flex-column row-gap-1">
            <div class="text-sm">Slug (уникальный)</div>
            <InputText v-model="createForm.slug" class="w-full" placeholder="например: moderator" />
          </div>

          <div class="flex flex-column row-gap-1">
            <div class="text-sm">Название (RU)</div>
            <InputText v-model="createForm.title_ru" class="w-full" placeholder="например: Модератор" />
          </div>

          <div class="flex flex-column row-gap-1">
            <div class="text-sm">Название (EN, опционально)</div>
            <InputText v-model="createForm.title_en" class="w-full" placeholder="например: Moderator" />
          </div>

          <div class="flex flex-column row-gap-1">
            <div class="text-sm">Права (опционально)</div>
            <MultiSelect
              v-model="createForm.permission_ids"
              :options="permissionOptions"
              optionLabel="label"
              optionValue="id"
              display="chip"
              placeholder="Выберите права"
              class="w-full"
            />
          </div>

          <div class="flex gap-2 mt-2">
            <Button label="Создать" :loading="isCreating" @click="onCreateRole" />
            <Button label="Очистить" severity="secondary" @click="resetCreateForm" />
          </div>
        </div>
      </template>
    </Card>

    <Card>
      <template #title>Роль → разрешения</template>
      <template #content>
        <div class="flex flex-column row-gap-2" style="max-width: 720px;">
          <div class="flex flex-column row-gap-1">
            <div class="text-sm">Роль</div>
            <Dropdown
              v-model="selectedRoleId"
              :options="roleOptions"
              optionLabel="label"
              optionValue="id"
              placeholder="Выберите роль"
            />
          </div>

          <div v-if="selectedRoleId" class="flex flex-column row-gap-1">
            <div class="text-sm">Разрешения</div>
            <MultiSelect
              v-model="selectedPermissionIds"
              :options="permissionOptions"
              optionLabel="label"
              optionValue="id"
              display="chip"
              placeholder="Выберите разрешения"
            />
          </div>

          <div class="flex gap-2 mt-2">
            <Button
              label="Сохранить"
              :disabled="!selectedRoleId"
              :loading="isSaving"
              @click="onSave"
            />
            <Button label="Обновить" severity="secondary" @click="loadAll" />
          </div>

          <div v-if="error" class="mt-2 p-2 border-1 border-round" style="border-color: var(--p-red-300);">
            {{ error }}
          </div>
        </div>
      </template>
    </Card>
  </div>
</template>

<script setup lang="ts">
import { Card, Button, Dropdown, MultiSelect, InputText } from "primevue";
import { computed, onMounted, ref, watch } from "vue";
import { useGlobalStore } from "@/store/globalStore";
import DictionariesRepository from "@/api/Dictionaries/DictionariesRepository";
import RolesRepository from "@/api/Roles/RolesRepository";

type TRole = {
  id: number;
  slug: string;
  title: { ru?: string; en?: string };
  permissions?: Array<{ id: number; slug: string; title: any }>;
};

type TPermission = {
  id: number;
  slug: string;
  title: { ru?: string; en?: string };
};

const globalStore = useGlobalStore();

const roles = ref<TRole[]>([]);
const permissions = ref<TPermission[]>([]);
const selectedRoleId = ref<number | null>(null);
const selectedPermissionIds = ref<number[]>([]);
const error = ref<string | null>(null);
const isSaving = ref(false);
const isCreating = ref(false);

const createForm = ref({
  slug: "",
  title_ru: "",
  title_en: "",
  permission_ids: [] as number[],
});

const roleOptions = computed(() => {
  return roles.value.map((r) => ({
    id: r.id,
    label: r.title?.ru ?? r.slug,
  }));
});

const permissionOptions = computed(() => {
  return permissions.value.map((p) => ({
    id: p.id,
    label: p.title?.ru ?? p.slug,
  }));
});

const selectedRole = computed(() => {
  return roles.value.find((r) => r.id === selectedRoleId.value) ?? null;
});

onMounted(async () => {
  await loadAll();
});

const loadAll = async () => {
  error.value = null;
  globalStore.changeLoading(true);
  try {
    const [{ data: rolesData }, { data: permissionsData }] = await Promise.all([
      RolesRepository.listWithPermissions(),
      DictionariesRepository.permissions(),
    ]);
    roles.value = rolesData;
    permissions.value = permissionsData;

    // preserve selection
    if (!selectedRoleId.value && roles.value.length) {
      selectedRoleId.value = roles.value[0].id;
    }
  } catch (e) {
    error.value = "Не удалось загрузить данные.";
    console.log(e);
  } finally {
    globalStore.changeLoading(false);
  }
};

const resetCreateForm = () => {
  createForm.value.slug = "";
  createForm.value.title_ru = "";
  createForm.value.title_en = "";
  createForm.value.permission_ids = [];
};

const onCreateRole = async () => {
  error.value = null;
  if (!createForm.value.slug.trim() || !createForm.value.title_ru.trim()) {
    error.value = "Заполните slug и название (RU).";
    return;
  }

  isCreating.value = true;
  try {
    const { data } = await RolesRepository.create({
      slug: createForm.value.slug.trim(),
      title_ru: createForm.value.title_ru.trim(),
      title_en: createForm.value.title_en?.trim() || null,
      permission_ids: createForm.value.permission_ids,
    });
    await loadAll();
    selectedRoleId.value = (data as any).id;
    resetCreateForm();
  } catch (e: any) {
    error.value = "Не удалось создать роль.";
    console.log(e);
  } finally {
    isCreating.value = false;
  }
};

watch(
  () => selectedRoleId.value,
  () => {
    const r = selectedRole.value;
    selectedPermissionIds.value = (r?.permissions ?? []).map((p) => p.id);
  },
  { immediate: true },
);

const onSave = async () => {
  if (!selectedRoleId.value) return;
  error.value = null;
  isSaving.value = true;
  try {
    const { data } = await RolesRepository.syncPermissions(
      selectedRoleId.value,
      selectedPermissionIds.value,
    );
    const idx = roles.value.findIndex((r) => r.id === selectedRoleId.value);
    if (idx >= 0) {
      roles.value[idx] = data;
    }
  } catch (e) {
    error.value = "Не удалось сохранить разрешения.";
    console.log(e);
  } finally {
    isSaving.value = false;
  }
};
</script>

<style scoped lang="scss">
.admin-roles {
  &__title {
    font-weight: 600;
    font-size: 70px;
  }
}
</style>


