export interface Product {
    id: number;
    name: string;
    price: number;
    stock: number;
    description?: string;
    created_at: string;
    updated_at: string;
}

export interface PaginationLink {
    url: string | null;
    label: string;
    active: boolean;
}

export interface PaginatedProducts {
    data: Product[];
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
    links: PaginationLink[];
}

export interface ProductFormData {
    name: string;
    price: string;
    stock: string;
    description: string;
}

export interface ProductCreateRequest extends Omit<ProductFormData, 'price' | 'stock'> {
    price: number;
    stock: number;
}

export interface ProductUpdateRequest extends ProductCreateRequest {
    id: number;
}

export type StockStatus = 'in_stock' | 'low_stock' | 'out_of_stock';

export interface StockBadgeVariant {
    variant: 'default' | 'secondary' | 'destructive';
}
