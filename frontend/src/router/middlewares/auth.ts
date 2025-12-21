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

  const isAuth = authStore.isAuth;
  const toLoginRegister = ["login", "register"].includes(to.name as string);

  if (!isAuth && !toLoginRegister) next({ name: "login" });
  if (isAuth && toLoginRegister) next({ name: "home" });

  const requiredRoles = (to.meta?.requiredRoles ?? null) as null | string[];
  if (requiredRoles && requiredRoles.length) {
    if (!authStore.hasAnyRole(requiredRoles)) {
      next({ name: "home" });
      return;
    }
  }
  next();
}
