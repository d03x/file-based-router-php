import { defineConfig } from "rolldown";
export default defineConfig({
  watch:true,
  input: "resources/main.ts",
  output: {
    minify:true,
    target : "esnext",
    sourcemap:true,
    file: "public/assets/bundle.js",
  },
});
