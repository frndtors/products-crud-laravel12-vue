<?php

namespace App\Http\Controllers;

use App\DTOs\ProductCreateDTO;
use App\DTOs\ProductUpdateDTO;
use App\Http\Requests\ProductCreateRequest;
use App\Http\Requests\ProductUpdateRequest;
use App\Services\ProductService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProductController extends Controller
{
    public function __construct(
        private readonly ProductService $productService
    ) {}

    public function index(Request $request)
    {
        // Extraer y validar parámetros de búsqueda
        $search = $request->query('search');
        $perPage = (int) $request->query('per_page', 10);

        // Asegurar que search sea string o null
        $search = is_string($search) && !empty(trim($search)) ? trim($search) : null;

        // Validar perPage
        $perPage = max(5, min(100, $perPage));

        try {
            $products = $this->productService->getProductsPaginated($perPage, $search);

            return Inertia::render('products/Index', [
                'products' => $products,
                'search' => $search,
                'perPage' => $perPage,
            ]);
        } catch (\Exception $e) {
            return Inertia::render('products/Index', [
                'products' => [
                    'data' => [],
                    'current_page' => 1,
                    'last_page' => 1,
                    'per_page' => $perPage,
                    'total' => 0,
                    'links' => [],
                ],
                'search' => $search,
                'perPage' => $perPage,
                'error' => 'Error al cargar los productos. Por favor intenta nuevamente.',
            ]);
        }
    }

    public function create()
    {
        return Inertia::render('products/Create');
    }

    public function store(ProductCreateRequest $request)
    {
        try {
            $dto = ProductCreateDTO::fromRequest($request);
            $product = $this->productService->createProduct($dto);

            return redirect()
                ->route('products.show', $product->id)
                ->with('success', 'Producto creado exitosamente.');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->withErrors(['error' => 'Error al crear el producto. Por favor intenta nuevamente.']);
        }
    }

    public function show(int $id)
    {
        try {
            $product = $this->productService->getProductById($id);

            return Inertia::render('products/Show', [
                'product' => $product,
            ]);
        } catch (\Exception $e) {
            return redirect()
                ->route('products.index')
                ->withErrors(['error' => 'Producto no encontrado.']);
        }
    }

    public function edit(int $id)
    {
        try {
            $product = $this->productService->getProductById($id);

            return Inertia::render('products/Edit', [
                'product' => $product,
            ]);
        } catch (\Exception $e) {
            return redirect()
                ->route('products.index')
                ->withErrors(['error' => 'Producto no encontrado.']);
        }
    }

    public function update(ProductUpdateRequest $request, int $id)
    {
        try {
            $dto = ProductUpdateDTO::fromRequest($request, $id);
            $product = $this->productService->updateProduct($dto);

            return redirect()
                ->route('products.show', $product->id)
                ->with('success', 'Producto actualizado exitosamente.');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->withErrors(['error' => 'Error al actualizar el producto. Por favor intenta nuevamente.']);
        }
    }

    public function destroy(int $id)
    {
        try {
            $this->productService->deleteProduct($id);

            return redirect()
                ->route('products.index')
                ->with('success', 'Producto eliminado exitosamente.');
        } catch (\Exception $e) {
            return back()
                ->withErrors(['error' => 'Error al eliminar el producto. Por favor intenta nuevamente.']);
        }
    }
}
