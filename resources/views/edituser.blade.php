@extends('template.backend.master')

@section('content')
{{-- การส่งค่า id ไปยัง component --}}
    @livewire('editusertable', ['id' => $id])



@endsection