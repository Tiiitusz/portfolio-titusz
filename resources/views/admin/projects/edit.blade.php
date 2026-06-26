@extends('layouts.app')

@section('title', 'Edit Project')

@section('content')
    @include('admin.projects._form', [
        'project' => $project,
        'action' => route('admin.projects.update', $project),
        'method' => 'PUT',
        'heading' => 'Edit Project',
        'buttonLabel' => 'Update Project',
        'backUrl' => route('admin.dashboard'),
    ])
@endsection