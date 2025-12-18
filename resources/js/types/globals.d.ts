import { AppPageProps } from '@/types/index';
import { route as ziggyRoute } from 'ziggy-js';

// Declare global route function from Ziggy
declare global {
    const route: typeof ziggyRoute;
}

// Flash message interface for Inertia
interface FlashMessages {
    success?: string;
    error?: string;
    warning?: string;
    info?: string;
}

// Extend Inertia page props with flash messages
declare module '@inertiajs/core' {
    interface PageProps extends InertiaPageProps, AppPageProps {
        flash?: FlashMessages;
    }
}

// Extend ImportMeta interface for Vite...
declare module 'vite/client' {
    interface ImportMetaEnv {
        readonly VITE_APP_NAME: string;
        [key: string]: string | boolean | undefined;
    }

    interface ImportMeta {
        readonly env: ImportMetaEnv;
        readonly glob: <T>(pattern: string) => Record<string, () => Promise<T>>;
    }
}

declare module 'vue' {
    interface ComponentCustomProperties {
        $inertia: typeof Router;
        $page: Page;
        $headManager: ReturnType<typeof createHeadManager>;
        route: typeof ziggyRoute;
    }
}
