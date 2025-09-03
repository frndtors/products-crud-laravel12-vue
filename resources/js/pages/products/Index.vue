<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import { Search, Plus, Eye, Edit, Trash2 } from 'lucide-vue-next';

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
const perPageValue = ref(props.perPage.toString());

const breadcrumbItems = [
    { title: 'Products', href: '/products' }
];

const formatPrice = (price: number) => `$${price.toFixed(2)}`;

const getStockBadgeVariant = (stock: number) => {
    if (stock === 0) return 'destructive';
    if (stock <= 5) return 'secondary';
    return 'default';
};

const handleSearch = () => {
    router.get('/products', {
        search: searchValue.value,
        per_page: perPageValue.value,
    }, {
        preserveState: true,
        replace: true,
    });
};

const clearSearch = () => {
    searchValue.value = '';
    router.get('/products', {
        per_page: perPageValue.value,
    }, {
        preserveState: true,
        replace: true,
    });
};

const deleteProduct = (id: number) => {
    if (confirm('Are you sure you want to delete this product?')) {
        router.delete(`/products/${id}`);
    }
};
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbItems">
        <Head title="Products" />

        <div class="flex flex-1 flex-col gap-4 p-4">
            <!-- Header -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div>
                    <h1 class="text-3xl font-bold tracking-tight">Products</h1>
                    <p class="text-muted-foreground">Manage your product inventory</p>
                </div>
                <Link href="/products/create">
                    <Button>
                        <Plus class="mr-2 h-4 w-4" />
                        Add Product
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
                                    placeholder="Search products by name..."
                                    class="pl-10"
                                    @keyup.enter="handleSearch"
                                />
                            </div>
                        </div>
                        <div class="flex gap-2">
                            <Button @click="handleSearch">Search</Button>
                            <Button v-if="props.search" variant="outline" @click="clearSearch">
                                Clear
                            </Button>
                        </div>
                    </div>
                </CardContent>
            </Card>

            <!-- Products List -->
            <Card>
                <CardHeader>
                    <CardTitle>Product List</CardTitle>
                    <CardDescription>
                        Showing {{ products.data.length }} of {{ products.total }} products
                    </CardDescription>
                </CardHeader>
                <CardContent>
                    <div v-if="products.data.length > 0" class="space-y-4">
                        <!-- Product Cards -->
                        <div class="grid gap-4">
                            <div v-for="product in products.data" :key="product.id"
                                 class="flex items-center justify-between p-4 border rounded-lg hover:bg-gray-50 dark:hover:bg-gray-800">
                                <div class="flex-1">
                                    <div class="flex items-center gap-4">
                                        <div class="flex-1">
                                            <h3 class="font-medium">{{ product.name }}</h3>
                                            <p v-if="product.description" class="text-sm text-muted-foreground truncate">
                                                {{ product.description }}
                                            </p>
                                        </div>
                                        <div class="text-right">
                                            <div class="font-medium">{{ formatPrice(product.price) }}</div>
                                            <Badge :variant="getStockBadgeVariant(product.stock)" class="mt-1">
                                                {{ product.stock }} units
                                            </Badge>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex gap-2 ml-4">
                                    <Link :href="`/products/${product.id}`">
                                        <Button variant="ghost" size="sm">
                                            <Eye class="h-4 w-4" />
                                        </Button>
                                    </Link>
                                    <Link :href="`/products/${product.id}/edit`">
                                        <Button variant="ghost" size="sm">
                                            <Edit class="h-4 w-4" />
                                        </Button>
                                    </Link>
                                    <Button variant="ghost" size="sm" @click="deleteProduct(product.id)">
                                        <Trash2 class="h-4 w-4" />
                                    </Button>
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
                                            'px-3 py-2 text-sm rounded-md',
                                            link.active
                                                ? 'bg-primary text-primary-foreground'
                                                : 'hover:bg-muted'
                                        ]"
                                        v-html="link.label"
                                    />
                                    <span
                                        v-else
                                        :class="[
                                            'px-3 py-2 text-sm rounded-md text-muted-foreground',
                                            link.active ? 'bg-primary text-primary-foreground' : ''
                                        ]"
                                        v-html="link.label"
                                    />
                                </template>
                            </div>
                        </div>
                    </div>

                    <!-- Empty State -->
                    <div v-else class="text-center py-12">
                        <div class="mx-auto h-12 w-12 text-muted-foreground mb-4">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2M4 13h2m13-8V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v1M7 8h2m4 0h2"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium mb-2">No products found</h3>
                        <p class="text-muted-foreground mb-6">
                            {{ props.search ? 'Try adjusting your search criteria.' : 'Get started by creating your first product.' }}
                        </p>
                        <Link v-if="!props.search" href="/products/create">
                            <Button>
                                <Plus class="mr-2 h-4 w-4" />
                                Add Product
                            </Button>
                        </Link>
                    </div>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>
