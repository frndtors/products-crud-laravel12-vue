<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import { ArrowLeft, Plus } from 'lucide-vue-next';

import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import type { ProductFormData } from '@/types';

const breadcrumbItems = [
    { title: 'Productos', href: '/products' },
    { title: 'Crear', href: '/products/create' }
];

const form = useForm<ProductFormData>({
    name: '',
    price: '',
    stock: '',
    description: '',
});

const submit = () => {
    form.post('/products');
};
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbItems">
        <Head title="Crear Producto" />

        <div class="flex flex-1 flex-col gap-4 px-12 py-10">
            <!-- Header -->
            <div class="flex items-center gap-4">
                <Button variant="outline" size="sm" @click="$inertia.visit('/products')">
                    <ArrowLeft class="h-4 w-4 mr-2" />
                    Volver a Productos
                </Button>
                <div>
                    <h1 class="text-3xl font-bold tracking-tight">Crear Producto</h1>
                    <p class="text-muted-foreground">Añadir un nuevo producto al inventario</p>
                </div>
            </div>

            <!-- Form -->
            <div class="max-w-2xl">
                <Card>
                    <CardHeader>
                        <CardTitle>Información del Producto</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <form @submit.prevent="submit" class="space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="space-y-2">
                                    <Label for="name">Nombre del Producto *</Label>
                                    <Input
                                        id="name"
                                        v-model="form.name"
                                        type="text"
                                        placeholder="Ingrese el nombre del producto"
                                        :class="{ 'border-red-500': form.errors.name }"
                                        required
                                    />
                                    <div v-if="form.errors.name" class="text-sm text-red-600 dark:text-red-400">
                                        {{ form.errors.name }}
                                    </div>
                                </div>

                                <div class="space-y-2">
                                    <Label for="price">Precio *</Label>
                                    <Input
                                        id="price"
                                        v-model="form.price"
                                        type="number"
                                        step="0.01"
                                        min="0"
                                        placeholder="0.00"
                                        :class="{ 'border-red-500': form.errors.price }"
                                        required
                                    />
                                    <div v-if="form.errors.price" class="text-sm text-red-600 dark:text-red-400">
                                        {{ form.errors.price }}
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-2">
                                <Label for="stock">Stock *</Label>
                                <Input
                                    id="stock"
                                    v-model="form.stock"
                                    type="number"
                                    min="0"
                                    placeholder="0"
                                    :class="{ 'border-red-500': form.errors.stock }"
                                    required
                                />
                                <div v-if="form.errors.stock" class="text-sm text-red-600 dark:text-red-400">
                                    {{ form.errors.stock }}
                                </div>
                            </div>

                            <div class="space-y-2">
                                <Label for="description">Descripción</Label>
                                <Textarea
                                    id="description"
                                    v-model="form.description"
                                    placeholder="Ingrese una descripción del producto (opcional)"
                                    :class="{ 'border-red-500': form.errors.description }"
                                    rows="4"
                                />
                                <div v-if="form.errors.description" class="text-sm text-red-600 dark:text-red-400">
                                    {{ form.errors.description }}
                                </div>
                            </div>

                            <div class="flex gap-4">
                                <Button type="submit" :disabled="form.processing">
                                    <Plus class="mr-2 h-4 w-4" />
                                    {{ form.processing ? 'Creando...' : 'Crear Producto' }}
                                </Button>
                                <Button type="button" variant="outline" @click="$inertia.visit('/products')">
                                    Cancelar
                                </Button>
                            </div>
                        </form>
                    </CardContent>
                </Card>
            </div>
        </div>
    </AppLayout>
</template>
