<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

// Add new library
use Illuminate\Validation\Rule;
use App\Models\User;
use App\Models\Employee;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\File;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $employee = Employee::leftJoin('roles', 'employees.role_id', '=', 'roles.id')
                ->leftJoin('users', 'employees.user_id', '=', 'users.id')
                ->withTrashed()
                ->orderBy('employees.deleted_at', 'DESC')
                ->get([
                    'roles.name AS role',
                    'users.email',
                    'employees.id',
                    'employees.name',
                    'employees.gander',
                    'employees.phone',
                    'employees.address',
                    'employees.date_join',
                    'employees.role_id',
                    'employees.picture',
                    'employees.deleted_at'
                ]);

            if (count($employee) > 0) {
                $response = [
                    'status' => 'success',
                    'message' => 'Mengambil Data Pegawai Sukses',
                    'data' => $employee,
                ];

                return response()->json($response, Response::HTTP_OK);
            }

            $response = [
                'status' => 'fails',
                'message' => 'Mengambil Data Pegawai Gagal -> Data Kosong',
                'data' => null,
            ];

            return response()->json($response, Response::HTTP_NOT_FOUND);
        } catch (QueryException $e) {
            $response = [
                'status' => 'fails',
                'message' => 'Mengambil Data Pegawai Gagal -> Server Error',
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
            $employee = Employee::leftJoin('roles', 'employees.role_id', '=', 'roles.id')
                ->leftJoin('users', 'employees.user_id', '=', 'users.id')
                ->where('employees.user_id', '=', $id)
                ->get([
                    'roles.name AS role',
                    'users.email',
                    'employees.id',
                    'employees.name',
                    'employees.gander',
                    'employees.phone',
                    'employees.address',
                    'employees.date_join',
                    'employees.role_id',
                    'employees.picture',
                    'employees.deleted_at'
                ]);

            if (count($employee) == 1) {
                $response = [
                    'status' => 'success',
                    'message' => 'Mencari Data Pegawai Sukses',
                    'data' => $employee,
                ];

                return response()->json($response, Response::HTTP_OK);
            }

            $response = [
                'status' => 'fails',
                'message' => 'Mencari Data Pegawai Gagal -> Data Kosong',
                'data' => null,
            ];

            return response()->json($response, Response::HTTP_NOT_FOUND);
        } catch (QueryException $e) {
            $response = [
                'status' => 'fails',
                'message' => 'Mencari Data Pegawai Gagal -> Server Error',
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
            'email' => 'required|email:rfc,dns|unique:users',
            'name' => 'required|max:100',
            'gander' => 'required|in:man,woman',
            'phone' => ['required', 'regex:/\(?(?:\+62|62|0)(?:\d{2,3})?\)?[ .-]?\d{2,4}[ .-]?\d{2,4}[ .-]?\d{2,4}/i'],
            'address' => 'required',
            'date_join' => 'required|date|date_format:Y-m-d',
            'role_id' => 'required',
        ];

        $input = [
            'email' => $request->input('email'),
            'name' => $request->input('name'),
            'gander' => $request->input('gander'),
            'phone' => $request->input('phone'),
            'address' => $request->input('address'),
            'date_join' => $request->input('date_join'),
            'role_id' => $request->input('role_id'),
        ];

        $message = [
            'required' => ':attribute wajib diisi.',
            'in' => ':attribute tidak valid.',
            'email' => ':attribute tidak sesuai format.',
            'unique' => ':attribute sudah terdaftar.',
            'max' => ':attribute hanya dapat memuat maksimal :max karakter.',
            'regex' => ':attribute tidak valid.',
            'date' => ':attribute hanya dapat memuat data berupa tanggal.',
            'date_format' => ':attribute tidak sesuai format penanggalan sistem.',
        ];

        $validator = Validator::make($input, $rule, $message);

        if ($validator->fails()) {
            $response = [
                'status' => 'fails',
                'message' => 'Menambah Data Pegawai Gagal -> ' . $validator->errors()->first(),
                'data' => null,
            ];

            return response()->json($response, Response::HTTP_BAD_REQUEST);
        }

        try {
            $inputUser = [
                'reference' => 'employee',
                'email' => $input['email'],
                'password' => bcrypt('12345678'),
            ];

            $user = User::create($inputUser);
            $user->markEmailAsVerified();

            $inputEmployee = [
                'name' => $input['name'],
                'gander' => $input['gander'],
                'phone' => $input['phone'],
                'address' => $input['address'],
                'date_join' => $input['date_join'],
                'picture' => 'no-image.jpg',
                'role_id' => $input['role_id'],
                'user_id' => $user->id,
            ];

            if (!is_null($request->file('picture'))) {
                $inputEmployee['picture'] = $this->uploadFile('storage/employee', $request->file('picture'));
            }

            $employee = Employee::create($inputEmployee);

            $response = [
                'status' => 'success',
                'message' => 'Menambah Data Pegawai Sukses',
                'data' => $employee,
            ];

            return response()->json($response, Response::HTTP_CREATED);
        } catch (QueryException $e) {
            $response = [
                'status' => 'fails',
                'message' => 'Menambah Data Pegawai Gagal -> Server Error',
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
        $employee = Employee::withTrashed()->find($id);

        if (is_null($employee)) {
            $response = [
                'status' => 'fails',
                'message' => 'Mengubah Data Pegawai Gagal -> Data Tidak Ditemukan',
                'data' => null,
            ];

            return response()->json($response, Response::HTTP_NOT_FOUND);
        }

        $user = User::withTrashed()->find($employee->user_id);

        if (is_null($user)) {
            $response = [
                'status' => 'fails',
                'message' => 'Mengubah Data Pegawai Gagal -> Data Tidak Ditemukan',
                'data' => null,
            ];

            return response()->json($response, Response::HTTP_NOT_FOUND);
        }

        $rule = [
            'email' => ['required', 'email:rfc,dns', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => 'nullable',
            'name' => 'required|max:100',
            'gander' => 'required|in:man,woman',
            'phone' => ['required', 'regex:/\(?(?:\+62|62|0)(?:\d{2,3})?\)?[ .-]?\d{2,4}[ .-]?\d{2,4}[ .-]?\d{2,4}/i'],
            'address' => 'required',
            'date_join' => 'required|date|date_format:Y-m-d',
            'role_id' => 'required',
        ];

        $input = [
            'email' => $request->input('email'),
            'password' => $request->input('password'),
            'name' => $request->input('name'),
            'gander' => $request->input('gander'),
            'phone' => $request->input('phone'),
            'address' => $request->input('address'),
            'date_join' => $request->input('date_join'),
            'role_id' => $request->input('role_id'),
        ];

        $message = [
            'required' => ':attribute wajib diisi.',
            'in' => ':attribute tidak valid.',
            'email' => ':attribute tidak sesuai format.',
            'unique' => ':attribute sudah terdaftar.',
            'max' => ':attribute hanya dapat memuat maksimal :max karakter.',
            'regex' => ':attribute tidak valid.',
            'date' => ':attribute hanya dapat memuat data berupa tanggal.',
            'date_format' => ':attribute tidak sesuai format penanggalan sistem.',
        ];

        $validator = Validator::make($input, $rule, $message);

        if ($validator->fails()) {
            $response = [
                'status' => 'fails',
                'message' => 'Menambah Data Pegawai Gagal -> ' . $validator->errors()->first(),
                'data' => null,
            ];

            return response()->json($response, Response::HTTP_BAD_REQUEST);
        }

        try {
            $inputUser = [
                'reference' => 'employee',
                'email' => $input['email'],
                'password' => $user->password,
            ];

            if (!is_null($request->input('password'))) {
                $inputUser['password'] = bcrypt($input['password']);
            }

            $user->update($inputUser);

            $inputEmployee = [
                'name' => $input['name'],
                'gander' => $input['gander'],
                'phone' => $input['phone'],
                'address' => $input['address'],
                'date_join' => $input['date_join'],
                'picture' => $employee->picture,
                'role_id' => $input['role_id'],
                'user_id' => $user->id,
            ];

            if (!is_null($request->file('picture'))) {
                $this->destroyFile($employee->picture);
                $inputEmployee['picture'] = $this->uploadFile('storage/employee', $request->file('picture'));
            }

            $employee->update($inputEmployee);

            $response = [
                'status' => 'success',
                'message' => 'Mengubah Data Pegawai Sukses',
                'data' => $employee,
            ];

            return response()->json($response, Response::HTTP_OK);
        } catch (QueryException $e) {
            $response = [
                'status' => 'fails',
                'message' => 'Mengubah Data Pegawai Gagal -> Server Error',
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
        $employee = Employee::withTrashed()->find($id);

        if (is_null($employee)) {
            $response = [
                'status' => 'fails',
                'message' => 'Menghapus/Mengembalikan Data Pegawai Gagal -> Data Tidak Ditemukan',
                'data' => null,
            ];

            return response()->json($response, Response::HTTP_NOT_FOUND);
        }

        $user = User::withTrashed()->find($employee->user_id);

        if (is_null($user)) {
            $response = [
                'status' => 'fails',
                'message' => 'Menghapus/Mengembalikan Data Pegawai Gagal -> Data Tidak Ditemukan',
                'data' => null,
            ];

            return response()->json($response, Response::HTTP_NOT_FOUND);
        }

        try {
            if (is_null($user->deleted_at) && is_null($employee->deleted_at)) {
                $employee->delete();
                $user->delete();
            } else {
                $employee->restore();
                $user->restore();
            }

            $response = [
                'status' => 'success',
                'message' => 'Menghapus/Mengembalikan Data Pegawai Sukses',
                'data' => $employee,
            ];

            return response()->json($response, Response::HTTP_OK);
        } catch (QueryException $e) {
            $response = [
                'status' => 'fails',
                'message' => 'Menghapus/Mengembalikan Data Pegawai Gagal -> Server Error',
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
