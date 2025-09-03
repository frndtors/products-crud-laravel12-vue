import type { Product } from './product';

export interface DashboardStats {
    total_products: number;
    in_stock_count: number;
    low_stock_count: number;
    out_of_stock_count: number;
    total_inventory_value: number;
    average_product_price: number;
}

export interface StockDistribution {
    in_stock: number;
    low_stock: number;
    out_of_stock: number;
}

export interface DashboardData {
    stats: DashboardStats;
    recent_products: Product[];
    low_stock_products: Product[];
    out_of_stock_products: Product[];
    stock_distribution: StockDistribution;
    error?: string;
}
