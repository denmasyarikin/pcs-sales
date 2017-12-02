<?php

namespace Denmasyarikin\Sales\Product\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Denmasyarikin\Sales\Product\Product;
use Denmasyarikin\Sales\Product\Requests\DetailProductRequest;
use Denmasyarikin\Sales\Product\Requests\CreateProductRequest;
use Denmasyarikin\Sales\Product\Transformers\ProductListTransformer;
use Denmasyarikin\Sales\Product\Transformers\ProductDetailTransformer;

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

        if ($request->has('key')) {
            $products->where('id', $request->key);
            $products->orwhere('name', 'like', "%{$request->key}%");
            $products->orWhere('description', 'like', "%{$request->key}%");
        }

        return $products->paginate(20);
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
     * create product
     *
     * @param CreateProductRequest $request
     * @return json
     */
    public function createProduct(CreateProductRequest $request)
    {
        try {
            DB::beginTransaction();

            $product = Product::create($request->only([
                'name', 'description', 'unit_id', 'min_order', 'customizable',
                'base_price', 'per_unit_price', 'process_service_count',
                'process_good_count', 'process_manual_count', 'status'
            ]));

            foreach ($request->processes as $process) {
                $this->createProcess($product, $process);
            }

            foreach ($request->medias as $media) {
                $this->createMedia($product, $media);
            }

            DB::commit();

            return new JsonResponse(['message' => 'Product has been created'], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return new JsonResponse(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * create processes
     *
     * @param Product $product
     * @param array $processInput
     * @return ProductProcess
     */
    protected function createProcess(Product $product, array $processInput)
    {
        $process = $product->processes()->create(array_intersect_key(
            $processInput, array_flip([
                'process_type', 'process_type_as', 'reference_id',
                'name', 'type', 'quantity', 'base_price', 'required',
                'static_price', 'static_to_order_count', 'unit_id'
            ])
        ));

        if (isset($processInput['options'])) {
            foreach ($processInput['options'] as $option) {
                $process->children()->create(array_merge(array_intersect_key(
                    $option, array_flip([
                        'process_type', 'process_type_as', 'reference_id',
                        'name', 'type', 'quantity', 'base_price', 'required',
                        'static_price', 'static_to_order_count', 'unit_id'
                    ])
                ), ['product_id' => $product->id]));
            }
        }

        return $process;
    }

    /**
     * create media
     *
     * @param Product $product
     * @param array $media
     * @return ProductMedia
     */
    protected function createMedia(Product $product, array $media)
    {
        return $product->medias()->create(array_intersect_key(
            $media, array_flip(['type', 'content', 'sequence','primary'])
        ));
    }
}
