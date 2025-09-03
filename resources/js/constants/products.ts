// Constantes relacionadas con productos

export const PRODUCT_CONSTANTS = {
    // Límites de stock
    STOCK_LIMITS: {
        LOW_STOCK_THRESHOLD: 5,
        OUT_OF_STOCK: 0,
        MIN_STOCK: 0,
        MAX_STOCK: 999999,
    },

    // Límites de precio
    PRICE_LIMITS: {
        MIN_PRICE: 0.01,
        MAX_PRICE: 999999.99,
        DECIMAL_PLACES: 2,
    },

    // Límites de texto
    TEXT_LIMITS: {
        NAME_MIN_LENGTH: 2,
        NAME_MAX_LENGTH: 255,
        DESCRIPTION_MAX_LENGTH: 1000,
    },

    // Paginación
    PAGINATION: {
        DEFAULT_PER_PAGE: 10,
        MAX_PER_PAGE: 100,
        MIN_PER_PAGE: 5,
    },

    // Búsqueda
    SEARCH: {
        MIN_SEARCH_LENGTH: 1,
        DEBOUNCE_DELAY: 300,
    },

    // Estados de stock
    STOCK_STATUS: {
        IN_STOCK: 'in_stock',
        LOW_STOCK: 'low_stock',
        OUT_OF_STOCK: 'out_of_stock',
    } as const,

    // Variantes de badges
    BADGE_VARIANTS: {
        DEFAULT: 'default',
        SECONDARY: 'secondary',
        DESTRUCTIVE: 'destructive',
    } as const,

    // Mensajes
    MESSAGES: {
        DELETE_CONFIRM: '¿Estás seguro de que quieres eliminar este producto?',
        DELETE_SUCCESS: 'Producto eliminado exitosamente',
        CREATE_SUCCESS: 'Producto creado exitosamente',
        UPDATE_SUCCESS: 'Producto actualizado exitosamente',
        SEARCH_NO_RESULTS: 'No se encontraron productos que coincidan con tu búsqueda',
        EMPTY_INVENTORY: 'No tienes productos en tu inventario',
        LOADING: 'Cargando...',
        SEARCHING: 'Buscando...',
    },

    // Rutas
    ROUTES: {
        INDEX: '/products',
        CREATE: '/products/create',
        SHOW: (id: number) => `/products/${id}`,
        EDIT: (id: number) => `/products/${id}/edit`,
        DELETE: (id: number) => `/products/${id}`,
    },
} as const;

// Tipos derivados de las constantes
export type StockStatus = typeof PRODUCT_CONSTANTS.STOCK_STATUS[keyof typeof PRODUCT_CONSTANTS.STOCK_STATUS];
export type BadgeVariant = typeof PRODUCT_CONSTANTS.BADGE_VARIANTS[keyof typeof PRODUCT_CONSTANTS.BADGE_VARIANTS];
