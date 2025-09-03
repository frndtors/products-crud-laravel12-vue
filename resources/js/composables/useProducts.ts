import { ref, computed } from 'vue';
import { router } from '@inertiajs/vue3';
import { debounce } from 'lodash-es';
import { ProductHelpers } from '@/utils/productHelpers';
import type { Product, PaginatedProducts } from '@/types';

export function useProducts(initialSearch?: string, initialPerPage = 10) {
    const searchValue = ref(initialSearch || '');
    const isSearching = ref(false);
    const perPage = ref(initialPerPage);

    // Crear funciones que usen ProductHelpers correctamente
    const formatPrice = (price: number): string => ProductHelpers.formatPrice(price);
    const formatDate = (date: string): string => ProductHelpers.formatDate(date);
    const getStockBadgeVariant = (stock: number) => ProductHelpers.getStockBadgeVariant(stock);

    const handleSearch = (search?: string) => {
        isSearching.value = true;

        // Obtener el término de búsqueda limpio
        let searchTerm: string | undefined;
        if (search !== undefined) {
            searchTerm = search;
        } else {
            searchTerm = searchValue.value;
        }

        // Limpiar y validar el término de búsqueda
        searchTerm = searchTerm && typeof searchTerm === 'string' ? searchTerm.trim() : '';

        // Crear objeto de parámetros limpio
        const params: Record<string, string | number> = {
            per_page: perPage.value,
        };

        // Solo agregar search si tiene valor válido
        if (searchTerm && searchTerm.length > 0) {
            params.search = searchTerm;
        }

        router.get('/products', params, {
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

        // Helper methods
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
