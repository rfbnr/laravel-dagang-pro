@extends('theem.layouts.app')

@section('content')
    <div class="content-page">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="d-flex flex-wrap flex-wrap align-items-center justify-content-between mb-4">
                        <div>
                            <h4 class="mb-3">User List</h4>
                            <p class="mb-0">Here you can manage your Users list.</p>

                        </div>
                        <a href="{{ route('user.create') }}" class="btn btn-primary add-list"><i
                                class="las la-plus mr-3"></i>Add
                            User</a>
                    </div>
                </div>

                @if (session('success'))
                    <div id="success-alert" class="alert alert-success alert-dismissible fade" role="alert"
                        style="opacity: 0;">
                        <strong>Success!</strong> {{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif


                <div class="col-lg-12">
                    <div class="table-responsive rounded mb-3">
                        <table class="data-tables table mb-0 tbl-server-info">
                            <thead class="bg-white text-uppercase">
                                <tr class="ligth ligth-data">
                                    <th>
                                        <div class="checkbox d-inline-block">
                                            <input type="checkbox" class="checkbox-input" id="checkbox1">
                                            <label for="checkbox1" class="mb-0"></label>
                                        </div>
                                    </th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody class="ligth-body">
                                @foreach ($users as $user)
                                    <tr>
                                        <td>
                                            <div class="checkbox d-inline-block">
                                                <input type="checkbox" class="checkbox-input" id="checkbox2">
                                                <label for="checkbox2" class="mb-0"></label>
                                            </div>
                                        </td>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->role }}</td>
                                        <td>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input status-toggle" type="checkbox" role="switch"
                                                    id="switch_{{ $user->id }}" data-user-id="{{ $user->id }}"
                                                    {{ $user->status === 'active' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="switch_{{ $user->id }}"></label>
                                            </div>
                                        </td>



                                        <td>
                                            <div class="d-flex align-items-center list-action">
                                                <!-- View -->
                                                <a class="badge-action badge-view" data-toggle="tooltip" title="View"
                                                    href="{{ route('user.show', ['user' => $user->id]) }}">
                                                    <i class="ri-eye-line"></i>
                                                </a>
                                                <!-- Edit -->
                                                <a class="badge-action badge-edit" data-toggle="tooltip" title="Edit"
                                                    href="{{ route('user.edit', ['user' => $user->id]) }}">
                                                    <i class="ri-pencil-line"></i>
                                                </a>

                                                <!-- Delete -->
                                                <form method="POST"
                                                    action="{{ route('user.destroy', ['user' => $user->id]) }}"
                                                    style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="badge-action badge-delete"
                                                        data-toggle="tooltip" title="Delete">
                                                        <i class="ri-delete-bin-line"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>

                                    </tr>
                                @endforeach
                            </tbody>

                        </table>
                    </div>
                </div>
            </div>
            <!-- Page end  -->
        </div>
    @endsection
