@extends('theem.layouts.app')

@section('content')
    <div class="content-page">
        <div class="container-fluid add-form-list">
            <div class="row">
                <div class="col-sm-12">



                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <div class="header-title">
                                <h4 class="card-title">Update Customer</h4>
                            </div>
                        </div>
                        <div class="card-body">
                            <form data-toggle="validator" method="POST"
                                action="{{ route('customer.update', ['customer' => $customer->id ?? 0]) }}">
                                @csrf
                                @method('PUT')
                                <div class="row">
                                    <div class="col-md-12">
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Name *</label>
                                            <input type="text" class="form-control" name="name"
                                                value="{{ old('name', $customer->name) }}">

                                            <div class="help-block with-errors"></div>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Email *</label>
                                            <input type="email" class="form-control" name="email" id="email"
                                                value="{{ old('email', $customer->email) }}">
                                            <small id="email-feedback" class="text-danger" style="display:none;"></small>
                                            <div class="help-block with-errors"></div>
                                        </div>
                                    </div>





                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Phone *</label>
                                            <input type="number" class="form-control" name="phone"
                                                value="{{ old('phone', $customer->phone) }}">

                                            <div class="help-block with-errors"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Address *</label>
                                            <input type="text" class="form-control" name="address"
                                                value="{{ old('address', $customer->address) }}">

                                            <div class="help-block with-errors"></div>
                                        </div>
                                    </div>

                                </div>

                                {{-- Buttons --}}
                                <div class="d-flex justify-content-between align-items-center mt-4">
                                    <div class="d-flex gap-2">
                                        <button type="submit" class="btn btn-primary">Update Customer</button>
                                    </div>
                                    <a href="{{ route('customer.index') }}" class="btn btn-outline-secondary btn-lg">
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
        const customerId = '{{ $customer->id }}'; // Current Customer ID

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
                fetch('{{ route('customer.checkEmail') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            email: email,
                            customer_id: customerId
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.exists) {
                            feedback.textContent =
                                'This email is already taken by another customer.';
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
