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
            'email' => 'required|email:rfc,dns',
            'password' => 'required'
        ];

        $input = [
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
                'message' => 'Log In Gagal -> ' . $validator->errors(),
                'data' => null,
            ];

            return response()->json($response, Response::HTTP_BAD_REQUEST);
        }

        try {
            if (!Auth::attempt($input)) {
                $response = [
                    'status' => 'fails',
                    'message' => 'Pengguna Tidak Dikenal Karena Email atau Password Salah.',
                    'data' => null,
                ];

                return response()->json($response, Response::HTTP_UNAUTHORIZED);
            }

            /** @var \App\Models\User $user **/
            $user = Auth::user();
            $token = $user->createToken('Authentication Token')->accessToken;

            $response = [
                'status' => 'success',
                'message' => 'Autentikasi Sukses',
                'data' => $user,
                'token_type' => 'Bearer',
                'access_token' => $token
            ];

            return response()->json($response, Response::HTTP_OK);  //Return Response Sukses
        } catch (QueryException $e) {
            $response = [
                'status' => 'fails',
                'message' => 'Autentikasi Gagal ',
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
