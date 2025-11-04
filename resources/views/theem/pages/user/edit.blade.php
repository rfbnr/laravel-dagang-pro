@extends('theem.layouts.app')

@section('content')
    <div class="content-page">
        <div class="container-fluid add-form-list">
            <div class="row">
                <div class="col-sm-12">

                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <div class="header-title">
                                <h4 class="card-title">Update User</h4>
                            </div>
                        </div>
                        <div class="card-body">
                            <form data-toggle="validator" method="POST"
                                action="{{ route('user.update', ['user' => $user->id ?? 0]) }}">
                                @csrf
                                @method('PUT')
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Name *</label>
                                            <input type="text" class="form-control" name="name"
                                                value="{{ old('name', $user->name) }}">
                                            <div class="help-block with-errors"></div>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Email *</label>
                                            <input type="email" class="form-control" name="email" id="email"
                                                value="{{ old('email', $user->email) }}">
                                            <small id="email-feedback" class="text-danger" style="display:none;"></small>
                                        </div>
                                    </div>


                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Phone *</label>
                                            <input type="number" class="form-control" name="phone"
                                                value="{{ old('phone', $user->phone) }}">
                                            <div class="help-block with-errors"></div>
                                        </div>
                                    </div>

                                    <!-- Role Select -->
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Role *</label>
                                            <select name="role" class="form-control" required>
                                                <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin
                                                </option>
                                                <option value="cashier" {{ $user->role == 'cashier' ? 'selected' : '' }}>
                                                    Cashier</option>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Force Password Change Checkbox -->
                                    <div class="col-md-12">
                                        <div class="form-group form-check">
                                            <input type="checkbox" class="form-check-input" id="force_password_change"
                                                name="force_password_change" value="1"
                                                {{ $user->force_password_change ? 'checked' : '' }}>
                                            <label class="form-check-label" for="force_password_change">
                                                Force Password Change on First Login
                                            </label>
                                        </div>
                                    </div>

                                </div>

                                {{-- Buttons --}}
                                <div class="d-flex justify-content-between align-items-center mt-4">
                                    <div class="d-flex gap-2">
                                        <button type="submit" class="btn btn-primary">Update User</button>
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
            <!-- Page end -->
        </div>
    </div>
@endsection


<script>
    document.addEventListener('DOMContentLoaded', function() {
        const emailInput = document.getElementById('email');
        const feedback = document.getElementById('email-feedback');
        const submitBtn = document.querySelector('button[type="submit"]');
        const userId = '{{ $user->id }}'; // Current user ID

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
                            email: email,
                            user_id: userId
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.exists) {
                            feedback.textContent =
                                'This email is already taken by another user.';
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
