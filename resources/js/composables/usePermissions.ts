import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

interface Permission {
    name: string;
}

interface Role {
    name: string;
}

interface User {
    id: number;
    name: string;
    email: string;
    roles: Role[];
    permissions: Permission[];
}

interface PageProps {
    auth: {
        user: User | null;
    };
}

export function usePermissions() {
    const page = usePage();

    const user = computed(() => (page.props.auth as any)?.user);
    const permissions = computed(() => user.value?.permissions?.map((p: Permission) => p.name) || []);
    const roles = computed(() => user.value?.roles?.map((r: Role) => r.name) || []);

    /**
     * Check if user has a specific permission
     */
    const can = (permission: string): boolean => {
        return permissions.value.includes(permission);
    };

    /**
     * Check if user has any of the given permissions
     */
    const canAny = (permissionList: string[]): boolean => {
        return permissionList.some(p => permissions.value.includes(p));
    };

    /**
     * Check if user has all of the given permissions
     */
    const canAll = (permissionList: string[]): boolean => {
        return permissionList.every(p => permissions.value.includes(p));
    };

    /**
     * Check if user has a specific role
     */
    const hasRole = (role: string): boolean => {
        return roles.value.includes(role);
    };

    /**
     * Check if user has any of the given roles
     */
    const hasAnyRole = (roleList: string[]): boolean => {
        return roleList.some(r => roles.value.includes(r));
    };

    /**
     * Check if user is admin
     */
    const isAdmin = computed(() => hasRole('admin'));

    return {
        user,
        permissions,
        roles,
        can,
        canAny,
        canAll,
        hasRole,
        hasAnyRole,
        isAdmin,
    };
}
