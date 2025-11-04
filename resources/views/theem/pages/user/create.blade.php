@extends('theem.layouts.app')

@section('content')
    <div class="content-page">
        <div class="container-fluid add-form-list">
            <div class="row">
                <div class="col-sm-12">

                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <div class="header-title">
                                <h4 class="card-title">Add User</h4>
                            </div>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('user.store') }}">
                                @csrf
                                <div class="row">

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Name *</label>
                                            <input type="text" class="form-control" placeholder="Enter Name"
                                                name="name" required>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Email *</label>
                                            <input type="email" class="form-control" placeholder="Enter Email"
                                                name="email" id="email" required>
                                            <small id="email-feedback" class="text-danger" style="display:none;"></small>
                                        </div>
                                    </div>





                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Phone</label>
                                            <input type="text" class="form-control" placeholder="Enter Phone"
                                                name="phone">
                                        </div>
                                    </div>

                                    {{-- <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Address</label>
                                            <input type="text" class="form-control" placeholder="Enter Address"
                                                name="address">
                                        </div>
                                    </div> --}}

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Role *</label>
                                            <select class="form-control" name="role" required>
                                                <option value="">Select Role</option>
                                                <option value="admin">Admin</option>
                                                <option value="cashier">Cashier</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6 d-flex align-items-center">
                                        <div class="form-check mt-4">
                                            <input class="form-check-input" type="checkbox" name="force_password_change"
                                                value="1" id="force_password_change">
                                            <label class="form-check-label" for="force_password_change">
                                                Force Password Change on First Login
                                            </label>
                                        </div>
                                    </div>

                                </div>

                                {{-- Buttons --}}
                                <div class="d-flex justify-content-between align-items-center mt-4">
                                    <div class="d-flex gap-2">
                                        <button type="submit" class="btn btn-primary">Add User</button>
                                        <button type="reset" class="btn btn-danger">Reset</button>
                                    </div>
                                    <a href="{{ route('user.index') }}" class="btn btn-outline-secondary btn-lg">
                                        <i class="bi bi-arrow-left-circle me-2"></i> Back
                                    </a>
                                </div>

                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection


<script>
    document.addEventListener('DOMContentLoaded', function() {
        const emailInput = document.getElementById('email');
        const feedback = document.getElementById('email-feedback');
        const submitBtn = document.querySelector('button[type="submit"]');

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
                fetch('{{ route('user.checkEmail') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            email: email
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.exists) {
                            feedback.textContent = 'This email is already taken.';
                            feedback.style.display = 'block';
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
            }, 500); // 500ms delay (Debounce)
        });
    });
</script>
