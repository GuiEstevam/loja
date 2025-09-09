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
                "resources/css/checkout.css",
                "resources/css/confirmation.css",
                "resources/css/dashboard.css",
                "resources/css/reviews.css",
                "resources/css/review-form.css",
                "resources/js/app.js",
                "resources/js/reviews.js",
                "resources/js/review-form.js",
            ],
            refresh: true,
        }),
    ],
});
