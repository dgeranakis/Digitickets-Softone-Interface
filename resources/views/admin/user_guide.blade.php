@extends('layouts.admin')

@section('meta_tags')
    <title>{{ __('admin.user_guide') }} | {{ config('app.name') }}</title>
@endsection

@section('page_title')
    @include('layouts.admin_partials.title_breadcrumbs', [
        'title' => __('admin.user_guide'),
        'current_page' => __('admin.user_guide'),
    ])
@endsection

@section('content')
    <section class="section vh-100">
        <div class="card bg-white h-75">
            <div class="card-body">
                <iframe src="{{ asset(__('admin.user_guide_file')) }}" width="100%" height="100%"></iframe>
            </div>
        </div>
    </section>
@endsection
