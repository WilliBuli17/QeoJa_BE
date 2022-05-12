<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

// Add new library
use Illuminate\Validation\Rule;
use App\Models\TransactionShipping;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class TransactionShippingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $transactionShipping = TransactionShipping::join('transactions', 'transaction_shippings.transaction_id', '=', 'transactions.id')
                ->join('employees', 'transaction_shippings.employee_id', '=', 'employees.id')
                ->join('expedition_trucks', 'transaction_shippings.expedition_truck_id', '=', 'expedition_trucks.id')
                ->get(['transactions.*', 'employees.*', 'expedition_trucks.*']);

            if (count($transactionShipping) > 0) {
                $response = [
                    'status' => 'success',
                    'message' => 'Mengambil Data Pengiriman Transaksi Sukses',
                    'data' => $transactionShipping,
                ];

                return response()->json($response, Response::HTTP_OK);
            }

            $response = [
                'status' => 'fails',
                'message' => 'Mengambil Data Pengiriman Transaksi Gagal -> Data Kosong',
                'data' => null,
            ];

            return response()->json($response, Response::HTTP_NOT_FOUND);
        } catch (QueryException $e) {
            $response = [
                'status' => 'fails',
                'message' => 'Mengambil Data Pengiriman Transaksi Gagal -> Server Error',
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
            $transactionShipping = TransactionShipping::join('transactions', 'transaction_shippings.transaction_id', '=', 'transactions.id')
                ->join('employees', 'transaction_shippings.employee_id', '=', 'employees.id')
                ->join('expedition_trucks', 'transaction_shippings.expedition_truck_id', '=', 'expedition_trucks.id')
                ->where('transaction_shippings.id', '=', $id)
                ->get(['transactions.*', 'employees.*', 'expedition_trucks.*']);

            if (!is_null($transactionShipping)) {
                $response = [
                    'status' => 'success',
                    'message' => 'Mencari Data Pengiriman Transaksi Sukses',
                    'data' => $transactionShipping,
                ];

                return response()->json($response, Response::HTTP_OK);
            }

            $response = [
                'status' => 'fails',
                'message' => 'Mencari Data Pengiriman Transaksi Gagal -> Data Kosong',
                'data' => null,
            ];

            return response()->json($response, Response::HTTP_NOT_FOUND);
        } catch (QueryException $e) {
            $response = [
                'status' => 'fails',
                'message' => 'Mencari Data Pengiriman Transaksi Gagal -> Server Error',
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
            'transaction_id' => 'required',
            'employee_id' => 'required',
            'expedition_truck_id' => 'required'
        ];

        $input = [
            'transaction_id' => $request->input('transaction_id'),
            'employee_id' => $request->input('employee_id'),
            'expedition_truck_id' => $request->input('expedition_truck_id')
        ];

        $message = [
            'required' => 'Kolom :attribute wajib diisi.'
        ];

        $validator = Validator::make($input, $rule, $message);

        if ($validator->fails()) {
            $response = [
                'status' => 'fails',
                'message' => 'Menambah Data Pengiriman Transaksi Gagal -> ' . $validator->errors(),
                'data' => null,
            ];

            return response()->json($response, Response::HTTP_BAD_REQUEST);
        }

        try {
            $transactionShipping = TransactionShipping::create($input);

            $response = [
                'status' => 'success',
                'message' => 'Menambah Data Pengiriman Transaksi Sukses',
                'data' => $transactionShipping,
            ];

            return response()->json($response, Response::HTTP_CREATED);
        } catch (QueryException $e) {
            $response = [
                'status' => 'fails',
                'message' => 'Menambah Data Pengiriman Transaksi Gagal -> Server Error',
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
        $transactionShipping = TransactionShipping::find($id);

        if (is_null($transactionShipping)) {
            $response = [
                'status' => 'fails',
                'message' => 'Mengubah Data Pengiriman Transaksi Gagal -> Data Tidak Ditemukan',
                'data' => null,
            ];

            return response()->json($response, Response::HTTP_NOT_FOUND);
        }

        $rule = [
            'transaction_id' => 'required',
            'employee_id' => 'required',
            'expedition_truck_id' => 'required'
        ];

        $input = [
            'transaction_id' => $request->input('transaction_id'),
            'employee_id' => $request->input('employee_id'),
            'expedition_truck_id' => $request->input('expedition_truck_id')
        ];

        $message = [
            'required' => 'Kolom :attribute wajib diisi.'
        ];

        $validator = Validator::make($input, $rule, $message);

        if ($validator->fails()) {
            $response = [
                'status' => 'fails',
                'message' => 'Mengubah Data Pengiriman Transaksi Gagal -> ' . $validator->errors(),
                'data' => null,
            ];

            return response()->json($response, Response::HTTP_BAD_REQUEST);
        }

        try {
            $transactionShipping->update($input);

            $response = [
                'status' => 'success',
                'message' => 'Mengubah Data Pengiriman Transaksi Sukses',
                'data' => $transactionShipping,
            ];

            return response()->json($response, Response::HTTP_OK);
        } catch (QueryException $e) {
            $response = [
                'status' => 'fails',
                'message' => 'Mengubah Data Pengiriman Transaksi Gagal -> Server Error',
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
        $transactionShipping = TransactionShipping::find($id);

        if (is_null($transactionShipping)) {
            $response = [
                'status' => 'fails',
                'message' => 'Menghapus Data Pengiriman Transaksi Gagal -> Data Tidak Ditemukan',
                'data' => null,
            ];

            return response()->json($response, Response::HTTP_NOT_FOUND);
        }

        try {
            $transactionShipping->delete();

            $response = [
                'status' => 'success',
                'message' => 'Menghapus Data Pengiriman Transaksi Sukses',
                'data' => $transactionShipping,
            ];

            return response()->json($response, Response::HTTP_OK);
        } catch (QueryException $e) {
            $response = [
                'status' => 'fails',
                'message' => 'Menghapus Data Pengiriman Transaksi Gagal -> Server Error',
                'data' => null,
            ];

            return response()->json($response, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
