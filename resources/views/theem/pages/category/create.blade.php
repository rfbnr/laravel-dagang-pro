@extends('theem.layouts.app')

@section('content')
    <div class="content-page">
        <div class="container-fluid add-form-list">
            <div class="row">
                <div class="col-sm-12">

                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <div class="header-title">
                                <h4 class="card-title">Add Category</h4>
                            </div>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('category.store') }}" enctype="multipart/form-data"
                                data-toggle="validator">
                                @csrf

                                <div class="row">

                                    {{-- صورة التصنيف --}}
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="image">Image</label>
                                            <input type="file" class="form-control image-file" name="image"
                                                id="image" accept="image/*">
                                        </div>
                                    </div>

                                    {{-- اسم التصنيف --}}
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="name">Category *</label>
                                            <input type="text" class="form-control" id="name" name="name"
                                                placeholder="Enter Category Name" required
                                                data-errors="Please enter a name.">
                                            <div class="help-block with-errors"></div>
                                        </div>
                                    </div>

                                    {{-- كود التصنيف --}}
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="code">Code *</label>
                                            <input type="text" class="form-control" id="code" name="code"
                                                placeholder="Enter Code" required data-errors="Please enter a code.">
                                            <small id="code-error" class="text-danger d-none">This code already
                                                exists.</small>
                                            <div class="help-block with-errors"></div>
                                        </div>
                                    </div>

                                    {{-- وصف التصنيف --}}
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="description">Description / Category Details</label>
                                            <textarea class="form-control" id="description" name="description" rows="2" placeholder="Optional description..."></textarea>
                                        </div>
                                    </div>

                                </div>

                                {{-- Buttons --}}
                                <div class="d-flex justify-content-between align-items-center mt-4">
                                    <div class="d-flex gap-2">
                                        <button type="submit" class="btn btn-primary" id="add-category-btn">Add
                                            Category</button>
                                        <button type="reset" class="btn btn-danger">Reset</button>
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
            <!-- Page end -->
        </div>
    </div>
@endsection

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const existingCodes = @json($existingCodes);

        const codeInput = document.getElementById('code');
        const errorElement = document.getElementById('code-error');
        const submitButton = document.getElementById('add-category-btn');

        codeInput.addEventListener('input', function() {
            const code = this.value.trim();

            if (existingCodes.includes(code)) {
                errorElement.classList.remove('d-none');
                submitButton.disabled = true; // اقفل الزر
            } else {
                errorElement.classList.add('d-none');
                submitButton.disabled = false; // افتح الزر
            }
        });
    });
</script>
