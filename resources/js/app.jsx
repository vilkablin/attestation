import "../css/app.css";
import { createInertiaApp } from "@inertiajs/react";
import { createRoot } from "react-dom/client";
import { Toaster } from "react-hot-toast";
import React from "react";

createInertiaApp({
    resolve: async (name) => {
        const pages = import.meta.glob("./Pages/**/*.jsx");
        const path = `./Pages/${name}.jsx`;

        if (!pages[path]) {
            throw new Error(`Page not found: ${path}`);
        }

        return pages[path]();
    },
    setup({ el, App, props }) {
        createRoot(el).render(
            <>
                <App {...props} />
                <Toaster
                    position="top-right"
                    toastOptions={{
                        duration: 4000,
                        style: {
                            fontSize: "14px",
                            borderRadius: "8px",
                            padding: "12px 16px",
                        },
                    }}
                />
            </>
        );
    },
});
