<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Symfony\Component\HttpFoundation\Response;

class LaporanController extends Controller
{
    public function laporanStockBulanan ($id, $year)
    {
        try {
            $laporan = DB::select('
                select
                    m.month as bulan,
                    SUM(case when ph.history_category = \'in\' then (ph.amount_of_product) ELSE 0 END) as barang_masuk,
                    SUM(case when ph.history_category = \'in\' then (ph.total_price) ELSE 0 END) as harga_barang_masuk,
                    SUM(case when ph.history_category = \'out\' then (ph.amount_of_product) ELSE 0 END) barang_keluar ,
                    SUM(case when ph.history_category = \'out\' then (ph.total_price) ELSE 0 END) as harga_barang_keluar,
                    (SUM(case when ph.history_category = \'in\' then (ph.amount_of_product) ELSE 0 END) - SUM(case when ph.history_category = \'out\' then (ph.amount_of_product) ELSE 0 END)) as total_barang,
                    (SUM(case when ph.history_category = \'in\' then (ph.total_price) ELSE 0 END) - SUM(case when ph.history_category = \'out\' then (ph.total_price) ELSE 0 END)) as total_harga_barang
                from product_histories ph
                right join (
                    SELECT \'January\'   AS month, 1  AS Bln UNION
                    SELECT \'February\'  AS month, 2  AS Bln UNION
                    SELECT \'March\'     AS month, 3  AS Bln UNION
                    SELECT \'April\'     AS month, 4  AS Bln UNION
                    SELECT \'May\'       AS month, 5  AS Bln UNION
                    SELECT \'June\'      AS month, 6  AS Bln UNION
                    SELECT \'July\'      AS month, 7  AS Bln UNION
                    SELECT \'August\'    AS month, 8  AS Bln UNION
                    SELECT \'September\' AS month, 9  AS Bln UNION
                    SELECT \'October\'   AS month, 10 AS Bln UNION
                    SELECT \'November\'  AS month, 11 AS Bln UNION
                    SELECT \'December\'  AS month, 12 AS Bln
                )
                as m on monthname(ph.history_date) = m.month
                where ph.product_id = ' . $id . '
                and YEAR(ph.history_date) = ' . $year . '
                and ph.deleted_at is null
                group by bulan
                order by m.Bln
            ');

            if (count($laporan) > 0) {
                $response = [
                    'status' => 'success',
                    'message' => 'Mengambil Data Laporan',
                    'data' => $laporan,
                ];

                return response()->json($response, Response::HTTP_OK);
            }

            $response = [
                'status' => 'fails',
                'message' => 'Mengambil Data Laporan -> Data Kosong',
                'data' => null,
            ];

            return response()->json($response, Response::HTTP_NOT_FOUND);
        } catch (QueryException $e) {
            $response = [
                'status' => 'fails',
                'message' => 'Mengambil Data Laporan -> Server Error',
                'data' => null,
            ];

            return response()->json($response, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function laporanStockTahunan ($id, $yearStart, $yearEnd)
    {
        try {
            $laporan = DB::select('
                select
                    YEAR(ph.history_date) as tahun,
                    SUM(case when ph.history_category = \'in\' then (ph.amount_of_product) ELSE 0 END) as barang_masuk,
                    SUM(case when ph.history_category = \'in\' then (ph.total_price) ELSE 0 END) as harga_barang_masuk,
                    SUM(case when ph.history_category = \'out\' then (ph.amount_of_product) ELSE 0 END) barang_keluar ,
                    SUM(case when ph.history_category = \'out\' then (ph.total_price) ELSE 0 END) as harga_barang_keluar,
                    (SUM(case when ph.history_category = \'in\' then (ph.amount_of_product) ELSE 0 END) - SUM(case when ph.history_category = \'out\' then (ph.amount_of_product) ELSE 0 END)) as total_barang,
                    (SUM(case when ph.history_category = \'in\' then (ph.total_price) ELSE 0 END) - SUM(case when ph.history_category = \'out\' then (ph.total_price) ELSE 0 END)) as total_harga_barang
                from product_histories ph
                where ph.product_id = ' . $id . '
                and YEAR(ph.history_date) BETWEEN ' . $yearStart . ' AND ' . $yearEnd . '
                and ph.deleted_at is null
                group by tahun
                order by tahun
            ');

            if (count($laporan) > 0) {
                $response = [
                    'status' => 'success',
                    'message' => 'Mengambil Data Laporan',
                    'data' => $laporan,
                ];

                return response()->json($response, Response::HTTP_OK);
            }

            $response = [
                'status' => 'fails',
                'message' => 'Mengambil Data Laporan -> Data Kosong',
                'data' => null,
            ];

            return response()->json($response, Response::HTTP_NOT_FOUND);
        } catch (QueryException $e) {
            $response = [
                'status' => 'fails',
                'message' => 'Mengambil Data Laporan -> Server Error ' . $e->getMessage(),
                'data' => null,
            ];

            return response()->json($response, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function laporanPendapatanBulanan ($year)
    {
        try {
            $laporan = DB::select('
                select
                    m.month as bulan,
                    SUM(t.subtotal_price) as subtotal_price,
                    SUM(t.tax) as tax,
                    SUM(t.shipping_cost) as shipping_cost,
                    SUM(t.grand_total_price) as grand_total_price
                from transactions t
                right join (
                    SELECT \'January\'   AS month, 1  AS Bln UNION
                    SELECT \'February\'  AS month, 2  AS Bln UNION
                    SELECT \'March\'     AS month, 3  AS Bln UNION
                    SELECT \'April\'     AS month, 4  AS Bln UNION
                    SELECT \'May\'       AS month, 5  AS Bln UNION
                    SELECT \'June\'      AS month, 6  AS Bln UNION
                    SELECT \'July\'      AS month, 7  AS Bln UNION
                    SELECT \'August\'    AS month, 8  AS Bln UNION
                    SELECT \'September\' AS month, 9  AS Bln UNION
                    SELECT \'October\'   AS month, 10 AS Bln UNION
                    SELECT \'November\'  AS month, 11 AS Bln UNION
                    SELECT \'December\'  AS month, 12 AS Bln
                )
                as m on monthname(t.created_at) = m.month
                where t.transaction_status_id in (5,6)
                and YEAR(t.created_at) = ' . $year . '
                group by bulan
                order by m.Bln
            ');

            if (count($laporan) > 0) {
                $response = [
                    'status' => 'success',
                    'message' => 'Mengambil Data Laporan',
                    'data' => $laporan,
                ];

                return response()->json($response, Response::HTTP_OK);
            }

            $response = [
                'status' => 'fails',
                'message' => 'Mengambil Data Laporan -> Data Kosong',
                'data' => null,
            ];

            return response()->json($response, Response::HTTP_NOT_FOUND);
        } catch (QueryException $e) {
            $response = [
                'status' => 'fails',
                'message' => 'Mengambil Data Laporan -> Server Error',
                'data' => null,
            ];

            return response()->json($response, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function laporanPendapatanTahunan ($yearStart, $yearEnd)
    {
        try {
            $laporan = DB::select('
                select
                    YEAR(t.created_at) as tahun,
                    SUM(t.subtotal_price) as subtotal_price,
                    SUM(t.tax) as tax,
                    SUM(t.shipping_cost) as shipping_cost,
                    SUM(t.grand_total_price) as grand_total_price
                from transactions t
                where t.transaction_status_id in (5,6)
                and YEAR(t.created_at)  BETWEEN ' . $yearStart . ' AND ' . $yearEnd . '
                group by tahun
                order by tahun
            ');

            if (count($laporan) > 0) {
                $response = [
                    'status' => 'success',
                    'message' => 'Mengambil Data Laporan',
                    'data' => $laporan,
                ];

                return response()->json($response, Response::HTTP_OK);
            }

            $response = [
                'status' => 'fails',
                'message' => 'Mengambil Data Laporan -> Data Kosong',
                'data' => null,
            ];

            return response()->json($response, Response::HTTP_NOT_FOUND);
        } catch (QueryException $e) {
            $response = [
                'status' => 'fails',
                'message' => 'Mengambil Data Laporan -> Server Error ' . $e->getMessage(),
                'data' => null,
            ];

            return response()->json($response, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function laporanPenjualanBulanan ($id, $year)
    {
        try {
            $laporan = DB::select('
                select
                    m.month as bulan,
                    SUM(case when dt.status = \'success\' then (dt.amount_of_product) ELSE 0 END) as success,
                    AVG(case when dt.status = \'success\' then (dt.total_price) ELSE 0 END) as success_price,
                    SUM(case when dt.status = \'fail\' then (dt.amount_of_product) ELSE 0 END) as fails,
                    AVG(case when dt.status = \'fail\' then (dt.total_price) ELSE 0 END) as fails_price
                from detail_transactions dt
                right join (
                    SELECT \'January\'   AS month, 1  AS Bln UNION
                    SELECT \'February\'  AS month, 2  AS Bln UNION
                    SELECT \'March\'     AS month, 3  AS Bln UNION
                    SELECT \'April\'     AS month, 4  AS Bln UNION
                    SELECT \'May\'       AS month, 5  AS Bln UNION
                    SELECT \'June\'      AS month, 6  AS Bln UNION
                    SELECT \'July\'      AS month, 7  AS Bln UNION
                    SELECT \'August\'    AS month, 8  AS Bln UNION
                    SELECT \'September\' AS month, 9  AS Bln UNION
                    SELECT \'October\'   AS month, 10 AS Bln UNION
                    SELECT \'November\'  AS month, 11 AS Bln UNION
                    SELECT \'December\'  AS month, 12 AS Bln
                )
                as m on monthname(dt.created_at) = m.month
                where dt.product_id = ' . $id . '
                and YEAR(dt.created_at) = ' . $year . '
                group by bulan
                order by m.Bln
            ');

            if (count($laporan) > 0) {
                $response = [
                    'status' => 'success',
                    'message' => 'Mengambil Data Laporan',
                    'data' => $laporan,
                ];

                return response()->json($response, Response::HTTP_OK);
            }

            $response = [
                'status' => 'fails',
                'message' => 'Mengambil Data Laporan -> Data Kosong',
                'data' => null,
            ];

            return response()->json($response, Response::HTTP_NOT_FOUND);
        } catch (QueryException $e) {
            $response = [
                'status' => 'fails',
                'message' => 'Mengambil Data Laporan -> Server Error',
                'data' => null,
            ];

            return response()->json($response, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function laporanPenjualanTahunan ($id, $yearStart, $yearEnd)
    {
        try {
            $laporan = DB::select('
                select
                    YEAR(dt.created_at) as tahun,
                    SUM(case when dt.status = \'success\' then (dt.amount_of_product) ELSE 0 END) as success,
                    AVG(case when dt.status = \'success\' then (dt.total_price) ELSE 0 END) as success_price,
                    SUM(case when dt.status = \'fail\' then (dt.amount_of_product) ELSE 0 END) as fails,
                    AVG(case when dt.status = \'fail\' then (dt.total_price) ELSE 0 END) as fails_price
                from detail_transactions dt
                where dt.product_id = ' . $id . '
                and YEAR(dt.created_at) BETWEEN ' . $yearStart . ' AND ' . $yearEnd . '
                group by tahun
                order by tahun
            ');

            if (count($laporan) > 0) {
                $response = [
                    'status' => 'success',
                    'message' => 'Mengambil Data Laporan',
                    'data' => $laporan,
                ];

                return response()->json($response, Response::HTTP_OK);
            }

            $response = [
                'status' => 'fails',
                'message' => 'Mengambil Data Laporan -> Data Kosong',
                'data' => null,
            ];

            return response()->json($response, Response::HTTP_NOT_FOUND);
        } catch (QueryException $e) {
            $response = [
                'status' => 'fails',
                'message' => 'Mengambil Data Laporan -> Server Error ' . $e->getMessage(),
                'data' => null,
            ];

            return response()->json($response, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function dashboardProdukTerjual ($year)
    {
        try {
            $laporan = DB::select('
                select
                    m.bln as bln,
                    SUM(case when dt.status = \'success\' then (dt.amount_of_product) ELSE 0 END) as success
                from detail_transactions dt
                right join (
                    SELECT \'January\'   AS month, 1  AS Bln UNION
                    SELECT \'February\'  AS month, 2  AS Bln UNION
                    SELECT \'March\'     AS month, 3  AS Bln UNION
                    SELECT \'April\'     AS month, 4  AS Bln UNION
                    SELECT \'May\'       AS month, 5  AS Bln UNION
                    SELECT \'June\'      AS month, 6  AS Bln UNION
                    SELECT \'July\'      AS month, 7  AS Bln UNION
                    SELECT \'August\'    AS month, 8  AS Bln UNION
                    SELECT \'September\' AS month, 9  AS Bln UNION
                    SELECT \'October\'   AS month, 10 AS Bln UNION
                    SELECT \'November\'  AS month, 11 AS Bln UNION
                    SELECT \'December\'  AS month, 12 AS Bln
                )
                as m on monthname(dt.created_at) = m.month
                where YEAR(dt.created_at) = ' . $year . '
                group by bln
                order by bln
            ');

            if (count($laporan) > 0) {
                $response = [
                    'status' => 'success',
                    'message' => 'Mengambil Data Laporan',
                    'data' => $laporan,
                ];

                return response()->json($response, Response::HTTP_OK);
            }

            $response = [
                'status' => 'fails',
                'message' => 'Mengambil Data Laporan -> Data Kosong',
                'data' => null,
            ];

            return response()->json($response, Response::HTTP_NOT_FOUND);
        } catch (QueryException $e) {
            $response = [
                'status' => 'fails',
                'message' => 'Mengambil Data Laporan -> Server Error',
                'data' => null,
            ];

            return response()->json($response, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function dashboardJumlahTransaksi ($year)
    {
        try {
            $laporan = DB::select('
                select
                    m.bln as bln,
                    COUNT(t.id) as success
                from transactions t
                right join (
                    SELECT \'January\'   AS month, 1  AS Bln UNION
                    SELECT \'February\'  AS month, 2  AS Bln UNION
                    SELECT \'March\'     AS month, 3  AS Bln UNION
                    SELECT \'April\'     AS month, 4  AS Bln UNION
                    SELECT \'May\'       AS month, 5  AS Bln UNION
                    SELECT \'June\'      AS month, 6  AS Bln UNION
                    SELECT \'July\'      AS month, 7  AS Bln UNION
                    SELECT \'August\'    AS month, 8  AS Bln UNION
                    SELECT \'September\' AS month, 9  AS Bln UNION
                    SELECT \'October\'   AS month, 10 AS Bln UNION
                    SELECT \'November\'  AS month, 11 AS Bln UNION
                    SELECT \'December\'  AS month, 12 AS Bln
                )
                as m on monthname(t.created_at) = m.month
                where t.transaction_status_id in (5,6)
                and YEAR(t.created_at) = ' . $year . '
                group by bln
                order by bln
            ');

            if (count($laporan) > 0) {
                $response = [
                    'status' => 'success',
                    'message' => 'Mengambil Data Laporan',
                    'data' => $laporan,
                ];

                return response()->json($response, Response::HTTP_OK);
            }

            $response = [
                'status' => 'fails',
                'message' => 'Mengambil Data Laporan -> Data Kosong',
                'data' => null,
            ];

            return response()->json($response, Response::HTTP_NOT_FOUND);
        } catch (QueryException $e) {
            $response = [
                'status' => 'fails',
                'message' => 'Mengambil Data Laporan -> Server Error ' . $e->getMessage(),
                'data' => null,
            ];

            return response()->json($response, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
