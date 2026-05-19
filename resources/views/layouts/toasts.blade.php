@php
    $toastMessages = [];
    $status = session('status');
    $statusMap = [
        'profile-updated' => 'Profil berhasil diupdate.',
        'password-updated' => 'Password berhasil diupdate.',
        'verification-link-sent' => 'Link verifikasi berhasil dikirim.',
    ];

    if ($status) {
        $toastMessages[] = [
            'type' => 'success',
            'message' => $statusMap[$status] ?? $status,
        ];
    }

    if (isset($errors) && $errors->any()) {
        foreach ($errors->all() as $error) {
            $toastMessages[] = [
                'type' => 'error',
                'message' => $error,
            ];
        }
    }
@endphp

@if ($toastMessages !== [])
    <div class="hidden" aria-hidden="true">
        @foreach ($toastMessages as $toast)
            <div data-toast-message data-toast-type="{{ $toast['type'] }}" data-toast-text="{{ $toast['message'] }}"></div>
        @endforeach
    </div>
@endif
