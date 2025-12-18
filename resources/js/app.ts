import '../css/app.css';

import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import type { DefineComponent } from 'vue';
import { createApp, h } from 'vue';
import { initializeTheme } from './composables/useAppearance';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => (title ? `${title} - ${appName}` : appName),
    resolve: (name) =>
        resolvePageComponent(
            `./pages/${name}.vue`,
            import.meta.glob<DefineComponent>('./pages/**/*.vue'),
        ),
    setup({ el, App, props, plugin }) {
        // Manual mockup for routing since Ziggy installation has issues in this environment
        const route = (name: string, params?: any) => {
            const routes: Record<string, string> = {
                'dashboard': '/dashboard',
                'doctors.index': '/doctors',
                'doctors.create': '/doctors/create',
                'doctors.store': '/doctors',
                'birth-records.index': '/birth-records',
                'birth-records.create': '/birth-records/create',
                'birth-records.export': '/birth-records/export',
                'birth-records.store': '/birth-records',
                'login': '/login',
                'register': '/register',
                'home': '/'
            };

            if (name === 'doctors.edit') return `/doctors/${params}/edit`;
            if (name === 'doctors.destroy') return `/doctors/${params}`;
            if (name === 'birth-records.show') return `/birth-records/${params}`;
            if (name === 'doctors.update') return `/doctors/${params}`; // update usually uses the same path as show but with PUT
            if (name === 'skl.verify') return `/verify-skl/${params}`;

            return routes[name] || '#';
        };

        createApp({ render: () => h(App, props) })
            .use(plugin)
            .mixin({ methods: { route } })
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});

// This will set light / dark mode on page load...
initializeTheme();
