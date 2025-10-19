@extends('layout.master_layout')

@section('content')
<body class="material-menu" id="top">
    <div class="page-error">
        <div class="container text-center">
            <h1 class="error-title">403</h1>
            <p class="lead">{{ __t('permissions.no_permission_title', 'Access Restricted') }}</p>
            <p>{{ __t('permissions.no_permission_message', 'Your account does not have the permissions required to view this page.') }}</p>
            <p class="text-muted">{{ __t('permissions.no_permission_hint', 'Please contact an administrator if you believe this is an error.') }}</p>

            <form method="GET" action="{{ route('user.signOut') }}">
                <button type="submit" class="btn btn-danger m-t-20">
                    {{ __t('permissions.logout_button', 'Sign out') }}
                </button>
            </form>
        </div>
    </div>
</body>
@endsection
