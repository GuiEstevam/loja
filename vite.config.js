import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";

export default defineConfig({
    plugins: [
        laravel({
            input: [
                "resources/css/app.css",
                "resources/css/navbar.css",
                "resources/css/welcome.css",
                "resources/css/favorites.css",
                "resources/css/products.css",
                "resources/css/product-detail.css",
                "resources/css/footer.css",
                "resources/css/cart.css",
                "resources/js/app.js",
            ],
            refresh: true,
        }),
    ],
});
