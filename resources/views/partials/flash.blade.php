@if (session('success'))
    <div class="mb-4 bg-green-50 border border-green-200 text-green-800 rounded-lg p-4">
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div class="mb-4 bg-red-50 border border-red-200 text-red-800 rounded-lg p-4">
        {{ session('error') }}
    </div>
@endif
