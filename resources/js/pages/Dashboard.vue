<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';
import {
    Package,
    DollarSign,
    TrendingUp,
    AlertTriangle,
    CheckCircle,
    XCircle,
    Eye,
    Plus,
    BarChart3
} from 'lucide-vue-next';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';

interface Product {
    id: number;
    name: string;
    price: number;
    stock: number;
    description?: string;
    created_at: string;
    updated_at: string;
}

interface DashboardStats {
    total_products: number;
    in_stock_count: number;
    low_stock_count: number;
    out_of_stock_count: number;
    total_inventory_value: number;
    average_product_price: number;
}

interface StockDistribution {
    in_stock: number;
    low_stock: number;
    out_of_stock: number;
}

interface Props {
    stats: DashboardStats;
    recent_products: Product[];
    low_stock_products: Product[];
    out_of_stock_products: Product[];
    stock_distribution: StockDistribution;
    error?: string;
}

const props = defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Tablero',
        href: dashboard().url,
    },
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
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 px-12 py-10">
            <!-- Welcome Header -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div>
                    <h1 class="text-3xl font-bold tracking-tight">Tablero</h1>
                    <p class="text-muted-foreground">Resumen de tu inventario de productos</p>
                </div>
                <Link href="/products/create">
                    <Button>
                        <Plus class="mr-2 h-4 w-4" /> Añadir Producto
                    </Button>
                </Link>
            </div>

            <!-- Error Message -->
            <div v-if="error" class="bg-red-50 border border-red-200 rounded-lg p-4 text-red-700">
                {{ error }}
            </div>

            <!-- Stats Grid -->
            <div class="grid auto-rows-min gap-4 md:grid-cols-2 lg:grid-cols-4">
                <!-- Total Products -->
                <Card>
                    <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                        <CardTitle class="text-sm font-medium">Total de Productos</CardTitle>
                        <Package class="h-4 w-4 text-muted-foreground" />
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold">{{ stats.total_products }}</div>
                        <p class="text-xs text-muted-foreground">
                            Productos en su inventario
                        </p>
                    </CardContent>
                </Card>

                <!-- Inventory Value -->
                <Card>
                    <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                        <CardTitle class="text-sm font-medium">Valor del Inventario</CardTitle>
                        <DollarSign class="h-4 w-4 text-muted-foreground" />
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold">{{ formatPrice(stats.total_inventory_value) }}</div>
                        <p class="text-xs text-muted-foreground">
                            Valor total de todos los productos
                        </p>
                    </CardContent>
                </Card>

                <!-- Average Price -->
                <Card>
                    <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                        <CardTitle class="text-sm font-medium">Precio Promedio</CardTitle>
                        <TrendingUp class="h-4 w-4 text-muted-foreground" />
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold">{{ formatPrice(stats.average_product_price) }}</div>
                        <p class="text-xs text-muted-foreground">
                            Precio promedio del producto
                        </p>
                    </CardContent>
                </Card>

                <!-- Stock Status -->
                <Card>
                    <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                        <CardTitle class="text-sm font-medium">Estado del inventario</CardTitle>
                        <BarChart3 class="h-4 w-4 text-muted-foreground" />
                    </CardHeader>
                    <CardContent>
                        <div class="flex gap-2">
                            <Badge variant="default">{{ stats.in_stock_count }} uds</Badge>
                            <Badge variant="secondary">{{ stats.low_stock_count }} bajo</Badge>
                            <Badge variant="destructive">{{ stats.out_of_stock_count }} agotado</Badge>
                        </div>
                    </CardContent>
                </Card>
            </div>

            <!-- Main Content Grid -->
            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                <!-- Recent Products -->
                <Card class="lg:col-span-2">
                    <CardHeader v-if="recent_products.length > 0">
                        <div class="flex items-center justify-between">
                            <div>
                                <CardTitle class="flex items-center gap-2">
                                    <Package class="h-5 w-5" />
                                    Productos Recientes
                                </CardTitle>
                                <CardDescription>Últimos productos añadidos a tu inventario</CardDescription>
                            </div>
                            <Link href="/products">
                                <Button variant="outline" size="sm">Ver Todo</Button>
                            </Link>
                        </div>
                    </CardHeader>
                    <CardContent>
                        <div v-if="recent_products.length > 0" class="space-y-4">
                            <div
                                v-for="product in recent_products"
                                :key="product.id"
                                class="flex items-center justify-between p-3 border rounded-lg hover:bg-muted/50 transition-colors"
                            >
                                <div class="flex-1">
                                    <h4 class="font-medium">{{ product.name }}</h4>
                                    <p class="text-sm text-muted-foreground">{{ formatDate(product.created_at) }}</p>
                                </div>
                                <div class="flex items-center gap-3">
                                    <div class="text-right">
                                        <div class="font-medium">{{ formatPrice(product.price) }}</div>
                                        <Badge :variant="getStockBadgeVariant(product.stock)" class="text-xs">
                                            {{ product.stock }} uds
                                        </Badge>
                                    </div>
                                    <Link :href="`/products/${product.id}`">
                                        <Button variant="ghost" size="sm">
                                            <Eye class="h-4 w-4" />
                                        </Button>
                                    </Link>
                                </div>
                            </div>
                        </div>
                        <div v-else class="text-center py-8 text-muted-foreground">
                            <div class="mx-auto h-12 w-12 text-muted-foreground mb-4 flex justify-center">
                                <Package class="h-12 w-12" />
                            </div>
                            <h3 class="text-lg font-medium mb-2">No se encontraron productos</h3>
                            <p class="text-muted-foreground mb-6">
                                {{ props.search ? 'Prueba modificando tus criterios de búsqueda.' : 'Comienza creando tu primer producto.' }}
                            </p>
                            <Link v-if="!props.search" href="/products/create">
                                <Button>
                                    <Plus class="mr-2 h-4 w-4" />
                                    Añadir Producto
                                </Button>
                            </Link>
                        </div>
                    </CardContent>
                </Card>

                <!-- Alerts Section -->
                <div class="space-y-6">
                    <!-- Low Stock Alert -->
                    <Card v-if="low_stock_products.length > 0">
                        <CardHeader>
                            <CardTitle class="flex items-center gap-2 text-amber-600">
                                <AlertTriangle class="h-5 w-5" />
                                Alerta de Stock Bajo
                            </CardTitle>
                            <CardDescription>Productos con poco stock</CardDescription>
                        </CardHeader>
                        <CardContent>
                            <div class="space-y-2">
                                <div
                                    v-for="product in low_stock_products"
                                    :key="product.id"
                                    class="flex items-center justify-between p-2 bg-amber-50 rounded border"
                                >
                                    <div>
                                        <p class="font-medium text-sm">{{ product.name }}</p>
                                        <p class="text-xs text-amber-600">Solo quedan {{ product.stock }}</p>
                                    </div>
                                    <Link :href="`/products/${product.id}/edit`">
                                        <Button variant="outline" size="sm" class="text-xs">
                                            Restock
                                        </Button>
                                    </Link>
                                </div>
                            </div>
                        </CardContent>
                    </Card>

                    <!-- Out of Stock Alert -->
                    <Card v-if="out_of_stock_products.length > 0">
                        <CardHeader>
                            <CardTitle class="flex items-center gap-2 text-red-600">
                                <XCircle class="h-5 w-5" />
                                Agotado
                            </CardTitle>
                            <CardDescription>Productos que necesitan reabastecimiento inmediato</CardDescription>
                        </CardHeader>
                        <CardContent>
                            <div class="space-y-2">
                                <div
                                    v-for="product in out_of_stock_products"
                                    :key="product.id"
                                    class="flex items-center justify-between p-2 bg-red-50 rounded border"
                                >
                                    <div>
                                        <p class="font-medium text-sm">{{ product.name }}</p>
                                        <p class="text-xs text-red-600">Agotado</p>
                                    </div>
                                    <Link :href="`/products/${product.id}/edit`">
                                        <Button variant="outline" size="sm" class="text-xs">
                                            Restock
                                        </Button>
                                    </Link>
                                </div>
                            </div>
                        </CardContent>
                    </Card>

                    <!-- Quick Actions -->
                    <Card>
                        <CardHeader>
                            <CardTitle>Acciones Rápidas</CardTitle>
                            <CardDescription>Tareas Comunes</CardDescription>
                        </CardHeader>
                        <CardContent class="space-y-2">
                            <Link href="/products/create" class="block">
                                <Button variant="outline" class="w-full justify-start">
                                    <Plus class="mr-2 h-4 w-4" />
                                    Añadir Nuevo Producto
                                </Button>
                            </Link>
                            <Link href="/products" class="block">
                                <Button variant="outline" class="w-full justify-start">
                                    <Package class="mr-2 h-4 w-4" />
                                    Ver Todos los Productos
                                </Button>
                            </Link>
                        </CardContent>
                    </Card>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
