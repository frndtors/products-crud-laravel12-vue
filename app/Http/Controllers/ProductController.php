<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Services\ProductService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Product Controller
 *
 * Handles HTTP requests for product operations
 */
class ProductController extends Controller
{
    public function __construct(
        private readonly ProductService $productService
    ) {}

    /**
     * Display a listing of products with pagination and search
     */
    public function index(Request $request): View
    {
        $search = $request->get('search');
        $perPage = (int) $request->get('per_page', 10);

        // Validate per_page parameter
        $perPage = in_array($perPage, [5, 10, 25, 50]) ? $perPage : 10;

        $products = $this->productService->getPaginatedProducts($perPage, $search);

        return view('products.index', [
            'products' => $products,
            'search' => $search,
            'perPage' => $perPage
        ]);
    }

    /**
     * Show the form for creating a new product
     */
    public function create(): View
    {
        return view('products.create');
    }

    /**
     * Store a newly created product
     */
    public function store(CreateProductRequest $request): RedirectResponse
    {
        try {
            $product = $this->productService->createProduct($request->validated());

            return redirect()
                ->route('products.show', $product->id)
                ->with('success', 'Product created successfully!');
        } catch (\InvalidArgumentException $e) {
            return back()
                ->withInput()
                ->withErrors(['error' => $e->getMessage()]);
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->withErrors(['error' => 'An error occurred while creating the product.']);
        }
    }

    /**
     * Display the specified product
     */
    public function show(int $id): View|RedirectResponse
    {
        $product = $this->productService->findProduct($id);

        if (!$product) {
            return redirect()
                ->route('products.index')
                ->withErrors(['error' => 'Product not found.']);
        }

        return view('products.show', [
            'product' => $product
        ]);
    }

    /**
     * Show the form for editing the specified product
     */
    public function edit(int $id): View|RedirectResponse
    {
        $product = $this->productService->findProduct($id);

        if (!$product) {
            return redirect()
                ->route('products.index')
                ->withErrors(['error' => 'Product not found.']);
        }

        return view('products.edit', [
            'product' => $product
        ]);
    }

    /**
     * Update the specified product
     */
    public function update(UpdateProductRequest $request, int $id): RedirectResponse
    {
        try {
            $product = $this->productService->updateProduct($id, $request->validated());

            if (!$product) {
                return redirect()
                    ->route('products.index')
                    ->withErrors(['error' => 'Product not found.']);
            }

            return redirect()
                ->route('products.show', $product->id)
                ->with('success', 'Product updated successfully!');
        } catch (\InvalidArgumentException $e) {
            return back()
                ->withInput()
                ->withErrors(['error' => $e->getMessage()]);
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->withErrors(['error' => 'An error occurred while updating the product.']);
        }
    }

    /**
     * Remove the specified product
     */
    public function destroy(int $id): RedirectResponse
    {
        try {
            $deleted = $this->productService->deleteProduct($id);

            if (!$deleted) {
                return redirect()
                    ->route('products.index')
                    ->withErrors(['error' => 'Product not found.']);
            }

            return redirect()
                ->route('products.index')
                ->with('success', 'Product deleted successfully!');
        } catch (\Exception $e) {
            return back()
                ->withErrors(['error' => 'An error occurred while deleting the product.']);
        }
    }
}
