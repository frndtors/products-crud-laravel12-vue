import { ref, computed } from 'vue';
import { router } from '@inertiajs/vue3';
import { debounce } from 'lodash-es';
import { ProductHelpers } from '@/utils/productHelpers';
import type { Product, PaginatedProducts, SearchProps } from '@/types';

export function useProducts(initialSearch?: string, initialPerPage = 10) {
    const searchValue = ref(initialSearch || '');
    const isSearching = ref(false);
    const perPage = ref(initialPerPage);

    // Usar las utilidades centralizadas
    const { formatPrice, formatDate, getStockBadgeVariant } = ProductHelpers;

    const handleSearch = (search?: string) => {
        isSearching.value = true;
        const searchTerm = search !== undefined ? search : searchValue.value;

        router.get('/products', {
            search: searchTerm || undefined,
            per_page: perPage.value,
        }, {
            preserveState: true,
            replace: true,
            onFinish: () => {
                isSearching.value = false;
            }
        });
    };

    // Búsqueda con debounce
    const debouncedSearch = debounce(() => {
        handleSearch();
    }, 300);

    const clearSearch = () => {
        searchValue.value = '';
        handleSearch('');
    };

    const deleteProduct = (id: number, confirmMessage?: string) => {
        const message = confirmMessage || '¿Estás seguro de que quieres eliminar este producto?';
        if (confirm(message)) {
            router.delete(`/products/${id}`);
        }
    };

    // Computed properties útiles
    const hasSearch = computed(() => searchValue.value.trim().length > 0);
    const searchPlaceholder = computed(() => 'Buscar productos por nombre...');

    // Métodos de navegación
    const goToProduct = (id: number) => {
        router.visit(`/products/${id}`);
    };

    const goToEdit = (id: number) => {
        router.visit(`/products/${id}/edit`);
    };

    const goToCreate = () => {
        router.visit('/products/create');
    };

    const goToIndex = () => {
        router.visit('/products');
    };

    return {
        // State
        searchValue,
        isSearching,
        perPage,

        // Computed
        hasSearch,
        searchPlaceholder,

        // Methods from ProductHelpers
        formatPrice,
        formatDate,
        getStockBadgeVariant,

        // Search methods
        handleSearch,
        debouncedSearch,
        clearSearch,

        // CRUD methods
        deleteProduct,

        // Navigation methods
        goToProduct,
        goToEdit,
        goToCreate,
        goToIndex,
    };
}
