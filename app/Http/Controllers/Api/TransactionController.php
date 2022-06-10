<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

// Add new library
use App\Models\Transaction;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $transaction = Transaction::leftJoin('customers', 'transactions.customer_id', '=', 'customers.id')
                ->leftJoin('transaction_statuses', 'transactions.transaction_status_id', '=', 'transaction_statuses.id')
                ->leftJoin('addresses', 'transactions.address_id', '=', 'addresses.id')
                ->leftJoin('bank_payments', 'transactions.bank_payment_id', '=', 'bank_payments.id')
                ->leftJoin('cities', 'addresses.city_id', '=', 'cities.id')
                ->orderBy('transactions.transaction_status_id', 'ASC')
                ->get([
                    'customers.name AS name',
                    'cities.name AS city',
                    'addresses.address',
                    'transaction_statuses.name AS status',
                    'transactions.id',
                    'transactions.message',
                    'transactions.subtotal_price',
                    'transactions.tax',
                    'transactions.shipping_cost',
                    'transactions.grand_total_price',
                    'transactions.transaction_status_id',
                    'transactions.created_at',
                    'transactions.total_volume_product',
                    'transactions.receipt_of_payment',
                    'bank_payments.bank_name',
                    'bank_payments.account_name',
                ]);

            if (count($transaction) > 0) {
                $response = [
                    'status' => 'success',
                    'message' => 'Mengambil Data Transaksi Sukses',
                    'data' => $transaction,
                ];

                return response()->json($response, Response::HTTP_OK);
            }

            $response = [
                'status' => 'fails',
                'message' => 'Mengambil Data Transaksi Gagal -> Data Kosong',
                'data' => null,
            ];

            return response()->json($response, Response::HTTP_NOT_FOUND);
        } catch (QueryException $e) {
            $response = [
                'status' => 'fails',
                'message' => 'Mengambil Data Transaksi Gagal -> Server Error',
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
            'subtotal_price' => 'required|numeric',
            'shipping_cost' => 'required|numeric',
            'tax' => 'required|numeric',
            'grand_total_price' => 'required|numeric',
            'message' => 'nullable',
            'total_volume_product' => 'required|numeric',
            'customer_id' => 'required',
            'address_id' => 'required',
            'bank_payment_id' => 'required',
            'transaction_status_id' => 'required'
        ];

        $input = [
            'subtotal_price' => $request->input('subtotal_price'),
            'shipping_cost' => $request->input('shipping_cost'),
            'tax' => $request->input('tax'),
            'grand_total_price' => $request->input('grand_total_price'),
            'message' => $request->input('message'),
            'receipt_of_payment' => 'no-image.jpg',
            'total_volume_product' => $request->input('total_volume_product'),
            'customer_id' => $request->input('customer_id'),
            'address_id' => $request->input('address_id'),
            'bank_payment_id' => $request->input('bank_payment_id'),
            'transaction_status_id' => 1,
        ];

        $message = [
            'required' => 'Kolom :attribute wajib diisi.',
            'numeric' => 'Kolom :attribute hanya dapat memuat data berupa angka',
        ];

        $validator = Validator::make($input, $rule, $message);

        if ($validator->fails()) {
            $response = [
                'status' => 'fails',
                'message' => 'Menambah Data Transaksi Gagal -> ' . $validator->errors()->first(),
                'data' => null,
            ];

            return response()->json($response, Response::HTTP_BAD_REQUEST);
        }

        try {
            $transaction = Transaction::create($input);

            DB::unprepared('
                INSERT INTO detail_transactions (amount_of_product, product_price, total_price, status, transaction_id, product_id, created_at, updated_at)
                SELECT amount_of_product, products.price, (amount_of_product * products.price), \'pending\', ' . $transaction->id . ', product_id, \'' . $transaction->created_at . '\', \'' . $transaction->updated_at . '\'
                FROM carts JOIN products ON (carts.product_id = products.id)
                WHERE carts.customer_id = ' . $transaction->customer_id . '
            ');

            $response = [
                'status' => 'success',
                'message' => 'Menambah Data Transaksi Sukses',
                'data' => $transaction,
            ];

            return response()->json($response, Response::HTTP_CREATED);
        } catch (QueryException $e) {
            $response = [
                'status' => 'fails',
                'message' => 'Menambah Data Transaksi Gagal -> Server Error ' . $e->getMessage(),
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
        $transaction = Transaction::find($id);

        if (is_null($transaction)) {
            $response = [
                'status' => 'fails',
                'message' => 'Mengubah Data Transaksi Gagal -> Data Tidak Ditemukan',
                'data' => null,
            ];

            return response()->json($response, Response::HTTP_NOT_FOUND);
        }

        $rule = [
            'transaction_status_id' => 'nullable|numeric'
        ];

        $input = [
            'transaction_status_id' => $request->input('transaction_status_id'),
            'receipt_of_payment' => $transaction->receipt_of_payment,
        ];

        $message = [
            'numeric' => 'Kolom :attribute hanya dapat memuat data berupa angka',
        ];

        $validator = Validator::make($input, $rule, $message);

        if ($validator->fails()) {
            $response = [
                'status' => 'fails',
                'message' => 'Mengubah Data Transaksi Gagal -> ' . $validator->errors()->first(),
                'data' => null,
            ];

            return response()->json($response, Response::HTTP_BAD_REQUEST);
        }

        try {
            if (!is_null($request->file('receipt_of_payment'))) {
                $this->destroyFile($transaction->receipt_of_payment);
                $input['receipt_of_payment'] = $this->uploadFile('storage/transaction', $request->file('receipt_of_payment'));
            }

            if (is_null($request->input('transaction_status_id'))){
                $input['transaction_status_id'] = $transaction->transaction_status_id;
            }

            $transaction->update($input);

            $response = [
                'status' => 'success',
                'message' => 'Mengubah Data Transaksi Sukses',
                'data' => $transaction,
            ];

            return response()->json($response, Response::HTTP_OK);
        } catch (QueryException $e) {
            $response = [
                'status' => 'fails',
                'message' => 'Mengubah Data Transaksi Gagal -> Server Error ' . $e->getMessage(),
                'data' => null,
            ];

            return response()->json($response, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Upload file user.
     *
     * @param $folder, $file
     * @return $fileName
     */
    function uploadFile($folder, $file)
    {
        $fileName = $folder . '/' . $file->getClientOriginalName();
        $file->move($folder, $fileName);

        return $fileName;
    }

    /**
     * Destroy file user.
     *
     * @param $fileName
     * @return true
     */
    function destroyFile($fileName)
    {
        File::delete($fileName);

        return true;
    }
}
