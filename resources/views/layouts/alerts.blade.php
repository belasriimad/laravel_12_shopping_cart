<div class="my-3">
    @session('error')
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endsession
    @session('info')
        <div class="alert alert-info">
            {{ session('info') }}
        </div>
    @endsession
    @session('success')
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endsession
</div>