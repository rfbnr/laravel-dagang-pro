<x-mail::message>
    # Introduction

    The body of your message.

    <x-mail::button :url="''">
        Button Text
    </x-mail::button>

    Thanks,<br>
    {{ config('app.name') }}
</x-mail::message>




{{-- @component('mail::message')
    # Hello {{ $user->name }},

    Your password has been temporarily reset.

    Use the following temporary password to log in:

    @component('mail::panel')
        {{ $tempPassword }}
    @endcomponent

    You will be asked to change your password after login.

    Thanks,<br>
    {{ config('app.name') }}
@endcomponent --}}
