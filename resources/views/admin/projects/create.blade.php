@extends('layouts.app')

@section('title', 'Add Project')

@section('content')
    @include('admin.projects._form', [
        'project' => $project,
        'action' => route('admin.projects.store'),
        'method' => 'POST',
        'heading' => 'Add Project',
        'buttonLabel' => 'Save Project',
        'backUrl' => route('admin.dashboard'),
    ])
@endsection


