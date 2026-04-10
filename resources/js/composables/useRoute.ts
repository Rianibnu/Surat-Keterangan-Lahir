import { getCurrentInstance } from 'vue';

type RouteFunction = (name: string, params?: any) => string;

/**
 * Composable to access the global route function
 * This is needed because the route function is added as a global mixin in app.ts
 * and is not automatically available in <script setup>
 */
export function useRoute(): RouteFunction {
    const instance = getCurrentInstance();

    // Fallback route function
    const fallbackRoute: RouteFunction = (name: string, params?: any) => {
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
            'home': '/',
            'users.index': '/users',
            'users.create': '/users/create',
            'activity-logs.index': '/activity-logs',
        };

        if (name === 'doctors.edit') return `/doctors/${params}/edit`;
        if (name === 'doctors.destroy') return `/doctors/${params}`;
        if (name === 'doctors.update') return `/doctors/${params}`;
        if (name === 'birth-records.show') return `/birth-records/${params}`;
        if (name === 'birth-records.edit') return `/birth-records/${params}/edit`;
        if (name === 'birth-records.destroy') return `/birth-records/${params}`;
        if (name === 'birth-records.update') return `/birth-records/${params}`;
        if (name === 'birth-records.generate-skl') return `/birth-records/${params}/generate-skl`;
        if (name === 'skl.preview') return `/birth-records/${params}/skl/preview`;
        if (name === 'skl.download') return `/birth-records/${params}/skl/download`;
        if (name === 'skl.verify') return `/verify-skl/${params}`;
        if (name === 'users.edit') return `/users/${params}/edit`;
        if (name === 'users.destroy') return `/users/${params}`;
        if (name === 'users.update') return `/users/${params}`;
        if (name === 'activity-logs.show') return `/activity-logs/${params}`;

        return routes[name] || '#';
    };

    if (!instance) {
        console.warn('useRoute called outside setup, using fallback');
        return fallbackRoute;
    }

    const globalRoute = instance.appContext.config.globalProperties.route;

    if (!globalRoute) {
        console.warn('Global route function not found, using fallback');
        return fallbackRoute;
    }

    return globalRoute as RouteFunction;
}
