<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

// Add new library
use App\Models\Product;
use App\Models\Cart;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        try {
            $cart = Cart::leftJoin('products', 'carts.product_id', '=', 'products.id')
                ->where('carts.customer_id', '=', $id)
                ->orderBy('carts.product_id')
                ->get([
                    'products.name',
                    'products.price',
                    'products.picture',
                    'products.volume',
                    'products.stock_quantity',
                    'carts.id',
                    'carts.amount_of_product',
                    'carts.customer_id',
                    'carts.product_id'
                ]);

            $cart->makeHidden(['created_at', 'updated_at']);

            if (count($cart) > 0) {
                $response = [
                    'status' => 'success',
                    'message' => 'Mengambil Data Cart Sukses',
                    'data' => $cart,
                ];

                return response()->json($response, Response::HTTP_OK);
            }

            $response = [
                'status' => 'fails',
                'message' => 'Mengambil Data Cart Gagal -> Data Kosong',
                'data' => null,
            ];

            return response()->json($response, Response::HTTP_NOT_FOUND);
        } catch (QueryException $e) {
            $response = [
                'status' => 'fails',
                'message' => 'Mengambil Data Cart Gagal -> Server Error',
                'data' => null,
            ];

            return response()->json($response, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rule = [
            'customer_id' => 'required',
            'product_id' => 'required',
        ];

        $input = [
            'amount_of_product' => $request->input('amount_of_product'),
            'customer_id' => $request->input('customer_id'),
            'product_id' => $request->input('product_id')
        ];

        $message = [
            'required' => 'Kolom :attribute wajib diisi.',
        ];

        $validator = Validator::make($input, $rule, $message);

        if ($validator->fails()) {
            $response = [
                'status' => 'fails',
                'message' => 'Menambah Data Cart Gagal -> ' . $validator->errors()->first(),
                'data' => null,
            ];

            return response()->json($response, Response::HTTP_BAD_REQUEST);
        }

        try {
            $cart = Cart::where('customer_id', '=', $input['customer_id'])
                ->where('product_id', '=', $input['product_id'])
                ->first();

            if (is_null($cart)) {
                $cart = Cart::create($input);
            } else {
                $input['amount_of_product'] = $cart->amount_of_product + $input['amount_of_product'];
                $product = Product::find($input['product_id']);

                if($product->stock_quantity < $input['amount_of_product'] && $cart->amount_of_product < $input['amount_of_product']){
                    $response = [
                        'status' => 'warning',
                        'message' => 'Menambah Data Cart Gagal -> Sudah Mencapai Maksimal Jumlah Stock',
                        'data' => null,
                    ];

                    return response()->json($response, Response::HTTP_BAD_REQUEST);
                } else {
                    $cart->update($input);
                }
            }

            $response = [
                'status' => 'success',
                'message' => 'Menambah Data Cart Sukses',
                'data' => $cart,
            ];

            return response()->json($response, Response::HTTP_CREATED);
        } catch (QueryException $e) {
            $response = [
                'status' => 'fails',
                'message' => 'Menambah Data Cart Gagal -> Server Error',
                'data' => null,
            ];

            return response()->json($response, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $cart = Cart::find($id);

        if (is_null($cart)) {
            $response = [
                'status' => 'fails',
                'message' => 'Menghapus Data Cart Gagal -> Data Tidak Ditemukan',
                'data' => null,
            ];

            return response()->json($response, Response::HTTP_NOT_FOUND);
        }

        try {
            $cart->delete();

            $response = [
                'status' => 'success',
                'message' => 'Menghapus Data Cart Sukses',
                'data' => $cart,
            ];

            return response()->json($response, Response::HTTP_OK);
        } catch (QueryException $e) {
            $response = [
                'status' => 'fails',
                'message' => 'Menghapus Data Cart Gagal -> Server Error',
                'data' => null,
            ];

            return response()->json($response, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified resource from storage but more than one row of data.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroyMultiple($id)
    {
        $cart = Cart::where('customer_id', '=', $id);

        if (is_null($cart)) {
            $response = [
                'status' => 'fails',
                'message' => 'Menghapus Data Cart Gagal -> Data Tidak Ditemukan',
                'data' => null,
            ];

            return response()->json($response, Response::HTTP_NOT_FOUND);
        }

        try {
            $cart->delete();

            $response = [
                'status' => 'success',
                'message' => 'Menghapus Data Cart Sukses',
                'data' => $cart,
            ];

            return response()->json($response, Response::HTTP_OK);
        } catch (QueryException $e) {
            $response = [
                'status' => 'fails',
                'message' => 'Menghapus Data Cart Gagal -> Server Error',
                'data' => null,
            ];

            return response()->json($response, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
