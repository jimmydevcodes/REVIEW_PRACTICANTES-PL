<div id="sidebar" class="w-72 min-h-screen bg-white shadow-md flex flex-col p-4">
    <!-- zona de perfil de usuario -->
    <div class="flex items-center space-x-3 mb-6 border rounded-lg p-2.5 border-gray-400 w-full">
        <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=FF9C00&color=fff"
            alt="Avatar" class="w-10 h-10 rounded-full">

        <div class="flex flex-col justify-center">
            <div class="flex items-center">
                <p class="text-sm text-gray-800">{{ Auth::user()->name }}</p>
                <i class="fas fa-circle text-green-400 text-xs ml-2"></i>
            </div>
            <span class="text-xs text-green-400">Online</span>
        </div>
    </div>

    <!-- navegacion del sidebar -->
    <nav class="flex flex-col space-y-2">
        <!-- home -->
        <a href="{{ route('dashboard') }}"
            class="flex items-center space-x-3 px-3 py-2 rounded-lg hover:bg-orange-50 transition {{ request()->routeIs('dashboard') ? 'bg-orange-50 text-[#FF9C00]' : 'text-gray-700' }}">
            <i class="fas fa-home w-5 text-center"></i>
            <span>Home</span>
        </a>

        <!-- my task -->
        <a href="{{ route('tasks.index') }}"
            class="flex items-center space-x-3 px-3 py-2 rounded-lg hover:bg-orange-50 transition {{ request()->routeIs('tasks.*') ? 'bg-orange-50 text-[#FF9C00]' : 'text-gray-700' }}">
            <i class="fas fa-tasks w-5 text-center"></i>
            <span>My Tasks</span>
        </a>

        <!-- my groups -->
        <a href="{{ route('groups.index') }}"
            class="flex items-center space-x-3 px-3 py-2 rounded-lg hover:bg-orange-50 transition {{ request()->routeIs('groups.*') ? 'bg-orange-50 text-[#FF9C00]' : 'text-gray-700' }}">
            <i class="fas fa-users w-5 text-center"></i>
            <span>My Groups</span>
        </a>

        <!-- my account -->
        <a href="{{ route('account') }}"
            class="flex items-center space-x-3 px-3 py-2 rounded-lg hover:bg-orange-50 transition {{ request()->routeIs('account') ? 'bg-orange-50 text-[#FF9C00]' : 'text-gray-700' }}">
            <i class="fas fa-user w-5 text-center"></i>
            <span>My Account</span>
        </a>

        <!-- logout -->
        <form method="POST" action="{{ route('logout') }}" class="mt-4">
            @csrf
            <button type="submit"
                class="flex items-center space-x-3 px-3 py-2 rounded-lg text-red-600 hover:bg-red-50 transition w-full">
                <i class="fas fa-sign-out-alt w-5 text-center"></i>
                <span>Cerrar sesión</span>
            </button>
        </form>
    </nav>
</div>