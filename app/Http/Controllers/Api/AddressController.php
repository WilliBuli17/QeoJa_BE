<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

// Add new library
use App\Models\Address;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class AddressController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        try {
            $address = Address::join('cities', 'addresses.city_id', '=', 'cities.id')
                ->where('addresses.customer_id', '=', $id)
                ->orderBy('addresses.created_at', 'ASC')
                ->get(['cities.name as city', 'addresses.*']);

            $address->makeHidden(['created_at', 'updated_at']);

            if (count($address) > 0) {
                $response = [
                    'status' => 'success',
                    'message' => 'Mengambil Data Alamat Sukses',
                    'data' => $address,
                ];

                return response()->json($response, Response::HTTP_OK);
            }

            $response = [
                'status' => 'fails',
                'message' => 'Mengambil Data Alamat Gagal -> Data Kosong',
                'data' => null,
            ];

            return response()->json($response, Response::HTTP_NOT_FOUND);
        } catch (QueryException $e) {
            $response = [
                'status' => 'fails',
                'message' => 'Mengambil Data Alamat Gagal -> Server Error',
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
            $address = Address::join('customers', 'addresses.customer_id', '=', 'customers.id')
                ->join('cities', 'addresses.city_id', '=', 'cities.id')
                ->where('addresses.id', '=', $id)
                ->get(['customers.name', 'cities.name', 'addresses.*']);

            if (count($address) != 1) {
                $response = [
                    'status' => 'success',
                    'message' => 'Mencari Data Alamat Sukses',
                    'data' => $address,
                ];

                return response()->json($response, Response::HTTP_OK);
            }

            $response = [
                'status' => 'fails',
                'message' => 'Mencari Data Alamat Gagal -> Data Kosong',
                'data' => null,
            ];

            return response()->json($response, Response::HTTP_NOT_FOUND);
        } catch (QueryException $e) {
            $response = [
                'status' => 'fails',
                'message' => 'Mencari Data Alamat Gagal -> Server Error',
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
            'address' => 'required',
            'customer_id' => 'required',
            'city_id' => 'required'
        ];

        $input = [
            'address' => $request->input('address'),
            'customer_id' => $request->input('customer_id'),
            'city_id' => $request->input('city_id')
        ];

        $message = [
            'required' => 'Kolom :attribute wajib diisi.',
        ];

        $validator = Validator::make($input, $rule, $message);

        if ($validator->fails()) {
            $response = [
                'status' => 'fails',
                'message' => 'Menambah Data Alamat Gagal -> ' . $validator->errors()->first(),
                'data' => null,
            ];

            return response()->json($response, Response::HTTP_BAD_REQUEST);
        }

        try {
            $address = Address::create($input);

            $response = [
                'status' => 'success',
                'message' => 'Menambah Data Alamat Sukses',
                'data' => $address,
            ];

            return response()->json($response, Response::HTTP_CREATED);
        } catch (QueryException $e) {
            $response = [
                'status' => 'fails',
                'message' => 'Menambah Data Alamat Gagal -> Server Error',
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
        $address = Address::find($id);

        if (is_null($address)) {
            $response = [
                'status' => 'fails',
                'message' => 'Menghapus Data Alamat Gagal -> Data Tidak Ditemukan',
                'data' => null,
            ];

            return response()->json($response, Response::HTTP_NOT_FOUND);
        }

        $rule = [
            'address' => 'required',
            'customer_id' => 'required',
            'city_id' => 'required'
        ];

        $input = [
            'address' => $request->input('address'),
            'customer_id' => $request->input('customer_id'),
            'city_id' => $request->input('city_id')
        ];

        $message = [
            'required' => 'Kolom :attribute wajib diisi.',
        ];

        $validator = Validator::make($input, $rule, $message);

        if ($validator->fails()) {
            $response = [
                'status' => 'fails',
                'message' => 'Menambah Data Alamat Gagal -> ' . $validator->errors()->first(),
                'data' => null,
            ];

            return response()->json($response, Response::HTTP_BAD_REQUEST);
        }

        try {
            $address->update($input);

            $response = [
                'status' => 'success',
                'message' => 'Mengubah Data Alamat Sukses',
                'data' => $address,
            ];

            return response()->json($response, Response::HTTP_OK);
        } catch (QueryException $e) {
            $response = [
                'status' => 'fails',
                'message' => 'Mengubah Data Alamat Gagal -> Server Error',
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
        $address = Address::find($id);

        if (is_null($address)) {
            $response = [
                'status' => 'fails',
                'message' => 'Menghapus Data Alamat Gagal -> Data Tidak Ditemukan',
                'data' => null,
            ];

            return response()->json($response, Response::HTTP_NOT_FOUND);
        }

        try {
            $address->delete();

            $response = [
                'status' => 'success',
                'message' => 'Menghapus Data Alamat Sukses',
                'data' => $address,
            ];

            return response()->json($response, Response::HTTP_OK);
        } catch (QueryException $e) {
            $response = [
                'status' => 'fails',
                'message' => 'Menghapus Data Alamat Gagal -> Server Error',
                'data' => null,
            ];

            return response()->json($response, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
