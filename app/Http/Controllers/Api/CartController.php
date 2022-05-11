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
            $cart = Cart::join('customers', 'carts.customer_id', '=', 'customers.id')
                ->join('products', 'carts.product_id', '=', 'products.id')
                ->where('customers.id', '=', $id)
                ->get(['customers.*', 'products.*', 'carts.*']);

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

            if (count($cart) > 0) {
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
            'amount_of_product' => 'required|numeric',
            'total_price' => 'required|numeric',
            'customer_id' => 'required',
            'product_id' => 'required',
        ];

        $input = [
            'amount_of_product' => $request->input('amount_of_product'),
            'total_price' => $request->input('total_price'),
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
                'message' => 'Menambah Data Cart Gagal -> ' . $validator->errors(),
                'data' => null,
            ];

            return response()->json($response, Response::HTTP_BAD_REQUEST);
        }

        try {
            $cart = Cart::create($input);

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
            'total_price' => 'required|numeric',
            'customer_id' => 'required',
            'product_id' => 'required',
        ];

        $input = [
            'amount_of_product' => $request->input('amount_of_product'),
            'total_price' => $request->input('total_price'),
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
                'message' => 'Mengubah Data Cart Gagal -> ' . $validator->errors(),
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
