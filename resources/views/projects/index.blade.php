<x-app-layout>
    <x-slot name="header">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-2xl text-gray-900 leading-tight">
                Mes Projets
            </h2>

            <button
                onclick="document.getElementById('create-project-modal').style.display='flex'"
                class="bg-blue-600 text-white px-6 py-2.5 rounded-xl hover:bg-blue-700 transition-all duration-200 text-sm font-medium shadow-sm hover:shadow-md flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Nouveau Projet
            </button>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto px-4 sm:px-6 lg:px-8 max-w-7xl">
            @if (session('success'))
            <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-r-lg shadow-sm" role="alert">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    {{ session('success') }}
                </div>
            </div>
            @endif

            @if(count($projects) > 0)
            <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-6">
                <div class="grid grid-cols-5 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider border-b border-gray-200">
                    <div class="col-span-1 pl-6 py-3">Nom</div>
                    <div class="col-span-1 py-3">Visibilité</div>
                    <div class="col-span-1 py-3">Créé le</div>
                    <div class="col-span-1 py-3">Statut</div>
                    <div class="col-span-1 pr-6 py-3 text-right">Actions</div>
                </div>

                <div class="divide-y divide-gray-100">
                    @foreach($projects as $project)
                    <div onclick="window.location.href='{{ route('kanban.index', $project) }}'"
                        class="grid grid-cols-5 hover:bg-gray-50 transition-colors duration-150 cursor-pointer">
                        <div class="col-span-1 pl-6 py-4 flex items-center">
                            <div class="font-medium text-gray-900">{{ $project->name }}</div>
                        </div>

                        <div class="col-span-1 py-4 flex items-center">
                            <span class="text-xs px-3 py-1 rounded-full font-medium {{ $project->visibility === 'public' ? 'bg-green-100 text-green-800' : 'bg-amber-100 text-amber-800' }}">
                                {{ $project->visibility === 'public' ? 'Public' : 'Privé' }}
                            </span>
                        </div>

                        <div class="col-span-1 py-4 text-sm text-gray-500 flex items-center">
                            {{ $project->created_at->format('d/m/Y') }}
                        </div>

                        <div class="col-span-1 py-4 flex items-center">
                            @if($project->creator_id === \Illuminate\Support\Facades\Auth::id())
                            <span class="text-xs bg-blue-100 text-blue-800 px-3 py-1 rounded-full font-medium">Propriétaire</span>
                            @else
                            <span class="text-xs bg-gray-100 text-gray-800 px-3 py-1 rounded-full font-medium">Membre</span>
                            @endif
                        </div>

                        <div class="col-span-1 pr-6 py-4 flex items-center justify-end">
                            <div x-data="{ open: false }" @click.outside="open = false" @close-all-menus.window="open = false">
                                <button @click.stop.prevent="
                                    window.dispatchEvent(new CustomEvent('close-all-menus'));
                                    open = !open;
                                    if (open) {
                                        $nextTick(() => {
                                            $refs.menu.style.top = ($event.target.offsetTop + $event.target.offsetHeight + 5) + 'px';
                                            $refs.menu.style.left = ($event.target.offsetLeft - 50) + 'px';
                                        });
                                    }"
                                    class="flex items-center text-gray-500 hover:text-gray-700">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </button>

                                <div x-show="open"
                                    x-ref="menu"
                                    class="absolute right-50 z-10 mt-2 w-48 rounded-md bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
                                    @click.away="open = false">
                                    <div class="py-1">
                                        <a href="{{ route('kanban.index', $project) }}"
                                            @click.stop
                                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            Ouvrir
                                        </a>
                                        <a href="{{ route('projects.members', $project) }}"
                                            @click.stop
                                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            Membres
                                        </a>
                                    </div>

                                    @if($project->creator_id === \Illuminate\Support\Facades\Auth::id())
                                    <div class="py-1">
                                        <button onclick="openEditModal('{{ $project->id }}', '{{ $project->name }}', '{{ $project->visibility }}')"
                                            @click.stop
                                            class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            Modifier
                                        </button>
                                        <button onclick="openDeleteModal('{{ $project->id }}', '{{ $project->name }}')"
                                            @click.stop
                                            class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                                            Supprimer
                                        </button>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @else
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6 text-gray-500 text-center">
                    <p>Vous n'avez pas encore de projet.</p>
                    <p>Cliquez sur "Ajouter un projet" pour commencer.</p>
                </div>
            </div>
            @endif

            @if(count($projects) > 0)
            <!-- Pagination -->
            <div class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 py-4">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    {{ $projects->links() }}
                </div>
            </div>
            <!-- Ajout d'un espace pour éviter que le contenu ne soit caché par la pagination fixe -->
            <div class="h-16"></div>
            @endif

            @if(count($projects) > 0)
            <!-- Pagination -->
            <div class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 py-4">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    {{ $projects->links() }}
                </div>
            </div>
            <!-- Ajout d'un espace pour éviter que le contenu ne soit caché par la pagination fixe -->
            <div class="h-16"></div>
            @endif
        </div>

        <!-- MODAL CRÉATION DE PROJET -->
        <div id="create-project-modal" style="display: none;"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 p-4">

            <div class="bg-white w-full max-w-md mx-auto rounded-2xl shadow-lg p-5">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Créer un nouveau projet</h3>

                <form method="POST" action="{{ route('projects.store') }}" class="space-y-4">
                    @csrf

                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700">Titre</label>
                        <input type="text" id="title" name="title" required
                            class="mt-1 block w-full rounded-lg border border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 p-2">
                    </div>

                    <div>
                        <label for="visibility" class="block text-sm font-medium text-gray-700">Visibilité</label>
                        <select id="visibility" name="visibility"
                            class="mt-1 block w-full rounded-lg border border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 p-2">
                            <option value="public">Public</option>
                            <option value="private">Privé</option>
                        </select>
                    </div>

                    <div class="flex justify-end space-x-2 pt-4">
                        <button type="button"
                            onclick="document.getElementById('create-project-modal').style.display='none'"
                            class="px-3 py-1.5 text-sm bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition">
                            Annuler
                        </button>
                        <button type="submit"
                            class="px-3 py-1.5 text-sm bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                            Ajouter
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- MODAL MODIFICATION DE PROJET -->
        <div id="edit-project-modal" style="display: none;"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 p-4">

            <div class="bg-white w-full max-w-md mx-auto rounded-2xl shadow-lg p-5">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Modifier le projet</h3>

                <form method="POST" id="edit-project-form" action="" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="edit-title" class="block text-sm font-medium text-gray-700">Titre</label>
                        <input type="text" id="edit-title" name="title" required
                            class="mt-1 block w-full rounded-lg border border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 p-2">
                    </div>

                    <div>
                        <label for="edit-visibility" class="block text-sm font-medium text-gray-700">Visibilité</label>
                        <select id="edit-visibility" name="visibility"
                            class="mt-1 block w-full rounded-lg border border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 p-2">
                            <option value="public">Public</option>
                            <option value="private">Privé</option>
                        </select>
                    </div>

                    <div class="flex justify-end space-x-2 pt-4">
                        <button type="button"
                            onclick="document.getElementById('edit-project-modal').style.display='none'"
                            class="px-3 py-1.5 text-sm bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition">
                            Annuler
                        </button>
                        <button type="submit"
                            class="px-3 py-1.5 text-sm bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition">
                            Mettre à jour
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- MODAL SUPPRESSION DE PROJET -->
        <div id="delete-project-modal" style="display: none;"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 p-4">

            <div class="bg-white w-full max-w-md mx-auto rounded-2xl shadow-lg p-5">
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Confirmer la suppression</h3>

                <p class="text-gray-600 mb-4">Êtes-vous sûr de vouloir supprimer le projet "<span id="delete-project-name"></span>" ? Cette action est irréversible.</p>

                <form method="POST" id="delete-project-form" action="" class="space-y-4">
                    @csrf
                    @method('DELETE')

                    <div class="flex justify-end space-x-2 pt-2">
                        <button type="button"
                            onclick="document.getElementById('delete-project-modal').style.display='none'"
                            class="px-3 py-1.5 text-sm bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition">
                            Annuler
                        </button>
                        <button type="submit"
                            class="px-3 py-1.5 text-sm bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                            Supprimer
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- MODAL INVITATION AU PROJET -->
        <div id="invitation-modal" style="display: none;"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 p-4">

            <div class="bg-white w-full max-w-md mx-auto rounded-2xl shadow-lg p-5">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Invitation au projet</h3>

                <p class="text-gray-600 mb-4">
                    <span id="inviter-name"></span> vous a invité à rejoindre le projet "<span id="project-name"></span>".
                    Voulez-vous accepter cette invitation ?
                </p>

                <div id="invitation-response-message" class="mb-4 p-3 rounded hidden"></div>

                <div class="flex justify-end space-x-2 pt-4">
                    <button type="button"
                        onclick="declineInvitation()"
                        class="px-3 py-1.5 text-sm bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition">
                        Refuser
                    </button>
                    <button type="button"
                        onclick="acceptInvitation()"
                        class="px-3 py-1.5 text-sm bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                        Accepter
                    </button>
                </div>
            </div>
        </div>
    </div>

    @vite(['resources/js/projects/index.js'])
</x-app-layout>