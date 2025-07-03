@extends('client.accounts.information')

@section('content-information')
    <div id="dashboard" class="content-section active mb-4">
        <h2 class="fs-5">Chào mừng {{ Auth::user()->name }} quay trở lại!</h2>

    </div>
@endsection
