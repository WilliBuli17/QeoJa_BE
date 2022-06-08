<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

// Add new library
use App\Models\Cart;
use App\Models\DetailTransaction;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class DetailTransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $detailTransaction = DetailTransaction::leftJoin('transactions', 'detail_transactions.transaction_id', '=', 'transactions.id')
                ->leftJoin('products', 'detail_transactions.product_id', '=', 'products.id')
                ->where('transactions.id', '=', $id)
                ->orderBy('detail_transactions.id')
                ->get([
                    'products.name AS name',
                    'products.picture AS picture',
                    'detail_transactions.id',
                    'detail_transactions.amount_of_product',
                    'detail_transactions.status',
                    'detail_transactions.total_price',
                ]);

            if (count($detailTransaction) > 0) {
                $response = [
                    'status' => 'success',
                    'message' => 'Mengambil Data Detail Transaksi Sukses',
                    'data' => $detailTransaction,
                ];

                return response()->json($response, Response::HTTP_OK);
            }

            $response = [
                'status' => 'fails',
                'message' => 'Mengambil Data Detail Transaksi Gagal -> Data Kosong',
                'data' => null,
            ];

            return response()->json($response, Response::HTTP_NOT_FOUND);
        } catch (QueryException $e) {
            $response = [
                'status' => 'fails',
                'message' => 'Mengambil Data Detail Transaksi Gagal -> Server Error',
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
        $detailTransaction = DetailTransaction::find($id);

        if (is_null($detailTransaction)) {
            $response = [
                'status' => 'fails',
                'message' => 'Mengubah Data Detail Transaksi Gagal -> Data Tidak Ditemukan',
                'data' => null,
            ];

            return response()->json($response, Response::HTTP_NOT_FOUND);
        }

        $rule = [
            'amount_of_product' => 'nullable|numeric',
            'total_price' => 'nullable|numeric',
            'status' => 'nullable|in:success,pending,fail',
        ];

        $input = [
            'amount_of_product' => $request->input('amount_of_product'),
            'total_price' => $request->input('total_price'),
            'status' => $request->input('status'),
        ];

        $message = [
            'numeric' => 'Kolom :attribute hanya dapat memuat data berupa angka',
            'in' => 'Kolom :attribute tidak valid.'
        ];

        $validator = Validator::make($input, $rule, $message);

        if ($validator->fails()) {
            $response = [
                'status' => 'fails',
                'message' => 'Mengubah Data Detail Transaksi Gagal -> ' . $validator->errors()->first(),
                'data' => null,
            ];

            return response()->json($response, Response::HTTP_BAD_REQUEST);
        }

        try {
            if (is_null($request->input('amount_of_product'))){
                $input['amount_of_product'] = $detailTransaction->amount_of_product;
            }

            if (is_null($request->input('total_price'))){
                $input['total_price'] = $detailTransaction->total_price;
            }

            if (is_null($request->input('status'))){
                $input['status'] = $detailTransaction->status;
            }

            $detailTransaction->update($input);

            $response = [
                'status' => 'success',
                'message' => 'Mengubah Data Detail Transaksi Sukses',
                'data' => $detailTransaction,
            ];

            return response()->json($response, Response::HTTP_OK);
        } catch (QueryException $e) {
            $response = [
                'status' => 'fails',
                'message' => 'Mengubah Data Detail Transaksi Gagal -> Server Error',
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
        $detailTransaction = DetailTransaction::find($id);

        if (is_null($detailTransaction)) {
            $response = [
                'status' => 'fails',
                'message' => 'Menghapus Data Detail Transaksi Gagal -> Data Tidak Ditemukan',
                'data' => null,
            ];

            return response()->json($response, Response::HTTP_NOT_FOUND);
        }

        try {
            $detailTransaction->delete();

            $response = [
                'status' => 'success',
                'message' => 'Menghapus Data Detail Transaksi Sukses',
                'data' => $detailTransaction,
            ];

            return response()->json($response, Response::HTTP_OK);
        } catch (QueryException $e) {
            $response = [
                'status' => 'fails',
                'message' => 'Menghapus Data Detail Transaksi Gagal -> Server Error',
                'data' => null,
            ];

            return response()->json($response, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
