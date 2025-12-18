<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { Card, CardContent, CardHeader, CardTitle, CardDescription } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Users, ArrowLeft, Pencil, Mail, Lock, Shield } from 'lucide-vue-next';
import InputError from '@/components/InputError.vue';
import { type BreadcrumbItem } from '@/types';

const props = defineProps<{
    user: {
        id: number;
        name: string;
        email: string;
        roles: Array<{ name: string }>;
    };
    roles: Array<{ id: number; name: string }>;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Manajemen User', href: '/users' },
    { title: 'Edit User', href: `/users/${props.user.id}/edit` },
];

const form = useForm({
    name: props.user.name,
    email: props.user.email,
    password: '',
    password_confirmation: '',
    role: props.user.roles[0]?.name || '',
});

const submit = () => {
    form.put(`/users/${props.user.id}`);
};
</script>

<template>
    <Head :title="`Edit User - ${user.name}`" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 p-6">
            <!-- Header Section -->
            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <div>
                    <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white flex items-center gap-2">
                        <Pencil class="h-7 w-7 text-indigo-600 dark:text-indigo-400" />
                        Edit User
                    </h1>
                    <p class="text-sm text-muted-foreground mt-1">
                        Perbarui informasi user: <strong>{{ user.name }}</strong>
                    </p>
                </div>
                <Link href="/users">
                    <Button variant="outline" size="sm">
                        <ArrowLeft class="h-4 w-4 mr-2" />
                        Kembali
                    </Button>
                </Link>
            </div>

            <!-- Form -->
            <Card class="max-w-2xl shadow-lg border-0 dark:bg-gray-800/50">
                <CardHeader>
                    <CardTitle class="flex items-center gap-2">
                        <Users class="h-5 w-5" />
                        Informasi User
                    </CardTitle>
                    <CardDescription>
                        Kosongkan password jika tidak ingin mengubahnya.
                    </CardDescription>
                </CardHeader>
                <CardContent>
                    <form @submit.prevent="submit" class="space-y-6">
                        <!-- Name -->
                        <div class="space-y-2">
                            <Label for="name" class="flex items-center gap-2">
                                <Users class="h-4 w-4" />
                                Nama Lengkap
                            </Label>
                            <Input
                                id="name"
                                v-model="form.name"
                                type="text"
                                placeholder="Masukkan nama lengkap"
                                required
                                autofocus
                            />
                            <InputError :message="form.errors.name" />
                        </div>

                        <!-- Email -->
                        <div class="space-y-2">
                            <Label for="email" class="flex items-center gap-2">
                                <Mail class="h-4 w-4" />
                                Email
                            </Label>
                            <Input
                                id="email"
                                v-model="form.email"
                                type="email"
                                placeholder="user@example.com"
                                required
                            />
                            <InputError :message="form.errors.email" />
                        </div>

                        <!-- Role -->
                        <div class="space-y-2">
                            <Label for="role" class="flex items-center gap-2">
                                <Shield class="h-4 w-4" />
                                Role
                            </Label>
                            <Select v-model="form.role" required>
                                <SelectTrigger>
                                    <SelectValue placeholder="Pilih role user" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem v-for="role in roles" :key="role.id" :value="role.name">
                                        <span class="capitalize">{{ role.name }}</span>
                                    </SelectItem>
                                </SelectContent>
                            </Select>
                            <InputError :message="form.errors.role" />
                        </div>

                        <!-- Password -->
                        <div class="space-y-2">
                            <Label for="password" class="flex items-center gap-2">
                                <Lock class="h-4 w-4" />
                                Password Baru (opsional)
                            </Label>
                            <Input
                                id="password"
                                v-model="form.password"
                                type="password"
                                placeholder="Kosongkan jika tidak ingin mengubah"
                            />
                            <InputError :message="form.errors.password" />
                        </div>

                        <!-- Password Confirmation -->
                        <div class="space-y-2">
                            <Label for="password_confirmation" class="flex items-center gap-2">
                                <Lock class="h-4 w-4" />
                                Konfirmasi Password Baru
                            </Label>
                            <Input
                                id="password_confirmation"
                                v-model="form.password_confirmation"
                                type="password"
                                placeholder="Ulangi password baru"
                            />
                        </div>

                        <!-- Submit -->
                        <div class="flex items-center gap-4 pt-4">
                            <Button 
                                type="submit" 
                                :disabled="form.processing"
                                class="bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700"
                            >
                                <Pencil class="h-4 w-4 mr-2" />
                                {{ form.processing ? 'Menyimpan...' : 'Simpan Perubahan' }}
                            </Button>
                            <Link href="/users">
                                <Button type="button" variant="outline">
                                    Batal
                                </Button>
                            </Link>
                        </div>
                    </form>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>
