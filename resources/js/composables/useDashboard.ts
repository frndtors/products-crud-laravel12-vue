import { computed } from 'vue';
import type { DashboardStats, StockDistribution } from '@/types';

export function useDashboard() {
    const formatCurrency = (amount: number): string => `L${Number(amount).toFixed(2)}`;

    const getStockStatusColor = (status: 'in_stock' | 'low_stock' | 'out_of_stock') => {
        const colors = {
            in_stock: 'text-green-600 dark:text-green-400',
            low_stock: 'text-amber-600 dark:text-amber-400',
            out_of_stock: 'text-red-600 dark:text-red-400'
        };
        return colors[status];
    };

    const getStockStatusText = (status: 'in_stock' | 'low_stock' | 'out_of_stock') => {
        const texts = {
            in_stock: 'En Stock',
            low_stock: 'Stock Bajo',
            out_of_stock: 'Sin Stock'
        };
        return texts[status];
    };

    const calculateStockPercentage = (stats: DashboardStats) => {
        const total = stats.total_products;
        if (total === 0) return { in_stock: 0, low_stock: 0, out_of_stock: 0 };

        return {
            in_stock: Math.round((stats.in_stock_count / total) * 100),
            low_stock: Math.round((stats.low_stock_count / total) * 100),
            out_of_stock: Math.round((stats.out_of_stock_count / total) * 100),
        };
    };

    const getInventoryHealthStatus = (stats: DashboardStats) => {
        const total = stats.total_products;
        if (total === 0) return 'empty';

        const outOfStockPercentage = (stats.out_of_stock_count / total) * 100;
        const lowStockPercentage = (stats.low_stock_count / total) * 100;

        if (outOfStockPercentage > 20) return 'critical';
        if (outOfStockPercentage > 10 || lowStockPercentage > 30) return 'warning';
        return 'good';
    };

    return {
        formatCurrency,
        getStockStatusColor,
        getStockStatusText,
        calculateStockPercentage,
        getInventoryHealthStatus,
    };
}
