import router from "@/router";
import { useNavsStore } from "@/store/navsStore";

export default async () => {
  const navsStore = useNavsStore();
  await navsStore.getNavs();

  navsStore.navs.forEach((nav: {name: string, component: string | null}) => {
    // Родительские пункты меню (dropdown) не имеют компонента и не должны создавать маршрут.
    if (!nav.component) return;

    router.addRoute({
      path: `/${nav.name}`,
      name: nav.name,
      component: () => import(`../pages/${nav.component}/${nav.component}.vue`),
    });
  });
};