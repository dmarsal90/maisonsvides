@extends('layouts.app')

@section('content')
<?php setlocale(LC_TIME, "fr_BE"); ?>

<div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @session()->forget('success')
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @session()->forget('error')
    @endif
      <script>
        setTimeout(function() {
            $('.alert').slideUp();
        }, 3000);
    </script>
    <div class="row mb-4">
        <a role="button" class="btn btn-success" data-toggle="modal" data-target="#createTicket"><i class="bi bi-plus" style="font-size:17px;"></i>Nouveau Ticket</a>
    </div>
    <div class="mb-10">
        <table data-table="touts" class="display responsive nowrap" style="width: 100%;">
            <thead>
                <tr>
                    <?php if (Auth::user()->type == 2) : ?>
                        <th>Agents</th>
                    <?php endif ?>
                    <th>Id</th>
                    <th>Suject</th>
                    <th>Demandeur</th>
                    <th>Demandé</th>
                    <th>Mis à jour</th>
                </tr>
            </thead>
            <tbody>
                @foreach($auxTickets as $ticket)
                <tr>
                    <?php if (Auth::user()->type == 2) : ?>
                        <td>Users</td>
                    <?php endif ?>
                    <td>{!! $ticket->id !!}</td>
                    <td><a href="{!! route('viewoneticket', $ticket->id) !!}">{!! $ticket->title !!}</a></td>
                    <td>{!! $ticket->requester->name !!}</td>
                    <td>{!! strftime('%a', strtotime($ticket->created_at)) !!} {!! date('d-m-Y', strtotime($ticket->created_at)) !!}</td>
                    <td>{!! strftime('%a', strtotime($ticket->updated_at)) !!} {!! date('d-m-Y', strtotime($ticket->updated_at)) !!}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <hr>
    <h4>Résolu</h4>
    <hr>
    <table data-table="touts" class="display responsive nowrap" style="width: 100%;">
        <thead>
            <tr>
                <?php if (Auth::user()->type == 2) : ?>
                    <th>Agents</th>
                <?php endif ?>
                <th>Id</th>
                <th>Suject</th>
                <th>Demandeur</th>
                <th>Demandé</th>
                <th>Mis à jour</th>
            </tr>
        </thead>
        <tbody>
            @foreach($auxTicketsSolved as $ticket)
            <tr>
                <td>{!! $ticket->id !!}</td>
                <td><a href="{!! route('viewoneticket', $ticket->id) !!}">{!! $ticket->title !!}</a></td>
                <td>{!! $ticket->requester->name !!}</td>
                <td>{!! strftime('%a', strtotime($ticket->created_at)) !!} {!! date('d-m-Y', strtotime($ticket->created_at)) !!}</td>
                <td>{!! strftime('%a', strtotime($ticket->updated_at)) !!} {!! date('d-m-Y', strtotime($ticket->updated_at)) !!}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
@section('modals')
<div class="modal fade" id="createTicket" tabindex="-1" aria-labelledby="createTicketLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
        <div class="modal-content">
            <div class="modal-body">
                <!-- <form action="{!! route('createticket') !!}" method="POST" data-form="form-create-ticket"> -->
                <form action="{!! route('createticket') !!}" method="POST">
                    @csrf()
                    <div class="card">
                        <div class="card-header">Créer un nouveau ticket</div>
                        <div class="card-body">
                            <div class="mb-2">
                                <span class="ffhnm">Demandeur: </span>
                            </div>
                            <div class="row mb-3">
                                <div class="col-xs-12 col-sm-4 col-md-4 col-lg-12 col-xl-4">
                                    Nom:
                                </div>
                                <div class="col-xs-12 col-sm-8 col-md-8 col-lg-12 col-xl-8">
                                    <input type="text" class="form-control" name="name_ticket">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-xs-12 col-sm-4 col-md-4 col-lg-12 col-xl-4">
                                    Email:
                                </div>
                                <div class="col-xs-12 col-sm-8 col-md-8 col-lg-12 col-xl-8">
                                    <input type="text" class="form-control" name="email">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-xs-12 col-sm-4 col-md-4 col-lg-12 col-xl-4">
                                    Sujet:
                                </div>
                                <div class="col-xs-12 col-sm-8 col-md-8 col-lg-12 col-xl-8">
                                    <input type="text" class="form-control" name="subject">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-xs-12 col-sm-4 col-md-4 col-lg-12 col-xl-4">
                                    Commenté:
                                </div>
                                <div class="col-xs-12 col-sm-8 col-md-8 col-lg-12 col-xl-8">
                                    <textarea class="form-control" rows="4" name="body"></textarea>
                                </div>
                            </div>
                            <div class="text-right">
                                <!-- <button type="submit" class="btn btn-lg btn-success" data-submit-form="form-create-ticket">Nouveau</button> -->
                                <button type="submit" class="btn btn-lg btn-success">Nouveau</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
