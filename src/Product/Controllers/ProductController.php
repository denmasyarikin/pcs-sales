<?php

namespace Denmasyarikin\Sales\Product\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Denmasyarikin\Sales\Product\Product;
use Denmasyarikin\Sales\Product\Requests\CreateProductRequest;
use Denmasyarikin\Sales\Product\Requests\DetailProductRequest;
use Denmasyarikin\Sales\Product\Requests\UpdateProductRequest;
use Denmasyarikin\Sales\Product\Requests\DeleteProductRequest;
use Denmasyarikin\Sales\Product\Transformers\ProductListTransformer;
use Denmasyarikin\Sales\Product\Transformers\ProductDetailTransformer;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class ProductController extends Controller
{
    /**
     * product list.
     *
     * @param Request $request
     *
     * @return json
     */
    public function getList(Request $request)
    {
        $products = $this->getProductList($request, $request->get('status'));
        $draftProducts = $this->getProductList($request, 'draft');

        $transform = new ProductListTransformer($products);
        $transformDraft = new ProductListTransformer($draftProducts);

        return new JsonResponse([
            'data' => $transform->toArray(),
            'draft' => $transformDraft->toArray(),
        ]);
    }

    /**
     * get product list.
     *
     * @param Request $request
     * @param string  $status
     *
     * @return paginator
     */
    protected function getProductList(Request $request, $status = null)
    {
        $products = Product::with('processes')->orderBy('name', 'ASC');

        if ($request->has('key')) {
            $products->where('name', 'like', "%{$request->key}%");
        }

        if ($request->has('category_id')) {
            $products->whereProductCategoryId($request->category_id);
        } elseif (!$request->has('key')) {
            $products->whereNull('product_category_id');
        }

        if ($request->has('workspace_id')) {
            $products->workspaceId($request->workspace_id);
        } else {
            $products->myWorkspace();
        }

        switch ($status) {
            case 'all':
                // do nothing
                break;

            case 'draft':
                $products->whereStatus('draft');
                break;

            case 'inactive':
                $products->whereStatus('inactive');
                break;

            default:
                $products->whereStatus('active');
                break;
        }

        return $products->paginate($request->get('per_page') ?: 10);
    }

    /**
     * get detail.
     *
     * @param DetailProductRequest $request
     *
     * @return json
     */
    public function getDetail(DetailProductRequest $request)
    {
        $transform = new ProductDetailTransformer($request->getProduct());

        return new JsonResponse(['data' => $transform->toArray()]);
    }

    /**
     * create product.
     *
     * @param CreateProductRequest $request
     *
     * @return json
     */
    public function createProduct(CreateProductRequest $request)
    {
        $product = Product::create($request->only([
            'name', 'description', 'unit_id',
            'product_category_id',
            'min_order', 'order_multiples'
        ]));

        $product->workspaces()->sync($request->workspace_ids);

        return new JsonResponse([
            'message' => 'Product has been created',
            'data' => (new ProductDetailTransformer($product))->toArray(),
        ], 201);
    }

    /**
     * update product.
     *
     * @param UpdateProductRequest $request
     *
     * @return json
     */
    public function updateProduct(UpdateProductRequest $request)
    {
        $product = $request->getProduct();

        if ('draft' !== $request->status
            and 0 === $product->processes()->count()) {
            throw new BadRequestHttpException('Can not update status with no processes');
        }

        $product->update($request->only([
            'name', 'description', 'unit_id',
            'product_category_id',
            'min_order', 'order_multiples', 'status'
        ]));

        $product->workspaces()->sync($request->workspace_ids);

        return new JsonResponse([
            'message' => 'Product has been updated',
            'data' => (new ProductDetailTransformer($product))->toArray(),
        ]);
    }

    /**
     * update product.
     *
     * @param DeleteProductRequest $request
     *
     * @return json
     */
    public function deleteProduct(DetailProductRequest $request)
    {
        $product = $request->getProduct();

        $product->delete();

        return new JsonResponse(['message' => 'Product has been deleted']);
    }
}
