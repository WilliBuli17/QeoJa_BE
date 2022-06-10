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
                    m.month as Bulan,
                    SUM(case when ph.history_category = \'in\' then (ph.amount_of_product) ELSE 0 END) as msk,
                    SUM(case when ph.history_category = \'in\' then (ph.total_price) ELSE 0 END) as msk_hrg,
                    SUM(case when ph.history_category = \'out\' then (ph.amount_of_product) ELSE 0 END) as klr,
                    SUM(case when ph.history_category = \'out\' then (ph.total_price) ELSE 0 END) as klr_hrg,
                    (SUM(case when ph.history_category = \'in\' then (ph.amount_of_product) ELSE 0 END) - SUM(case when ph.history_category = \'out\' then (ph.amount_of_product) ELSE 0 END)) as ttl,
                    (SUM(case when ph.history_category = \'in\' then (ph.total_price) ELSE 0 END) - SUM(case when ph.history_category = \'out\' then (ph.total_price) ELSE 0 END)) as ttl_hrg
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
                group by Bulan
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
                    EXTRACT(year from CAST(ph.history_date AS DATE)) as Tahun,
                    SUM(case when ph.history_category = \'in\' then (ph.amount_of_product) ELSE 0 END) as msk,
                    SUM(case when ph.history_category = \'in\' then (ph.total_price) ELSE 0 END) as msk_hrg,
                    SUM(case when ph.history_category = \'out\' then (ph.amount_of_product) ELSE 0 END) as klr,
                    SUM(case when ph.history_category = \'out\' then (ph.total_price) ELSE 0 END) as klr_hrg,
                    (SUM(case when ph.history_category = \'in\' then (ph.amount_of_product) ELSE 0 END) - SUM(case when ph.history_category = \'out\' then (ph.amount_of_product) ELSE 0 END)) as ttl,
                    (SUM(case when ph.history_category = \'in\' then (ph.total_price) ELSE 0 END) - SUM(case when ph.history_category = \'out\' then (ph.total_price) ELSE 0 END)) as ttl_hrg
                from product_histories ph
                where ph.product_id = ' . $id . '
                and EXTRACT(year from CAST(ph.history_date AS DATE))  BETWEEN ' . $yearStart . ' AND ' . $yearEnd . '
                and ph.deleted_at is null
                group by Tahun
                order by Tahun
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
                    m.month as Bulan,
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
                where t.transaction_status_id in (6,7)
                and EXTRACT(year from CAST(t.created_at AS DATE)) = ' . $year . '
                group by Bulan
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
                    EXTRACT(year from CAST(t.created_at AS DATE)) as Tahun,
                    SUM(t.subtotal_price) as subtotal_price,
                    SUM(t.tax) as tax,
                    SUM(t.shipping_cost) as shipping_cost,
                    SUM(t.grand_total_price) as grand_total_price
                from transactions t
                where t.transaction_status_id in (6,7)
                and EXTRACT(year from CAST(t.created_at AS DATE))  BETWEEN ' . $yearStart . ' AND ' . $yearEnd . '
                group by Tahun
                order by Tahun
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
                    m.month as Bulan,
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
                group by Bulan
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
                    EXTRACT(year from CAST(dt.created_at AS DATE)) as Tahun,
                    SUM(case when dt.status = \'success\' then (dt.amount_of_product) ELSE 0 END) as success,
                    AVG(case when dt.status = \'success\' then (dt.total_price) ELSE 0 END) as success_price,
                    SUM(case when dt.status = \'fail\' then (dt.amount_of_product) ELSE 0 END) as fails,
                    AVG(case when dt.status = \'fail\' then (dt.total_price) ELSE 0 END) as fails_price
                from detail_transactions dt
                where dt.product_id = ' . $id . '
                and EXTRACT(year from CAST(dt.created_at AS DATE))  BETWEEN ' . $yearStart . ' AND ' . $yearEnd . '
                group by Tahun
                order by Tahun
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
