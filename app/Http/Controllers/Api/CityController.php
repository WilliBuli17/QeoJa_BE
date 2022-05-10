<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

// Add new library
use Illuminate\Validation\Rule;
use App\Models\City;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class CityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $city = City::all();

            if (count($city) > 0) {
                $response = [
                    'status' => 'success',
                    'message' => 'Mengambil Data Kota Sukses',
                    'data' => $city,
                ];

                return response()->json($response, Response::HTTP_OK);
            }

            $response = [
                'status' => 'fails',
                'message' => 'Mengambil Data Kota Gagal -> Data Kosong',
                'data' => null,
            ];

            return response()->json($response, Response::HTTP_NOT_FOUND);
        } catch (QueryException $e) {
            $response = [
                'status' => 'fails',
                'message' => 'Mengambil Data Kota Gagal -> Server Error',
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
            $city = City::find($id);

            if (!is_null($city)) {
                $response = [
                    'status' => 'success',
                    'message' => 'Mencari Data Kota Sukses',
                    'data' => $city,
                ];

                return response()->json($response, Response::HTTP_OK);
            }

            $response = [
                'status' => 'fails',
                'message' => 'Mencari Data Kota Gagal -> Data Kosong',
                'data' => null,
            ];

            return response()->json($response, Response::HTTP_NOT_FOUND);
        } catch (QueryException $e) {
            $response = [
                'status' => 'fails',
                'message' => 'Mencari Data Kota Gagal -> Server Error',
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
            'name' => 'required|max:60|unique:cities',
            'expedition_cost' => 'required|numeric|digits_between:6,7'
        ];

        $input = [
            'name' => $request->input('name'),
            'expedition_cost' => $request->input('expedition_cost'),
        ];

        $message = [
            'required' => 'Kolom :attribute wajib diisi.',
            'unique' => 'Kolom :attribute sudah terdaftar.',
            'max' => 'Kolom :attribute hanya dapat memuat maksimal :max karakter',
            'numeric' => 'Kolom :attribute hanya dapat memuat data berupa angka',
            'digits_between' => 'Kolom :attribute hanya dapat memuat antara 6-7 digit',
        ];

        $validator = Validator::make($input, $rule, $message);

        if ($validator->fails()) {
            $response = [
                'status' => 'fails',
                'message' => 'Menambah Data Kota Gagal -> ' . $validator->errors(),
                'data' => null,
            ];

            return response()->json($response, Response::HTTP_BAD_REQUEST);
        }

        try {
            $city = City::create($input);

            $response = [
                'status' => 'success',
                'message' => 'Menambah Data Kota Sukses',
                'data' => $city,
            ];

            return response()->json($response, Response::HTTP_CREATED);
        } catch (QueryException $e) {
            $response = [
                'status' => 'fails',
                'message' => 'Menambah Data Kota Gagal -> Server Error',
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
        $city = City::find($id);

        if (is_null($city)) {
            $response = [
                'status' => 'fails',
                'message' => 'Menghapus Data Kota Gagal -> Data Tidak Ditemukan',
                'data' => null,
            ];

            return response()->json($response, Response::HTTP_NOT_FOUND);
        }

        $rule = [
            'name' => ['required', 'max:60', Rule::unique('cities', 'name')->ignore($id)],
            'expedition_cost' => 'required|numeric|digits_between:6,7'
        ];

        $input = [
            'name' => $request->input('name'),
            'expedition_cost' => $request->input('expedition_cost'),
        ];

        $message = [
            'required' => 'Kolom :attribute wajib diisi.',
            'unique' => 'Kolom :attribute sudah terdaftar.',
            'max' => 'Kolom :attribute hanya dapat memuat maksimal :max karakter',
            'numeric' => 'Kolom :attribute hanya dapat memuat data berupa angka',
            'digits_between' => 'Kolom :attribute hanya dapat memuat antara 6-7 digit',
        ];

        $validator = Validator::make($input, $rule, $message);

        if ($validator->fails()) {
            $response = [
                'status' => 'fails',
                'message' => 'Menambah Data Kota Gagal -> ' . $validator->errors(),
                'data' => null,
            ];

            return response()->json($response, Response::HTTP_BAD_REQUEST);
        }

        try {
            $city->update($input);

            $response = [
                'status' => 'success',
                'message' => 'Mengubah Data Kota Sukses',
                'data' => $city,
            ];

            return response()->json($response, Response::HTTP_OK);
        } catch (QueryException $e) {
            $response = [
                'status' => 'fails',
                'message' => 'Mengubah Data Kota Gagal -> Server Error',
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
        $city = City::find($id);

        if (is_null($city)) {
            $response = [
                'status' => 'fails',
                'message' => 'Menghapus Data Kota Gagal -> Data Tidak Ditemukan',
                'data' => null,
            ];

            return response()->json($response, Response::HTTP_NOT_FOUND);
        }

        try {
            $city->delete();

            $response = [
                'status' => 'success',
                'message' => 'Menghapus Data Kota Sukses',
                'data' => $city,
            ];

            return response()->json($response, Response::HTTP_OK);
        } catch (QueryException $e) {
            $response = [
                'status' => 'fails',
                'message' => 'Menghapus Data Kota Gagal -> Server Error',
                'data' => null,
            ];

            return response()->json($response, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
