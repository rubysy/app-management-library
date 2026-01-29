@extends(auth()->user()->role === 'reader' ? 'layouts.reader' : 'layouts.admin')

@section('header', __('Profile'))

@section('content')
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white border border-black shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white border border-black shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white border border-black shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
@endsection
