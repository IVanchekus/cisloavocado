<template>
  <Navbar v-if="isShowNavbar" />
  <PageTemplate :style="{ height: pageTemplateHeight }">
    <router-view></router-view>
  </PageTemplate>
</template>

<script setup>
import Navbar from "@/components/Navbar/Navbar.vue";
import PageTemplate from "@/components/PageTemplate/PageTemplate.vue";
import { computed } from "vue";
import { useRoute } from "vue-router";

const route = useRoute();
const isShowNavbar = computed(() => route.meta.isShowNavbar ?? true);
const pageTemplateHeight = computed(
  () => route.meta.pageTemplateHeight ?? "100%",
);
</script>
