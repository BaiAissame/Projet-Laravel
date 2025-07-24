@php
use Illuminate\Support\Facades\Auth;
@endphp

@section('title')
    {{ $project->name }} - Membres du projet
@endsection
<x-app-layout>
    <x-slot name="header">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="project-id" content="{{ $project->id }}">

        <div class="flex justify-between items-center">
            <h2 class="theme-text-primary text-xl font-semibold">
                {{ $project->name }} - Membres du projet
            </h2>

            <!-- Add a link back to the project kanban -->
            <div class="flex space-x-2">
                @if($project->creator_id === Auth::id())
                <!-- Bouton d'ouverture de la modal d'invitation uniquement pour le créateur -->
                <button
                    onclick="document.getElementById('invite-modal').style.display='flex'"
                    class="bg-green-600 text-white px-3 py-1.5 sm:px-4 sm:py-2 rounded-lg hover:bg-green-700 transition text-xs sm:text-sm">
                    + Ajouter un membre
                </button>
                @endif

                <a href="{{ route('kanban.index', $project) }}"
                    class="bg-blue-600 text-white px-3 py-1.5 sm:px-4 sm:py-2 rounded-lg hover:bg-blue-700 transition text-xs sm:text-sm">
                    Retour au Kanban
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-4 sm:py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Message de succès -->
            @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-3 mb-4 text-sm" role="alert">
                {{ session('success') }}
            </div>
            @endif

            <!-- Message d'erreur -->
            @if (session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-3 mb-4 text-sm" role="alert">
                {{ session('error') }}
            </div>
            @endif

            <!-- Liste des membres -->
            <div class="bg-white rounded-lg shadow overflow-hidden mb-4">
                <div class="hidden sm:grid sm:grid-cols-12 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">
                    <div class="sm:col-span-4 px-4 py-3">Nom</div>
                    <div class="sm:col-span-4 px-4 py-3">Email</div>
                    <div class="sm:col-span-2 px-4 py-3">Rôle</div>
                    <div class="sm:col-span-2 px-4 py-3 text-right">Actions</div>
                </div>

                <div class="divide-y divide-gray-200">
                    <!-- Projet owner -->
                    <div class="block sm:grid sm:grid-cols-12 hover:bg-gray-50 {{ $creator->id === Auth::id() ? 'bg-blue-100' : '' }}">
                        <div class="sm:col-span-4 px-4 py-3 flex items-center">
                            <div>
                                <div class="font-medium text-gray-900">
                                    {{ $creator->firstname }} {{ $creator->lastname }}
                                    @if($creator->id === Auth::id())
                                    <span class="text-xs text-blue-600 ml-1">(Vous)</span>
                                    @endif
                                </div>
                                <!-- Info visible uniquement sur mobile -->
                                <div class="sm:hidden mt-1 space-y-1">
                                    <div class="text-xs text-gray-500">{{ $creator->email }}</div>
                                    <div class="flex flex-wrap gap-1 mt-1">
                                        <span class="text-xs bg-blue-100 text-blue-800 px-2 py-0.5 rounded-full">Propriétaire</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Email (caché sur mobile) -->
                        <div class="hidden sm:flex sm:col-span-4 px-4 py-3 items-center">
                            {{ $creator->email }}
                        </div>

                        <!-- Rôle (caché sur mobile) -->
                        <div class="hidden sm:flex sm:col-span-2 px-4 py-3 items-center">
                            <span class="text-xs bg-blue-100 text-blue-800 px-2 py-0.5 rounded-full">Propriétaire</span>
                        </div>

                        <!-- Actions (caché sur mobile) -->
                        <div class="hidden sm:flex sm:col-span-2 px-4 py-3 items-center justify-end space-x-2">
                            <!-- Pas d'action possible sur le propriétaire -->
                        </div>
                    </div>

                    <!-- Membres du projet -->
                    @foreach($members as $member)
                    <div class="block sm:grid sm:grid-cols-12 hover:bg-gray-50 {{ $member->id === Auth::id() ? 'bg-blue-50' : '' }}">
                        <div class="sm:col-span-4 px-4 py-3 flex items-center">
                            <div>
                                <div class="font-medium text-gray-900">
                                    {{ $member->firstname }} {{ $member->lastname }}
                                    @if($member->id === Auth::id())
                                    <span class="text-xs text-blue-600 ml-1">(Vous)</span>
                                    @endif
                                </div>
                                <!-- Info visible uniquement sur mobile -->
                                <div class="sm:hidden mt-1 space-y-1">
                                    <div class="text-xs text-gray-500">{{ $member->email }}</div>
                                    <div class="flex flex-wrap gap-1 mt-1">
                                        <span class="text-xs px-2 py-0.5 rounded-full 
                                                {{ $member->role === 'owner' ? 'bg-purple-100 text-purple-800' : 'bg-gray-100 text-gray-800' }}">
                                            {{ $member->role === 'owner' ? 'Propriétaire' : 'Membre' }}
                                        </span>
                                    </div>

                                    <!-- Actions sur mobile -->
                                    @if($project->creator_id === Auth::id())
                                    <div class="flex gap-2 mt-2">
                                        <form method="POST" action="{{ route('projects.members.remove', ['project' => $project->id, 'user' => $member->id]) }}"
                                            onsubmit="return confirm('Êtes-vous sûr de vouloir retirer ce membre du projet?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800 text-xs font-medium">
                                                Retirer
                                            </button>
                                        </form>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Email (caché sur mobile) -->
                        <div class="hidden sm:flex sm:col-span-4 px-4 py-3 items-center">
                            {{ $member->email }}
                        </div>

                        <!-- Rôle (caché sur mobile) -->
                        <div class="hidden sm:flex sm:col-span-2 px-4 py-3 items-center">
                            <span class="text-xs px-2 py-0.5 rounded-full 
                                    {{ $member->role === 'owner' ? 'bg-purple-100 text-purple-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ $member->role === 'owner' ? 'Propriétaire' : 'Membre' }}
                            </span>
                        </div>

                        <!-- Actions (caché sur mobile) -->
                        <div class="hidden sm:flex sm:col-span-2 px-4 py-3 items-center justify-end space-x-2">
                            @if($project->creator_id === Auth::id())
                            <form method="POST" action="{{ route('projects.members.remove', ['project' => $project->id, 'user' => $member->id]) }}"
                                onsubmit="return confirm('Êtes-vous sûr de vouloir retirer ce membre du projet?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800 font-medium text-xs">
                                    Retirer
                                </button>
                            </form>
                            @endif
                        </div>
                    </div>
                    @endforeach

                    @if(count($members) == 0 && $creator->id !== Auth::id())
                    <div class="px-4 py-3 text-center text-gray-500">
                        Aucun autre membre dans ce projet.
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- MODAL D'INVITATION -->
        <div id="invite-modal" style="display: none;"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 p-4">
            <div class="bg-white w-full max-w-md mx-auto rounded-2xl shadow-lg p-5">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Inviter un membre au projet</h3>

                <div id="invitation-result" class="mb-4 hidden"></div>

                <form id="invite-form" class="space-y-4">
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" id="email" name="email" required
                            class="mt-1 block w-full rounded-lg border border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 p-2">
                    </div>

                    <div class="flex justify-end space-x-2 pt-4">
                        <button type="button"
                            onclick="document.getElementById('invite-modal').style.display='none'"
                            class="px-3 py-1.5 text-sm bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition">
                            Annuler
                        </button>
                        <button type="submit"
                            class="px-3 py-1.5 text-sm bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                            Inviter
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @vite(['resources/js/projects/members.js'])
</x-app-layout>