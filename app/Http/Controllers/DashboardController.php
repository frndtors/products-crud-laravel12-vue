<?php

namespace App\Http\Controllers;

use App\Services\ProductService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function __construct(
        private readonly ProductService $productService
    ) {}

    /**
     * Display the dashboard with product statistics and insights.
     */
    public function index(Request $request): Response
    {
        try {
            $stats = $this->productService->getProductStats();
            $recentProducts = $this->productService->getProductsPaginated(5); // Last 5 products

            $dashboardData = [
                'stats' => [
                    'total_products' => $stats['total_products'],
                    'in_stock_count' => $stats['in_stock_products']->count(),
                    'low_stock_count' => $stats['low_stock_products']->count(),
                    'out_of_stock_count' => $stats['out_of_stock_products']->count(),
                    'total_inventory_value' => $this->calculateTotalInventoryValue($stats),
                    'average_product_price' => $this->calculateAverageProductPrice($stats),
                ],
                'recent_products' => $recentProducts->take(5),
                'low_stock_products' => $stats['low_stock_products']->take(5),
                'out_of_stock_products' => $stats['out_of_stock_products']->take(5),
                'stock_distribution' => $this->getStockDistribution($stats),
            ];

            return Inertia::render('dashboard/Index', $dashboardData);
        } catch (\Exception $e) {
            \Log::error('Failed to load dashboard data', [
                'error' => $e->getMessage()
            ]);

            return Inertia::render('dashboard/Index', [
                'stats' => [
                    'total_products' => 0,
                    'in_stock_count' => 0,
                    'low_stock_count' => 0,
                    'out_of_stock_count' => 0,
                    'total_inventory_value' => 0,
                    'average_product_price' => 0,
                ],
                'recent_products' => collect([]),
                'low_stock_products' => collect([]),
                'out_of_stock_products' => collect([]),
                'stock_distribution' => [],
                'error' => 'Error al cargar los datos del dashboard.',
            ]);
        }
    }

    /**
     * Calcular el valor total del inventario
     */
    private function calculateTotalInventoryValue(array $stats): float
    {
        $allProducts = $this->productService->getAllProducts();
        return $allProducts->sum(fn($product) => $product->price * $product->stock);
    }

    /**
     * Calcular el precio promedio de los productos
     */
    private function calculateAverageProductPrice(array $stats): float
    {
        $allProducts = $this->productService->getAllProducts();
        return $allProducts->count() > 0 ? $allProducts->avg('price') : 0;
    }

    /**
     * Obtener la distribución de stock
     */
    private function getStockDistribution(array $stats): array
    {
        return [
            'in_stock' => $stats['in_stock_products']->count(),
            'low_stock' => $stats['low_stock_products']->count(),
            'out_of_stock' => $stats['out_of_stock_products']->count(),
        ];
    }
}
