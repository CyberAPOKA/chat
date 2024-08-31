import './bootstrap';
import '../css/app.css';

import { createApp, h } from 'vue';
import { createPinia } from 'pinia'
import { createInertiaApp, router } from "@inertiajs/vue3";
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
import { MotionPlugin } from '@vueuse/motion'
import PrimeVue from 'primevue/config';
import Aura from '@primevue/themes/aura';
import 'primeicons/primeicons.css'
import { i18nVue } from "laravel-vue-i18n";

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';
const pinia = createPinia()

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        const app = createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .use(MotionPlugin)
            .use(pinia)
            .use(i18nVue, {
                lang: "en",
                resolve: (lang) => {
                    const langs = import.meta.glob("../../lang/*.json", {
                        eager: true,
                    });
                    return langs[`../../lang/${lang}.json`].default;
                },
            })
            .use(PrimeVue, {
                theme: {
                    preset: Aura,
                    options: {
                        cssLayer: {
                            darkModeSelector: '.my-app-dark',
                        }
                    }
                }
            });
        app.mount(el);
        router.on("before", (event) => {
            const currentLang = localStorage.getItem("currentLang") || "en";
            event.detail.visit.headers["Accept-Language"] = currentLang;
        });
    },
    progress: {
        color: '#4B5563',
    },
});
