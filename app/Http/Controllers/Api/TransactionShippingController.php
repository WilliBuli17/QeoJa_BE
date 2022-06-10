<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

// Add new library
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
            $transactionShipping = TransactionShipping::leftJoin('transactions', 'transaction_shippings.transaction_id', '=', 'transactions.id')
                ->leftJoin('employees', 'transaction_shippings.employee_id', '=', 'employees.id')
                ->leftJoin('expedition_trucks', 'transaction_shippings.expedition_truck_id', '=', 'expedition_trucks.id')
                ->leftJoin('customers', 'transactions.customer_id', '=', 'customers.id')
                ->leftJoin('transaction_statuses', 'transactions.transaction_status_id', '=', 'transaction_statuses.id')
                ->leftJoin('addresses', 'transactions.address_id', '=', 'addresses.id')
                ->leftJoin('cities', 'addresses.city_id', '=', 'cities.id')
                ->get([
                    'transaction_shippings.*',
                    'customers.name AS customer',
                    'cities.name AS city',
                    'addresses.address',
                    'transaction_statuses.name AS transaction_status_name',
                    'transactions.transaction_status_id AS transaction_status',
                    'transactions.total_volume_product',
                    'employees.name AS employee',
                    'expedition_trucks.license_id',
                ]);

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
            $transactionShipping = TransactionShipping::leftJoin('transactions', 'transaction_shippings.transaction_id', '=', 'transactions.id')
                ->leftJoin('customers', 'transactions.customer_id', '=', 'customers.id')
                ->leftJoin('transaction_statuses', 'transactions.transaction_status_id', '=', 'transaction_statuses.id')
                ->leftJoin('addresses', 'transactions.address_id', '=', 'addresses.id')
                ->leftJoin('bank_payments', 'transactions.bank_payment_id', '=', 'bank_payments.id')
                ->leftJoin('cities', 'addresses.city_id', '=', 'cities.id')
                ->where('transactions.customer_id', '=', $id)
                ->orderBy('transactions.transaction_status_id', 'ASC')
                ->get([
                    'customers.name AS name',
                    'cities.name AS city',
                    'addresses.address',
                    'transaction_statuses.name AS status',
                    'transactions.id',
                    'transactions.message',
                    'transactions.receipt_of_payment',
                    'transactions.subtotal_price',
                    'transactions.tax',
                    'transactions.shipping_cost',
                    'transactions.grand_total_price',
                    'transactions.bank_payment_id',
                    'transactions.created_at',
                    'transaction_shippings.id as idKey',
                    'transaction_shippings.delivery_date',
                    'transaction_shippings.arrived_date',
                    'bank_payments.bank_name',
                    'bank_payments.account_name',
                ]);

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
            'expedition_truck_id' => 'required',
            'delivery_date' => 'required|date|date_format:Y-m-d',
        ];

        $input = [
            'transaction_id' => $request->input('transaction_id'),
            'employee_id' => $request->input('employee_id'),
            'expedition_truck_id' => $request->input('expedition_truck_id'),
            'delivery_date' => $request->input('delivery_date'),
        ];

        $message = [
            'required' => 'Kolom :attribute wajib diisi.',
            'date' => ':attribute hanya dapat memuat data berupa tanggal.',
            'date_format' => ':attribute tidak sesuai format penanggalan sistem.',
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
            'delivery_date' => 'required|date|date_format:Y-m-d',
            'arrived_date' => 'required|date|date_format:Y-m-d',
        ];

        $input = [
            'delivery_date' => $request->input('delivery_date'),
            'arrived_date' => $request->input('arrived_date'),
        ];

        $message = [
            'required' => 'Kolom :attribute wajib diisi.',
            'date' => ':attribute hanya dapat memuat data berupa tanggal.',
            'date_format' => ':attribute tidak sesuai format penanggalan sistem.',
        ];

        $validator = Validator::make($input, $rule, $message);

        if ($validator->fails()) {
            $response = [
                'status' => 'fails',
                'message' => 'Mengubah Data Pengiriman Transaksi Gagal -> ' . $validator->errors()->first(),
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
