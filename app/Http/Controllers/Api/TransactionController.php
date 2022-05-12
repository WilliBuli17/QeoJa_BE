<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

// Add new library
use App\Models\Transaction;
use App\Models\Cart;
use App\Models\DetailTransaction;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
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
            $transaction = Transaction::join('customers', 'transactions.customer_id', '=', 'customers.id')
                ->join('addresses', 'transactions.address_id', '=', 'addresses.id')
                ->join('bank_payments', 'transactions.bank_payment_id', '=', 'bank_payments.id')
                ->join('transaction_statuses', 'transactions.transaction_status_id', '=', 'transaction_statuses.id')
                ->get(['customers.*', 'addresses.*', 'bank_payments.*', 'transaction_statuses.*']);

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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $transaction = Transaction::join('customers', 'transactions.customer_id', '=', 'customers.id')
                ->join('addresses', 'transactions.address_id', '=', 'addresses.id')
                ->join('bank_payments', 'transactions.bank_payment_id', '=', 'bank_payments.id')
                ->join('transaction_statuses', 'transactions.transaction_status_id', '=', 'transaction_statuses.id')
                ->where('transactions.id', '=', $id)
                ->get(['customers.*', 'addresses.*', 'bank_payments.*', 'transaction_statuses.*']);

            if (count($transaction) != 1) {
                $response = [
                    'status' => 'success',
                    'message' => 'Mencari Data Transaksi Sukses',
                    'data' => $transaction,
                ];

                return response()->json($response, Response::HTTP_OK);
            }

            $response = [
                'status' => 'fails',
                'message' => 'Mencari Data Transaksi Gagal -> Data Kosong',
                'data' => null,
            ];

            return response()->json($response, Response::HTTP_NOT_FOUND);
        } catch (QueryException $e) {
            $response = [
                'status' => 'fails',
                'message' => 'Mencari Data Transaksi Gagal -> Server Error',
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
        $cart = Cart::join('products', 'carts.product_id', '=', 'products.id')
            ->where('carts.customer_id', '=', $request->input('customer_id'))
            ->get(['products.*', 'carts.*']);

        $rule = [
            'subtotal_price' => 'required|numeric',
            'shipping_cost' => 'required|numeric',
            'tax' => 'required|numeric',
            'grand_total_price' => 'required|numeric',
            'receipt_of_payment' => 'nullable',
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
            'receipt_of_payment' => $request->file('receipt_of_payment'),
            'customer_id' => $request->input('customer_id'),
            'address_id' => $request->input('address_id'),
            'bank_payment_id' => $request->input('bank_payment_id'),
            'transaction_status_id' => $request->input('transaction_status_id'),
        ];

        $message = [
            'required' => 'Kolom :attribute wajib diisi.',
            'numeric' => 'Kolom :attribute hanya dapat memuat data berupa angka',
        ];

        $validator = Validator::make($input, $rule, $message);

        if ($validator->fails()) {
            $response = [
                'status' => 'fails',
                'message' => 'Menambah Data Transaksi Gagal -> ' . $validator->errors(),
                'data' => null,
            ];

            return response()->json($response, Response::HTTP_BAD_REQUEST);
        }

        try {
            if (!is_null($request->file('receipt_of_payment'))) {
                $input['receipt_of_payment'] = $this->uploadFile('storage/transaction', $request->file('receipt_of_payment'));
            }

            $transaction = Transaction::create($input);
            $this->storeDetailTransaction($transaction->id, $cart);

            $response = [
                'status' => 'success',
                'message' => 'Menambah Data Transaksi Sukses',
                'data' => $transaction,
            ];

            return response()->json($response, Response::HTTP_CREATED);
        } catch (QueryException $e) {
            $response = [
                'status' => 'fails',
                'message' => 'Menambah Data Transaksi Gagal -> Server Error',
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
            'subtotal_price' => 'required|numeric',
            'shipping_cost' => 'required|numeric',
            'tax' => 'required|numeric',
            'grand_total_price' => 'required|numeric',
            'receipt_of_payment' => 'nullable',
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
            'receipt_of_payment' => $transaction->receipt_of_payment,
            'customer_id' => $request->input('customer_id'),
            'address_id' => $request->input('address_id'),
            'bank_payment_id' => $request->input('bank_payment_id'),
            'transaction_status_id' => $request->input('transaction_status_id'),
        ];

        $message = [
            'required' => 'Kolom :attribute wajib diisi.',
            'numeric' => 'Kolom :attribute hanya dapat memuat data berupa angka',
        ];

        $validator = Validator::make($input, $rule, $message);

        if ($validator->fails()) {
            $response = [
                'status' => 'fails',
                'message' => 'Mengubah Data Transaksi Gagal -> ' . $validator->errors(),
                'data' => null,
            ];

            return response()->json($response, Response::HTTP_BAD_REQUEST);
        }

        try {
            if (!is_null($request->file('receipt_of_payment'))) {
                $this->destroyFile($transaction->receipt_of_payment);
                $input['receipt_of_payment'] = $this->uploadFile('storage/transaction', $request->file('receipt_of_payment'));
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
                'message' => 'Mengubah Data Transaksi Gagal -> Server Error',
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
        $transaction = Transaction::find($id);

        if (is_null($transaction)) {
            $response = [
                'status' => 'fails',
                'message' => 'Menghapus Data Transaksi Gagal -> Data Tidak Ditemukan',
                'data' => null,
            ];

            return response()->json($response, Response::HTTP_NOT_FOUND);
        }

        try {
            $this->destroyFile($transaction->receipt_of_payment);
            $transaction->delete();

            $response = [
                'status' => 'success',
                'message' => 'Menghapus Data Transaksi Sukses',
                'data' => $transaction,
            ];

            return response()->json($response, Response::HTTP_OK);
        } catch (QueryException $e) {
            $response = [
                'status' => 'fails',
                'message' => 'Menghapus Data Transaksi Gagal -> Server Error',
                'data' => null,
            ];

            return response()->json($response, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  $transaction_id, $cart
     * @return true
     */
    function storeDetailTransaction($transaction_id, $cart)
    {
        for ($i = 0; $i < count($cart); $i++) {
            DetailTransaction::create([
                'amount_of_product' => $cart[$i]->amount_of_product,
                'product_price' => $cart[$i]->price,
                'total_price' => $cart[$i]->total_price,
                'status' => 'success',
                'transaction_id' => $transaction_id,
                'product_id' => $cart[$i]->product_id
            ]);
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
