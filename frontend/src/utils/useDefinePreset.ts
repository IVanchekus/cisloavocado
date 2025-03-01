import { definePreset } from "@primevue/themes";
import Lara from '@primevue/themes/lara';
import { $dt } from "@primevue/themes";

const useDefinePreset = () => definePreset(Lara, {
  semantic: {
    primary: {
      50: "#FFFFF0",
      100: "#FFFDE7",
      200: "#F9EC78",
      300: "#FFEF6B",
      400: "#F5E339",
      500: "#FFD700",
      600: "#FFC800",
      700: "#FFB300",
      800: "#FFA000",
      900: "#BAA800",
      950: "#8C7E00"
    }
  }
})

export default useDefinePreset;