<?php

namespace Denmasyarikin\Sales\Customer\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Denmasyarikin\Sales\Customer\Customer;
use Denmasyarikin\Sales\Customer\Transformers\CustomerList;
use Denmasyarikin\Sales\Customer\Requests\CreateCustomerRequest;
use Denmasyarikin\Sales\Customer\Requests\UpdateCustomerRequest;
use Denmasyarikin\Sales\Customer\Requests\DeleteCustomerRequest;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class CustomerController extends Controller
{
    /**
     * customer list.
     *
     * @param Request $request
     *
     * @return json
     */
    public function getList(Request $request)
    {
        $customers = $this->getCustomerList($request);

        $response = new CustomerList($customers);

        return new JsonResponse([
            'data' => $response->toArray(),
            'pagination' => $response->pagination(),
        ]);
    }

    /**
     * get customer list.
     *
     * @param Request $request
     *
     * @return paginator
     */
    protected function getCustomerList(Request $request)
    {
        $customers = Customer::orderBy('created_at', 'DESC');

        switch ($request->get('type')) {
            case 'public':
                $customers->whereType('public');
                break;
            case 'agent':
                $customers->whereType('agent');
                break;
            case 'company':
                $customers->whereType('company');
                break;
        }

        if ($request->has('key')) {
            $customers->where('id', $request->key);
            $customers->orwhere('name', 'like', "%{$request->key}%");
            $customers->orWhere('address', 'like', "%{$request->key}%");
        }

        return $customers->paginate(20);
    }

    /**
     * create customer.
     *
     * @param CreateCustomerRequest $request
     *
     * @return json
     */
    public function createCustomer(CreateCustomerRequest $request)
    {
        Customer::create($request->only(['name', 'description', 'customer_type', 'markup', 'status']));

        return new JsonResponse(['message' => 'Customer has been created']);
    }

    /**
     * update customer.
     *
     * @param UpdateCustomerRequest $request
     *
     * @return json
     */
    public function updateCustomer(UpdateCustomerRequest $request)
    {
        $customer = $request->getCustomer();
        $customer->update($request->only(['name', 'description', 'customer_type', 'markup', 'status']));

        return new JsonResponse(['message' => 'Customer has been updated']);
    }

    /**
     * delete customer.
     *
     * @param DeleteCustomerRequest $request
     *
     * @return json
     */
    public function deleteCustomer(DeleteCustomerRequest $request)
    {
        $customer = $request->getCustomer();
        $customer->delete();

        return new JsonResponse(['message' => 'Customer has been deleted']);
    }
}
