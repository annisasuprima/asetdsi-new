@extends('layouts.master')
@section('title') Detail Pengusulan @endsection

@section('css')
<link href="{{ URL::asset('assets/plugins/jvectormap/jquery-jvectormap-2.0.2.css') }}" rel="stylesheet">
<link href="{{ URL::asset('assets/plugins/sweet-alert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css">
<link href="{{ URL::asset('assets/plugins/animate/animate.css') }}" rel="stylesheet" type="text/css">
@endsection


<style>
    /* ukuran font */
    .ukuran {
        font-size: 1rem !important;
        color: black;
    }

    .ukuran-icon {
        font-size: 1.2rem !important;

    }

    .warna-header {
        background-color: rgba(0, 0, 0, 0.03) !important;
    }

    .table th {
        color: #3a3636 !important;
        text-align: center !important;
    }

    /* warna button */
    .warna1 {
        background: unset !important;
        background-color: #206A5D !important;
        color: white !important;
        border-color: #206A5D !important;
        transition: all 0.5s;
        cursor: pointer;
    }

    .warna1 span {
        cursor: pointer;
        display: inline-block;
        position: relative;
        transition: 0.5s;

    }

    .warna1 span:after {
        content: '\00bb';
        position: absolute;
        opacity: 0;
        top: 0;
        right: -20px;
        transition: 0.5s;
    }

    .warna1:hover span {
        padding-right: 25px;
    }

    .warna1:hover span:after {
        opacity: 1;
        right: 0;
    }


    .warna2 {
        background: unset !important;
        background-color: #81B214 !important;
        color: white !important;
        border-color: #81B214 !important;
    }

    .warna2 span {
        cursor: pointer;
        display: inline-block;
        position: relative;
        transition: 0.5s;
    }

    .warna2 span:after {
        content: '\00bb';
        position: absolute;
        opacity: 0;
        top: 0;
        right: -20px;
        transition: 0.5s;
    }

    .warna2:hover span {
        padding-right: 25px;
    }

    .warna2:hover span:after {
        opacity: 1;
        right: 0;
    }

    .warna3 {
        background: unset !important;
        background-color: #FFCC29 !important;
        color: black !important;
        border-color: #FFCC29 !important;
    }

    .warna3 span {
        cursor: pointer;
        display: inline-block;
        position: relative;
        transition: 0.5s;
    }

    .warna3 span:after {
        content: '\00bb';
        position: absolute;
        opacity: 0;
        top: 0;
        right: -20px;
        transition: 0.5s;
    }

    .warna3:hover span {
        padding-right: 25px;
    }

    .warna3:hover span:after {
        opacity: 1;
        right: 0;
    }


    .warna4 {
        background: unset !important;
        background-color: #7042da !important;
        color: white !important;
        border-color: #7042da !important;
    }

    .warna4 span {
        cursor: pointer;
        display: inline-block;
        position: relative;
        transition: 0.5s;
    }

    .warna4 span:after {
        content: '\00bb';
        position: absolute;
        opacity: 0;
        top: 0;
        right: -20px;
        transition: 0.5s;
    }

    .warna4:hover span {
        padding-right: 25px;
    }

    .warna4:hover span:after {
        opacity: 1;
        right: 0;
    }

    .warna5 {
        background: unset !important;
        background-color: #15b67d !important;
        color: white !important;
        border-color: #15b67d !important;
    }

    .warna5 span {
        cursor: pointer;
        display: inline-block;
        position: relative;
        transition: 0.5s;
    }

    .warna5 span:after {
        content: '\00bb';
        position: absolute;
        opacity: 0;
        top: 0;
        right: -20px;
        transition: 0.5s;
    }

    .warna5:hover span {
        padding-right: 25px;
    }

    .warna5:hover span:after {
        opacity: 1;
        right: 0;
    }

    .transisi {
        position: relative;
        background-color: #ffaf00 !important;
        border: none;

        color: #FFFFFF;

        text-align: center;
        -webkit-transition-duration: 0.4s;
        /* Safari */
        transition-duration: 0.4s;
        text-decoration: none;
        overflow: hidden;
        cursor: pointer;
        margin-right: 2rem !important;
        padding: 0.8rem !important;
    }

    .transisi:hover {

        background-color: #FE9E28 !important;
        color: white;
    }


    .transisi3 {
        position: relative;
        background-color: #1A4D2E !important;
        border: none;

        color: #FFFFFF;

        text-align: center;
        -webkit-transition-duration: 0.4s;
        /* Safari */
        transition-duration: 0.4s;
        text-decoration: none;
        overflow: hidden;
        cursor: pointer;
        margin-right: 2rem !important;
        padding: 0.8rem !important;
    }

    .transisi3:hover {

        background-color: #4bac71 !important;
        color: white;
    }


    /* warna header */

    .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
        color: white !important;
        border: 1px solid #338f00;

        background: #1A4D2E;
    }

    .arai {
        display: block;
    }

    td.bg_red {
        background-color: red;
    }

    td.bg_yellow {
        background-color: yellow;
    }

    td.bg_green {
        background-color: green;
    }

    .atur {
        display: flex !important;
        align-items: center !important;
    }

    .list tr {
        border: unset !important;
    }

    .list tr td {
        border: 1px #ccc solid !important;
    }


    /* Add Animation */

    /* 100% Image Width on Smaller Screens */
    @media only screen and (max-width: 700px) {
        .modal-content {
            width: 100%;
        }

        .buat {
            width: 20rem !important;

        }

        .resp {
            flex-direction: column;

        }

        h5.card-title {
            font-size: 0.9rem;
        }

        h6.card-subtitle {
            font-size: 0.9rem;
        }
    }
