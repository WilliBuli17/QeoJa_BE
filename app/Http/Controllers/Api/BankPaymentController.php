<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

// Add new library
use Illuminate\Validation\Rule;
use App\Models\BankPayment;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class BankPaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $bankPayment = BankPayment::all();

            $bankPayment->makeHidden(['created_at', 'updated_at']);

            if (count($bankPayment) > 0) {
                $response = [
                    'status' => 'success',
                    'message' => 'Mengambil Data Bank Sukses',
                    'data' => $bankPayment,
                ];

                return response()->json($response, Response::HTTP_OK);
            }

            $response = [
                'status' => 'fails',
                'message' => 'Mengambil Data Bank Gagal -> Data Kosong',
                'data' => null,
            ];

            return response()->json($response, Response::HTTP_NOT_FOUND);
        } catch (QueryException $e) {
            $response = [
                'status' => 'fails',
                'message' => 'Mengambil Data Bank Gagal -> Server Error',
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
            $bankPayment = BankPayment::find($id);

            $bankPayment->makeHidden(['created_at', 'updated_at']);

            if (!is_null($bankPayment)) {
                $response = [
                    'status' => 'success',
                    'message' => 'Mencari Data Bank Sukses',
                    'data' => $bankPayment,
                ];

                return response()->json($response, Response::HTTP_OK);
            }

            $response = [
                'status' => 'fails',
                'message' => 'Mencari Data Bank Gagal -> Data Kosong',
                'data' => null,
            ];

            return response()->json($response, Response::HTTP_NOT_FOUND);
        } catch (QueryException $e) {
            $response = [
                'status' => 'fails',
                'message' => 'Mencari Data Bank Gagal -> Server Error',
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
            'bank_name' => 'required|max:100|unique:bank_payments',
            'account_name' => 'required|max:100',
            'account_number' => 'required|max:100|unique:bank_payments'
        ];

        $input = [
            'bank_name' => $request->input('bank_name'),
            'account_name' => $request->input('account_name'),
            'account_number' => $request->input('account_number')
        ];

        $message = [
            'required' => ':attribute wajib diisi.',
            'unique' => ':attribute sudah terdaftar.',
            'max' => ':attribute hanya dapat memuat maksimal :max karakter'
        ];

        $validator = Validator::make($input, $rule, $message);

        if ($validator->fails()) {
            $response = [
                'status' => 'fails',
                'message' => 'Menambah Data Bank Gagal -> ' . $validator->errors()->first(),
                'data' => null,
            ];

            return response()->json($response, Response::HTTP_BAD_REQUEST);
        }

        try {
            $bankPayment = BankPayment::create($input);

            $response = [
                'status' => 'success',
                'message' => 'Menambah Data Bank Sukses',
                'data' => $bankPayment,
            ];

            return response()->json($response, Response::HTTP_CREATED);
        } catch (QueryException $e) {
            $response = [
                'status' => 'fails',
                'message' => 'Menambah Data Bank Gagal -> Server Error',
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
        $bankPayment = BankPayment::find($id);

        if (is_null($bankPayment)) {
            $response = [
                'status' => 'fails',
                'message' => 'Mengubah Data Bank Gagal -> Data Tidak Ditemukan',
                'data' => null,
            ];

            return response()->json($response, Response::HTTP_NOT_FOUND);
        }

        $rule = [
            'bank_name' => ['required', 'max:100', Rule::unique('bank_payments', 'bank_name')->ignore($id)],
            'account_name' => 'required|max:100',
            'account_number' => ['required', 'max:100', Rule::unique('bank_payments', 'account_number')->ignore($id)]
        ];

        $input = [
            'bank_name' => $request->input('bank_name'),
            'account_name' => $request->input('account_name'),
            'account_number' => $request->input('account_number')
        ];

        $message = [
            'required' => ':attribute wajib diisi.',
            'unique' => ':attribute sudah terdaftar.',
            'max' => ':attribute hanya dapat memuat maksimal :max karakter'
        ];

        $validator = Validator::make($input, $rule, $message);

        if ($validator->fails()) {
            $response = [
                'status' => 'fails',
                'message' => 'Mengubah Data Bank Gagal -> ' . $validator->errors()->first(),
                'data' => null,
            ];

            return response()->json($response, Response::HTTP_BAD_REQUEST);
        }

        try {
            $bankPayment->update($input);

            $response = [
                'status' => 'success',
                'message' => 'Mengubah Data Bank Sukses',
                'data' => $bankPayment,
            ];

            return response()->json($response, Response::HTTP_OK);
        } catch (QueryException $e) {
            $response = [
                'status' => 'fails',
                'message' => 'Mengubah Data Bank Gagal -> Server Error',
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
        $bankPayment = BankPayment::find($id);

        if (is_null($bankPayment)) {
            $response = [
                'status' => 'fails',
                'message' => 'Menghapus Data Bank Gagal -> Data Tidak Ditemukan',
                'data' => null,
            ];

            return response()->json($response, Response::HTTP_NOT_FOUND);
        }

        try {
            $bankPayment->delete();

            $response = [
                'status' => 'success',
                'message' => 'Menghapus Data Bank Sukses',
                'data' => $bankPayment,
            ];

            return response()->json($response, Response::HTTP_OK);
        } catch (QueryException $e) {
            $response = [
                'status' => 'fails',
                'message' => 'Menghapus Data Bank Gagal -> Server Error',
                'data' => null,
            ];

            return response()->json($response, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
