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
                    select \'January  \' as month UNION
                    select \'February \' as month UNION
                    select \'March    \' as month UNION
                    select \'April    \' as month UNION
                    select \'May      \' as month UNION
                    select \'June     \' as month UNION
                    select \'July     \' as month UNION
                    select \'August   \' as month UNION
                    select \'September\' as month UNION
                    select \'October  \' as month UNION
                    select \'November \' as month UNION
                    select \'December \' as month
                )
                as m on (TO_CHAR(ph.history_date, \'Month\') = m.month)
                where ph.product_id = ' . $id . '
                and EXTRACT(year from CAST(ph.history_date AS DATE)) = ' . $year . '
                and ph.deleted_at is null
                group by bulan
                order by EXTRACT(month from TO_DATE(m.month, \'Month\'))
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
                    EXTRACT(year from CAST(ph.history_date AS DATE)) as tahun,
                    SUM(case when ph.history_category = \'in\' then (ph.amount_of_product) ELSE 0 END) as barang_masuk,
                    SUM(case when ph.history_category = \'in\' then (ph.total_price) ELSE 0 END) as harga_barang_masuk,
                    SUM(case when ph.history_category = \'out\' then (ph.amount_of_product) ELSE 0 END) barang_keluar ,
                    SUM(case when ph.history_category = \'out\' then (ph.total_price) ELSE 0 END) as harga_barang_keluar,
                    (SUM(case when ph.history_category = \'in\' then (ph.amount_of_product) ELSE 0 END) - SUM(case when ph.history_category = \'out\' then (ph.amount_of_product) ELSE 0 END)) as total_barang,
                    (SUM(case when ph.history_category = \'in\' then (ph.total_price) ELSE 0 END) - SUM(case when ph.history_category = \'out\' then (ph.total_price) ELSE 0 END)) as total_harga_barang
                from product_histories ph
                where ph.product_id = ' . $id . '
                and EXTRACT(year from CAST(ph.history_date AS DATE))  BETWEEN ' . $yearStart . ' AND ' . $yearEnd . '
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
                    select \'January  \' as month UNION
                    select \'February \' as month UNION
                    select \'March    \' as month UNION
                    select \'April    \' as month UNION
                    select \'May      \' as month UNION
                    select \'June     \' as month UNION
                    select \'July     \' as month UNION
                    select \'August   \' as month UNION
                    select \'September\' as month UNION
                    select \'October  \' as month UNION
                    select \'November \' as month UNION
                    select \'December \' as month
                )
                as m on (TO_CHAR(t.created_at, \'Month\') = m.month)
                where t.transaction_status_id in (5,6)
                and EXTRACT(year from CAST(t.created_at AS DATE)) = ' . $year . '
                group by bulan
                order by EXTRACT(month from TO_DATE(m.month, \'Month\'))
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
                    EXTRACT(year from CAST(t.created_at AS DATE)) as tahun,
                    SUM(t.subtotal_price) as subtotal_price,
                    SUM(t.tax) as tax,
                    SUM(t.shipping_cost) as shipping_cost,
                    SUM(t.grand_total_price) as grand_total_price
                from transactions t
                where t.transaction_status_id in (5,6)
                and EXTRACT(year from CAST(t.created_at AS DATE))  BETWEEN ' . $yearStart . ' AND ' . $yearEnd . '
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
                    select \'January  \' as month UNION
                    select \'February \' as month UNION
                    select \'March    \' as month UNION
                    select \'April    \' as month UNION
                    select \'May      \' as month UNION
                    select \'June     \' as month UNION
                    select \'July     \' as month UNION
                    select \'August   \' as month UNION
                    select \'September\' as month UNION
                    select \'October  \' as month UNION
                    select \'November \' as month UNION
                    select \'December \' as month
                )
                as m on (TO_CHAR(dt.created_at, \'Month\') = m.month)
                where dt.product_id = ' . $id . '
                and EXTRACT(year from CAST(dt.created_at AS DATE)) = ' . $year . '
                group by bulan
                order by EXTRACT(month from TO_DATE(m.month, \'Month\'))
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
                    EXTRACT(year from CAST(dt.created_at AS DATE)) as tahun,
                    SUM(case when dt.status = \'success\' then (dt.amount_of_product) ELSE 0 END) as success,
                    AVG(case when dt.status = \'success\' then (dt.total_price) ELSE 0 END) as success_price,
                    SUM(case when dt.status = \'fail\' then (dt.amount_of_product) ELSE 0 END) as fails,
                    AVG(case when dt.status = \'fail\' then (dt.total_price) ELSE 0 END) as fails_price
                from detail_transactions dt
                where dt.product_id = ' . $id . '
                and EXTRACT(year from CAST(dt.created_at AS DATE))  BETWEEN ' . $yearStart . ' AND ' . $yearEnd . '
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
                    select \'January  \' as month, 1 AS bln UNION
                    select \'February \' as month, 2 AS bln  UNION
                    select \'March    \' as month, 3 AS bln  UNION
                    select \'April    \' as month, 4 AS bln  UNION
                    select \'May      \' as month, 5 AS bln  UNION
                    select \'June     \' as month, 6 AS bln  UNION
                    select \'July     \' as month, 7 AS bln  UNION
                    select \'August   \' as month, 8 AS bln  UNION
                    select \'September\' as month, 9 AS bln  UNION
                    select \'October  \' as month, 10 AS bln  UNION
                    select \'November \' as month, 11 AS bln  UNION
                    select \'December \' as month, 12 AS bln
                )
                as m on (TO_CHAR(dt.created_at, \'Month\') = m.month)
                where EXTRACT(year from CAST(dt.created_at AS DATE)) = ' . $year . '
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
                    select \'January  \' as month, 1 AS bln UNION
                    select \'February \' as month, 2 AS bln  UNION
                    select \'March    \' as month, 3 AS bln  UNION
                    select \'April    \' as month, 4 AS bln  UNION
                    select \'May      \' as month, 5 AS bln  UNION
                    select \'June     \' as month, 6 AS bln  UNION
                    select \'July     \' as month, 7 AS bln  UNION
                    select \'August   \' as month, 8 AS bln  UNION
                    select \'September\' as month, 9 AS bln  UNION
                    select \'October  \' as month, 10 AS bln  UNION
                    select \'November \' as month, 11 AS bln  UNION
                    select \'December \' as month, 12 AS bln
                )
                as m on (TO_CHAR(t.created_at, \'Month\') = m.month)
                where t.transaction_status_id in (5,6)
                and EXTRACT(year from CAST(t.created_at AS DATE)) = ' . $year . '
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
