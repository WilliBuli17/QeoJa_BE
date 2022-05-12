<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

// Add new library
use Illuminate\Validation\Rule;
use App\Models\User;
use App\Models\Customer;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\File;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $customer = Customer::join('users', 'customers.user_id', '=', 'users.id')
                ->withTrashed()
                ->get(['users.*', 'customers.*']);

            if (count($customer) > 0) {
                $response = [
                    'status' => 'success',
                    'message' => 'Mengambil Data Pelanggan Sukses',
                    'data' => $customer,
                ];

                return response()->json($response, Response::HTTP_OK);
            }

            $response = [
                'status' => 'fails',
                'message' => 'Mengambil Data Pelanggan Gagal -> Data Kosong',
                'data' => null,
            ];

            return response()->json($response, Response::HTTP_NOT_FOUND);
        } catch (QueryException $e) {
            $response = [
                'status' => 'fails',
                'message' => 'Mengambil Data Pelanggan Gagal -> Server Error',
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
            $customer = Customer::join('users', 'customers.user_id', '=', 'users.id')
                ->withTrashed()
                ->where('customers.id', '=', $id)
                ->get(['users.*', 'customers.*']);

            if (count($customer) == 1) {
                $response = [
                    'status' => 'success',
                    'message' => 'Mencari Data Pelanggan Sukses',
                    'data' => $customer,
                ];

                return response()->json($response, Response::HTTP_OK);
            }

            $response = [
                'status' => 'fails',
                'message' => 'Mencari Data Pelanggan Gagal -> Data Kosong',
                'data' => null,
            ];

            return response()->json($response, Response::HTTP_NOT_FOUND);
        } catch (QueryException $e) {
            $response = [
                'status' => 'fails',
                'message' => 'Mencari Data Pelanggan Gagal -> Server Error',
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
            'reference' => 'required|in:employee,customer',
            'email' => 'required|email:rfc,dns|unique:users',
            'password' => 'required|min:8',
            'name' => 'required|max:60',
            'phone' => ['required', 'regex:/\(?(?:\+62|62|0)(?:\d{2,3})?\)?[ .-]?\d{2,4}[ .-]?\d{2,4}[ .-]?\d{2,4}/i'],
        ];

        $input = [
            'reference' => $request->input('reference'),
            'email' => $request->input('email'),
            'password' => $request->input('password'),
            'name' => $request->input('name'),
            'phone' => $request->input('phone'),
        ];

        $message = [
            'required' => 'Kolom :attribute wajib diisi.',
            'in' => 'Kolom :attribute tidak valid.',
            'email' => 'Kolom :attribute tidak sesuai format.',
            'unique' => 'Kolom :attribute sudah terdaftar.',
            'min' => 'Kolom :attribute hanya dapat memuat minimal :min karakter.',
            'max' => 'Kolom :attribute hanya dapat memuat maksimal :max karakter.',
            'regex' => 'Kolom :attribute tidak valid.',
        ];

        $validator = Validator::make($input, $rule, $message);

        if ($validator->fails()) {
            $response = [
                'status' => 'fails',
                'message' => 'Menambah Data Pelanggan Gagal -> ' . $validator->errors(),
                'data' => null,
            ];

            return response()->json($response, Response::HTTP_BAD_REQUEST);
        }

        try {
            $inputUser = [
                'reference' => $input['reference'],
                'email' => $input['email'],
                'password' => bcrypt($input['password']),
            ];

            $user = User::create($inputUser);

            $inputCustomer = [
                'name' => $input['name'],
                'phone' => $input['phone'],
                'picture' => 'no_image.png',
                'user_id' => $user->id,
            ];

            if (!is_null($request->file('picture'))) {
                $inputCustomer['picture'] = $this->uploadFile('storage/customer', $request->file('picture'));
            }

            $customer = Customer::create($inputCustomer);

            $response = [
                'status' => 'success',
                'message' => 'Menambah Data Pelanggan Sukses',
                'data' => $customer,
            ];

            return response()->json($response, Response::HTTP_CREATED);
        } catch (QueryException $e) {
            $response = [
                'status' => 'fails',
                'message' => 'Menambah Data Pelanggan Gagal -> Server Error',
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
        $customer = Customer::withTrashed()
            ->where('id', '=', $id)
            ->get();

        if (count($customer) != 1) {
            $response = [
                'status' => 'fails',
                'message' => 'Mengubah Data Pelanggan Gagal -> Data Tidak Ditemukan',
                'data' => null,
            ];

            return response()->json($response, Response::HTTP_NOT_FOUND);
        }

        $user = User::withTrashed()
            ->where('id', '=', $customer->user_id)
            ->get();

        if (count($user) != 1) {
            $response = [
                'status' => 'fails',
                'message' => 'Mengubah Data Pelanggan Gagal -> Data Tidak Ditemukan',
                'data' => null,
            ];

            return response()->json($response, Response::HTTP_NOT_FOUND);
        }

        $rule = [
            'reference' => 'required|in:employee,customer',
            'email' => ['required', 'email:rfc,dns', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => 'nullable|min:8',
            'name' => 'required|max:60',
            'phone' => ['required', 'regex:/\(?(?:\+62|62|0)(?:\d{2,3})?\)?[ .-]?\d{2,4}[ .-]?\d{2,4}[ .-]?\d{2,4}/i'],
        ];

        $input = [
            'reference' => $request->input('reference'),
            'email' => $request->input('email'),
            'password' => $request->input('password'),
            'name' => $request->input('name'),
            'phone' => $request->input('phone'),
        ];

        $message = [
            'required' => 'Kolom :attribute wajib diisi.',
            'in' => 'Kolom :attribute tidak valid.',
            'email' => 'Kolom :attribute tidak sesuai format.',
            'unique' => 'Kolom :attribute sudah terdaftar.',
            'min' => 'Kolom :attribute hanya dapat memuat minimal :min karakter.',
            'max' => 'Kolom :attribute hanya dapat memuat maksimal :max karakter.',
            'regex' => 'Kolom :attribute tidak valid.',
        ];

        $validator = Validator::make($input, $rule, $message);

        if ($validator->fails()) {
            $response = [
                'status' => 'fails',
                'message' => 'Menambah Data Pelanggan Gagal -> ' . $validator->errors(),
                'data' => null,
            ];

            return response()->json($response, Response::HTTP_BAD_REQUEST);
        }

        try {
            $inputUser = [
                'reference' => $input['reference'],
                'email' => $input['email'],
                'password' => $user->password,
            ];

            if (!is_null($request->input('password'))) {
                $inputUser['password'] = bcrypt($input['password']);
            }

            $user->update($inputUser);

            $inputCustomer = [
                'name' => $input['name'],
                'phone' => $input['phone'],
                'picture' => $customer->picture,
                'user_id' => $user->id,
            ];

            if (!is_null($request->file('picture'))) {
                $this->destroyFile($customer->picture);
                $inputCustomer['picture'] = $this->uploadFile('storage/customer', $request->file('picture'));
            }

            $customer->update($inputCustomer);

            $response = [
                'status' => 'success',
                'message' => 'Mengubah Data Pelanggan Sukses',
                'data' => $customer,
            ];

            return response()->json($response, Response::HTTP_OK);
        } catch (QueryException $e) {
            $response = [
                'status' => 'fails',
                'message' => 'Mengubah Data Pelanggan Gagal -> Server Error',
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
        $customer = Customer::withTrashed()
            ->where('id', '=', $id)
            ->get();

        if (count($customer) != 1) {
            $response = [
                'status' => 'fails',
                'message' => 'Mengubah Data Pelanggan Gagal -> Data Tidak Ditemukan',
                'data' => null,
            ];

            return response()->json($response, Response::HTTP_NOT_FOUND);
        }

        $user = User::withTrashed()
            ->where('id', '=', $customer->user_id)
            ->get();

        if (count($user) != 1) {
            $response = [
                'status' => 'fails',
                'message' => 'Mengubah Data Pelanggan Gagal -> Data Tidak Ditemukan',
                'data' => null,
            ];

            return response()->json($response, Response::HTTP_NOT_FOUND);
        }

        try {
            $customer->delete();
            $user->delete();

            $response = [
                'status' => 'success',
                'message' => 'Menghapus Data Pelanggan Sukses',
                'data' => $customer,
            ];

            return response()->json($response, Response::HTTP_OK);
        } catch (QueryException $e) {
            $response = [
                'status' => 'fails',
                'message' => 'Menghapus Data Pelanggan Gagal -> Server Error',
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
