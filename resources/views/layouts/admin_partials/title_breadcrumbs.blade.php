<div class="row">
    @isset($title)
        <div class="col-12 col-md-6 order-md-1 order-last">
            <h4>{{ $title }}</h4>
            @isset($sub_title)
                <p class="text-sm text-muted mb-1">{{ $sub_title }}</p>
            @endisset
        </div>
    @endisset
    @isset($current_page)
        <div class="col-12 col-md-6 order-md-2 order-first">
            <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="bi bi-grid-fill"></i>
                            {{ __('admin.dashboard') }}</a></li>
                    @isset($paths)
                        @foreach ($paths as $path)
                            <li class="breadcrumb-item"><a href="{{ $path['url'] }}">{{ $path['title'] }}</a></li>
                        @endforeach
                    @endisset
                    <li class="breadcrumb-item active" aria-current="page">{{ $current_page }}</li>
                </ol>
            </nav>
        </div>
    @endisset
</div>
