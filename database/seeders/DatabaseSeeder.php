<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\User;
use App\Models\Employee;
use App\Models\Customer;
use App\Models\Category;
use App\Models\Supplier;
use App\Models\Product;
use App\Models\City;
use App\Models\Address;
use App\Models\TransactionStatus;
use App\Models\ExpeditionTruck;
use App\Models\BankPayment;
use App\Models\ProductHistory;
use App\Models\Transaction;
use App\Models\DetailTransaction;
use App\Models\TransactionShipping;

use DateTime;
use DateInterval;
use DatePeriod;

use Illuminate\Support\Collection;

use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $role = Role::create([
            'name' => 'Super Admin',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ])->getAttributes();

        $user = User::create([
            'reference' => 'employee',
            'email' => 'superAdmin@mail.com',
            'email_verified_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'password' => bcrypt('12345678'),
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'deleted_at' => null,
        ])->getAttributes();

        $employee = Employee::create([
            'name' => 'Super Admin',
            'gander' => 'man',
            'phone' => '021111111111',
            'address' => 'Super Admin',
            'date_join' => Carbon::now()->format('Y-m-d'),
            'picture' => 'no-image.jpg',
            'role_id' => $role['id'],
            'user_id' => $user['id'],
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'deleted_at' => null,
        ])->getAttributes();

        $user2 = User::create([
            'reference' => 'customer',
            'email' => 'superCustomer@mail.com',
            'email_verified_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'password' => bcrypt('12345678'),
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'deleted_at' => null,
        ])->getAttributes();

        $customer = Customer::create([
            'name' => 'Super Customer',
            'phone' => '021111111111',
            'picture' => 'no-image.jpg',
            'user_id' => $user2['id'],
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'deleted_at' => null,
        ])->getAttributes();

        Role::create([
            'name' => 'Owner',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ])->getAttributes();

        Role::create([
            'name' => 'Manager',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ])->getAttributes();

        Role::create([
            'name' => 'Admin Penjualan',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ])->getAttributes();

        Role::create([
            'name' => 'Admin Gudang',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ])->getAttributes();

        Role::create([
            'name' => 'Admin Ekspedisi',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ])->getAttributes();

        $imageProfil =  [
            '1.jpg',
            '2.jpg',
            '3.jpg',
            '1.jpg',
            '5.jpg',
        ];

        for ($i = 1; $i <= 5; $i++) {
            $user = User::create([
                'reference' => 'employee',
                'email' => 'employee' . $i . '@mail.com',
                'email_verified_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'password' => bcrypt('12345678'),
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'deleted_at' => null,
            ])->getAttributes();

            Employee::create([
                'name' => 'Employee ' . $i,
                'gander' => 'man',
                'phone' => '021111111111',
                'address' => 'Employee ' . $i . ' Address',
                'date_join' => Carbon::now()->format('Y-m-d'),
                'picture' => 'storage/employee/' . $imageProfil[$i - 1],
                'role_id' => $i + 1,
                'user_id' => $user['id'],
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'deleted_at' => null,
            ])->getAttributes();
        }

        TransactionStatus::create([
            'name' => 'Menunggu Konfirmasi',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ])->getAttributes();

        TransactionStatus::create([
            'name' => 'Sedang Dikemas',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ])->getAttributes();

        TransactionStatus::create([
            'name' => 'Dalam Antrian Pengiriman',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ])->getAttributes();

        TransactionStatus::create([
            'name' => 'Dalam Pengiriman',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ])->getAttributes();

        TransactionStatus::create([
            'name' => 'Pengiriman Selesai',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ])->getAttributes();

        TransactionStatus::create([
            'name' => 'Pengiriman Selesai - Konfirmasi Pengguna',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ])->getAttributes();

        TransactionStatus::create([
            'name' => 'Transaksi Dibatalkan',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ])->getAttributes();

        $supplier =  [
            'PT. Universal Jaya Perkasa',
            'PT. Limas Timur Pratama',
            'PT. Berdikari Tunggal Perkasa',
            'PT. Karya Baru Indonesia',
            'CV. Kencana Unggul Sentosa',
            'PT. Usaha Pangan Sejahtera',
            'Cahaya Tiga Tunggal',
            'UD. Sido Mumbul',
            'UD. Selatan Jaya',
        ];

        for ($i = 1; $i <= 9; $i++) {
            Supplier::create([
                'name' => $supplier[$i - 1],
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ])->getAttributes();
        }

        $category =  [
            'Alat Tulis',
            'Beras',
            'Curah',
            'Keperluan Dapur',
            'Kantong Kresek',
            'Sabun',
            'Kopi, Susu, & Teh',
            'Jajanan',
            'Mie Instan',
            'Minuman',
            'Sandal Jepit',
            'Obat-Obatan',
            'Keperluan Bayi',
            'Lainnya',
        ];

        for ($i = 1; $i <= 14; $i++) {
            Category::create([
                'name' => $category[$i - 1],
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ])->getAttributes();
        }

        $volume =  [
            4500,
            6000,
            14720,
            16800,
            64000,
            96000,
            4500,
            20100,
            14720,
            20100,
            20100,
            5100,
            20100,
            20100,
            20100,
            16800,
            64000,
            96000,
            4500,
            6000,
            14720,
            16800,
            64000,
            96000,
            4500,
            6000,
            14720,
            16800,
            64000,
            96000,
            4500,
            6000,
            14720,
            16800,
            64000,
            96000,
            4500,
            6000,
            14720,
            16800,
            64000,
            96000,
            4500,
            6000,
            14720,
            16800,
            64000,
            96000,
            4500,
            6000,
            14720,
            16800,
            64000,
            96000,
            4500,
            6000,
            14720,
            16800,
            64000,
            96000,
            4500,
            6000,
            14720,
            16800,
            64000,
            96000,
            4500,
            6000,
            14720,
            16800,
            64000,
            96000,
            4500,
            6000,
            14720,
            16800,
            64000,
            96000,
            4500,
            6000,
            14720,
            16800,
            64000,
            96000,
            4500,
            6000,
            14720,
            16800,
            64000,
            96000,
            4500,
            10100,
            20100,
            50100,
            50100,
            50100,
            50100,
            50100,
            50100,
            50100,
        ];

        $unit =  [
            'Dus',
            'Dus',
            'Dus',
            'Dus',
            'Dus',
            'Dus',
            'Dus',
            'Karung',
            'Dus',
            'Karung',
            'Karung',
            'Karung',
            'Karung',
            'Karung',
            'Karung',
            'Dus',
            'Dus',
            'Dus',
            'Dus',
            'Dus',
            'Dus',
            'Dus',
            'Dus',
            'Dus',
            'Dus',
            'Dus',
            'Dus',
            'Dus',
            'Dus',
            'Dus',
            'Dus',
            'Dus',
            'Dus',
            'Dus',
            'Dus',
            'Dus',
            'Dus',
            'Dus',
            'Dus',
            'Dus',
            'Dus',
            'Dus',
            'Dus',
            'Dus',
            'Dus',
            'Dus',
            'Dus',
            'Dus',
            'Dus',
            'Dus',
            'Dus',
            'Dus',
            'Dus',
            'Dus',
            'Dus',
            'Dus',
            'Dus',
            'Dus',
            'Dus',
            'Dus',
            'Dus',
            'Dus',
            'Dus',
            'Dus',
            'Dus',
            'Dus',
            'Dus',
            'Dus',
            'Dus',
            'Dus',
            'Dus',
            'Dus',
            'Dus',
            'Dus',
            'Dus',
            'Dus',
            'Dus',
            'Dus',
            'Dus',
            'Dus',
            'Dus',
            'Dus',
            'Dus',
            'Dus',
            'Dus',
            'Dus',
            'Dus',
            'Dus',
            'Dus',
            'Dus',
            'Dus',
            'Karung',
            'Karung',
            'Karung',
            'Karung',
            'Karung',
            'Karung',
            'Karung',
            'Karung',
            'Karung',
        ];

        $imageProduct =  [
            '001.jpg',
            '002.jpg',
            '003.jpg',
            '004.jpg',
            '005.jpg',
            '006.jpg',
            '007.jpg',
            '008.jpg',
            '009.jpg',
            '010.jpg',
            '011.jpg',
            '012.jpg',
            '013.jpg',
            '014.jpg',
            '015.jpg',
            '016.jpg',
            '017.jpg',
            '018.jpg',
            '019.jpg',
            '020.jpg',
            '021.jpg',
            '022.jpg',
            '023.jpg',
            '024.jpg',
            '025.jpg',
            '026.jpg',
            '027.jpg',
            '028.jpg',
            '029.jpg',
            '030.jpg',
            '031.jpg',
            '032.jpg',
            '033.jpg',
            '034.jpg',
            '035.jpg',
            '036.jpg',
            '037.jpg',
            '038.jpg',
            '039.jpg',
            '040.jpg',
            '041.jpg',
            '042.jpg',
            '043.jpg',
            '044.jpg',
            '045.jpg',
            '046.jpg',
            '047.jpg',
            '048.jpg',
            '049.jpg',
            '050.jpg',
            '051.jpg',
            '052.jpg',
            '053.jpg',
            '054.jpg',
            '055.jpg',
            '056.jpg',
            '057.jpg',
            '058.jpg',
            '059.jpg',
            '060.jpg',
            '061.jpg',
            '062.jpg',
            '063.jpg',
            '064.jpg',
            '065.jpg',
            '066.jpg',
            '067.jpg',
            '068.jpg',
            '069.jpg',
            '070.jpg',
            '071.jpg',
            '072.jpg',
            '073.jpg',
            '074.jpg',
            '075.jpg',
            '076.jpg',
            '077.jpg',
            '078.jpg',
            '079.jpg',
            '080.jpg',
            '081.jpg',
            '082.jpg',
            '083.jpg',
            '084.jpg',
            '085.jpg',
            '086.jpg',
            '087.jpg',
            '088.jpg',
            '089.jpg',
            '090.jpg',
            '091.jpg',
            '092.jpg',
            '093.jpg',
            '094.jpg',
            '095.jpg',
            '096.jpg',
            '097.jpg',
            '098.jpg',
            '099.jpg',
            '100.jpg',
        ];

        $product =  [
            'Amplop 110x230',
            'Amplop 95x152',
            'Amplop Lamaran',
            'Buku Gambar A4 Ria',
            'Buku Gambar kecil Ria',
            'Buku Tulis Big Boss 42 Lembar',
            'BUKU Tulis S DODO 38 Lembar',
            'Bawang putih',
            'Jagung bihun',
            'Kacang Tanah',
            'Kacang Hijau',
            'Kerupuk Nasi Uduk',
            'Kemiri',
            'Ketumbar',
            'Lencana terigu',
            'ABC Kecap Botol 135ML',
            'ABC Kecap Saset',
            'ABC Sambal Saos Meja',
            'ABC Saos Saset',
            'ABC Saos Tomat Meja',
            'ABC Terasi Saset',
            'ABC Sarden 155 Gr',
            'Dayak 15',
            'Dayak 24',
            'Kresek Bola Api GK08',
            'Sendok kecil',
            'Sendok Kecil Warna',
            'Sendok 24',
            'Sparta 15',
            'Attack Saset 500',
            'Attack Saset 1000',
            'Boom Detergen 750  gr',
            'Bayclin Botol 100 ml',
            'Citrun',
            'Citra sabun',
            'Ciptadent 75 gr',
            'Biskuat Bolu',
            'Beng-Beng 20 GR',
            'Better Roma',
            'Big Babol 3 PCS',
            'Biskuat COKLAT 20 GR',
            'Biskuat ENERGI 22.5 GR',
            'Biskuat SUSU 21 GR',
            'Twist Corn',
            'Indomie ayam bawang',
            'Indomie soto',
            'Indomie kari ayam',
            'Indomie goreng',
            'Mie Sedap Kuah Soto',
            'Mie Sedap Goreng',
            'Mie TELOR 3 AYAM',
            'Arinda',
            'Aqua gelas',
            'Aqua Mini',
            'AQUA Galon',
            'Ale-Ale',
            'AQUA 1500 ML',
            'ABC Squash Delight 600 ml',
            'AQUA 600 ml',
            'ADEM SARI',
            'ALANG SARI',
            'ANAKONIDIN',
            'ANTANGIN JRG SIRUP',
            'ANTANGIN JRG TABLET',
            'ANTIMO ANAK SIRUP',
            'ANTIMO TABLET',
            'AMANPLAST',
            'ABC kopi susu',
            'ABC mocca',
            'GOODAY 3 IN 1',
            'GOOD DAY FREZE',
            'Indocafe coffee mix',
            'Kapal api special mix',
            'Kapal api special 35 gr',
            'Kapal api special 65 gr',
            'AUTAN SACH',
            'Autan junior sach',
            'Aica aibon',
            'ABC battery AA',
            'ABC battery D',
            'AMP KARET',
            'Baygon bakar 5pc',
            'Baygon cair 400 ml',
            'CUSSONS BABY SABUN',
            'CERELAC BERAS M',
            'CUSSONS Bedak',
            'HAPPY BEDAK',
            'TELON LANG 25 ML',
            'TELON LANG 60 ML',
            'MAMYPOKO S',
            'MAMYPOKO M',
            'Beras Murah',
            'Beras Sedang',
            'Beras Termahal',
            'Beras Pera IR 42',
            'SUN SWALLOW SERI',
            'SUN SWALLOW TANGGUNG',
            'SWALL0W SIZE 10-10,5',
            'SWALLOW SERI',
            'SWALLOW SIZE 11',
        ];

        $kategori = [
            1,
            1,
            1,
            1,
            1,
            1,
            1,
            3,
            3,
            3,
            3,
            3,
            3,
            3,
            3,
            4,
            4,
            4,
            4,
            4,
            4,
            4,
            5,
            5,
            5,
            5,
            5,
            5,
            5,
            6,
            6,
            6,
            6,
            6,
            6,
            6,
            8,
            8,
            8,
            8,
            8,
            8,
            8,
            8,
            9,
            9,
            9,
            9,
            9,
            9,
            9,
            10,
            10,
            10,
            10,
            10,
            10,
            10,
            10,
            12,
            12,
            12,
            12,
            12,
            12,
            12,
            12,
            7,
            7,
            7,
            7,
            7,
            7,
            7,
            7,
            14,
            14,
            14,
            14,
            14,
            14,
            14,
            14,
            13,
            13,
            13,
            13,
            13,
            13,
            13,
            13,
            2,
            2,
            2,
            2,
            11,
            11,
            11,
            11,
            11,
        ];

        $deskripsi = [
            'Amplop Putih Merk Paperline merupakan amplop dengan kualitas yang baik dengan harga terjangkau. Dilengkapi dengan perekat, dengan kertas yang putih dan tebal. Cocok digunakan untuk kantor.',
            'Bgain Peel & Seal Amplop - Putih [Ukuran 104 / 95x152 mm / 100 pcs / Pack] merupakan amplop berbahan kertas berkualitas tinggi yang didesain praktis dan ekonomis. Amplop ini tebal, tidak tembus pandang, dan tidak mudah sobek. Ideal digunakan untuk mengundang sesorang pada suatu acara.',
            'GROSIR BROWN KRAFT ENVELOPE AMPLOP COKLAT FOLIO CV LAMARAN KERJA Merek akan kirim acak Ketebalan 70gsm Ukuran 24x34cm Tanpa motif/polos Tanpa lem Cocok untuk kertas ukuran Folio F4 dan A4.',
            'Buku Gambar/Drawing Book Ria A4 media kreasi belajar menggambar yang dapat dikreasikan untuk membuat sebuah kreatifitas dalam bentuk seni gambar. Buku Gambar/Drawing Book Ria A4 dapat digunakan untuk anak - anak, remaja, dan dewasa.',
            'Buku Gambar/Drawing Book Ria kecil media kreasi belajar menggambar yang dapat dikreasikan untuk membuat sebuah kreatifitas dalam bentuk seni gambar. Buku Gambar/Drawing Book Ria A4 dapat digunakan untuk anak - anak, remaja, dan dewasa.',
            'Buku Tulis merk Big Boss ukuran Boxy dengan kualitas terpercaya, kertasnya tebal, tidak tembus, sangat cocok untuk tulis menulis. Dengan motif cover yang beraneka sehingga belajar putra putri anda jadi menyenangkan. Ukuran 18cm x 25cm.',
            'Buku tulis dodo 38 lembar. Buku tulis yang dapat digunakan oleh siapapun dengan kualitas yang baik.',
            'Bawang kating ini cocok untuk digunakan dalam memasak sup, bakso, soto, mie goreng dll. karena akan membuat aroma masakan lebih wangi tercium.',
            'Bihun Jagung Merk Padamu kemasan 350 Gr adalah bihun yang tahan direbus, tidak berbau (tidak apek), bersih, kenyal dan tidak lengket.',
            'Kacang tanah ini merupakan produk impor dari India. Produk ini merupakan produk untuk konsumsi, bukan untuk ditanam.',
            'Kacang Hijau /Mung Bean per karung. Per karung isi 25 kg.',
            'Kerupuk udang yg siap di goreng berat 5 kg . terbuat dr udang pilihan yg sangat nikmat untuk di santap dengan nasi uduk, nasi goreng, gado gado, soto , dll.',
            'Kemiri Utuh 1 Ball isi 25 kg. Biasa digunakan sebagai bumbu untuk memasak masakan khas Indonesia. Merk karung bisa berbeda namun kualitas barang tetap kami jamin.',
            'Ketumbar Coklat segar ini dijual tanpa menggunakan pewarna kuning yang tidak sehat yang biasa dijual agar membuat masakan lebih sedap dipandang. Ketumbar sangat cocok dipakai untuk memasak daging ayam. Produk ini merupakan produk untuk konsumsi, bukan untuk ditanam. Ketumbar berkhasiat untuk membantu segala masalah pencernaan, merupakan agen antioxidant, membantu fungsi hati, mengurangi kolesterol jahat (LDL) dan menambah kolesterol baik (HDL), membantu kesehatan jantung dan metabolisme, mengurangi berat badan, mengatur level glukosa dalam darah, kaya akan vitamin K, C dan B yg membantu kesehatan kulit dan rambut.',
            'Tepung ini bisa menghasilkan makanan yang lebih garing dan renyah sehingga cocok sekali untuk membuat makanan yang digoreng. Sama seperti ketiga tepung terigu Bogasari lainnya, Bogasari Lencana Merah juga tersedia dalam kemasan 1 kg dan 25 kg. Merk tepung terigu Bogasari Lencana Merah cukup  bagus dan mengandung protein lebih rendah dibanding tepung terigu Kunci Biru. Kandungan protein di dalam produk ini hanya 11% saja dan kekurangan Bogasari Lencana Biru bukan termaksud terigu serba guna.',
            'ABC Kecap Manis Botol [135 mL] 2 PCS merupakan kecap manis terbuat dari bahan-bahan eksotis pilihan yang hanya diproduksi di pulau Jawa, yaitu gula aren dan kacang kedelai. Kecap manis ABC diproses secara modern dan higienis untuk menghasilkan kecap dengan kekentalan dan kemurnian rasa sehingga memberikan cita rasa istimewa pada setiap masakan. Rasa manis ABC Kecap Manis yang alami diperoleh dari gula aren yang dikeringkan menggunakan panas sinar matahari, menambah aroma dan memberi rasa manis istimewa pada berbagai masakan.Hasilkan hidangan istimewa nan lezat bagi keluarga tercinta dengan memasak menggunakan ABC Kecap Manis Botol 135ML. Cocok digunakan untuk berbagai sajian masakan, seperti tumisan, goreng, bakar atau panggang serta untuk celupan dan topping.',
            'Kecap Manis ABC dibuat dengan kedelai, gandum dan gula merah pilihan sehingga menghasilkan kecap manis dengan citarasa mantap, hitam dan kental. Hadir dalam kemasan 1x pakai, Praktis & Ekonomis!',
            'Saus Sambal ABC Terbukti Kelezatannya Bicara tentang kebiasaan orang Indonesia yang menyantap makanan pedas, di Industri lokal sendiri PT. Heinz ABC Indonesia memperkenalkan berbagai macam produk kecap dan sambal. Namun, yang sudah jelas menjadi andalan orang yang suka pedas yaitu saus sambal ABC, yang sejak dulu terpercaya karena memiliki cita rasa yang khas, lezat, dan memiliki rasa pedas yang maksimal. Bahkan untuk mendukung kepraktisan, maka saus sambal asli ABC hadir dalam kemasan botol atau sachet sehingga Andapun lebih mudah dalam menikmati makanan pedas.',
            'ABC Sambal Asli Sachet 18gr x 10 merupakan sambal ABC yang terbuat dari paduan cabe segar dan bawang putih berkualitas untuk menghadirkan aroma, rasa, dan warna yang menggugah selera. Saus sambal ABC diproses secara modern dan higienis untuk menghasilkan saus dengan kekentalan dan kemurnian rasa yang pas sehingga memberikan cita rasa istimewa pada setiap makanan. Rasa pedas yang pas dan mantap dari ABC Sambal Asli, menambah aroma dan membuat acara makan lebih menyenangkan.',
            'ABC HEINZ Saus Tomat 275ml merupakan saus tomat persembahan ABC HEINZ yang terbuat dari bahan tomat pilihan  berkualitas tinggi sehingga memberikan cita rasa nikmat saat Anda santap. Saus ini aman di kosumsi karena tidak mengandung gluten serta halal. Saus ini olah dengan teknologi canggih dan dikemas dengan rapi sehingga masih terjaga dan hiegienis. Sangat cocok di campurkan pada pasta dan masakan lainnya.',
            'Terasi ABC sachet yang dikirim telah melalui proses quality control. Kami selalu berusaha menjaga kualitas produk kami. Kepuasan pelanggan adalah nomor 1 bagi kami. Apabila ada produk atau pelayanan kami yang dirasa kurang memuaskan, mohon disampaikan melalui ruang chat.',
            'ABC Sarden Saus Cabai 155g adalah resep masakan Indonesia yang dihasilkan dari perpaduan ikan terbaik dan bumbu berkualitas. Sarden ABC ini hadir dalam kemasan kaleng dan siap dimasak kapan saja sehingga dapat Anda nikmati setiap hari.',
            'Kantong untuk makanan & kue, untuk keperluan sehari-hari.',
            'Kantong untuk makanan & kue, untuk keperluan sehari-hari.',
            'Kantong untuk makanan & kue, untuk keperluan sehari-hari.',
            'Sendok kecil bening / sendok puding / sendok kecil A022 / Sendok kecil polos. Bahan sendok plastik tebal. Serbaguna, bisa untuk dessert seperti puding. Panjang sendok 11,5 cm',
            'Sendok kecil berwarna / sendok puding / sendok kecil A022 / Sendok kecil warna. Bahan sendok plastik tebal. Serbaguna, bisa untuk dessert seperti puding. Panjang sendok 11,5 cm',
            'SET SENDOK GARPU MAKAN 808 sendok garpu super tebal set isi 24 pcs, tidak mudah bengkok dan anti karat, cocok buat sehari hari di dapur , warung & kedai',
            'Produk di iwasmama dijamin ready dengan harga kaki lima dan kualitas bintang lima. Sendok takar Stainless ukuran 15ml cairan / 1 sendok teh / 7 gram kopi Bahan stainless solid.',
            'Detergen Attack yang membuat mencuci jadi lebih efektif dan hemat. Produk detergen dengan ultra biolite dan ultra soft. Membuat pakaian menjadi bersih, lembut, dan mudah disetrika.',
            'Jaz1 Attack Pesona Segar Deterjen Bubuk Sachet 50 Gram Satuan : . 1000 Grosir : . 917 Jaz 1 Pesona Segar Deterjen adalah deterjen dengan formula super aktif yang dapat membersihkan noda pada pakaian, membuat kegiatan mencuci mudah & enteng, serta memberikan keharuman yang tahan lama. Deterjen Jurus Perontok Super Satu Solusi Mencuci Baru 5 Menit Air Rendaman Lebih Gelap',
            'Boom Powder Detergent merupakan detergen bubuk dengan busa melimpah yang ampuh untuk membersihkan noda. Detergen ini dapat memberikan sensasi wangi yang tahan lama di pakaian Anda. Kandungan pada detergen ini juga sangat lembut di tangan. Merendam pakaian dalam waktu 30 menit pada larutannya dan memisahkan warna-warna yang mudah luntur dapat menghasilkan cucian menjadi lebih maksimal. Detergen ini mengandung parfum dengan aroma bunga-bunga. Dalam setiap butir halus serbuknya dapat membantu menghasilkan cucian lebih bersih dan bebas dari noda membandel. ',
            'Bayclin efektif mengusir warna kusam yang menempel dan menghilangkan noda membandel pada baju. Dapat digunakan untuk membersihkan dan menghilangkan bau di kamar mandi, membersihkan lantai, serta membersihkan dapur.',
            'Citric acid atau dikenal dengan citrun biasa digunakan untuk pembuat rasa asam pada minuman, es buah, jelly dsb. Dapat digunakan juga untuk mencuci baju atau membersihkan toilet dan perkakas rumah tangga lainnya.',
            'Sabun mandi. Dengan ekstrak bahan alami yang dikenal akan membuat kulitmu berseri secara alami. Mengandung beras yang dikenal secara alami bisa mengangkat sel kulit mati dan mencerahkan kulit kusam. Mengandung kunyit dapat mencerahkan warna asli kulitmu',
            'CIPTADENT Fresh Pasta Gigi [75 g] merupakan pasta gigi kandungan fresh spring mint yang membersihkan semua sisa makanan dan minuman yang tersisa di seluruh permukaan dan sela-sela gigi, membuatnya segar selama 12 jam. Micro active foam pasta gigi ini memaksimalkan kerja pasta gigi untuk mencegah karang gigi dan bakteri hinggap yang melubangi gigi putih milik Anda. Selain melindungi dengan cara membersihkan gigi, pasta gigi ini juga melindungi gusi dengan formula ringan dan lembut potassium citrat yang tidak menimbulkan rasa pedas ketika Anda memakainya.',
            'Biskuat Bolu Coklat [16.6 g/12 pcs] merupakan makanan ringan berupa biskuit yang terbuat dari perpaduan susu dan gandum yang menyatu dan menghasilkan sebuah biskuit yang enak, lezat, dan menyehatkan. Diperkaya dengan 9 vitamin dan 6 mineral, sehingga dapat dinikmati kapan saja dan di mana saja.',
            'Beng-Beng Regular Cokelat merupakan wafer coklat dengan paduan karamel dan rice yang memiliki tekstur crispy, sehingga menghasilkan rasa gurih yang nikmat. Ideal dijadikan cemilan sehat saat santai Anda bersama keluarga. Komposisi : glukosa, gula, susu bubu, tepung terigu, lemak nabati, lemak kakao, kakao massa, sereal , maltodextrin, dekstrosa, lemak susu, pengemulsi (lesitin kedelai), garam, bahan pengembang',
            'Sandwich Better coklat vanila adalah biskuit yang dibalut dengan coklat dengan isi vanilla yang begitu nikmat. Terbuat dari bahan baku berkualitas dan diproses higienis. Cocok dinikmati di segala situasi.',
            'Permen karet. Hadir dalam rasa blueberry yang manis dan nikmat. Dibuat dari bahan-bahan pilihan yang diolah secara seksama dan higienis sehingga terjaga kualitasnya. Cocok dinikmati oleh anak-anak maupun dewasa',
            'Biskuat adalah merek biskuit yang dipasarkan oleh Mondelēz Indonesia. Merek ini juga dikenal sebagai Tiger di negara-negara Asia Tenggara selain Indonesia. Target konsumen terbesar yang mengonsumsi Biskuat adalah anak-anak usia 5-11 tahun. Seluruh produk Biskuat diperkaya 9 vitamin dan 6 mineral. Cocok menemani pagi/sore anda, untuk camilan sehari-hari. Dibentuk dengan kemasan yang ekonomis agar mudah dibawa kemanapun. Berat 10g isi 20 pcs',
            'Biskuat Energi adalah biskuit susu, terbuat dari susu gandum  diperkaya oleh 6 vitgamin, kalsium, dan mineral penting. Biskuat memiliki dua rasa yaitu original dan Coklat. Biskuat Energi memiliki berat 140 gram. Biskuat energi mengandung',
            'Biskuat biskuit susu, terbuat dari susu gandum diperkaya oleh 6 vitgamin, kalsium, dan mineral penting. Energi 4 biskuat susu setara dengan energi segelas susu.',
            'Stik jagung renyah. Dengan bumbu pilahan rasa jagung bakar. Sangat cocok di jadikan teman saat Anda sedang bersantai',
            'Indomie terbuat dari tepung terigu berkualitas dengan paduan rempah rempah pilihan terbaik dan diproses dengan higienis menggunakan standar internasional dan teknologi berkualitas tinggi Juga diperkaya tambahan fortifikasi mineral dan vitamin A B1 B6 B16 Niasin Asam Folat dan Mineral Zat Besi.',
            'Orang Indonesia mana yang tak kenal dengan Indomie? Indomie adalah salah satu produk paling ikonik dari Indonesia yang ada sejak lebih dari 4 dekade lalu. Mie instan Indomie memiliki rasa yang nikmat, bahkan rasanya yang sedap sudah diakui di luar negeri. Salah satu favorit masyarkat Indonesia dan dunia adalah Indomie Goreng. Salah satu varian favorit masyarakat Indonesia dan dunia adalah Soto Mie. Varian ini adalah mie kuah yang sangat nikmat apabila di santap selagi hangat. Selain itu, mie instan Indomie Soto Mie sangat cocok dilengkapi dengan pelengkap seperti telur mata sapi ataupun daging ayam. Berikut kelebihan Indomie Soto Mie.',
            'Mie instan. Memiliki cita rasa yang gurih dan lezat, membuat siapa saja menyukainya. Dengan tekstur yang lembut, kenyal, dan rasa yang gurih. Cocok dinikmati setiap saat kapanpun dan dimanapun, serta aman dikonsumsi. Mudah dibuat dan cepat disajikan',
            'Indomie Goreng Mie Instan merupakan mie instant goreng yang memiliki cita rasa gurih dan lezat, sehingga membuat siapa saja menyukainya. Mie Instan dari Indomie ini dapat dikonsumsi setiap saat kapanpun dan dimanapun, serta aman dikonsumsi. Dengan tekstur yang lembut, kenyal, dan rasa yang gurih, membuat makanan siap saji ini bisa dinikmati kapan saja. Apalagi, cara pengolahannya yang mudah dibuat, menjadikan penyajian tidak memakan waktu yang lama.',
            'MIE SEDAAP Soto merupakan mie instan dengan rasa soto segar berpadu serbuk gurih renyah. Diproduksi dan diproses secara higienis di bawah pengawasan ketat dari para ahli, Mie Sedaap Mie Soto juga diperkaya 7 vitamin. Tekstur mienya yang lebih kenyal dan tidak cepat lunak. Akan lebih nikmat bila disantap saat cuaca dingin atau hujan.',
            'Mie merupakan sebuah jenis makanan yang nikmat dan lezat. Anda bisa mengolah mie sesuai dengan selera Anda. Pada saat ini, mie instan merupakan sebuah makanan yang disukai dan di gandrungi oleh banyak kalangan karena mudah pembuatannya, lezat, dan memiliki rasa yang berbeda-beda yang pastinya sesuai dengan selera Anda. Salah satu jenis mie instan yang terkenal di Indonesia adalah Mie Sedaap. Mie Sedaap mempunyai varian mie goreng yang lezat dan diolah dengan higienis sehingga aman untuk dikonsumsi siapapun. Mie ini dilengkapi dengan bumbu pelengkap yang lezat serta tekstur mienya yang lebih kenyal dan tidak cepat lunak yang akan memberikan kenikmatan lebih di lidah Anda.',
            'Cap 3 Ayam Mi Telur bungkus merah untuk mi keriting yang gurih dan lembut Mudah diolah atau dimasak sebagai masakan utama ataupun aneka kreasi masakan lainnya seperti kue dan cemilan Terbuat dari bahan berkualitas tinggi yang halal dan tanpa menggunakan bahan pengawet',
            'Arinda classtea 230ml. Perkarton 24pcs. Rasa Teh gula batu. Enak dan Besar. Cocok untuk hidangan.',
            'Aqua gelas kemasan baru edisi lebaran ada gambar karakternya. Aqua gelas 220 ml ini sangat terkenal karena kualitas dan rasanya. Sangat praktis dalam penyajian.',
            'Aqua botol 330 ml atau yang sering disebut aqua botol mini karena ukurannya yang kecil sehingga mudah dibawa saat berpergian atau buat bawa minum anak sekolah. 1 dus isi 24 botol.',
            'Aqua adalah produk air mineral dalam kemasan yang masih satu induk yang diproduksi dari mata air pilihan yang memenuhi standarisasi kebersihannya, diolah dengan teknologi yang terbaik yang menjadikan Aqua aman, sehat dan segar untuk di konsumsi. Aqua air mineral yang menjawab semua kebutuhan Anda akan air minum yang bermutu tinggi.',
            'Minuman. Dengan rasa yang nikmat dan menyegarkan untuk Anda. Memiliki kandungan Vitamin C dan Vitamin E yang berperan sebagai antioksidan pada tubuh Anda. Diproses secara higienis. Volume 200 mL',
            'Sertifikat Halal. Air mineral. Berasal dari sumber mata air yang terpilih. Dengan segala kemurnian dan kandungan mineral alami yang terpelihara. Dikemas dengan proses higienis. Untuk menunjang kegiatan yang dinamis agar terhindar dari dehidrasi',
            'Sirup. Dengan sensasi kesegaran buah. Dapat juga digunakan untuk membuat puding, koktail dan mocktails. Cocok disajikan di segala suasana terutama saat berkumpul bersama teman atau keluarga. Volume 460 mL',
            'Air mineral. Dibuat dari sumber mata air pilihan. Diproduksi menggunakan teknologi modern untuk mempertahankan rasa alami dan kesegarannya. Dikemas secara higienis untuk menjaga kualitasnya. Nikmati segala kebaikan alam dalam setiap tetesnya. Aqua Air Mineral Kemasan Botol [600 mL/ 1 Karton/ 24 pcs]',
            'Adem Sari Ching Ku Sachet 7 Gr - Ekstrak Jeruk Nipis Obat Pereda Panas Dalam Sariawan Suplemen Makanan Vitamin C Serbuk Larutan Minuman Penyegar Penyejuk',
            'Alangsari Plus Jeruk Manis terbuat dari akar Alang-Alang, daun Cincau Hijau, dan ekstrak Jeruk Manis, sehingga secara alami bermanfaat untuk mengatasi panas dalam dan mencegah sariawan. Rasanya yang segar, cocok dinikmati sebagai teman makanan pedas dan berminyak.',
            'Sirup obat batuk pilek dengan rasa chery yang disukai anak-anak. Meredakan batuk dan pilek pada anak-anak',
            'Antangin JRG Syrup mengobati masuk angin dengan keunggulan sensasi hangat & meningkatkan daya tahan tubuh. Antangin JRG Syrup dengan kandungan utama Jahe, Royal Jelly dan Ginseng ini berkhasiat untuk meredakan masuk angin, meriang, rasa mual, perut kembung, capek-capek dan pusing.',
            'Selain berkhasiat mengatasi gelaja-gejala masuk angin, Antangin JRG terbukti membantu meningkatkan sistim imun (kekebalan tubuh), dan menjaga stamina, khasiat yang tidak ditemukan pada obat masuk angin lain. (Sumber hasil penelitian Bag. Farmakologi, Universitas Gadjah Mada Yogyakarta). Aroma jahenya mantap, memberi rasa hangat yang tahan lama. Itu karena Antangin JRG mengandung jahe alami yang telah terbukti berkhasiat menghangatkan tubuh dan melancarkan peredaran darah. Antangin JRG Tablet aman dikonsumsi penderita Diabetes Melitus.',
            'Nikmati perjalanan Anda dan buah hati dengan selalu menyiapkan ANTIMO Anak Rasa Jeruk Sachet 5ml yang ampuh mencegah mual dan mabuk dengan rasa jeruk yang enak. Sirup rasa jeruk ini efektif mencegah mual dan mabuk baik itu perjalana darat, udara, dan air. Rasa jeruk dalam sirup ini membuat buah hati mudah untuk meminumnya karena rasanya yang manis.',
            'Antimo merupakan obat yang digunakan untuk mengatasi rasa mual dan muntah akibat mabuk perjalanan (motion sickness) maupun kondisi vertigo. Antimo mengandung zat aktif Dimenhidrinat yaitu obat golongan antihistamin yang efektif untuk mual dan muntah yang disebabkan oleh banyak kondisi. Dimenhidrinat dibantu dengan Vitamin B6 akan bekerja secara efektif pada sistem saraf pusat dengan menghambat zat histamin dan mencegah adanya stimulasi di saraf otak dan telinga dalam yang bisa menyebabkan mual, muntah, dan pusing.',
            'Ayo Bunda, selalu sedia plester dimanapun dan kapanpun. Sediakan selalu di dalam mobil, di kotak obat rumah, serta di dalam tas. Dengan warna2 menarik, cocok digunakan dewasa dan anak2. Anak2 pun ketika terkena luka, menjadi tidak takut dengan obat, karena plester yang bermotif menarik :)',
            'Kopi ABC susu. Aman untuk lambung Anda. Terbuat dari bahan berkualitas serta dapat dinikmati dalam berbagai suasana. Campuran yang sempurna antara kopi bubuk dengan aroma yang khas, gula dan rasa susu yang nikmat. Kopi susu ini dapat menjadi teman untuk membangkitkan semangat dan kreativitas Anda.',
            'Kopi ABC Mocca. Terbuat dari bahan berkualitas serta dapat dinikmati dalam berbagai suasana. Campuran yang sempurna antara kopi bubuk dengan aroma yang khas, krimer dan cokelat yang nikmat',
            'Kopi instan 3 in 1. Kopi, gula, dan krimer dalam satu sachet. Rasakan kenikmatan yang lebih maksimal dari Good Day dengan meminumnya ketika dingin atau panas. Menemani kamu agar cool sepanjang hari. Minuman kopi instan enak yang pas dibuat jadi kopi panas atau kopi dingin',
            'Good frezer. Kopi yg larut pakai air dingin. Gak percaya?. Ayo buktikan sendiri. Kopi 3 in 1 sachet. Isi 12 sachet. 1 sachet 30 gram. Kopi nikmat gak pakai ribet, seduh dengan air dingin langsung jadi, tidak pakai ampas jadi nikmat sampai tetes terakhir.',
            'Coffeemix 3 in 1 adalah kopi instan kombinasi antara kopi gula dan creamer rasanya pas cocok untuk menemani anda saat bersantai.',
            'Kopi Indonesia dengan biji kopi pilihan. Berpadu spesial dengan gula murni. Hadir dalam kemasan sachet yang praktis. Diolah dengan teknologi mesin kopi modern',
            'Kopi Indonesia dengan biji kopi pilihan. Berpadu spesial dengan gula murni. Hadir dalam kemasan sachet yang praktis. Diolah dengan teknologi mesin kopi modern',
            'Kopi Indonesia dengan biji kopi pilihan. Berpadu spesial dengan gula murni. Hadir dalam kemasan sachet yang praktis. Diolah dengan teknologi mesin kopi modern',
            'Autan merupakan lotion antinyamuk dalam kemasan sachet yang memberikan perlindungan dari nyamuk hingga 6-8 jam. Autan mengandung bahan aktif DEET (Diethiltoluamide) yaitu obat anti serangga yang bekerja dengan mengganggu neuron dan reseptor yang terletak di antena serta bagian mulut nyamuk yang berfungsi mendeteksi bahan kimia seperti asam laktat dan karbon dioksida. Tersedia dalam wangi bunga dan kandungan ekstrak Aloe Vera yang dapat membuat kulit tetap lembut, wangi, sekaligus terlindungi dari nyamuk.',
            'Autan merupakan lotion antinyamuk dalam kemasan sachet yang memberikan perlindungan dari nyamuk hingga 6-8 jam. Autan mengandung bahan aktif DEET (Diethiltoluamide) yaitu obat anti serangga yang bekerja dengan mengganggu neuron dan reseptor yang terletak di antena serta bagian mulut nyamuk yang berfungsi mendeteksi bahan kimia seperti asam laktat dan karbon dioksida. Tersedia dalam wangi bunga dan kandungan ekstrak Aloe Vera yang dapat membuat kulit tetap lembut, wangi, sekaligus terlindungi dari nyamuk.',
            'Aica Aibon adalah sebuah nama yang sudah terkenal dan melekat di telinga orang Indonesia sebagai salah satu nama produk lem perekat unggulan berkualitas karena daya tahan rekatnya yang kuat. Aica Aibon Lem Perekat dibuat dari bahan sintetis dan peralut organik pilihan dan diproses dengan teknologi canggih dari Jepang sehingga menghasilkan lem yang memiliki tekstur kental berwarna kuning. Lem ini selain mudah digunakan juga memiliki daya rekat yang tinggi dan kuat. Selain itu, lem perekat ini memiliki daya tahan yang sangat baik dan tidak mudah mengelupas jika terkena air, panas atau lembab.',
            'Batu baterai keluaran ABC. Berbentuk silinder berwarna biru dengan tinggi 49.2-50.5 mm, diameter 13.5-14.5 mm, mempunyai daya sebesar 1.5 V, dan berjumlah 4 pcs baterai AA. Dibuat dengan standar kualitas ABC sehingga batu baterai ini mampu bertahan lama. Telah memenuhi standar ramah lingkungan',
            'Baterai super power. Brand : ABC. Dimensi (P x L) : 6 x 3.2 cm. Lebih tahan lama dibanding baterai biasa. Cocok digunakan untuk lampu senter besar, penerima radio dan pemancar, produk dengan motor listrik, sistem keamanan, penghitung geiger, megafon dan lain-lain. Jaminan original battery',
            'Karet AMP yang dijual ini adalah ukuran karet gelang kecil dengan kualitas yang sangat bagus. Karet ini merupakan karet dengan olahan yang matang, bukan olahan yang setengah matang sehingga karet tidak mudah putus dan sangat kuat.',
            'Efektif melindungi dari Nyamuk hingga 10 Jam. Efektik mengusir nyamuk yang telah dipercaya oleh masyarakat pada umumnya',
            'Baygon anti nyamuk berbentuk cair dengan formula Tridaya terbukti membunuh nyamuk, lalat, dan kecoa secara efektif dan tahan lama. Produk ini merupakan isi ulang dari Baygon Pump Spray atau pakai semprotan tanaman juga bisa kak Efektif membunuh Nyamuk, Kecoa, dan Semut',
            'Manfaat Menutrisi kulit Melembabkan kulit Melembutkan kulit Kelebihan Kulit bayi menjadi lebih segar Kulit bayi menjadi lebih lembut Kulit menjadi lebih bersih Tidak membuat kulit si kecil iritasi Aman digunakan untuk bayi Dapat membuat kulit bayi nyaman BARANG READY SILAHKAN DI ORDER Jika ada pertanyaan silahkan chat store kami.',
            'Bubur sereal dengan susu. Untuk bayi usia 6-24 bulan. Diperkaya kandungan CHE (Carbohydrate Hydrolysed Enzimatically) untuk menghidrolisa karbohidrat secara alami, DHA, Probiotik, Zat Besi, dan Vitamin A&C',
            'Cuaca panas membuat bayi Anda berkeringat secara berebihan sehingga kulit bayi mudah kehilangan kelembabannya dan menjadi lebih kering.',
            'Bedak lembut dengan formula yang sangat ringan, sehingga aman digunakan pada kulit bayi hingga dewasa. Kandungan Vitamin E di dalamnya membantu menutrisi kulit juga disertai dengan wangi lembut yang menyegarkan.',
            'Minyak Telon Lang mengikuti formulasi turun temurun dari leluhur dan sudah digunakan dari masa ke masa. Minyak Telon Lang mengandung tiga campuran jenis minyak alami yaitu minyak kayu putih, minyak adas manis, dan minyak kelapa yang dapat digunakan untuk menjaga kehangatan tubuh bayi, meredakan perut kembung, mencegah gigitan nyamuk. Selain itu, Minyak Telon Lang juga dapat digunakan untuk minyak pijat pada bayi. Minyak Telon Lang tersedia dalam kemasan 25 mL, sehingga praktis untuk dibawa dan digunakan.',
            'Minyak Telon Lang mengikuti formulasi turun temurun dari leluhur dan sudah digunakan dari masa ke masa. Minyak Telon Lang mengandung tiga campuran jenis minyak alami yaitu minyak kayu putih, minyak adas manis, dan minyak kelapa yang dapat digunakan untuk menjaga kehangatan tubuh bayi, meredakan perut kembung, mencegah gigitan nyamuk. Selain itu, Minyak Telon Lang juga dapat digunakan untuk minyak pijat pada bayi. Minyak Telon Lang tersedia dalam kemasan 25 mL, sehingga praktis untuk dibawa dan digunakan.',
            'MamyPoko Pants Standar S40 akan memberikan kemudahan untuk bunda dan si kecil, dengan lapisan berdaya serap tinggi yang dapat menyerap pipis dengan cepat, sehingga kulit si kecil akan tetap kering dan terjaga kelembutannya. MamyPoko Pants Standar Standar S40 adalah popok celana untuk si kecil yang memilki berat 4-8 kg. Dengan desain celana menjadikan popok ini praktis dan mudah digunakan.',
            'MamyPoko Pants Standar S40 akan memberikan kemudahan untuk bunda dan si kecil, dengan lapisan berdaya serap tinggi yang dapat menyerap pipis dengan cepat, sehingga kulit si kecil akan tetap kering dan terjaga kelembutannya. MamyPoko Pants Standar Standar S40 adalah popok celana untuk si kecil yang memilki berat 4-8 kg. Dengan desain celana menjadikan popok ini praktis dan mudah digunakan.',
            'Beras ini adalah jenis beras Pulen Kualitas OKE. Cocok untuk makan di Rumah & Restoran. Tidak Pakai Pemutih, Tidak Pakai Pengawet, dan Tidak Pakai Pewangi',
            'Beras ini adalah jenis beras Pulen Kualitas OKE. Cocok untuk makan di Rumah & Restoran. Tidak Pakai Pemutih, Tidak Pakai Pengawet, dan Tidak Pakai Pewangi',
            'Beras ini adalah jenis beras Pulen Kualitas OKE. Cocok untuk makan di Rumah & Restoran. Tidak Pakai Pemutih, Tidak Pakai Pengawet, dan Tidak Pakai Pewangi',
            'Beras ini adalah jenis beras Pulen Kualitas OKE. Cocok untuk makan di Rumah & Restoran. Tidak Pakai Pemutih, Tidak Pakai Pengawet, dan Tidak Pakai Pewangi',
            'Sandal jepit SUN Swallow dibuat menggunakan bahan baku terbaik agar dapat mengikuti aktifitas kamu sehari-hari. Berbagai variasi sandal SUN Swallow memungkinkan kamu untuk menggunakannya di berbagai saat: sewaktu beraktifitas diluar ataupun beristirahat dirumah.',
            'Sandal jepit SUN Swallow dibuat menggunakan bahan baku terbaik agar dapat mengikuti aktifitas kamu sehari-hari. Berbagai variasi sandal SUN Swallow memungkinkan kamu untuk menggunakannya di berbagai saat: sewaktu beraktifitas diluar ataupun beristirahat dirumah.',
            'Swallow adalah salah satu merek sandal jepit yang paling populer dan tertua yang diproduksi di pabrik PT Sinar Jaya Prakarsa. Swallow adalah merek yang hampir dikenal oleh setiap orang Indonesia karena dijual di hampir semua pasar tradisional dan modern',
            'Swallow adalah salah satu merek sandal jepit yang paling populer dan tertua yang diproduksi di pabrik PT Sinar Jaya Prakarsa. Swallow adalah merek yang hampir dikenal oleh setiap orang Indonesia karena dijual di hampir semua pasar tradisional dan modern',
            'Swallow adalah salah satu merek sandal jepit yang paling populer dan tertua yang diproduksi di pabrik PT Sinar Jaya Prakarsa. Swallow adalah merek yang hampir dikenal oleh setiap orang Indonesia karena dijual di hampir semua pasar tradisional dan modern',
        ];

        for ($i = 1; $i <= 100; $i++) {
            Product::create([
                'name' => $product[$i - 1],
                'description' => $deskripsi[$i - 1],
                'unit' => $unit[$i - 1],
                'volume' => $volume[$i - 1],
                'price' => rand(10000, 1000000),
                'picture' => 'storage/product/' . $imageProduct[$i - 1],
                'stock_quantity' => 0,
                'category_id' => $kategori[$i - 1],
                'suplier_id' => rand(1, 9),
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'deleted_at' => null,
            ]);
        }

        for ($i = 1; $i <= 10; $i++) {
            City::create([
                'name' => 'City ' . $i,
                'expedition_cost' => rand(1, 8) / 10,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ])->getAttributes();
        }

        for ($i = 1; $i <= 10; $i++) {
            Address::create([
                'address' => 'Address ' . $i,
                'customer_id' => $customer['id'],
                'city_id' => $i,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ])->getAttributes();
        }

        for ($i = 1; $i <= 10; $i++) {
            ExpeditionTruck::create([
                'license_id' => 'Expedition Truck ' . $i,
                'min_volume' => 18000000,
                'max_volume' => 24000000,
                'picture' => 'no-image.jpg',
                'status' => 'available',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ])->getAttributes();
        }

        for ($i = 1; $i <= 10; $i++) {
            BankPayment::create([
                'bank_name' => 'Bank Payment ' . $i,
                'account_name' => 'Account Name BankPayment ' . $i,
                'account_number' => rand(10000000, 999999999999),
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ])->getAttributes();
        }

        //product history
        $begin = new DateTime('2017-01-01');
        $end = new DateTime('2022-06-01');

        $interval = DateInterval::createFromDateString('1 day');
        $period = new DatePeriod($begin, $interval, $end);

        foreach ($period as $dt) {
            $loop_collection = rand(5, 10);
            for ($i = 1; $i <= $loop_collection; $i++) {
                $product_id = rand(1, 100);
                $amount_of_product = rand(500, 1000);
                $product_price = (1000 * $product_id);
                $total_price = $amount_of_product * $product_price;

                ProductHistory::create([
                    'history_category' => 'in',
                    'history_date' => $dt,
                    'amount_of_product' => $amount_of_product,
                    'product_price' => $product_price,
                    'total_price' => $total_price,
                    'product_expired_date' => Carbon::parse($dt)->addYears(12)->format('Y-m-d'),
                    'product_id' => $product_id,
                    'created_by' => $employee['id'],
                    'updated_by' => null,
                    'deleted_by' => null,
                    'created_at' => Carbon::parse($dt)->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::parse($dt)->format('Y-m-d H:i:s'),
                    'deleted_at' => null,
                ]);

                $amount_of_product = rand(10, 20);
                $product_price = (1500 * $product_id);
                $total_price = $amount_of_product * $product_price;

                ProductHistory::create([
                    'history_category' => 'out',
                    'history_date' => $dt,
                    'amount_of_product' => $amount_of_product,
                    'product_price' => $product_price,
                    'total_price' => $total_price,
                    'product_expired_date' => Carbon::parse($dt)->format('Y-m-d'),
                    'product_id' => $product_id,
                    'created_by' => $employee['id'],
                    'updated_by' => null,
                    'deleted_by' => null,
                    'created_at' => Carbon::parse($dt)->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::parse($dt)->format('Y-m-d H:i:s'),
                    'deleted_at' => null,
                ]);
            }
        }

        // transaction
        foreach ($period as $dt) {
            $loop_collection = rand(5, 10);
            $collection = new Collection;
            $subtotal = 0;
            $total_volume = 0;

            for ($i = 1; $i <= $loop_collection; $i++) {
                $amount_of_product = rand(10, 20);
                $product_id = rand(1, 100);
                $product = Product::find($product_id);
                $subtotal = $subtotal + ($product->price * $amount_of_product);
                $total_volume = $total_volume + ($product->volume * $amount_of_product);

                $collection->push((object)[
                    'amount_of_product' => $amount_of_product,
                    'product_price' => $product->price,
                    'total_price' => $product->price * $amount_of_product,
                    'status' => 'success',
                    'product_id' => $product_id,
                ]);
            }

            $address = Address::leftJoin('cities', 'addresses.city_id', '=', 'cities.id')
                ->where('addresses.customer_id', '=', $customer['id'])
                ->get();
            $address_id = rand(0, 9);
            $transaction_status_id = rand(1, 6);

            $transaction = Transaction::create([
                'subtotal_price' => $subtotal,
                'tax' => $subtotal * 0.11,
                'shipping_cost' => $address[$address_id]->expedition_cost * $total_volume,
                'grand_total_price' => $subtotal + ($subtotal * 0.11) + ($address[$address_id]->expedition_cost * $total_volume),
                'message' => 'Tolong dibungkus dengan baik',
                'total_volume_product' => $total_volume,
                'receipt_of_payment' => 'no-image.jpg',
                'customer_id' => $customer['id'],
                'address_id' => $address[$address_id]->id,
                'bank_payment_id' => rand(1, 10),
                'transaction_status_id' => $transaction_status_id,
                'created_at' => Carbon::parse($dt)->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::parse($dt)->format('Y-m-d H:i:s'),
            ])->getAttributes();

            //detail transaction
            foreach ($collection as $c) {
                DetailTransaction::create([
                    'amount_of_product' => $c->amount_of_product,
                    'product_price' => $c->product_price,
                    'total_price' => $c->total_price,
                    'status' => $c->status,
                    'transaction_id' => $transaction['id'],
                    'product_id' => $c->product_id,
                    'created_at' => Carbon::parse($dt)->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::parse($dt)->format('Y-m-d H:i:s'),
                ]);
            }

            if ($transaction_status_id === 4) {
                TransactionShipping::create([
                    'transaction_id' => $transaction['id'],
                    'employee_id' => 6,
                    'expedition_truck_id' => rand(1, 10),
                    'delivery_date' => Carbon::parse($dt)->format('Y-m-d H:i:s'),
                    'created_at' => Carbon::parse($dt)->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::parse($dt)->format('Y-m-d H:i:s'),
                ]);
            }

            if ($transaction_status_id === 5 || $transaction_status_id === 6) {
                TransactionShipping::create([
                    'transaction_id' => $transaction['id'],
                    'employee_id' => 6,
                    'expedition_truck_id' => rand(1, 10),
                    'delivery_date' => Carbon::parse($dt)->format('Y-m-d H:i:s'),
                    'arrived_date' => Carbon::parse($dt)->addDays(2)->format('Y-m-d H:i:s'),
                    'created_at' => Carbon::parse($dt)->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::parse($dt)->format('Y-m-d H:i:s'),
                ]);
            }
        }
    }
}
