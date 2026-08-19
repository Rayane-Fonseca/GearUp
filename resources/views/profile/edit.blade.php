<x-app-layout>
    <div class="py-12 max-w-7xl mx-auto space-y-6 sm:px-6 lg:px-8">
        <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
            @include('profile.partials.update-profile-information-form')
        </div>
        <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
            @include('profile.partials.update-password-form')
        </div>
    </div>
</x-app-layout>