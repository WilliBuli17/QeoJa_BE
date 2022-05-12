<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

// Add new library
use App\Models\DetailTransaction;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class DetailTransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        try {
            $detailTransaction = DetailTransaction::join('transactions', 'detail_transactions.transaction_id', '=', 'transactions.id')
                ->join('products', 'detail_transactions.product_id', '=', 'products.id')
                ->where('transactions.id', '=', $id)
                ->get(['transactions.*', 'products.*', 'detail_transactions.*']);

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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $detailTransaction = DetailTransaction::join('transactions', 'detail_transactions.transaction_id', '=', 'transactions.id')
                ->join('products', 'detail_transactions.product_id', '=', 'products.id')
                ->where('detail_transactions.id', '=', $id)
                ->get(['transactions.*', 'products.*', 'detail_transactions.*']);

            if (count($detailTransaction) != 1) {
                $response = [
                    'status' => 'success',
                    'message' => 'Mencari Data Detail Transaksi Sukses',
                    'data' => $detailTransaction,
                ];

                return response()->json($response, Response::HTTP_OK);
            }

            $response = [
                'status' => 'fails',
                'message' => 'Mencari Data Detail Transaksi Gagal -> Data Kosong',
                'data' => null,
            ];

            return response()->json($response, Response::HTTP_NOT_FOUND);
        } catch (QueryException $e) {
            $response = [
                'status' => 'fails',
                'message' => 'Mencari Data Detail Transaksi Gagal -> Server Error',
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
            'amount_of_product' => 'required|numeric',
            'product_price' => 'required|numeric',
            'total_price' => 'required|numeric',
            'status' => 'required',
            'transaction_id' => 'required',
            'product_id' => 'required',
        ];

        $input = [
            'amount_of_product' => $request->input('amount_of_product'),
            'product_price' => $request->input('product_price'),
            'total_price' => $request->input('total_price'),
            'status' => $request->input('status'),
            'transaction_id' => $request->input('transaction_id'),
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
                'message' => 'Mengubah Data Detail Transaksi Gagal -> ' . $validator->errors(),
                'data' => null,
            ];

            return response()->json($response, Response::HTTP_BAD_REQUEST);
        }

        try {
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
