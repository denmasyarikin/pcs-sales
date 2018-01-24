<?php

namespace Denmasyarikin\Sales\Product\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Denmasyarikin\Sales\Product\Product;
use Denmasyarikin\Sales\Product\Requests\DetailProductRequest;
use Denmasyarikin\Sales\Product\Requests\CreateProductRequest;
use Denmasyarikin\Sales\Product\Requests\UpdateProductRequest;
use Denmasyarikin\Sales\Product\Requests\DeleteProductRequest;
use Denmasyarikin\Sales\Product\Transformers\ProductListTransformer;
use Denmasyarikin\Sales\Product\Transformers\ProductDetailTransformer;
use Denmasyarikin\Sales\Product\Transformers\ProductListDetailTransformer;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class ProductController extends Controller
{
    /**
     * get list.
     *
     * @param Request $request
     *
     * @return json
     */
    public function getList(Request $request)
    {
        $products = $this->getProductList($request);

        $transform = new ProductListTransformer($products);

        return new JsonResponse([
            'data' => $transform->toArray(),
            'draft_count' => Product::whereStatus('draft')->count(),
            'pagination' => $transform->pagination(),
        ]);
    }

    /**
     * get product list.
     *
     * @param Request $request
     *
     * @return paginator
     */
    protected function getProductList(Request $request)
    {
        $products = Product::orderBy('created_at', 'DESC');

        if ($request->has('status')) {
            if ('all' !== $request->status) {
                switch ($request->status) {
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
            }
        } else {
            $products->whereStatus('active');
        }

        if ($request->has('key')) {
            $products->where(function($query) use ($request) {
                $query->where('id', $request->key);
                $query->orwhere('name', 'like', "%{$request->key}%");
                $query->orWhere('description', 'like', "%{$request->key}%");
            });
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
    public function getDetail(DetailProductRequest $request, $id)
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
            'min_order', 'customizable', 'product_group_id'
        ]));

        return new JsonResponse([
            'message' => 'Product has been created',
            'data' => (new ProductListDetailTransformer($product))->toArray(),
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

        if ($request->has('status')
            and 'draft' !== $request->status
            and 0 === count($product->processes)) {
            throw new BadRequestHttpException(
                "Can not update status to {$request->status} while processes count is 0"
            );
        }

        $product->update($request->only([
            'name', 'description', 'unit_id',
            'min_order', 'customizable', 'status',
            'product_group_id'
        ]));

        return new JsonResponse(['message' => 'Product has been updated']);
    }

    /**
     * delete product.
     *
     * @param DeleteProductRequest $request
     *
     * @return json
     */
    public function deleteProduct(DeleteProductRequest $request)
    {
        $product = $request->getProduct();
        $product->delete();

        return new JsonResponse(['message' => 'Product has been deleted']);
    }
}
