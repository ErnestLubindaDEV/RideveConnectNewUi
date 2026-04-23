@extends('partials.layouts.master')

@section('title', 'Rideve Connect')

@section('title-sub', 'Pages')
@section('pagetitle', 'Employee Documentation')
@section('css')
<link rel="stylesheet" href="{{ asset('../assets/libs/air-datepicker/air-datepicker.css') }}">
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css">
<link rel="stylesheet" href="../assets/libs/simplebar/simplebar.min.css">
<link href="../assets/libs/swiper/swiper-bundle.min.css" rel="stylesheet">
<link href="../assets/libs/nouislider/nouislider.min.css" rel="stylesheet">
<link href="../assets/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css">
<link href="../assets/css/icons.min.css" rel="stylesheet" type="text/css">
<link href="../assets/css/app.min.css" id="app-style" rel="stylesheet" type="text/css">
@endsection
@section('content')


    <!-- begin::App -->
    <div id="layout-wrapper">
    </div><!--End container-fluid-->
    </main><!--End app-wrapper-->
@endsection

@section('js')

    <!-- App js -->
    <script type="module" src="assets/js/app.js"></script>
@endsection
