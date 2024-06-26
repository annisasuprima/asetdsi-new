<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Carbon\Carbon;

class RekapController extends Controller
{
    public function index()
    {

        $indexBangunan = DB::table('building')
            ->join('asset', 'asset.id', '=', 'building.asset_id')
            ->join('person_in_charge', 'person_in_charge.id', '=', 'building.pic_id')
            ->select([
                'asset.asset_name as nama_aset', 'building.building_name as nama_barang', 'building.building_code as kode_aset',
                'building.condition as kondisi', 'building.available as status', 'person_in_charge.pic_name as pj', 'building.photo as photo',
                'asset.id as asset_id'

            ]);

        $indexItems = DB::table('inventory_item')
            ->join('inventory', 'inventory.id', '=', 'inventory_item.inventory_id')
            ->join('asset_location', 'asset_location.id', '=', 'inventory_item.location_id')
            ->join('person_in_charge', 'person_in_charge.id', '=', 'inventory_item.pic_id')
            ->join('asset', 'asset.id', '=', 'inventory.asset_id')
            ->select([
                'asset.asset_name as nama_aset', 'inventory.inventory_brand as nama_barang', 'inventory_item.item_code as kode_aset',
                'inventory_item.condition as kondisi', 'inventory_item.available as status', 'person_in_charge.pic_name as pj', 'inventory.photo as photo',
                'asset.id as asset_id'

            ])
            ->union($indexBangunan)
            ->orderBy('nama_aset')
            ->get();

        $newItems = collect($indexItems);

        $indexItem = $newItems->map(function ($item, $index)  use ($newItems) {
            $filterItem = $newItems->filter(function ($itemFIlter) use ($item) {
                return $itemFIlter->asset_id ===  $item->asset_id;
            });
            // dd($newItems[0]->nama_aset);
            $item->jumlah = count($filterItem);
            if ($index == 0) {
                $item->indexPosition = 'start';
            } else if ($newItems[$index - 1]->nama_aset != $item->nama_aset) {
                $item->indexPosition = 'start';
            } else if (count($newItems) - 1 === $index) {
                $item->indexPosition = 'end';
            } else if ($newItems[$index + 1]->nama_aset != $item->nama_aset) {
                $item->indexPosition = 'end';
            }else {
                $item->indexPosition = 'middle';
            }
            // $item->indexPosition = 
            return $item;
        });


        return view('pages.rekap.rekap', compact('indexItem'));
    }

    public function print()
    {

        $indexBangunan = DB::table('building')
            ->join('asset', 'asset.id', '=', 'building.asset_id')
            ->join('person_in_charge', 'person_in_charge.id', '=', 'building.pic_id')
            ->select([
                'asset.asset_name as nama_aset', 'building.building_name as nama_barang', 'building.building_code as kode_aset',
                'building.condition as kondisi', 'building.available as status', 'person_in_charge.pic_name as pj', 'building.photo as photo',
                'asset.id as asset_id'

            ]);

        $indexItems = DB::table('inventory_item')
            ->join('inventory', 'inventory.id', '=', 'inventory_item.inventory_id')
            ->join('asset_location', 'asset_location.id', '=', 'inventory_item.location_id')
            ->join('person_in_charge', 'person_in_charge.id', '=', 'inventory_item.pic_id')
            ->join('asset', 'asset.id', '=', 'inventory.asset_id')
            ->select([
                'asset.asset_name as nama_aset', 'inventory.inventory_brand as nama_barang', 'inventory_item.item_code as kode_aset',
                'inventory_item.condition as kondisi', 'inventory_item.available as status', 'person_in_charge.pic_name as pj', 'inventory.photo as photo',
                'asset.id as asset_id'

            ])
            ->union($indexBangunan)
            ->orderBy('nama_aset')
            ->orderBy('nama_barang')
            ->get();


        $newItems = collect($indexItems);

        $indexItem = $newItems->map(function ($item, $index)  use ($newItems) {
            $filterItem = $newItems->filter(function ($itemFIlter) use ($item) {
                return $itemFIlter->asset_id ===  $item->asset_id;
            });
            // dd($newItems[0]->nama_aset);
            $item->jumlah = count($filterItem);
            if ($index == 0) {
                $item->indexPosition = 'start';
            } else if ($newItems[$index - 1]->nama_aset != $item->nama_aset) {
                $item->indexPosition = 'start';
            } else if (count($newItems) - 1 === $index) {
                $item->indexPosition = 'end';
            } else if ($newItems[$index + 1]->nama_aset != $item->nama_aset) {
                $item->indexPosition = 'end';
            } else if (($index + 1) % 28 == 0 && ($index + 1) < 28) {
                $item->indexPosition = 'end_line';
            } 
            else {
                $item->indexPosition = 'middle';
            }
            // $item->indexPosition = 
            return $item;
        });
        $now = Carbon::today();
        $year = $now->year;

        $pdf = pdf::loadview('pages.rekap.cetak', ['indexItem' => $indexItem], ['year' => $year])->setPaper('A4', 'portrait');
        return $pdf->stream('rekap-asetDSI-pdf');
    }

