<template>
  <div class="flex flex-column row-gap-3">
    <div class="admin-roles__title">Редактирование ролей</div>

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
import { Card, Button, Dropdown, MultiSelect } from "primevue";
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


