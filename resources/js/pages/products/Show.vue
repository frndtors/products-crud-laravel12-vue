<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { ArrowLeft, Edit, Trash2 } from 'lucide-vue-next';

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
    { title: 'Products', href: '/products' },
    { title: props.product.name, href: `/products/${props.product.id}` }
];

const formatPrice = (price: number) => `$${price.toFixed(2)}`;

const getStockBadgeVariant = (stock: number) => {
    if (stock === 0) return 'destructive';
    if (stock <= 5) return 'secondary';
    return 'default';
};

const getStockText = (stock: number) => {
    if (stock === 0) return 'Out of Stock';
    if (stock <= 5) return 'Low Stock';
    return 'In Stock';
};

const deleteProduct = () => {
    if (confirm('Are you sure you want to delete this product?')) {
        router.delete(`/products/${props.product.id}`);
    }
};

const formatDate = (dateString: string) => {
    return new Date(dateString).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
};
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbItems">
        <Head :title="product.name" />

        <div class="flex flex-1 flex-col gap-4 p-4 max-w-4xl mx-auto">
            <!-- Header -->
            <div class="flex items-center gap-4">
                <Link href="/products">
                    <Button variant="ghost" size="sm">
                        <ArrowLeft class="h-4 w-4" />
                    </Button>
                </Link>
                <div class="flex-1">
                    <h1 class="text-3xl font-bold tracking-tight">{{ product.name }}</h1>
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-3">
                    <Link :href="`/products/${product.id}/edit`">
                        <Button variant="outline">
                            <Edit class="mr-2 h-4 w-4" />
                            Edit Product
                        </Button>
                    </Link>

                    <Button variant="destructive" @click="deleteProduct">
                        <Trash2 class="mr-2 h-4 w-4" />
                        Delete Product
                    </Button>
                </div>
            </div>

            <!-- Product Details Card -->
            <Card>
                <CardHeader>
                    <CardTitle>Product Information</CardTitle>
                    <CardDescription>
                        Complete details about this product
                    </CardDescription>
                </CardHeader>
                <CardContent>
                    <!-- Product Info Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <!-- Basic Information -->
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-muted-foreground mb-1">Product Name</label>
                                <p class="text-lg font-semibold">{{ product.name }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-muted-foreground mb-1">Price</label>
                                <p class="text-2xl font-bold text-green-600 dark:text-green-400">{{ formatPrice(product.price) }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-muted-foreground mb-1">Stock Status</label>
                                <div class="flex items-center gap-2">
                                    <Badge :variant="getStockBadgeVariant(product.stock)">
                                        {{ product.stock }} units available
                                    </Badge>
                                    <span v-if="product.stock <= 5 && product.stock > 0" class="text-sm text-yellow-600 dark:text-yellow-400">
                                        ({{ getStockText(product.stock) }})
                                    </span>
                                    <span v-else-if="product.stock === 0" class="text-sm text-red-600 dark:text-red-400">
                                        ({{ getStockText(product.stock) }})
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Metadata -->
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-muted-foreground mb-1">Product ID</label>
                                <p class="text-sm font-mono">#{{ product.id }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-muted-foreground mb-1">Created</label>
                                <p class="text-sm">{{ formatDate(product.created_at) }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-muted-foreground mb-1">Last Updated</label>
                                <p class="text-sm">{{ formatDate(product.updated_at) }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Description -->
                    <div v-if="product.description" class="border-t pt-6">
                        <label class="block text-sm font-medium text-muted-foreground mb-2">Description</label>
                        <div class="prose prose-sm max-w-none dark:prose-invert">
                            <p class="whitespace-pre-wrap">{{ product.description }}</p>
                        </div>
                    </div>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>
