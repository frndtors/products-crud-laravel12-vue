<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import { ArrowLeft, Save } from 'lucide-vue-next';

import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';

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
    { title: props.product.name, href: `/products/${props.product.id}` },
    { title: 'Edit', href: `/products/${props.product.id}/edit` }
];

const form = useForm({
    name: props.product.name,
    price: props.product.price.toString(),
    stock: props.product.stock.toString(),
    description: props.product.description || '',
});

const submit = () => {
    form.put(`/products/${props.product.id}`);
};
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbItems">
        <Head :title="`Edit Product: ${product.name}`" />

        <div class="flex flex-1 flex-col gap-4 p-4">
            <!-- Header -->
            <div class="flex items-center gap-4">
                <Button variant="outline" size="sm" @click="$inertia.visit(`/products/${product.id}`)">
                    <ArrowLeft class="h-4 w-4 mr-2" />
                    Back to Product
                </Button>
                <div>
                    <h1 class="text-3xl font-bold tracking-tight">Edit Product</h1>
                    <p class="text-muted-foreground">Update product information</p>
                </div>
            </div>

            <!-- Form -->
            <div class="max-w-2xl">
                <Card>
                    <CardHeader>
                        <CardTitle>Product Information</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <form @submit.prevent="submit" class="space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="space-y-2">
                                    <Label for="name">Product Name *</Label>
                                    <Input
                                        id="name"
                                        v-model="form.name"
                                        type="text"
                                        placeholder="Enter product name"
                                        :class="{ 'border-red-500': form.errors.name }"
                                        required
                                    />
                                    <div v-if="form.errors.name" class="text-sm text-red-600">
                                        {{ form.errors.name }}
                                    </div>
                                </div>

                                <div class="space-y-2">
                                    <Label for="price">Price *</Label>
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
                                    <div v-if="form.errors.price" class="text-sm text-red-600">
                                        {{ form.errors.price }}
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-2">
                                <Label for="stock">Stock Quantity *</Label>
                                <Input
                                    id="stock"
                                    v-model="form.stock"
                                    type="number"
                                    min="0"
                                    placeholder="0"
                                    :class="{ 'border-red-500': form.errors.stock }"
                                    required
                                />
                                <div v-if="form.errors.stock" class="text-sm text-red-600">
                                    {{ form.errors.stock }}
                                </div>
                            </div>

                            <div class="space-y-2">
                                <Label for="description">Description</Label>
                                <Textarea
                                    id="description"
                                    v-model="form.description"
                                    placeholder="Enter product description (optional)"
                                    rows="4"
                                    :class="{ 'border-red-500': form.errors.description }"
                                />
                                <div v-if="form.errors.description" class="text-sm text-red-600">
                                    {{ form.errors.description }}
                                </div>
                            </div>

                            <div class="flex gap-4">
                                <Button type="submit" :disabled="form.processing">
                                    <Save class="mr-2 h-4 w-4" />
                                    {{ form.processing ? 'Updating...' : 'Update Product' }}
                                </Button>
                                <Button type="button" variant="outline" @click="$inertia.visit(`/products/${product.id}`)">
                                    Cancel
                                </Button>
                            </div>
                        </form>
                    </CardContent>
                </Card>
            </div>
        </div>
    </AppLayout>
</template>
