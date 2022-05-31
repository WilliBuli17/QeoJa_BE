<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

// Add new library
use Illuminate\Validation\Rule;
use App\Models\ExpeditionTruck;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\File;

class ExpeditionTruckController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $expeditionTruck = ExpeditionTruck::orderBy('expedition_trucks.id')
                ->get([
                    'expedition_trucks.id',
                    'expedition_trucks.license_id',
                    'expedition_trucks.min_volume',
                    'expedition_trucks.max_volume',
                    'expedition_trucks.status',
                    'expedition_trucks.picture'
                ]);

            if (count($expeditionTruck) > 0) {
                $response = [
                    'status' => 'success',
                    'message' => 'Mengambil Data Truk Ekspedisi Sukses',
                    'data' => $expeditionTruck,
                ];

                return response()->json($response, Response::HTTP_OK);
            }

            $response = [
                'status' => 'fails',
                'message' => 'Mengambil Data Truk Ekspedisi Gagal -> Data Kosong',
                'data' => null,
            ];

            return response()->json($response, Response::HTTP_NOT_FOUND);
        } catch (QueryException $e) {
            $response = [
                'status' => 'fails',
                'message' => 'Mengambil Data Truk Ekspedisi Gagal -> Server Error',
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
            'license_id' => 'required|unique:expedition_trucks',
            'min_volume' => 'required|numeric',
            'max_volume' => 'required|numeric',
        ];

        $input = [
            'license_id' => $request->input('license_id'),
            'min_volume' => $request->input('min_volume'),
            'max_volume' => $request->input('max_volume'),
            'picture' => 'no-image.jpg',
            'status' => 'available'
        ];

        $message = [
            'required' => ':attribute wajib diisi.',
            'unique' => ':attribute sudah terdaftar.',
            'numeric' => ':attribute hanya dapat memuat data berupa angka',
        ];

        $validator = Validator::make($input, $rule, $message);

        if ($validator->fails()) {
            $response = [
                'status' => 'fails',
                'message' => 'Menambah Data Truk Ekspedisi Gagal -> ' . $validator->errors()->first(),
                'data' => null,
            ];

            return response()->json($response, Response::HTTP_BAD_REQUEST);
        }

        try {
            if (!is_null($request->file('picture'))) {
                $input['picture'] = $this->uploadFile('storage/expeditionTruck', $request->file('picture'));
            }

            $expeditionTruck = ExpeditionTruck::create($input);

            $response = [
                'status' => 'success',
                'message' => 'Menambah Data Truk Ekspedisi Sukses',
                'data' => $expeditionTruck,
            ];

            return response()->json($response, Response::HTTP_CREATED);
        } catch (QueryException $e) {
            $response = [
                'status' => 'fails',
                'message' => 'Menambah Data Truk Ekspedisi Gagal -> Server Error',
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
        $expeditionTruck = ExpeditionTruck::find($id);

        if (is_null($expeditionTruck)) {
            $response = [
                'status' => 'fails',
                'message' => 'Mengubah Data Truk Ekspedisi Gagal -> Data Tidak Ditemukan',
                'data' => null,
            ];

            return response()->json($response, Response::HTTP_NOT_FOUND);
        }

        $rule = [
            'license_id' => ['required', Rule::unique('expedition_trucks', 'license_id')->ignore($id)],
            'min_volume' => 'required|numeric',
            'max_volume' => 'required|numeric',
        ];

        $input = [
            'license_id' => $request->input('license_id'),
            'min_volume' => $request->input('min_volume'),
            'max_volume' => $request->input('max_volume'),
            'picture' => $expeditionTruck->picture,
            'status' => $expeditionTruck->status
        ];

        $message = [
            'required' => ':attribute wajib diisi.',
            'unique' => ':attribute sudah terdaftar.',
            'numeric' => ':attribute hanya dapat memuat data berupa angka',
        ];

        $validator = Validator::make($input, $rule, $message);

        if ($validator->fails()) {
            $response = [
                'status' => 'fails',
                'message' => 'Mengubah Data Truk Ekspedisi Gagal -> ' . $validator->errors()->first(),
                'data' => null,
            ];

            return response()->json($response, Response::HTTP_BAD_REQUEST);
        }

        try {
            if (!is_null($request->file('picture'))) {
                $this->destroyFile($expeditionTruck->picture);
                $input['picture'] = $this->uploadFile('storage/expeditionTruck', $request->file('picture'));
            }

            $expeditionTruck->update($input);

            $response = [
                'status' => 'success',
                'message' => 'Mengubah Data Truk Ekspedisi Sukses',
                'data' => $expeditionTruck,
            ];

            return response()->json($response, Response::HTTP_OK);
        } catch (QueryException $e) {
            $response = [
                'status' => 'fails',
                'message' => 'Mengubah Data Truk Ekspedisi Gagal -> Server Error',
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
        $expeditionTruck = ExpeditionTruck::find($id);

        if (is_null($expeditionTruck)) {
            $response = [
                'status' => 'fails',
                'message' => 'Menghapus Data Truk Ekspedisi Gagal -> Data Tidak Ditemukan',
                'data' => null,
            ];

            return response()->json($response, Response::HTTP_NOT_FOUND);
        }

        try {
            $this->destroyFile($expeditionTruck->picture);
            $expeditionTruck->delete();

            $response = [
                'status' => 'success',
                'message' => 'Menghapus Data Truk Ekspedisi Sukses',
                'data' => $expeditionTruck,
            ];

            return response()->json($response, Response::HTTP_OK);
        } catch (QueryException $e) {
            $response = [
                'status' => 'fails',
                'message' => 'Menghapus Data Truk Ekspedisi Gagal -> Server Error',
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
        if ($fileName !== 'no-image.jpg') {
            File::delete($fileName);
        }

        return true;
    }
}
