@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100">
    <livewire:manage-program-baseline :program_id="$program_id" />
</div>
@endsection
