@extends('layouts.myLayout')

@section('content')
    <div class="wrapper wrapper-content">
        <div class="row">
            <div class="col-lg-4">
                <div class="ibox ">
                    <div class="ibox-title">
                        <span class="label label-success float-right">Monthly</span>
                        <h5>Reservas</h5>
                    </div>
                    <div class="ibox-content">
                        <h1 class="no-margins"></h1>
                        <div class="stat-percent font-bold text-white">
                            <a href="#" class="btn btn-success">Ir a Reservas <i class="fa fa-level-up"></i></a>
                        </div>
                        <small>Total reservas</small>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="ibox ">
                    <div class="ibox-title">
                        <span class="label label-info float-right">Annual</span>
                        <h5>Buses</h5>
                    </div>
                    <div class="ibox-content">
                        <h1 class="no-margins"></h1>
                        <div class="stat-percent font-bold text-white">
                            <a href="#" class="btn btn-info">Ir a Buses <i class="fa fa-level-up"></i></a>
                        </div>
                        <small>Total Buses</small>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="ibox ">
                    <div class="ibox-title">
                        <span class="label label-primary float-right">Today</span>
                        <h5>Rutas</h5>
                    </div>
                    <div class="ibox-content">
                        <h1 class="no-margins"></h1>
                        <div class="stat-percent font-bold text-white">
                            <a href="#" class="btn btn-primary">Ir a Rutas <i class="fa fa-level-up"></i></a>
                        </div>
                        <small>Total Rutas</small>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="ibox ">
                    <div class="ibox-title">
                        <span class="label label-warning float-right">Annual</span>
                        <h5>Clientes</h5>
                    </div>
                    <div class="ibox-content">
                        <h1 class="no-margins"></h1>
                        <div class="stat-percent font-bold text-white">
                            <a href="#" class="btn btn-warning">Ir a Clientes <i class="fa fa-level-up"></i></a>
                        </div>
                        <small>Total Clientes</small>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="ibox ">
                    <div class="ibox-title">
                        <span class="label label-white text-white float-right">Today</span>
                        <h5>Choferes</h5>
                    </div>
                    <div class="ibox-content">
                        <h1 class="no-margins"></h1>
                        <div class="stat-percent font-bold text-white">
                            <a href="#" class="btn btn-dark">Ir a Choferes <i class="fa fa-level-up"></i></a>
                        </div>
                        <small>Total Choferes</small>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="ibox ">
                    <div class="ibox-title">
                        <span class="label label-danger float-right">Low value</span>
                        <h5>Ganancias</h5>
                    </div>
                    <div class="ibox-content">
                        <h1 class="no-margins"></h1>
                        <div class="stat-percent font-bold text-white">
                            <a href="#" class="btn btn-danger">Ir a Reportes <i class="fa fa-level-up"></i></a>
                        </div>
                        <small>Total Ganancias</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
