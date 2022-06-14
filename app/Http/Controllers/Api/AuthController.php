<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

// Add new library
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    /**
     * Login to sistem.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $rules = [
            'reference' => 'required',
            'email' => 'required|email:rfc,dns',
            'password' => 'required',
        ];

        $input = [
            'reference' => $request->input('reference'),
            'email' => $request->input('email'),
            'password' => $request->input('password')
        ];

        $messages = [
            'required' => 'Kolom :attribute wajib diisi.',
            'email' => 'Kolom :attribute tidak sesuai format.'
        ];

        $validator = Validator::make($input, $rules, $messages);

        if ($validator->fails()) {
            $response = [
                'status' => 'fails',
                'message' => 'Log In Gagal -> ' . $validator->errors()->first(),
                'data' => null,
            ];

            return response()->json($response, Response::HTTP_BAD_REQUEST);
        }

        try {
            if (!Auth::attempt($input)) {
                $response = [
                    'status' => 'fails',
                    'message' => 'Akun Tidak Terdeteksi Oleh Sistem',
                    'data' => null,
                ];

                return response()->json($response, Response::HTTP_UNAUTHORIZED);
            }

            /** @var \App\Models\User $user **/
            $user = Auth::user();

            if(is_null($user->deleted_at)){
                $token = $user->createToken('Authentication Token')->accessToken;

                $response = [
                    'status' => 'success',
                    'message' => 'Autentikasi Sukses',
                    'data' => $user->id,
                    'token_type' => 'Bearer',
                    'access_token' => $token
                ];

                return response()->json($response, Response::HTTP_OK);  //Return Response Sukses
            } else {
                $response = [
                    'status' => 'fails',
                    'message' => 'Akun Ditangguhkan -> Hubungi Admin Terkait Untuk Mengaktifkan Kembali',
                    'data' => null,
                ];

                return response()->json($response, Response::HTTP_FORBIDDEN);
            }
        } catch (QueryException $e) {
            $response = [
                'status' => 'fails',
                'message' => 'Autentikasi Gagal',
                'data' => null,
            ];
            return response()->json($response, Response::HTTP_INTERNAL_SERVER_ERROR); //Return Response Gagal
        }
    }

    /**
     * Logout from sistem.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        try {
            $request->user()->token()->revoke();
            $response = [
                'status' => 'success',
                'message' => 'Log Out Sukses',
                'data' => null,
            ];
            return response()->json($response, Response::HTTP_OK);
        } catch (QueryException $e) {
            $response = [
                'status' => 'fails',
                'message' => 'Log Out Gagal ',
                'data' => null,
            ];
            return response()->json($response, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
