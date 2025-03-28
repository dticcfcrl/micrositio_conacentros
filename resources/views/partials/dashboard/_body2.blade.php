<div id="loading">
    @include('partials.dashboard._body_loader')
</div>
<main class="main-content">
@include('partials.dashboard._body_header1')
    <div class="conatiner-fluid content-inner pb-0 p-0">
    {{ $slot }}
    </div>
    @include('partials.dashboard._body_footer')
</main>

@include('partials.components.setting-offcanvas')
@include('partials.dashboard._scripts')
@include('partials.dashboard._app_toast')
@stack('js')
<div class="modal fade" id="formModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
<div class="modal-dialog">
    <div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title" id="formTitle">Modal title</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
        <div class="main_form"></div>
    </div>
    </div>
</div>
</div>
