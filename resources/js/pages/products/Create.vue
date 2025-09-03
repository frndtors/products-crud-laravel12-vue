<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ArrowLeft, Check } from 'lucide-vue-next';

import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';

const breadcrumbItems = [
    { title: 'Products', href: '/products' },
    { title: 'Create Product', href: '/products/create' }
];

const form = useForm({
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
        <Head title="Create Product" />

        <div class="flex flex-1 flex-col gap-4 p-4 max-w-2xl mx-auto">
            <!-- Header -->
            <div class="flex items-center gap-4">
                <Link href="/products">
                    <Button variant="ghost" size="sm">
                        <ArrowLeft class="h-4 w-4" />
                    </Button>
                </Link>
                <div>
                    <h1 class="text-3xl font-bold tracking-tight">Create Product</h1>
                    <p class="text-muted-foreground">Add a new product to your inventory</p>
                </div>
            </div>

            <!-- Form -->
            <Card>
                <CardHeader>
                    <CardTitle>Product Information</CardTitle>
                    <CardDescription>
                        Fill in the details for your new product
                    </CardDescription>
                </CardHeader>
                <CardContent>
                    <form @submit.prevent="submit" class="space-y-6">
                        <!-- Product Name -->
                        <div class="space-y-2">
                            <Label for="name">Product Name <span class="text-destructive">*</span></Label>
                            <Input
                                id="name"
                                v-model="form.name"
                                placeholder="Enter product name"
                                :class="{ 'border-destructive': form.errors.name }"
                                required
                            />
                            <div v-if="form.errors.name" class="text-sm text-destructive">
                                {{ form.errors.name }}
                            </div>
                        </div>

                        <!-- Price and Stock -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Price -->
                            <div class="space-y-2">
                                <Label for="price">Price <span class="text-destructive">*</span></Label>
                                <div class="relative">
                                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-muted-foreground">$</span>
                                    <Input
                                        id="price"
                                        v-model="form.price"
                                        type="number"
                                        step="0.01"
                                        min="0.01"
                                        placeholder="0.00"
                                        class="pl-8"
                                        :class="{ 'border-destructive': form.errors.price }"
                                        required
                                    />
                                </div>
                                <div v-if="form.errors.price" class="text-sm text-destructive">
                                    {{ form.errors.price }}
                                </div>
                            </div>

                            <!-- Stock -->
                            <div class="space-y-2">
                                <Label for="stock">Stock Quantity <span class="text-destructive">*</span></Label>
                                <Input
                                    id="stock"
                                    v-model="form.stock"
                                    type="number"
                                    min="0"
                                    placeholder="0"
                                    :class="{ 'border-destructive': form.errors.stock }"
                                    required
                                />
                                <div v-if="form.errors.stock" class="text-sm text-destructive">
                                    {{ form.errors.stock }}
                                </div>
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="space-y-2">
                            <Label for="description">Description</Label>
                            <Textarea
                                id="description"
                                v-model="form.description"
                                placeholder="Enter product description (optional)"
                                rows="4"
                                :class="{ 'border-destructive': form.errors.description }"
                            />
                            <div v-if="form.errors.description" class="text-sm text-destructive">
                                {{ form.errors.description }}
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="flex flex-col sm:flex-row gap-3 pt-6 border-t">
                            <Button type="submit" :disabled="form.processing" class="flex-1 sm:flex-none">
                                <Check v-if="!form.processing" class="mr-2 h-4 w-4" />
                                <span v-if="form.processing">Creating...</span>
                                <span v-else>Create Product</span>
                            </Button>
                            <Link href="/products">
                                <Button variant="outline" class="w-full sm:w-auto">
                                    Cancel
                                </Button>
                            </Link>
                        </div>
                    </form>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>
