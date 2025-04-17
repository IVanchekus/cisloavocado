import { useAuthStore } from "@/store/authStore";
import { NavigationGuardNext, RouteLocationNormalized } from "vue-router";
import { useGlobalStore } from '../../store/globalStore';

export default async function authMiddleware(
  to: RouteLocationNormalized,
  _from: RouteLocationNormalized,
  next: NavigationGuardNext,
) {
  const authStore = useAuthStore();
  const globalStore = useGlobalStore();

  if (!authStore.isCheckedAuth) {
    globalStore.changeLoading(true);

    await authStore.login();
    
    globalStore.changeLoading(false);
  }

  if (["login", "register"].includes(to.name as string) && authStore.isAuth) {
    next({ name: "home" });
  } else {
    next();
  }
}
