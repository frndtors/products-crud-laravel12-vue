// Tipos para las respuestas de API y requests

export interface ApiResponse<T = any> {
    data: T;
    message?: string;
    status: 'success' | 'error';
}

export interface ValidationError {
    message: string;
    errors: Record<string, string[]>;
}

export interface ApiError extends Error {
    status: number;
    response?: {
        data: ValidationError;
    };
}

// Request types para el backend
export interface ProductCreateRequest {
    name: string;
    price: number;
    stock: number;
    description?: string;
}

export interface ProductUpdateRequest extends ProductCreateRequest {
    id: number;
}

export interface ProductSearchRequest {
    search?: string;
    per_page?: number;
    page?: number;
    sort_by?: 'name' | 'price' | 'stock' | 'created_at';
    sort_direction?: 'asc' | 'desc';
}

// Response types del backend
export interface ProductResponse {
    id: number;
    name: string;
    price: number;
    stock: number;
    description: string | null;
    created_at: string;
    updated_at: string;
}

export interface ProductsIndexResponse {
    products: PaginatedProducts;
    search?: string;
    perPage: number;
}

export interface DashboardResponse {
    stats: DashboardStats;
    recent_products: Product[];
    low_stock_products: Product[];
    out_of_stock_products: Product[];
    stock_distribution: StockDistribution;
    error?: string;
}

// Import types needed
import type { PaginatedProducts, DashboardStats, Product, StockDistribution } from './index';
