<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

// Add new library
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
            $cart = Cart::join('products', 'carts.product_id', '=', 'products.id')
                ->where('carts.customer_id', '=', $id)
                ->orderBy('carts.product_id')
                ->get(['products.name AS name', 'products.price AS price', 'products.picture AS picture', 'carts.*']);

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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $cart = Cart::join('customers', 'carts.customer_id', '=', 'customers.id')
                ->join('products', 'carts.product_id', '=', 'products.id')
                ->where('carts.id', '=', $id)
                ->get(['customers.*', 'products.*', 'carts.*']);

            if (count($cart) != 1) {
                $response = [
                    'status' => 'success',
                    'message' => 'Mencari Data Cart Sukses',
                    'data' => $cart,
                ];

                return response()->json($response, Response::HTTP_OK);
            }

            $response = [
                'status' => 'fails',
                'message' => 'Mencari Data Cart Gagal -> Data Kosong',
                'data' => null,
            ];

            return response()->json($response, Response::HTTP_NOT_FOUND);
        } catch (QueryException $e) {
            $response = [
                'status' => 'fails',
                'message' => 'Mencari Data Cart Gagal -> Server Error',
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
            'numeric' => 'Kolom :attribute hanya dapat memuat data berupa angka',
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
                $cart->update($input);
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
                'message' => 'Menambah Data Cart Gagal -> Server Error ' . $e,
                'data' => null,
            ];

            return response()->json($response, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $cart = Cart::find($id);

        if (is_null($cart)) {
            $response = [
                'status' => 'fails',
                'message' => 'Mengubah Data Cart Gagal -> Data Tidak Ditemukan',
                'data' => null,
            ];

            return response()->json($response, Response::HTTP_NOT_FOUND);
        }

        $rule = [
            'amount_of_product' => 'required|numeric',
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
            'numeric' => 'Kolom :attribute hanya dapat memuat data berupa angka',
        ];

        $validator = Validator::make($input, $rule, $message);

        if ($validator->fails()) {
            $response = [
                'status' => 'fails',
                'message' => 'Mengubah Data Cart Gagal -> ' . $validator->errors()->first(),
                'data' => null,
            ];

            return response()->json($response, Response::HTTP_BAD_REQUEST);
        }

        try {
            $cart->update($input);

            $response = [
                'status' => 'success',
                'message' => 'Mengubah Data Cart Sukses',
                'data' => $cart,
            ];

            return response()->json($response, Response::HTTP_OK);
        } catch (QueryException $e) {
            $response = [
                'status' => 'fails',
                'message' => 'Mengubah Data Cart Gagal -> Server Error',
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
}
