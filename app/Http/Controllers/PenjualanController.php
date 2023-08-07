<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;


class PenjualanController extends Controller
{
    // request data api
    public function index()
    {
        // $tahun berdasarkan tahun variable
        $tahun = '2021';

        $data = Http::get('https://tes-web.landa.id/intermediate/transaksi?tahun='.$tahun);

        // dd($data->json('tanggal', '2021-01-01', 'nama_menu', 'Nasi Goreng'));
        $menu = Http::get('https://tes-web.landa.id/intermediate/menu');
        // dd($menu->json('kategori', 'makanan'));

        // $nasi = $data->json('nama_menu', 'Nasi Goreng');
        $penjualan = json_decode($data, true);
        // dd($penjualan);

        // filter penjualan pada bulan 1 lalu total penjualan
        $penjualan1 = array_filter($penjualan, function ($val) {
            // tahun berdasarkan variable
            return $val['tanggal'] >= '2021-01-01' && $val['tanggal'] <= '2021-01-31';
        });

        // dd($penjualan1);



        // Create an associative array to store the total for each menu
        $menuTotal = [];

        // Calculate the total for each menu
        for ($i = 0; $i < count($penjualan1); $i++) {
            $menu = $penjualan1[$i]['menu'];
            $total = $penjualan1[$i]['total'];

            // If the menu already exists in $menuTotal, add the total to its current value
            if (isset($menuTotal[$menu])) {
                $menuTotal[$menu] += $total;
            } else { // Otherwise, set the total for that menu
                $menuTotal[$menu] = $total;
            }
        }



        return view('home', ['penjualan' => $penjualan, 'menuTotal' => $menuTotal]);
    }
}
