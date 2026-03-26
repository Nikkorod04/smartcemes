@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100">
    <livewire:manage-program-outputs :program_id="$program_id" />
</div>
@endsection
