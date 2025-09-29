<x-app-layout>
    <div id="myaccount" class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Información del Usuario</h3>
        <div class="space-y-3">
            <div class="flex justify-between">
                <span class="text-gray-600 font-medium">Nombre:</span>
                <span class="text-gray-800">{{ $user->name }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-600 font-medium">Email:</span>
                <span class="text-gray-800">{{ $user->email }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-600 font-medium">Miembro desde:</span>
                <span class="text-gray-800">{{ $user->created_at->format('d/m/Y') }}</span>
            </div>
        </div>
    </div>
</x-layouts.app-layout>
