import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";

export default defineConfig({
    plugins: [
        laravel({
            input: [
                "resources/portal/scss/portal.scss",
                "resources/portal/js/portal.js",
                "resources/shop/scss/shop.scss",
                "resources/shop/js/shop.js",
            ],
            refresh: true,
        }),
    ],
});
