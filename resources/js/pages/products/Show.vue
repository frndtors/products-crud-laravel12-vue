<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { ArrowLeft, Edit, Trash2, Package } from 'lucide-vue-next';

import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Badge } from '@/components/ui/badge';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';

interface Product {
    id: number;
    name: string;
    price: number;
    stock: number;
    description?: string;
    created_at: string;
    updated_at: string;
}

interface Props {
    product: Product;
}

const props = defineProps<Props>();

const breadcrumbItems = [
    { title: 'Productos', href: '/products' },
    { title: props.product.name, href: `/products/${props.product.id}` }
];

const formatPrice = (price: number) => `L${Number(price).toFixed(2)}`;
const formatDate = (date: string) => new Date(date).toLocaleDateString();

const getStockBadgeVariant = (stock: number) => {
    if (stock === 0) return 'destructive';
    if (stock <= 5) return 'secondary';
    return 'default';
};
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbItems">
        <Head :title="`Producto: ${product.name}`" />

        <div class="flex flex-1 flex-col gap-4 px-12 py-10">
            <!-- Header -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div class="flex items-center gap-4">
                    <Link href="/products">
                        <Button variant="outline" size="sm">
                            <ArrowLeft class="h-4 w-4 mr-2" />
                            Regresar a Productos
                        </Button>
                    </Link>
                    <div>
                        <h1 class="text-3xl font-bold tracking-tight">{{ product.name }}</h1>
                        <p class="text-muted-foreground">Detalles del Producto</p>
                    </div>
                </div>
                <div class="flex gap-2">
                    <Link :href="`/products/${product.id}/edit`">
                        <Button variant="outline">
                            <Edit class="mr-2 h-4 w-4" />
                            Editar Producto
                        </Button>
                    </Link>
                </div>
            </div>

            <!-- Product Details -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Main Info -->
                <div class="lg:col-span-2">
                    <Card>
                        <CardHeader>
                            <CardTitle class="flex items-center gap-2">
                                <Package class="h-5 w-5" />
                                Información del Producto
                            </CardTitle>
                        </CardHeader>
                        <CardContent class="space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="text-sm font-medium text-muted-foreground">Nombre del Producto</label>
                                    <p class="text-lg font-semibold">{{ product.name }}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-muted-foreground">Precio</label>
                                    <p class="text-2xl font-bold text-green-600">{{ formatPrice(product.price) }}</p>
                                </div>
                            </div>

                            <div>
                                <label class="text-sm font-medium text-muted-foreground">Stock</label>
                                <div class="mt-1">
                                    <Badge :variant="getStockBadgeVariant(product.stock)" class="text-base px-3 py-1">
                                        {{ product.stock }} Unidades Disponibles
                                    </Badge>
                                </div>
                            </div>

                            <div v-if="product.description">
                                <label class="text-sm font-medium text-muted-foreground">Descripción</label>
                                <p class="mt-1 text-gray-700 leading-relaxed">{{ product.description }}</p>
                            </div>
                        </CardContent>
                    </Card>
                </div>

                <!-- Sidebar Info -->
                <div class="space-y-6">
                    <!-- Stock Status -->
                    <Card>
                        <CardHeader>
                            <CardTitle class="text-lg">Estado del Inventario</CardTitle>
                        </CardHeader>
                        <CardContent>
                            <div class="text-center py-4">
                                <div class="text-3xl font-bold mb-2">{{ product.stock }}</div>
                                <Badge :variant="getStockBadgeVariant(product.stock)" class="mb-4">
                                    {{ product.stock === 0 ? 'Out of Stock' : product.stock <= 5 ? 'Low Stock' : 'In Stock' }}
                                </Badge>
                                <div v-if="product.stock <= 5 && product.stock > 0" class="text-sm text-amber-600">
                                    ⚠️ El stock se está agotando
                                </div>
                                <div v-if="product.stock === 0" class="text-sm text-red-600">
                                    ❌ El producto está agotado
                                </div>
                            </div>
                        </CardContent>
                    </Card>

                    <!-- Metadata -->
                    <Card>
                        <CardHeader>
                            <CardTitle class="text-lg">Metadata</CardTitle>
                        </CardHeader>
                        <CardContent class="space-y-3">
                            <div>
                                <label class="text-sm font-medium text-muted-foreground">ID del Producto</label>
                                <p class="font-mono">{{ product.id }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-muted-foreground">Creado</label>
                                <p>{{ formatDate(product.created_at) }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-muted-foreground">Última Actualización</label>
                                <p>{{ formatDate(product.updated_at) }}</p>
                            </div>
                        </CardContent>
                    </Card>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
