@extends('layouts.admin.app')

@section('content')
<div class="container-fluid py-4">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-black uppercase">Manual Book</h2>
        <a href="{{ route('manual.download') }}" target="_blank"
            class="px-4 py-2 bg-black text-white rounded text-xs font-bold uppercase">
            <i class="fas fa-download mr-1"></i> DOWNLOAD
        </a>
    </div>

    <div class="border-2 border-black h-[80vh]">
        <iframe src="{{ route('manual.download') }}" class="w-full h-full" style="border: none;"></iframe>
    </div>
</div>
@endsection