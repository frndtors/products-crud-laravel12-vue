<?php

namespace App\Http\Controllers;

use App\DTOs\ProductDTO;
use App\Http\Requests\CreateProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Services\ProductService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ProductController extends Controller
{
    public function __construct(
        private readonly ProductService $productService
    ) {}

    /**
     * Display a listing of products with pagination and search.
     */
    public function index(Request $request): Response
    {
        $perPage = (int) $request->query('per_page', 10);
        $search = $request->query('search');

        try {
            $products = $this->productService->getProductsPaginated($perPage, $search);

            return Inertia::render('products/Index', [
                'products' => $products,
                'search' => $search,
                'perPage' => $perPage,
            ]);
        } catch (\Exception $e) {
            \Log::error('Failed to load products', [
                'error' => $e->getMessage(),
                'search' => $search,
                'perPage' => $perPage
            ]);

            // Return empty result on error
            return Inertia::render('products/Index', [
                'products' => [
                    'data' => [],
                    'current_page' => 1,
                    'last_page' => 1,
                    'per_page' => $perPage,
                    'total' => 0,
                    'links' => []
                ],
                'search' => $search,
                'perPage' => $perPage,
                'error' => 'Failed to load products. Please try again.',
            ]);
        }
    }

    /**
     * Show the form for creating a new product.
     */
    public function create(): Response
    {
        return Inertia::render('products/Create');
    }

    /**
     * Store a newly created product in storage.
     */
    public function store(CreateProductRequest $request): RedirectResponse
    {
        try {
            $dto = ProductDTO::fromCreateRequest($request);
            $product = $this->productService->createProduct($dto);

            return redirect()
                ->route('products.show', $product->id)
                ->with('success', 'Product created successfully!');
        } catch (\Exception $e) {
            \Log::error('Failed to create product', [
                'error' => $e->getMessage(),
                'data' => $request->validated()
            ]);

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to create product. Please try again.');
        }
    }

    /**
     * Display the specified product.
     */
    public function show(int $id): Response|RedirectResponse
    {
        try {
            $product = $this->productService->getProductById($id);

            return Inertia::render('products/Show', [
                'product' => $product,
            ]);
        } catch (ModelNotFoundException $e) {
            return redirect()
                ->route('products.index')
                ->with('error', 'Product not found.');
        } catch (\Exception $e) {
            \Log::error('Failed to load product details', [
                'error' => $e->getMessage(),
                'product_id' => $id
            ]);

            return redirect()
                ->route('products.index')
                ->with('error', 'Failed to load product details.');
        }
    }

    /**
     * Show the form for editing the specified product.
     */
    public function edit(int $id): Response|RedirectResponse
    {
        try {
            $product = $this->productService->getProductById($id);

            return Inertia::render('products/Edit', [
                'product' => $product,
            ]);
        } catch (ModelNotFoundException $e) {
            return redirect()
                ->route('products.index')
                ->with('error', 'Product not found.');
        } catch (\Exception $e) {
            \Log::error('Failed to load product for editing', [
                'error' => $e->getMessage(),
                'product_id' => $id
            ]);

            return redirect()
                ->route('products.index')
                ->with('error', 'Failed to load product for editing.');
        }
    }

    /**
     * Update the specified product in storage.
     */
    public function update(UpdateProductRequest $request, int $id): RedirectResponse
    {
        try {
            $dto = ProductDTO::fromUpdateRequest($request);
            $product = $this->productService->updateProduct($id, $dto);

            return redirect()
                ->route('products.show', $product->id)
                ->with('success', 'Product updated successfully!');
        } catch (ModelNotFoundException $e) {
            return redirect()
                ->route('products.index')
                ->with('error', 'Product not found.');
        } catch (\Exception $e) {
            \Log::error('Failed to update product', [
                'error' => $e->getMessage(),
                'product_id' => $id,
                'data' => $request->validated()
            ]);

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to update product. Please try again.');
        }
    }

    /**
     * Remove the specified product from storage.
     */
    public function destroy(int $id): RedirectResponse
    {
        try {
            $this->productService->deleteProduct($id);

            return redirect()
                ->route('products.index')
                ->with('success', 'Product deleted successfully!');
        } catch (ModelNotFoundException $e) {
            return redirect()
                ->route('products.index')
                ->with('error', 'Product not found.');
        } catch (\Exception $e) {
            \Log::error('Failed to delete product', [
                'error' => $e->getMessage(),
                'product_id' => $id
            ]);

            return redirect()
                ->route('products.index')
                ->with('error', 'Failed to delete product. Please try again.');
        }
    }
}
