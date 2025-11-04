@extends('theem.layouts.app')

@section('content')
    <div class="content-page">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="d-flex flex-wrap align-items-center justify-content-between mb-4">
                        <div>
                            <h4 class="mb-3">Category List</h4>
                            <p class="mb-0">
                                Below is a list of all product categories available in the system. <br>
                                You can view, edit, or delete any category as needed.
                            </p>

                        </div>
                        <a href="{{ route('category.create') }}" class="btn btn-primary add-list"><i
                                class="las la-plus mr-3"></i>Add
                            Category</a>
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
                                    <th>Image</th>
                                    <th>Code</th>
                                    <th>Category</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody class="ligth-body">
                                @foreach ($categories as $category)
                                    <tr>
                                        <td>
                                            <div class="checkbox d-inline-block">
                                                <input type="checkbox" class="checkbox-input" id="checkbox2">
                                                <label for="checkbox2" class="mb-0"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="{{ asset($category->image_path) }}"
                                                    class="img-fluid rounded avatar-50 me-3" alt="{{ $category->name }}">
                                            </div>
                                        </td>

                                        <td>{{ $category->code }}</td>
                                        <td>{{ optional($category)->name }}</td>
                                        <td>
                                            <div class="d-flex align-items-center list-action">
                                                <!-- View -->
                                                <a class="badge-action badge-view" data-toggle="tooltip" title="View"
                                                    href="{{ route('category.show', ['category' => $category->id]) }}">
                                                    <i class="ri-eye-line"></i>
                                                </a>

                                                <!-- Edit -->
                                                <a class="badge-action badge-edit" data-toggle="tooltip" title="Edit"
                                                    href="{{ route('category.edit', ['category' => $category->id]) }}">
                                                    <i class="ri-pencil-line"></i>
                                                </a>

                                                <!-- Delete -->
                                                <form method="POST"
                                                    action="{{ route('category.destroy', ['category' => $category->id]) }}"
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
