import { PRODUCT_CONSTANTS } from '@/constants/products';

export const ProductValidators = {
    /**
     * Valida el nombre del producto
     */
    validateName(name: string): string | null {
        if (!name || name.trim().length === 0) {
            return 'El nombre del producto es requerido';
        }

        if (name.trim().length < PRODUCT_CONSTANTS.TEXT_LIMITS.NAME_MIN_LENGTH) {
            return `El nombre debe tener al menos ${PRODUCT_CONSTANTS.TEXT_LIMITS.NAME_MIN_LENGTH} caracteres`;
        }

        if (name.length > PRODUCT_CONSTANTS.TEXT_LIMITS.NAME_MAX_LENGTH) {
            return `El nombre no puede tener más de ${PRODUCT_CONSTANTS.TEXT_LIMITS.NAME_MAX_LENGTH} caracteres`;
        }

        return null;
    },

    /**
     * Valida el precio del producto
     */
    validatePrice(price: string | number): string | null {
        const numPrice = typeof price === 'string' ? parseFloat(price) : price;

        if (isNaN(numPrice)) {
            return 'El precio debe ser un número válido';
        }

        if (numPrice < PRODUCT_CONSTANTS.PRICE_LIMITS.MIN_PRICE) {
            return `El precio debe ser mayor a ${PRODUCT_CONSTANTS.PRICE_LIMITS.MIN_PRICE}`;
        }

        if (numPrice > PRODUCT_CONSTANTS.PRICE_LIMITS.MAX_PRICE) {
            return `El precio no puede ser mayor a ${PRODUCT_CONSTANTS.PRICE_LIMITS.MAX_PRICE}`;
        }

        return null;
    },

    /**
     * Valida el stock del producto
     */
    validateStock(stock: string | number): string | null {
        const numStock = typeof stock === 'string' ? parseInt(stock) : stock;

        if (isNaN(numStock)) {
            return 'El stock debe ser un número entero válido';
        }

        if (numStock < PRODUCT_CONSTANTS.STOCK_LIMITS.MIN_STOCK) {
            return `El stock no puede ser menor a ${PRODUCT_CONSTANTS.STOCK_LIMITS.MIN_STOCK}`;
        }

        if (numStock > PRODUCT_CONSTANTS.STOCK_LIMITS.MAX_STOCK) {
            return `El stock no puede ser mayor a ${PRODUCT_CONSTANTS.STOCK_LIMITS.MAX_STOCK}`;
        }

        return null;
    },

    /**
     * Valida la descripción del producto
     */
    validateDescription(description: string): string | null {
        if (description && description.length > PRODUCT_CONSTANTS.TEXT_LIMITS.DESCRIPTION_MAX_LENGTH) {
            return `La descripción no puede tener más de ${PRODUCT_CONSTANTS.TEXT_LIMITS.DESCRIPTION_MAX_LENGTH} caracteres`;
        }

        return null;
    },

    /**
     * Valida todo el formulario de producto
     */
    validateProductForm(data: {
        name: string;
        price: string | number;
        stock: string | number;
        description?: string;
    }): Record<string, string> {
        const errors: Record<string, string> = {};

        const nameError = this.validateName(data.name);
        if (nameError) errors.name = nameError;

        const priceError = this.validatePrice(data.price);
        if (priceError) errors.price = priceError;

        const stockError = this.validateStock(data.stock);
        if (stockError) errors.stock = stockError;

        if (data.description) {
            const descriptionError = this.validateDescription(data.description);
            if (descriptionError) errors.description = descriptionError;
        }

        return errors;
    },

    /**
     * Verifica si hay errores en la validación
     */
    hasErrors(errors: Record<string, string>): boolean {
        return Object.keys(errors).length > 0;
    },
};
