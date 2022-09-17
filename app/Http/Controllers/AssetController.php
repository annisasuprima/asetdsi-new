<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use Illuminate\Support\Facades\DB;
use App\AssetType;

use Illuminate\Http\Request;


class AssetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $indexAset = DB::table('asset')
        ->join('asset_type', 'asset_type.type_id', '=', 'asset.type_id')
        ->get([
            'asset_type.type_name','asset.asset_name','asset.asset_id'
        ]);


    return view('pages.aset.aset', compact('indexAset'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $aset = DB::table('asset')
            ->get(['asset_id','asset_name']);

        $jenis = DB::table('asset_type')
            ->get(['type_id', 'type_name']);

        
        return view('pages.aset.create', compact('aset', 'jenis'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $aset = Asset::create([
            'asset_name'       => $request->asset_name,
            'type_id'  => $request->type_id
        ]);

      
        return redirect('aset')->with('success', 'Aset berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Asset  $asset
     * @return \Illuminate\Http\Response
     */
    public function show(Asset $asset)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Asset  $asset
     * @return \Illuminate\Http\Response
     */
    public function edit($asset_id)
    {
        $indexAset = DB::table('asset')
        // ->distinct('request_pengadaan.i                                    d_pengadaan')
        ->join('asset_type', 'asset_type.type_id', '=', 'asset.type_id')
        ->where('asset.asset_id', '=', $asset_id)
        // ->join('request_pengadaan', 'request_pengadaan.id_pengadaan', '=', 'pengadaan.id_pengadaan')
        ->get([
            'asset_type.type_name','asset.asset_name','asset.asset_id','asset_type.type_id'
        ]);
     

        $jenis = DB::table('asset_type')
        ->get(['type_id', 'type_name']);

    return view('pages.aset.edit', compact('indexAset','jenis'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests  $request
     * @param  \App\Models\Asset  $asset
     * @return \Illuminate\Http\Response
     */

    public function update(Request $request, $asset_id)
    {
        $indexAset = DB::table('asset')
            // ->distinct('request_pengadaan.i                                    d_pengadaan')
            ->join('asset_type', 'asset_type.type_id', '=', 'asset.type_id')
            ->where('asset.asset_id', '=', $asset_id)
            // ->join('request_pengadaan', 'request_pengadaan.id_pengadaan', '=', 'pengadaan.id_pengadaan')
            ->get([
                'asset_type.type_name','asset.asset_name','asset.asset_id','asset_type.type_id'
            ]);

            
            $update = DB::table('asset')
            ->where('asset.asset_id', '=', $asset_id)
            ->update([
                'asset_name'=> $request->asset_name,
                'type_id'=> $request->type_id
            ])
            ;
        

        return redirect('aset')->with('success', 'Aset berhasil diedit!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Asset  $asset
     * @return \Illuminate\Http\Response
     */
    public function destroy($asset_id)
    {
        $aset = Asset::find($asset_id);
        $aset->delete();
      
        return redirect('aset')->with('success', 'Aset berhasil dihapus');
    }
}