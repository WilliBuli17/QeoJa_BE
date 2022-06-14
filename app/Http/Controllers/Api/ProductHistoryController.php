<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

// Add new library
use App\Models\ProductHistory;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class ProductHistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $productHistory = ProductHistory::leftJoin('products', 'product_histories.product_id', '=', 'products.id')
                ->leftJoin('employees as create', 'product_histories.created_by', '=', 'create.id')
                ->leftJoin('employees as update', 'product_histories.updated_by', '=', 'update.id')
                ->leftJoin('employees as delete', 'product_histories.deleted_by', '=', 'delete.id')
                ->withTrashed()
                ->orderBy('product_histories.created_at', 'DESC')
                ->get([
                    'create.name as created_by_name',
                    'update.name as updated_by_name',
                    'delete.name as deleted_by_name',
                    'products.unit',
                    'products.name',
                    'product_histories.id',
                    'product_histories.history_category',
                    'product_histories.history_date',
                    'product_histories.amount_of_product',
                    'product_histories.product_price',
                    'product_histories.total_price',
                    'product_histories.product_expired_date',
                    'product_histories.product_id',
                    'product_histories.created_by',
                    'product_histories.updated_by',
                    'product_histories.deleted_by'
                ]);

            if (count($productHistory) > 0) {
                $response = [
                    'status' => 'success',
                    'message' => 'Mengambil Data Riwayat Produk Sukses',
                    'data' => $productHistory,
                ];

                return response()->json($response, Response::HTTP_OK);
            }

            $response = [
                'status' => 'fails',
                'message' => 'Mengambil Data Riwayat Produk Gagal -> Data Kosong',
                'data' => null,
            ];

            return response()->json($response, Response::HTTP_NOT_FOUND);
        } catch (QueryException $e) {
            $response = [
                'status' => 'fails',
                'message' => 'Mengambil Data Riwayat Produk Gagal -> Server Error',
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
            'history_category' => 'required|in:in,out',
            'history_date' => 'required|date|date_format:Y-m-d',
            'amount_of_product' => 'required|numeric',
            'product_price' => 'required|numeric',
            'total_price' => 'required|numeric',
            'product_expired_date' => 'required|date|date_format:Y-m-d',
            'product_id' => 'required',
            'created_by' => 'required'
        ];

        $input = [
            'history_category' => $request->input('history_category'),
            'history_date' => $request->input('history_date'),
            'amount_of_product' => $request->input('amount_of_product'),
            'product_price' => $request->input('product_price'),
            'total_price' => $request->input('product_price') * $request->input('amount_of_product'),
            'product_expired_date' => $request->input('product_expired_date'),
            'product_id' => $request->input('product_id'),
            'created_by' => $request->input('created_by')
        ];

        $message = [
            'required' => 'Kolom :attribute wajib diisi.',
            'in' => 'Kolom :attribute tidak valid.',
            'date' => 'Kolom :attribute hanya dapat memuat data berupa tanggal.',
            'date_format' => 'Kolom :attribute tidak sesuai format penanggalan sistem.',
            'numeric' => 'Kolom :attribute hanya dapat memuat data berupa angka',
        ];

        $validator = Validator::make($input, $rule, $message);

        if ($validator->fails()) {
            $response = [
                'status' => 'fails',
                'message' => 'Menambah Data Riwayat Produk Gagal -> ' . $validator->errors()->first(),
                'data' => null,
            ];

            return response()->json($response, Response::HTTP_BAD_REQUEST);
        }

        try {
            $productHistory = ProductHistory::create($input);

            $response = [
                'status' => 'success',
                'message' => 'Menambah Data Riwayat Produk Sukses',
                'data' => $productHistory,
            ];

            return response()->json($response, Response::HTTP_CREATED);
        } catch (QueryException $e) {
            $response = [
                'status' => 'fails',
                'message' => 'Menambah Data Riwayat Produk Gagal -> Server Error ' . $e->getMessage(),
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
        $productHistory = ProductHistory::withTrashed()->find($id);

        if (is_null($productHistory)) {
            $response = [
                'status' => 'fails',
                'message' => 'Mengubah Data Riwayat Produk Gagal -> Data Tidak Ditemukan',
                'data' => null,
            ];

            return response()->json($response, Response::HTTP_NOT_FOUND);
        }

        $rule = [
            'history_category' => 'required|in:in,out',
            'history_date' => 'required|date|date_format:Y-m-d',
            'amount_of_product' => 'required|numeric',
            'product_price' => 'required|numeric',
            'total_price' => 'required|numeric',
            'product_expired_date' => 'required|date|date_format:Y-m-d',
            'product_id' => 'required',
            'updated_by' => 'required'
        ];

        $input = [
            'history_category' => $request->input('history_category'),
            'history_date' => $request->input('history_date'),
            'amount_of_product' => $request->input('amount_of_product'),
            'product_price' => $request->input('product_price'),
            'total_price' => $request->input('product_price') * $request->input('amount_of_product'),
            'product_expired_date' => $request->input('product_expired_date'),
            'product_id' => $request->input('product_id'),
            'updated_by' => $request->input('updated_by')
        ];

        $message = [
            'required' => 'Kolom :attribute wajib diisi.',
            'in' => 'Kolom :attribute tidak valid.',
            'date' => 'Kolom :attribute hanya dapat memuat data berupa tanggal.',
            'date_format' => 'Kolom :attribute tidak sesuai format penanggalan sistem.',
            'numeric' => 'Kolom :attribute hanya dapat memuat data berupa angka',
        ];

        $validator = Validator::make($input, $rule, $message);

        if ($validator->fails()) {
            $response = [
                'status' => 'fails',
                'message' => 'Mengubah Data Riwayat Produk Gagal -> ' . $validator->errors()->first(),
                'data' => null,
            ];

            return response()->json($response, Response::HTTP_BAD_REQUEST);
        }

        try {
            $productHistory->update($input);

            $response = [
                'status' => 'success',
                'message' => 'Mengubah Data Riwayat Produk Sukses',
                'data' => $productHistory,
            ];

            return response()->json($response, Response::HTTP_OK);
        } catch (QueryException $e) {
            $response = [
                'status' => 'fails',
                'message' => 'Mengubah Data Riwayat Produk Gagal -> Server Error',
                'data' => null,
            ];

            return response()->json($response, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $productHistory = ProductHistory::withTrashed()->find($id);

        if (is_null($productHistory)) {
            $response = [
                'status' => 'fails',
                'message' => 'Menghapus Data Riwayat Produk Gagal -> Data Tidak Ditemukan',
                'data' => null,
            ];

            return response()->json($response, Response::HTTP_NOT_FOUND);
        }

        try {
            if (is_null($productHistory->deleted_at)) {
                $input = [
                    'deleted_by' => $request->input('deleted_by')
                ];
                $productHistory->update($input);
                $productHistory->delete();
            } else {
                $input = [
                    'deleted_by' => null
                ];
                $productHistory->update($input);
                $productHistory->restore();
            }

            $response = [
                'status' => 'success',
                'message' => 'Menghapus Data Riwayat Produk Sukses',
                'data' => $productHistory,
            ];

            return response()->json($response, Response::HTTP_OK);
        } catch (QueryException $e) {
            $response = [
                'status' => 'fails',
                'message' => 'Menghapus Data Riwayat Produk Gagal -> Server Error',
                'data' => null,
            ];

            return response()->json($response, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
