<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Loan;
use App\Http\Requests\StoreLoanRequest;
use App\Http\Requests\UpdateLoanRequest;
use App\Models\Inventory;
use App\Models\Mahasiswa;
use App\Models\PersonInCharge;
use App\Models\Proposal;
use App\Models\RequestMaintenenceAsset;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Hash;

class HistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $user_id = auth('sanctum')->user()->id;
        $indexPeminjamanBarang = DB::table('loan')
            ->join('mahasiswa', 'mahasiswa.id', '=', 'loan.mahasiswa_id')
            ->join('person_in_charge', 'person_in_charge.id', '=', 'loan.pic_id')
            ->join('loan_type', 'loan_type.id', '=', 'loan.type_id')
            ->join('asset_loan_detail', 'asset_loan_detail.loan_id', '=', 'loan.id')
            ->join('rejected_loan', 'rejected_loan.loan_id', '=', 'loan.id')
            ->where('type_id', '=', 1)
            ->where('loan.status', '!=', "waiting")
            ->where('loan.mahasiswa_id', '=', $user_id)
            ->select([
                'mahasiswa.name as nama_mahasiswa',
                'loan.loan_date as tanggal', 'loan.loan_description as deskripsi', 'loan.loan_time as waktu', 'loan.mahasiswa_id',
                'loan.id', 'loan.type_id', 'loan.status as status', 'loan.loan_time_end as waktu_akhir', 'rejected_loan.reasons as alasan'
            ]);


        $indexPeminjamanBangunan = DB::table('loan')
            ->join('mahasiswa', 'mahasiswa.id', '=', 'loan.mahasiswa_id')
            ->join('person_in_charge', 'person_in_charge.id', '=', 'loan.pic_id')
            ->join('loan_type', 'loan_type.id', '=', 'loan.type_id')
            ->join('building_loan_detail', 'building_loan_detail.loan_id', '=', 'loan.id')
            ->join('rejected_loan', 'rejected_loan.loan_id', '=', 'loan.id')
            ->where('type_id', '=', 2)
            ->where('loan.status', '!=', "waiting")
            ->where('loan.mahasiswa_id', '=', $user_id)
            ->select([
                'mahasiswa.name as nama_mahasiswa',
                'loan.loan_date as tanggal', 'loan.loan_description as deskripsi', 'loan.loan_time as waktu', 'loan.mahasiswa_id',
                'loan.id', 'loan.type_id', 'loan.status as status', 'loan.loan_time_end as waktu_akhir', 'rejected_loan.reasons as alasan'
            ])
            ->union($indexPeminjamanBarang)
            ->orderBy('nama_mahasiswa')
            ->get();

         
            $indexPeminjamanBangunan = array_map(function($peminjaman){
                $peminjaman->waktu = date( 'H:i', strtotime($peminjaman->waktu) );
                $peminjaman->waktu_akhir = date( 'H:i', strtotime($peminjaman->waktu_akhir) );
                return $peminjaman;
            },$indexPeminjamanBangunan->toArray());

           
            // dd(date( 'H:i', strtotime("14:00:00") ));
        $response = new \stdClass();
        $response->indexPeminjamanBangunan = $indexPeminjamanBangunan;
        return response()->json([
            'data' => $indexPeminjamanBangunan,
            'success' => true,
            'message' => 'Success',
        ]);
    }

    public function indexPengusulan()
    {

        $user_id = auth('sanctum')->user()->id;

        $indexPengusulan = DB::table('proposal')
            ->join('mahasiswa', 'mahasiswa.id', '=', 'proposal.mahasiswa_id')
            ->join('proposal_type', 'proposal_type.id', '=', 'proposal.type_id')
            ->join('rejected_proposal', 'rejected_proposal.proposal_id', '=', 'proposal.id')
            ->where('proposal.mahasiswa_id', '=', $user_id)
            ->where('proposal.status', '!=', "waiting")
            ->where('type_id', '=', 1)
            ->select([
                'proposal.proposal_description as deskripsi', 'proposal.status as statuspr',
                'proposal.id', 'proposal.type_id', 'proposal.status_confirm_faculty', 'rejected_proposal.reasons as alasan'
            ])

            ->orderBy('deskripsi')
            ->get();

          

            $resultFilter = array_merge($indexPengusulan->toArray());
            $resultFilter = array_map("unserialize", array_unique(array_map("serialize", $resultFilter)));

            $newResultFilter = array_filter($resultFilter, function ($v1) {
                return in_array($v1->alasan, ["Sedang Diproses", "Mohon Ditunggu"]);
            });

            
            $resultFilter = collect($indexPengusulan);
            $filtered = $resultFilter->whereNotIn('alasan', ["Sedang Diproses", "Mohon Ditunggu"]);

            $filtered->all();
            $result = array_merge($filtered->toArray());


            //use = untuk memasukkan kode dari luar kedalam function
            //array_map = untuk membuat 
        
            $a = array_filter($newResultFilter, function ($v1) use ($result) {
                
                $mapids = array_map(function ($v2) {
                    return $v2->id;
                }, $result);
                return !in_array($v1->id, $mapids);
            });

            if(count($a) > 0){
            array_push($result, $a[count($a)-1]);
        }
            
            
       

            

        // $resultFilter = array_merge($indexPengusulan->toArray());
        // $resultFilter = array_map("unserialize", array_unique(array_map("serialize", $resultFilter)));

        // $newResultFilter = array_filter($resultFilter, function ($v1) {
        //     return !in_array($v1->alasan, ["Sedang Diproses", "Mohon Ditunggu"]);
        // });

        // $newResult = array_map(function ($v1) {
        //     return $v1->alasan;
        // }, $newResultFilter);

        // if (count($newResult) > 0) {
        //     // $resultFilter[0]->alasans = array_values($newResult);
        //     $resultFilter[0]->alasansb = array_values($newResult)[0];
        //     // $resultFilter[0]->alasansb = $newResult[0];
        // }

        // $resultFilter = $resultFilter[0];



        $response = new \stdClass();
        $response->indexPengusulanmt = $result;
        return response()->json([
            'data' => $result,
            'success' => true,
            'message' => 'Success',
        ]);
    }

    public function show($id)
    {
        $loan = Loan::where('id', $id)->get();
        $user_id = auth('sanctum')->user()->id;

        $indexPeminjaman = DB::table('loan')
            ->join('mahasiswa', 'mahasiswa.id', '=', 'loan.mahasiswa_id')
            ->join('person_in_charge', 'person_in_charge.id', '=', 'loan.pic_id')
            ->where('loan.id', '=', $id)
            ->select([
                'mahasiswa.name as nama_mahasiswa',
                'loan.loan_date as tanggal', 'loan.loan_description as deskripsi', 'loan.loan_time as waktu', 'loan.mahasiswa_id',
                'loan.id', 'loan.status as statuspj', 'loan.loan_time_end as waktu_akhir'
            ])
            ->orderBy('nama_mahasiswa')
            ->get();

        $detailpj = DB::table('asset_loan_detail')
            ->join('inventory_item', 'inventory_item.id', '=', 'asset_loan_detail.inventory_item_id')
            ->join('loan', 'loan.id', '=', 'asset_loan_detail.loan_id')
            ->join('inventory', 'inventory.id', '=', 'inventory_item.inventory_id')
            ->join('asset', 'asset.id', '=', 'inventory.asset_id')
            ->where('asset_loan_detail.loan_id', '=', $id)
            ->where('loan.type_id', '=', 1)
            ->where('loan.mahasiswa_id', '=', $user_id)

            ->selectRaw(
                'count(inventory.inventory_brand) as jumlah,
            inventory.inventory_brand as merk_barang,
            inventory_item.condition as kondisi,
            inventory_item.available,
            asset_loan_detail.loan_id as loan_id,
            asset.asset_name as nama_aset,
            loan.loan_date as tanggal, loan.loan_description as deskripsi, loan.loan_time as waktu, loan.mahasiswa_id,
            loan.id, loan.status as statuspj,loan.loan_time_end as waktu_akhir,asset_loan_detail.status_pj as status_pj'

            )->orderBy('nama_aset')
            ->groupBy('merk_barang', 'kondisi', 'loan_id', 'status_pj');

        $indexPeminjamanBangunan = DB::table('building_loan_detail')
            ->join('building', 'building.id', '=', 'building_loan_detail.building_id')
            ->join('loan', 'loan.id', '=', 'building_loan_detail.loan_id')
            ->join('asset', 'asset.id', '=', 'building.asset_id')
            ->where('building_loan_detail.loan_id', '=', $id)
            ->where('loan.type_id', '=', 2)
            ->where('loan.mahasiswa_id', '=', $user_id)

            ->selectRaw(
                'count(building.building_name) as jumlah,
            building.building_name as merk_barang,
            building.condition as kondisi,
            building.available,
            building_loan_detail.loan_id as loan_id,
            asset.asset_name as nama_aset,
            loan.loan_date as tanggal, loan.loan_description as deskripsi, loan.loan_time as waktu, loan.mahasiswa_id,
            loan.id, loan.status as statuspj,loan.loan_time_end as waktu_akhir,building_loan_detail.status_pj as status_pj'
            )
            ->orderBy('nama_aset')
            ->groupBy('merk_barang', 'kondisi', 'loan_id', 'status_pj')
            ->union($detailpj)
            ->get();

        $indexPeminjamanBangunan = collect($indexPeminjamanBangunan)->filter(function ($item) {
            return $item->jumlah > 0;
        });


        $response = new \stdClass();
        // $response->indexPeminjamanBangunan = $indexPeminjamanBangunan;
        $pem = (array_values($indexPeminjamanBangunan->toArray()));
        return response()->json([
            'data' => $pem,
            'success' => true,
            'message' => 'Success',
        ]);
    }

    public function showpengusulan($id)
    {
        $proposal = Proposal::where('id', $id)->get();
        $user_id = auth('sanctum')->user()->id;

        $indexPengusulan = DB::select(
            "SELECT DISTINCT  mahasiswa.name as nama_mahasiswa,proposal.proposal_description as deskripsi, 
        proposal.status as statuspr, proposal.mahasiswa_id,proposal.id,request_proposal_asset.asset_name, 
        request_proposal_asset.spesification_detail, request_proposal_asset.amount, request_proposal_asset.unit_price, 
        request_proposal_asset.source_shop, request_proposal_asset.proposal_id,request_proposal_asset.status_pr,
        request_proposal_asset.status_confirm_faculty
        from proposal 
        join mahasiswa on mahasiswa.id = proposal.mahasiswa_id 
        JOIN proposal_type on proposal_type.id = proposal.type_id 
        JOIN request_proposal_asset on request_proposal_asset.proposal_id = proposal.id 
        WHERE type_id=1 and proposal.id=$id and proposal.mahasiswa_id=$user_id and proposal.status!='waiting'"

        );

        $response = new \stdClass();
        $response->indexPengusulan = $indexPengusulan;
        return response()->json([
            'data' => $indexPengusulan,
            'success' => true,
            'message' => 'Success',
        ]);
    }

    public function showpengusulanmt($id)
    {
        $proposal = Proposal::where('id', $id)->get();
        $user_id = auth('sanctum')->user()->id;

        $indexProposalMaintenence = DB::select(
            "SELECT DISTINCT  mahasiswa.name as nama_mahasiswa,proposal.proposal_description as deskripsi, proposal.status as statuspr, 
                    proposal.mahasiswa_id,proposal.id,request_maintenence_asset.problem_description, request_maintenence_asset.proposal_id, 
                    request_maintenence_asset.inventory_item_id,inventory_item.condition,
                    inventory_item.item_code,inventory.inventory_brand,request_maintenence_asset.id as id_req_maintenence
                    from proposal join mahasiswa on mahasiswa.id = proposal.mahasiswa_id 
                    JOIN person_in_charge on person_in_charge.id = proposal.pic_id 
                    JOIN proposal_type on proposal_type.id = proposal.type_id 
                    JOIN request_maintenence_asset on request_maintenence_asset.proposal_id = proposal.id 
                    join inventory_item on inventory_item.id=request_maintenence_asset.inventory_item_id 
                    JOIN inventory on inventory.id=inventory_item.inventory_id
                    WHERE type_id=2 and proposal.id=$id and proposal.mahasiswa_id=$user_id and proposal.status!='waiting'"
        );


        // if(count($indexProposalMaintenence) > 0){
        //     $proposalmap = collect($indexProposalMaintenence)->map(function($item){
        //         $photos = DB::select("SELECT photos.photo_name, photos.req_maintenence_id from photos
        //         where photos.req_maintenence_id = $item->id_req_maintenence");

        //         $item->photos = $photos;
        //         return $item;
        //     });

        //     $indexProposalMaintenence =  $proposalmap;
        // }


        $response = new \stdClass();
        $response->indexProposalMaintenence = $indexProposalMaintenence;

        return response()->json([
            'data' => $indexProposalMaintenence,
            'success' => true,
            'message' => 'Success',
        ]);
    }

    public function showbukti($id)
    {
        $request_maintenence_asset = RequestMaintenenceAsset::where('id', $id)->get();
        // $user_id = auth('sanctum')->user()->id;

        $indexProposalMaintenence = DB::select(
            "SELECT photos.photo_name, photos.req_maintenence_id
                    from photos 
                    JOIN request_maintenence_asset on request_maintenence_asset.id = photos.req_maintenence_id 
                    WHERE photos.req_maintenence_id=$id"
        );

        $response = new \stdClass();
        $response->indexProposalMaintenence = $indexProposalMaintenence;
        return response()->json([
            'data' => $indexProposalMaintenence,
            'success' => true,
            'message' => 'Success',
        ]);
    }
}
