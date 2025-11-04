@extends('theem.layouts.app')

@section('content')
    <div class="content-page">
        <div class="container-fluid add-form-list">
            <div class="row">
                <div class="col-sm-12">



                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <div class="header-title">
                                <h4 class="card-title">Update Supplier</h4>
                            </div>
                        </div>
                        <div class="card-body">
                            <form data-toggle="validator" method="POST"
                                action="{{ route('supplier.update', ['supplier' => $supplier->id ?? 0]) }}">
                                @csrf
                                @method('PUT')
                                <div class="row">
                                    <div class="col-md-12">
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Company_Name *</label>
                                            <input type="text" class="form-control" name="company_name"
                                                value="{{ old('company_name', $supplier->company_name) }}">

                                            <div class="help-block with-errors"></div>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Name *</label>
                                            <input type="text" class="form-control" name="name"
                                                value="{{ old('name', $supplier->name) }}">

                                            <div class="help-block with-errors"></div>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Email *</label>
                                            <input type="email" class="form-control" name="email" id="email"
                                                value="{{ old('email', $supplier->email) }}">
                                            <small id="email-feedback" class="text-danger" style="display:none;"></small>
                                            <div class="help-block with-errors"></div>
                                        </div>
                                    </div>





                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Phone *</label>
                                            <input type="number" class="form-control" name="phone"
                                                value="{{ old('phone', $supplier->phone) }}">

                                            <div class="help-block with-errors"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Address *</label>
                                            <input type="text" class="form-control" name="address"
                                                value="{{ old('address', $supplier->address) }}">

                                            <div class="help-block with-errors"></div>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>GST No *</label>
                                            <input type="number" class="form-control" name="gst_no"
                                                value="{{ old('gst_no', $supplier->gst_no) }}">

                                            <div class="help-block with-errors"></div>
                                        </div>
                                    </div>

                                </div>

                                {{-- Buttons --}}
                                <div class="d-flex justify-content-between align-items-center mt-4">
                                    <div class="d-flex gap-2">
                                        <button type="submit" class="btn btn-primary">Update Supplier</button>
                                    </div>
                                    <a href="{{ route('supplier.index') }}" class="btn btn-outline-secondary btn-lg">
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





<script>
    document.addEventListener('DOMContentLoaded', function() {
        const emailInput = document.getElementById('email');
        const feedback = document.getElementById('email-feedback');
        const submitBtn = document.querySelector('button[type="submit"]');
        const supplierId = '{{ $supplier->id }}'; // Current Supplier ID

        let debounceTimer;

        emailInput.addEventListener('input', function() {
            clearTimeout(debounceTimer);

            const email = emailInput.value.trim();
            if (email === '') {
                feedback.style.display = 'none';
                submitBtn.disabled = false;
                return;
            }

            debounceTimer = setTimeout(() => {
                fetch('{{ route('supplier.checkEmail') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            email: email,
                            supplier_id: supplierId
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.exists) {
                            feedback.textContent =
                                'This email is already taken by another supplier.';
                            feedback.style.display = 'block';
                            feedback.classList.remove('text-success');
                            feedback.classList.add('text-danger');
                            submitBtn.disabled = true;
                        } else {
                            feedback.textContent = 'This email is available.';
                            feedback.style.display = 'block';
                            feedback.classList.remove('text-danger');
                            feedback.classList.add('text-success');
                            submitBtn.disabled = false;
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
            }, 500); // Debounce 500ms
        });
    });
</script>
