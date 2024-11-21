@extends('layouts.admin')

@section('meta_tags')
    <title>{{ __('admin.dashboard') }} | {{ config('app.name') }}</title>
@endsection

@section('content')
    <section class="section vh-100">
        <div class="card bg-white h-75">
            <div class="card-header">{{ __('admin.dashboard') }}</div>
            <div class="card-body">
                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif

            </div>
        </div>
    </section>
@endsection
