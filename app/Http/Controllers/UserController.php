<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::paginate(10);
        return view('users.index', compact('users'));
    }
    /**
     * Mostrar formulario de crear usuario
     */
    public function create()
    {
        return view('users.create');
    }
    /**
     * Guardar nuevo usuario
     */
    public function store(Request $request)
    {
        // Lógica para crear usuario (admin)
    }
    /**
     * Mostrar perfil de usuario
     */
    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }
    /**
     * Editar usuario
     */
    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }
    /**
     * Actualizar usuario
     */
    public function update(Request $request, User $user)
    {
    }

    /**
     * Eliminar usuario
     */
    public function destroy(User $user)
    {
    }
}