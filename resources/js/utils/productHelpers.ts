import type { Product, StockStatus } from '@/types';

export const ProductHelpers = {
    /**
     * Determina el estado del stock basado en la cantidad
     */
    getStockStatus(stock: number): StockStatus {
        if (stock === 0) return 'out_of_stock';
        if (stock <= 5) return 'low_stock';
        return 'in_stock';
    },

    /**
     * Obtiene la variante del badge basada en el stock
     */
    getStockBadgeVariant(stock: number): 'default' | 'secondary' | 'destructive' {
        const status = this.getStockStatus(stock);
        const variants = {
            'in_stock': 'default' as const,
            'low_stock': 'secondary' as const,
            'out_of_stock': 'destructive' as const,
        };
        return variants[status];
    },

    /**
     * Formatea el precio con el símbolo de moneda
     */
    formatPrice(price: number, currency = 'L'): string {
        return `${currency}${Number(price).toFixed(2)}`;
    },

    /**
     * Formatea la fecha en formato legible
     */
    formatDate(date: string, locale = 'es-ES'): string {
        return new Date(date).toLocaleDateString(locale);
    },

    /**
     * Filtra productos por estado de stock
     */
    filterByStockStatus(products: Product[], status: StockStatus): Product[] {
        return products.filter(product => this.getStockStatus(product.stock) === status);
    },

    /**
     * Calcula el valor total del inventario de productos
     */
    calculateTotalValue(products: Product[]): number {
        return products.reduce((total, product) => total + (product.price * product.stock), 0);
    },

    /**
     * Obtiene el precio promedio de los productos
     */
    getAveragePrice(products: Product[]): number {
        if (products.length === 0) return 0;
        const total = products.reduce((sum, product) => sum + product.price, 0);
        return total / products.length;
    },

    /**
     * Busca productos por nombre
     */
    searchByName(products: Product[], searchTerm: string): Product[] {
        if (!searchTerm.trim()) return products;
        const term = searchTerm.toLowerCase();
        return products.filter(product =>
            product.name.toLowerCase().includes(term) ||
            (product.description && product.description.toLowerCase().includes(term))
        );
    },
};
