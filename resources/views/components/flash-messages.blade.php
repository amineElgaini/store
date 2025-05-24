@if(session('success'))
    <div class="bg-green-100 text-green-800 p-3 rounded mb-4">
        {{ session('success') }}
    </div>
@endif

@if(session('info'))
    <div class="bg-blue-100 text-blue-800 p-3 rounded mb-4">
        {{ session('info') }}
    </div>
@endif

@if($errors->any())
    <div class="bg-red-100 text-red-800 p-3 rounded mb-4">
        @foreach ($errors->all() as $error)
            <div>{{ $error }}</div>
        @endforeach
    </div>
@endif
