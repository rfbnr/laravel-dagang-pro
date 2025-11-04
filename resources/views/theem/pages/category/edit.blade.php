@extends('theem.layouts.app')

@section('content')
    <div class="content-page">
        <div class="container-fluid add-form-list">
            <div class="row">
                <div class="col-sm-12">



                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <div class="header-title">
                                <h4 class="card-title">Update Category</h4>
                            </div>
                        </div>
                        <div class="card-body">
                            <form data-toggle="validator" method="POST" enctype="multipart/form-data"
                                action="{{ route('category.update', ['category' => $category->id ?? 0]) }}">
                                @csrf
                                @method('PUT')
                                <div class="row">
                                    <div class="col-md-12">
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Category *</label>
                                            <input type="text" class="form-control" name="name"
                                                value="{{ old('name', $category->name) }}">

                                            <div class="help-block with-errors"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Code *</label>
                                            <input type="text" class="form-control" name="code"
                                                value="{{ old('code', $category->code) }}">

                                            <div class="help-block with-errors"></div>
                                        </div>
                                    </div>


                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Image</label>
                                            <input type="file" class="form-control image-file" name="image"
                                                accept="image/*">

                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Description / Category Details</label>
                                            <textarea name="description" class="form-control">{{ old('description', $category->description) }}</textarea>

                                        </div>
                                    </div>
                                </div>

                                {{-- Buttons --}}
                                <div class="d-flex justify-content-between align-items-center mt-4">
                                    <div class="d-flex gap-2">
                                        <button type="submit" class="btn btn-primary" id="add-category-btn">Update
                                            Category</button>
                                    </div>
                                    <a href="{{ route('category.index') }}" class="btn btn-outline-secondary btn-lg">
                                        <i class="bi bi-arrow-left-circle me-2"></i> Back
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Page end  -->
        </div>
    </div>
@endsection
