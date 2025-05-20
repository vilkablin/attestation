import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import react from "@vitejs/plugin-react";
import path from "path";

export default defineConfig({
    plugins: [
        laravel({
            input: ["resources/js/app.jsx", "resources/css/app.css"],
            refresh: true,
        }),
        react(),
    ],
    resolve: {
        alias: {
            "@": "/resources/js",
            "@images": "/public/images",
            "@icons": "/public/icons",
            "/css": path.resolve(__dirname, "resources/css"),
        },
    },
    css: {
        preprocessorOptions: {
            scss: {
                additionalData: `
                    @use "/css/mixins.scss" as *;
                `,
            },
        },
    },
});
