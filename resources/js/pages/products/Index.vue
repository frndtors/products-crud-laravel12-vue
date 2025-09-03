<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import { Search, Plus, Eye, Edit, Trash2, Package } from 'lucide-vue-next';
import { debounce } from 'lodash-es';

import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
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

interface PaginationLink {
    url: string | null;
    label: string;
    active: boolean;
}

interface PaginatedProducts {
    data: Product[];
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
    links: PaginationLink[];
}

interface Props {
    products: PaginatedProducts;
    search?: string;
    perPage: number;
}

const props = defineProps<Props>();

const searchValue = ref(props.search || '');
const isSearching = ref(false);

const breadcrumbItems = [
    { title: 'Productos', href: '/products' }
];

const formatPrice = (price: number) => `L${Number(price).toFixed(2)}`;

const getStockBadgeVariant = (stock: number) => {
    if (stock === 0) return 'destructive';
    if (stock <= 5) return 'secondary';
    return 'default';
};

// Función de búsqueda inmediata (sin debounce)
const handleSearch = () => {
    isSearching.value = true;
    router.get('/products', {
        search: searchValue.value || undefined,
        per_page: props.perPage,
    }, {
        preserveState: true,
        replace: true,
        onFinish: () => {
            isSearching.value = false;
        }
    });
};

// Función de búsqueda con debounce para escribir
const debouncedSearch = debounce(() => {
    if (searchValue.value !== props.search) {
        handleSearch();
    }
}, 300);

// Función para limpiar búsqueda
const clearSearch = () => {
    searchValue.value = '';
    isSearching.value = true;
    router.get('/products', {
        per_page: props.perPage,
    }, {
        preserveState: true,
        replace: true,
        onFinish: () => {
            isSearching.value = false;
        }
    });
};

// Watcher para búsqueda en tiempo real
watch(searchValue, (newValue) => {
    // Si el valor cambió y no es igual al search actual de props
    if (newValue !== props.search) {
        debouncedSearch();
    }
});

const deleteProduct = (id: number) => {
    if (confirm('¿Estás seguro de que quieres eliminar este producto?')) {
        router.delete(`/products/${id}`);
    }
};
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbItems">
        <Head title="Productos" />

        <div class="flex flex-1 flex-col gap-4 px-12 py-10">
            <!-- Header -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div>
                    <h1 class="text-3xl font-bold tracking-tight">Productos</h1>
                    <p class="text-muted-foreground">Gestiona tu inventario de productos</p>
                </div>
                <Link href="/products/create" v-if="products?.data?.length > 0">
                    <Button>
                        <Plus class="mr-2 h-4 w-4" />
                        Añadir Producto
                    </Button>
                </Link>
            </div>

            <!-- Search and Filters -->
            <Card>
                <CardContent class="p-6">
                    <div class="flex flex-col md:flex-row gap-4">
                        <div class="flex-1">
                            <div class="relative">
                                <Search class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground" />
                                <Input
                                    v-model="searchValue"
                                    placeholder="Buscar productos por nombre..."
                                    class="pl-10"
                                    :class="{ 'opacity-75': isSearching }"
                                    @keyup.enter="handleSearch"
                                />
                                <!-- Loading indicator -->
                                <div v-if="isSearching" class="absolute right-3 top-1/2 -translate-y-1/2">
                                    <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-foreground"></div>
                                </div>
                            </div>
                            <p class="text-xs text-muted-foreground mt-1">
                                Escribe para buscar en tiempo real o presiona Enter
                            </p>
                        </div>
                        <div class="flex gap-2">
                            <Button @click="handleSearch" :disabled="isSearching">
                                {{ isSearching ? 'Buscando...' : 'Buscar' }}
                            </Button>
                            <Button v-if="props.search" variant="outline" @click="clearSearch" :disabled="isSearching">
                                Limpiar
                            </Button>
                        </div>
                    </div>
                </CardContent>
            </Card>

            <!-- Products List -->
            <Card>
                <CardHeader v-if="products?.data?.length > 0">
                    <CardTitle>Lista de Productos</CardTitle>
                    <CardDescription>
                        Mostrando {{ products?.data?.length || 0 }} de {{ products?.total || 0 }} productos
                        <span v-if="props.search" class="font-medium">
                            para "{{ props.search }}"
                        </span>
                    </CardDescription>
                </CardHeader>
                <CardContent>
                    <div v-if="products?.data?.length > 0" class="space-y-4">
                        <!-- Products Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            <div
                                v-for="product in products.data"
                                :key="product.id"
                                class="border rounded-lg p-4 hover:shadow-md transition-all duration-200 bg-card hover:bg-muted/50"
                                :class="{ 'opacity-75': isSearching }"
                            >
                                <div class="space-y-3">
                                    <h3 class="font-semibold text-lg truncate text-card-foreground">{{ product.name }}</h3>
                                    <p class="text-2xl font-bold text-green-600 dark:text-green-400">{{ formatPrice(product.price) }}</p>
                                    <div class="flex items-center gap-2">
                                        <Badge :variant="getStockBadgeVariant(product.stock)">
                                            {{ product.stock }} uds
                                        </Badge>
                                    </div>
                                    <p v-if="product.description" class="text-sm text-muted-foreground line-clamp-2">
                                        {{ product.description }}
                                    </p>
                                    <div class="flex gap-2 mt-4">
                                        <Link :href="`/products/${product.id}`">
                                            <Button variant="outline" size="sm" class="flex-1">
                                                <Eye class="h-4 w-4 mr-1" />
                                                Ver
                                            </Button>
                                        </Link>
                                        <Link :href="`/products/${product.id}/edit`">
                                            <Button variant="outline" size="sm" class="flex-1">
                                                <Edit class="h-4 w-4 mr-1" />
                                                Editar
                                            </Button>
                                        </Link>
                                        <Button
                                            variant="outline"
                                            size="sm"
                                            @click="deleteProduct(product.id)"
                                            class="text-red-600 hover:text-red-700 hover:bg-red-50 dark:text-red-400 dark:hover:text-red-300 dark:hover:bg-red-950/20"
                                        >
                                            <Trash2 class="h-4 w-4" />
                                        </Button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Pagination -->
                        <div v-if="products.last_page > 1" class="flex justify-center mt-6">
                            <div class="flex gap-2">
                                <template v-for="link in products.links" :key="link.label">
                                    <Link
                                        v-if="link.url"
                                        :href="link.url"
                                        :class="[
                                            'px-3 py-2 text-sm rounded-md border transition-colors',
                                            link.active
                                                ? 'bg-primary text-primary-foreground border-primary'
                                                : 'hover:bg-muted border-border'
                                        ]"
                                        v-html="link.label"
                                    />
                                    <span
                                        v-else
                                        :class="[
                                            'px-3 py-2 text-sm rounded-md border text-muted-foreground border-border',
                                            link.active ? 'bg-primary text-primary-foreground border-primary' : ''
                                        ]"
                                        v-html="link.label"
                                    />
                                </template>
                            </div>
                        </div>
                    </div>

                    <!-- Empty State -->
                    <div v-else class="text-center py-12">
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
                        <Button v-else variant="outline" @click="clearSearch">
                            <Search class="mr-2 h-4 w-4" />
                            Ver todos los productos
                        </Button>
                    </div>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>