</style>


@section('content')
@component('components.breadcrumb')
@slot('li_1') AsetDSI @endslot
@slot('li_2') Detail @endslot
@slot('li_3') Daftar Maintenence @endslot
@slot('title') Detail @endslot
@endcomponent


<div class="row mt-2">
    <div class="col-md-12 grid-margin">
        <div class="card shadow-sm bg-body rounded">
            <div class="card-header warna-header">


                <h4 class="card-title" style="margin-bottom: unset; color: #1A4D2E !important;">Detail Pengusulan</h4>

            </div>

            <div class="card-body">
                <div class="d-flex justify-content-between m-3 resp">


                    @php
                    $s = $resultFilter;
                    @endphp

                    <!-- Card header -->
                    <div class="card buat shadow-sm" style="width: 30rem;display:flex;flex-direction:row;align-self: flex-start;">
                        <div class="card-body">
                            <div style="display: flex;align-items:center">
                                <i class="mdi mdi-rename-box" style="color: #1a4d2e;"></i>
                                <h5 class="card-title" style="margin-left: 1rem;color:#1A4D2E">Pengusul : {{$s->pic_name}}</h5>

                            </div>
                            <div style="display: flex;align-items:center">
                                <i class="mdi mdi-car-door" style="color: #1a4d2e;"> </i>
                                <h6 class="card-subtitle text-dark" style="margin-left: 1rem;">Keterangan : {{$s->deskripsi}}</h6>

                            </div>
                            <div style="display: flex;align-items:center">
                                <i class="mdi mdi-car-door" style="color: #1a4d2e;"> </i>

                                @if ($s->statuspr == 'waiting')
                                <h6 class="card-subtitle text-dark" style="margin-left: 1rem;">Status :</h6><span class="badge rounded bg-warning name mb-0 text-md p-1 ms-3" style="display: block;color:black !important;">{{$s->statuspr}}</span>
                                @elseif ($s->statuspr == 'accepted')
                                <h6 class="card-subtitle text-dark" style="margin-left: 1rem;">Status :</h6><span class="badge rounded bg-success name mb-0 text-md p-1 ms-3" style="display: block;color:white !important;">{{$s->statuspr}}</span>
                                @elseif ($s->statuspr == 'rejected')
                                <h6 class="card-subtitle text-dark" style="margin-left: 1rem;">Status :</h6><span class="badge rounded bg-danger name mb-0 text-md p-1 ms-3" style="display: block;color:white !important;">{{$s->statuspr}}</span>
                                @elseif ($s->statuspr == 'cancelled')
                                <h6 class="card-subtitle text-dark" style="margin-left: 1rem;">Status :</h6><span class="badge rounded bg-danger name mb-0 text-md p-1 ms-3" style="display: block;color:white !important;">{{$s->statuspr}}</span>
                                @endif




                            </div>

                            @if($s->statuspr == 'rejected')
                            <hr>
                            <h5 class="card-title" style="color:#1A4D2E">Alasan Penolakan</h5>
                            @foreach ($s->alasans as $a)
                            <div style="display: flex;align-items:center">
                                <i class="mdi mdi-circle-outline" style="color: #1a4d2e;"> </i>
                                <h6 class="card-subtitle text-dark" style="margin-left: 1rem;">{{$a}}</h6>
                            </div>
                            @endforeach

                            @endif

                        </div>
                    </div>




                    @if($indexPengusulan[0]->statuspr == 'cancelled')

                    <div class="card buat shadow-sm" style="width: 10rem;display:flex;flex-direction:row;align-self:flex-start !important">
                        <div class="card-body">
                            <div class="mb-2" style="align-items:center">

                                <h5 class="card-title text-center" style="margin-left: 1rem;color:#1A4D2E">No Action</h5>
                                <hr>

                            </div>

                        </div>
                    </div>
                    @else
                    <div class="card buat shadow-sm" style="width: 10rem;display:flex;flex-direction:row;align-self:flex-start !important;align-self:flex-start !important">
                        <div class="card-body">
                            <div class="mb-2" style="align-items:center">

                                <h5 class="card-title text-center" style="margin-left: 1rem;color:#1A4D2E">Setujui/Tolak</h5>
                                <hr>

                            </div>
                            @if(count($result)>0)
                            <div class="d-flex justify-content-center">
                                <button class="btn btn-success btn-sm me-2"><a class="ukuran-icon" id="setuju">
                                        <i class=" mdi mdi-check" aria-hidden="true" style="color: white;"></i></a>

                                </button>

                                <button class="btn btn-danger btn-sm"><a class="ukuran-icon" id="tolak">
                                        <i class=" mdi mdi-close" aria-hidden="true" style="color: white;"></i></a>

                                </button>
                            </div>
                            @endif

                        </div>
                    </div>
                    @endif
                </div>


                <!-- Light table -->
                <div class="table-responsive" style="padding: 10px; padding-top: 10px;">
                    <table id="table" class="table table-bordered table-hover align-items-center table-flush pt-2 ">
                        <thead class="thead-light">
                            <tr>
                                <th scope="col" class="ukuran">Nama Aset</th>
                                <th scope="col" class="ukuran">Merk Barang</th>
                                <th scope="col" class="ukuran" style="width:8%;">Kondisi</th>
                                <th scope="col" class="ukuran">Permasalahan</th>
                                <th scope="col" class="ukuran">Foto</th>
                                <th scope="col" class="ukuran" style="width:10%;">Status</th>
                                <th scope="col" class="ukuran" style="width:5%;">Action</th>

                            </tr>
                        </thead>
                        <tbody class="list">

                            @php
                            $a = 0;
                            @endphp
                            @foreach($result as $i)
                            <tr>

                                <td>
                                    <span class="name mb-0 text-md ukuran">{{$i->asset_name}}</span>
                                </td>
                                <td>
                                    <span class="name mb-0 text-md ukuran">{{$i->merk_barang}}</span>
                                </td>


                                <td>

                                    <span class="name mb-0 text-md ukuran arai" style="display: block;">{{$i->kondisi}}</span>

                                </td>
                                <td>

                                    <span class="name mb-0 text-md ukuran arai" style="display: block;">{{$i->problem_description}}</span>

                                </td>

                                <td>

                                    <div class="d-flex justify-content-center">
                                        <div id="carouselExampleDark-{{$i->item_id}}" class="carousel carousel-dark slide" data-bs-ride="carousel">
                                            <div class="carousel-indicators">
                                                @php
                                                $pa = 0;
                                                @endphp
                                                @if(count($photos[$a]) > 0)
                                                @foreach($photos[$a] as $p)
                                                @if($pa == 0)
                                                <button type="button" data-bs-target="#carouselExampleDark-{{$i->item_id}}" data-bs-slide-to={{$pa}} class="active" aria-current="true" aria-label="Slide 1"></button>
                                                @else
                                                <button type="button" data-bs-target="#carouselExampleDark-{{$i->item_id}}" data-bs-slide-to={{$pa}} aria-label="Slide 2"></button>
                                                @endif
                                                @php
                                                $pa++;
                                                @endphp
                                                @endforeach
                                                @endif
                                            </div>
                                            <div class="carousel-inner">
                                                @php
                                                $pi = 0;
                                                @endphp
                                                @if(count($photos[$a]) > 0)
                                                @foreach($photos[$a] as $p)
                                                @if($pi == 0)
                                                <div class="carousel-item active">
                                                    <img src="{{$p->photo_name}}" alt="" class="d-block w-20" style="width: 120px;height:100px;">
                                                </div>

                                                @else
                                                <div class="carousel-item">
                                                    <img src="{{$p->photo_name}}" alt="" class="d-block w-20" style="width: 120px;height:100px;">
                                                </div>

                                                @endif
                                                @php
                                                $pi++;
                                                @endphp
                                                @endforeach
                                                @endif
                                            </div>
                                            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleDark-{{$i->item_id}}" data-bs-slide="prev">
                                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                                <span class="visually-hidden">Previous</span>
                                            </button>
                                            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleDark-{{$i->item_id}}" data-bs-slide="next">
                                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                                <span class="visually-hidden">Next</span>
                                            </button>
                                        </div>
                                    </div>

                                </td>
                                <td>

                                    @if ($i->status_mt == 'waiting')
                                    <span class="badge rounded-pill bg-warning name mb-0 text-md p-2" style="display: block;color:black !important;">{{$i->status_mt}}</span>
                                    @elseif ($i->status_mt == 'accepted')
                                    <span class="badge rounded-pill bg-success name mb-0 text-md p-2" style="display: block;color:white !important;">{{$i->status_mt}}</span>
                                    @elseif ($i->status_mt == 'rejected')
                                    <span class="badge rounded-pill bg-danger name mb-0 text-md p-2" style="display: block;color:white !important;">{{$i->status_mt}}</span>
                                    @elseif ($i->status_mt == 'cancelled')
                                    <span class="badge rounded-pill bg-danger name mb-0 text-md p-2" style="display: block;color:white !important;">{{$i->status_mt}}</span>

                                    @endif

                                </td>

                                <td>
                                    <div class="d-flex justify-content-center">
                                        <a class="btn btn-sm btn-neutral ukuran-icon">
                                            <i class=" mdi mdi-pencil " style="color: green;" aria-hidden="true" data-bs-toggle="modal" data-bs-target="#exampleModal-{{$i->id}}"></i></a>
                                        <!-- <a class="btn btn-sm btn-neutral brgdeletebtn ukuran-icon" href="{{route('pj-aset.stock.destroy',[$i->item_id])}}" onclick="return confirm('Yakin Ingin Menghapus?')"><i class=" mdi mdi-delete " style="color: red;" aria-hidden="true"></i></a> -->

                                        @foreach($result as $data)
                                        <!-- Modal -->
                                        <div class="modal fade" id="exampleModal-{{$data->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header" style="background-color:#1A4D2E !important;">
                                                        <h5 class="modal-title" id="exampleModalLabel">Konfirmasi Status</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <form action="{{route('pengusulanmt.updatemt',[$data->id])}}" method="post" id="add_form" enctype="multipart/form-data">

                                                        <div class="modal-body">


                                                            {{csrf_field()}}
                                                            <div class="content m-3 p-1">

                                                                <div class="col-12 col-md-12">

                                                                    <div class="row mb-3">

                                                                        <div class="col">
                                                                            <label>Status</label>
                                                                            <select class="form-select form-group-default" aria-label="status_mt" id="status_mt" name="status_mt">
                                                                                <option selected>{{$data->status_mt}}</option>
                                                                                <option value="accepted">accepted</option>
                                                                                <option value="rejected">rejected</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>

                                                                </div>
                                                            </div>

                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                            <button type="submit" class="btn btn-warning">Save Konfirmasi</button>
                                                        </div>

                                                    </form>
                                                </div>

                                            </div>
                                        </div>
                                        @endforeach


                                    </div>

                                </td>


                            </tr>
                            @php
                            $a++;
                            @endphp
                            @endforeach
                        </tbody>
                    </table>

                </div>

                <!-- Datatables -->
                <!-- <script src="../../assets/js/plugin/datatables/datatables.min.js"></script> -->
                <script type="text/javascript">
                    $.noConflict();
                    jQuery(document).ready(function($) {
                        $('#table').DataTable();

                    });
                </script>

                <script>
                    $('#setuju').click(function() {
                        const href = "{{route('pengusulanmt.acc',[$result[0]->proposal_id])}}"
                        Swal.fire({
                            title: 'Confirm Pengusulan',
                            text: "Apakah kamu yakin ingin menyetujui?",
                            type: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#157347',
                            cancelButtonColor: '#bb2d3b',
                            confirmButtonText: 'Setujui',
                            cancelButtonText: 'Batal'
                        }).then(function(result) {
                            if (result.value) {
                                document.location.href = href;
                                Swal.fire(
                                    'Sukses!',
                                    'Pengusulan berhasil disetujui',
                                    'success'
                                )
                            }
                        })
                    });


                    $('#tolak').click(function() {

                        Swal.fire({
                            title: 'Confirm Pengusulan',
                            text: "Apakah kamu yakin ingin menolak?",
                            type: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#157347',
                            cancelButtonColor: '#bb2d3b',
                            confirmButtonText: 'Tolak',
                            cancelButtonText: 'Batal',
                            input: 'textarea',
                            inputPlaceholder: 'Masukkan Alasan Penolakan....'
                        }).then(function(result) {
                            if (result.value) {
                                const value = result?.value || 'cancel'
                                const href = "{{route('pengusulanmt.reject',['id' => $result[0]->proposal_id,'messages' => ' '])}}" + value
                                // console.log(result.value);
                                document.location.href = href;
                                Swal.fire(
                                    'Sukses!',
                                    'Pengusulan berhasil ditolak',
                                    'success'
                                )
                            }
                        })
                    });
                </script>




                <!-- Card footer -->
            </div>
        </div>

    </div>
</div>

@endsection
@section('script')
<!-- sweeetalert -->
<script src="{{ URL::asset('assets/plugins/sweet-alert2/sweetalert2.min.js') }}"></script>
<script src="{{ URL::asset('assets/js/pages/jquery.sweet-alert.init.js') }}"></script>

<script src="{{ URL::asset('assets/plugins/apex-charts/apexcharts.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/jvectormap/jquery-jvectormap-2.0.2.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/jvectormap/jquery-jvectormap-us-aea-en.js') }}"></script>
<script src="{{ URL::asset('assets/js/pages/jquery.analytics_dashboard.init.js') }}"></script>
<script src="{{ URL::asset('assets/js/app.js') }}"></script>
@endsection