    public function printbarang()
    {
        $indexItems = DB::table('inventory_item')
            ->join('inventory', 'inventory.id', '=', 'inventory_item.inventory_id')
            ->join('asset_location', 'asset_location.id', '=', 'inventory_item.location_id')
            ->join('person_in_charge', 'person_in_charge.id', '=', 'inventory_item.pic_id')
            ->join('asset', 'asset.id', '=', 'inventory.asset_id')
            ->orderBy('asset_name')
            ->get([
                'inventory.inventory_brand', 'inventory.id', 'inventory.asset_id', 'inventory.photo',
                'inventory_item.item_code', 'inventory_item.condition', 'inventory_item.available', 'inventory_item.id as item_id',
                'inventory_item.location_id', 'inventory_item.pic_id', 'person_in_charge.pic_name',
                'person_in_charge.id', 'asset_location.id', 'asset_location.location_name', 'asset.id', 'asset.asset_name'
            ]);

        $newItems = collect($indexItems);

        $indexItem = $newItems->map(function ($item, $index)  use ($newItems) {
            $filterItem = $newItems->filter(function ($itemFIlter) use ($item) {
                return $itemFIlter->asset_id ===  $item->asset_id;
            });
            // dd($newItems[0]->nama_aset);
            $item->jumlah = count($filterItem);
            if ($index == 0) {
                $item->indexPosition = 'start';
            } else if ($newItems[$index - 1]->asset_name != $item->asset_name) {
                $item->indexPosition = 'start';
            } else if (count($newItems) - 1 === $index) {
                $item->indexPosition = 'end';
            } else if ($newItems[$index + 1]->asset_name != $item->asset_name) {
                $item->indexPosition = 'end';
            } else {
                $item->indexPosition = 'middle';
            }
            // $item->indexPosition = 
            return $item;
        });
        $now = Carbon::today();
        $year = $now->year;

        $pdf = pdf::loadview('pages.rekap.cetakbarang', ['indexItem' => $indexItem, 'year' => $year])->setPaper('A4', 'portrait');
        return $pdf->stream('rekap-asetDSI-pdf');
    }

    public function printbangunan()
    {
        $indexBangunans = DB::table('building')
            ->join('asset', 'asset.id', '=', 'building.asset_id')
            ->join('person_in_charge', 'person_in_charge.id', '=', 'building.pic_id')
            ->orderBy('asset_name')
            ->get([
                'asset.asset_name', 'asset.id', 'building.id as building_id',
                'building.asset_id', 'building.building_name', 'building.building_code', 'building.condition', 'building.available', 'building.photo', 'building.pic_id', 'person_in_charge.pic_name',
                'person_in_charge.id'
            ]);



        $newItems = collect($indexBangunans);

        $indexBangunan = $newItems->map(function ($item, $index)  use ($newItems) {
            $filterItem = $newItems->filter(function ($itemFIlter) use ($item) {
                return $itemFIlter->asset_id ===  $item->asset_id;
            });
            // dd($newItems[0]->building_name);
            $item->jumlah = count($filterItem);
            if ($index == 0) {
                $item->indexPosition = 'start';
            } else if ($newItems[$index - 1]->asset_name != $item->asset_name) {
                $item->indexPosition = 'start';
            } else if (count($newItems) - 1 === $index) {
                $item->indexPosition = 'end';
            } else if ($newItems[$index + 1]->asset_name != $item->asset_name) {
                $item->indexPosition = 'end';
            } else {
                $item->indexPosition = 'middle';
            }
            // $item->indexPosition = 
            return $item;
        });

        $now = Carbon::today();
        $year = $now->year;


        $pdf = pdf::loadview('pages.rekap.cetakbangunan', ['indexBangunan' => $indexBangunan, 'year' => $year])->setPaper('A4', 'portrait');;
        return $pdf->stream('asetbangunan-pdf');
    }
}
