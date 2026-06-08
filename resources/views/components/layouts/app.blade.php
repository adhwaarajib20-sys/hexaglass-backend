@props(['title' => 'Dashboard'])

@extends('layouts.app')

@section('content')
{{ $slot }}
@endsection
