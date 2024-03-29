<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

// Add new library
use Illuminate\Validation\Rule;
use App\Models\Product;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\File;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $product = Product::leftJoin('categories', 'products.category_id', '=', 'categories.id')
                ->leftJoin('suppliers', 'products.suplier_id', '=', 'suppliers.id')
                ->withTrashed()
                ->orderBy('products.deleted_at', 'ASC')
                ->orderBy('products.stock_quantity', 'ASC')
                ->get([
                    'categories.name AS category',
                    'suppliers.name AS suplier',
                    'products.id',
                    'products.name',
                    'products.description',
                    'products.unit',
                    'products.volume',
                    'products.price',
                    'products.stock_quantity',
                    'products.category_id',
                    'products.suplier_id',
                    'products.picture',
                    'products.deleted_at'
                ]);

            if (count($product) > 0) {
                $response = [
                    'status' => 'success',
                    'message' => 'Mengambil Data Produk Sukses',
                    'data' => $product,
                ];

                return response()->json($response, Response::HTTP_OK);
            }

            $response = [
                'status' => 'fails',
                'message' => 'Mengambil Data Produk Gagal -> Data Kosong',
                'data' => null,
            ];

            return response()->json($response, Response::HTTP_NOT_FOUND);
        } catch (QueryException $e) {
            $response = [
                'status' => 'fails',
                'message' => 'Mengambil Data Produk Gagal -> Server Error',
                'data' => null,
            ];

            return response()->json($response, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        try {
            $product = Product::leftJoin('categories', 'products.category_id', '=', 'categories.id')
                ->leftJoin('suppliers', 'products.suplier_id', '=', 'suppliers.id')
                ->where('products.stock_quantity', '>',  0)
                ->orderBy('products.stock_quantity', 'ASC')
                ->get([
                    'categories.name AS category',
                    'suppliers.name AS suplier',
                    'products.id',
                    'products.name',
                    'products.description',
                    'products.unit',
                    'products.volume',
                    'products.price',
                    'products.stock_quantity',
                    'products.category_id',
                    'products.suplier_id',
                    'products.picture',
                ]);

            if (count($product) > 0) {
                $response = [
                    'status' => 'success',
                    'message' => 'Mencari Data Produk Sukses',
                    'data' => $product,
                ];

                return response()->json($response, Response::HTTP_OK);
            }

            $response = [
                'status' => 'fails',
                'message' => 'Mencari Data Produk Gagal -> Data Kosong',
                'data' => null,
            ];

            return response()->json($response, Response::HTTP_NOT_FOUND);
        } catch (QueryException $e) {
            $response = [
                'status' => 'fails',
                'message' => 'Mencari Data Produk Gagal -> Server Error ' . $e,
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
            'name' => 'required|unique:products',
            'description' => 'required',
            'unit' => 'required',
            'volume' => 'required|numeric',
            'price' => 'required|numeric',
            'category_id' => 'required',
            'suplier_id' => 'required'
        ];

        $input = [
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'unit' => $request->input('unit'),
            'volume' => $request->input('volume'),
            'price' => $request->input('price'),
            'picture' => 'no-image.jpg',
            'stock_quantity' => 0,
            'category_id' => $request->input('category_id'),
            'suplier_id' => $request->input('suplier_id'),
        ];

        $message = [
            'required' => 'Kolom :attribute wajib diisi.',
            'unique' => 'Kolom :attribute sudah terdaftar.',
            'numeric' => 'Kolom :attribute hanya dapat memuat data berupa angka',
        ];

        $validator = Validator::make($input, $rule, $message);

        if ($validator->fails()) {
            $response = [
                'status' => 'fails',
                'message' => 'Menambah Data Produk Gagal -> ' . $validator->errors(),
                'data' => null,
            ];

            return response()->json($response, Response::HTTP_BAD_REQUEST);
        }

        try {
            if (!is_null($request->file('picture'))) {
                $input['picture'] = $this->uploadFile('storage/product', $request->file('picture'));
            }

            $product = Product::create($input);

            $response = [
                'status' => 'success',
                'message' => 'Menambah Data Produk Sukses',
                'data' => $product,
            ];

            return response()->json($response, Response::HTTP_CREATED);
        } catch (QueryException $e) {
            $response = [
                'status' => 'fails',
                'message' => 'Menambah Data Produk Gagal -> Server Error',
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
        $product = Product::withTrashed()->find($id);

        if (is_null($product)) {
            $response = [
                'status' => 'fails',
                'message' => 'Mengubah Data Produk Gagal -> Data Tidak Ditemukan',
                'data' => null,
            ];

            return response()->json($response, Response::HTTP_NOT_FOUND);
        }

        $rule = [
            'name' => ['required', Rule::unique('products', 'name')->ignore($id)],
            'description' => 'required',
            'unit' => 'required',
            'volume' => 'required|numeric',
            'price' => 'required|numeric',
            'category_id' => 'required',
            'suplier_id' => 'required'
        ];

        $input = [
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'unit' => $request->input('unit'),
            'volume' => $request->input('volume'),
            'price' => $request->input('price'),
            'picture' => $product->picture,
            'stock_quantity' => $product->stock_quantity,
            'category_id' => $request->input('category_id'),
            'suplier_id' => $request->input('suplier_id'),
        ];

        $message = [
            'required' => 'Kolom :attribute wajib diisi.',
            'unique' => 'Kolom :attribute sudah terdaftar.',
            'numeric' => 'Kolom :attribute hanya dapat memuat data berupa angka',
        ];

        $validator = Validator::make($input, $rule, $message);

        if ($validator->fails()) {
            $response = [
                'status' => 'fails',
                'message' => 'Mengubah Data Produk Gagal -> ' . $validator->errors()->first(),
                'data' => null,
            ];

            return response()->json($response, Response::HTTP_BAD_REQUEST);
        }

        try {
            if (!is_null($request->file('picture'))) {
                $this->destroyFile($product->picture);
                $input['picture'] = $this->uploadFile('storage/product', $request->file('picture'));
            }

            $product->update($input);

            $response = [
                'status' => 'success',
                'message' => 'Mengubah Data Produk Sukses',
                'data' => $product,
            ];

            return response()->json($response, Response::HTTP_OK);
        } catch (QueryException $e) {
            $response = [
                'status' => 'fails',
                'message' => 'Mengubah Data Produk Gagal -> Server Error',
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
        $product = Product::withTrashed()->find($id);

        if (is_null($product)) {
            $response = [
                'status' => 'fails',
                'message' => 'Menghapus Data Produk Gagal -> Data Tidak Ditemukan',
                'data' => null,
            ];

            return response()->json($response, Response::HTTP_NOT_FOUND);
        }

        try {
            if (is_null($product->deleted_at)) {
                $product->delete();
            } else {
                $product->restore();
            }

            $response = [
                'status' => 'success',
                'message' => 'Menghapus Data Produk Sukses',
                'data' => $product,
            ];

            return response()->json($response, Response::HTTP_OK);
        } catch (QueryException $e) {
            $response = [
                'status' => 'fails',
                'message' => 'Menghapus Data Produk Gagal -> Server Error',
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
