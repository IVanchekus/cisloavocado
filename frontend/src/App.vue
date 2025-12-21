<template>
  <FullScreenLoader :is-blocked="globalStore.isLoading"/>
  <Navbar v-if="isShowNavbar" />
  <PageTemplate :style="{ height: pageTemplateHeight }">
    <router-view :key="route.fullPath"></router-view>
  </PageTemplate>
</template>

<script setup>
import Navbar from "@/components/Navbar/Navbar.vue";
import PageTemplate from "@/components/PageTemplate/PageTemplate.vue";
import FullScreenLoader from "@/components/FullScreenLoader/FullScreenLoader.vue";
import { computed } from "vue";
import { useRoute } from "vue-router";
import { useGlobalStore } from "./store/globalStore";

const route = useRoute();
const isShowNavbar = computed(() => route.meta.isShowNavbar ?? true);
const pageTemplateHeight = computed(
  () => route.meta.pageTemplateHeight ?? "100%",
);
const globalStore = useGlobalStore();
</script>
