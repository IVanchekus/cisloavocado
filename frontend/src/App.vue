<template>
  <Navbar v-if="isShowNavbar"/>
  <PageTemplate>
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

</script>