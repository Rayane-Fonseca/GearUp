<section class="space-y-4">
    <header>
        <h3 class="text-lg font-bold text-gray-900">Informações do Perfil</h3>
        <p class="text-xs text-gray-500">Atualize o nome e o endereço de e-mail da sua conta.</p>
    </header>

    <form method="post" action="{{ route('profile.update') }}" class="space-y-4 max-w-xl">
        @csrf
        @method('patch')
        <div>
            <label class="block text-xs font-medium text-gray-700">Nome</label>
            <input type="text" name="name" value="{{ old('name', $user->name ?? $user->nome) }}" class="mt-1 w-full text-xs rounded-lg border-gray-300">
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-700">E-mail</label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}" class="mt-1 w-full text-xs rounded-lg border-gray-300">
        </div>
        <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-xs font-bold rounded-lg">Salvar</button>
    </form>
</section>