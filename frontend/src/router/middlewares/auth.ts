import { useAuthStore } from "@/store/authStore";
import { NavigationGuardNext, RouteLocationNormalized } from "vue-router";

export default async function authMiddleware(
  to: RouteLocationNormalized,
  from: RouteLocationNormalized,
  next: NavigationGuardNext,
) {
  const authStore = useAuthStore();

  if (!authStore.isCheckedAuth) {
    await authStore.login();
  }

  if (["login", "register"].includes(to.name as string) && authStore.isAuth) {
    next({ name: "home" });
  } else {
    next();
  }
}
