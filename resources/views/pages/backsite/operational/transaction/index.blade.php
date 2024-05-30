@extends('layouts.app')

@section('title', 'Transaction')

@section('content')

<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="content-wrapper">

        @if ($errors->any())
            <div class="alert bg-danger alert-dismissible mb-2" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="content-header row">
            <div class="content-header-left col-md-6 col-12 mb-2 breadcrumb-new">
                <h3 class="content-header-title mb-0 d-inline-block">Transaction</h3>
                <div class="row breadcrumbs-top d-inline-block">
                    <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('backsite.dashboard.index') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Transaction</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        @can('transaction_table')
            <div class="content-body">
                <section id="table-home">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Transaction List</h4>
                                    <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                                    <div class="heading-elements">
                                        <ul class="list-inline mb-0">
                                            <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                                            <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                                        </ul>
                                    </div>
                                </div>

                                <div class="card-content collapse show">
                                    <div class="card-body card-dashboard">

                                        <!-- Filter Form -->
                                        <form method="GET" action="{{ route('backsite.transaction.index') }}">
                                            <div class="row">
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label for="start_date">Start Date</label>
                                                        <input type="date" class="form-control" id="start_date" name="start_date" value="{{ request('start_date') }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label for="end_date">End Date</label>
                                                        <input type="date" class="form-control" id="end_date" name="end_date" value="{{ request('end_date') }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-2 d-flex align-items-end">
                                                    <div class="form-group">
                                                        <button type="submit" class="btn btn-primary">Filter</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>

                                        <div class="table-responsive">
                                            <table class="table table-striped table-bordered text-inputs-searching default-table">
                                                <thead>
                                                    <tr>
                                                        <th>Date</th>
                                                        <th>Doctor</th>
                                                        <th>Patient</th>
                                                        <th>Fee Doctor</th>
                                                        <th>Fee Specialist</th>
                                                        <th>Fee Hospital</th>
                                                        <th>Sub total</th>
                                                        <th>Vat</th>
                                                        <th>Total</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse($transaction as $transaction_item)
                                                        <tr data-entry-id="{{ $transaction_item->id }}">
                                                            <td>{{ isset($transaction_item->created_at) ? date("d/m/Y H:i:s",strtotime($transaction_item->created_at)) : '' }}</td>
                                                            <td>{{ $transaction_item->appointment->doctor->name ?? 'No Doctor' }}</td>
                                                            <td>{{ $transaction_item->appointment->user->name ?? 'No Patient' }}</td>
                                                            <td>{{ 'IDR '.number_format($transaction_item->fee_doctor) ?? '' }}</td>
                                                            <td>{{ 'IDR '.number_format($transaction_item->fee_specialist) ?? '' }}</td>
                                                            <td>{{ 'IDR '.number_format($transaction_item->fee_hospital) ?? '' }}</td>
                                                            <td>{{ 'IDR '.number_format($transaction_item->sub_total) ?? '' }}</td>
                                                            <td>{{ 'IDR '.number_format($transaction_item->vat) ?? '' }}</td>
                                                            <td>{{ 'IDR '.number_format($transaction_item->total) ?? '' }}</td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="9">No transactions found</td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <th>Date</th>
                                                        <th>Doctor</th>
                                                        <th>Patient</th>
                                                        <th>Fee Doctor</th>
                                                        <th>Fee Specialist</th>
                                                        <th>Fee Hospital</th>
                                                        <th>Sub total</th>
                                                        <th>Vat</th>
                                                        <th>Total</th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        @endcan

    </div>
</div>

@endsection

@push('after-script')
    <script>
        $('.default-table').DataTable({
            "order": [],
            "paging": true,
            "lengthMenu": [ [5, 10, 25, 50, 100, -1], [5, 10, 25, 50, 100, "All"] ],
            "pageLength": 10
        });
    </script>
@endpush
