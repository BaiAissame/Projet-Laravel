<x-app-layout>
    <x-slot name="header">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Invitation au Projet
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="mb-6">
                        <div class="text-xl font-medium text-gray-900 mb-2">
                            Vous avez été invité à rejoindre le projet "{{ $project->name }}"
                        </div>
                        <p class="text-gray-600">
                            {{ $inviter ? $inviter->firstname . ' ' . $inviter->lastname : 'Un membre de l\'équipe' }}
                            vous a invité à collaborer sur ce projet.
                        </p>
                    </div>

                    <div class="flex justify-between items-center">
                        <div class="flex space-x-4">
                            <button onclick="acceptInvitation()"
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                Accepter l'invitation
                            </button>
                            <button onclick="declineInvitation()"
                                class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Refuser
                            </button>
                        </div>
                        <a href="{{ route('dashboard') }}" class="text-sm text-gray-500 hover:text-gray-700">
                            Retour au tableau de bord
                        </a>
                    </div>

                    <div id="status-message" class="hidden mt-6 p-4 rounded"></div>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>

<script>
    document.body.dataset.invitationId = "{{ $invitation->id }}";

    function acceptInvitation() {
        const invitationId = document.body.dataset.invitationId;
        const statusMessage = document.getElementById('status-message');

        fetch('{{ route("project.invitation.finalize") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                invitation_id: invitationId
            })
        })
            .then(response => response.json())
            .then(data => {
                statusMessage.classList.remove('hidden');

                if (data.success) {
                    statusMessage.className = 'mt-6 p-4 rounded bg-green-50 border border-green-200';
                    statusMessage.innerHTML = `
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-green-700">${data.message}</p>
                        </div>
                    </div>
                `;

                    // Rediriger après 2 secondes
                    setTimeout(() => {
                        window.location.href = '{{ route("dashboard") }}';
                    }, 2000);
                } else {
                    statusMessage.className = 'mt-6 p-4 rounded bg-red-50 border border-red-200';
                    statusMessage.innerHTML = `
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-red-700">${data.message}</p>
                        </div>
                    </div>
                `;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                statusMessage.classList.remove('hidden');
                statusMessage.className = 'mt-6 p-4 rounded bg-red-50 border border-red-200';
                statusMessage.innerHTML = `
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-red-700">Une erreur est survenue. Veuillez réessayer.</p>
                    </div>
                </div>
            `;
            });
    }

    function declineInvitation() {
        const invitationId = document.body.dataset.invitationId;
        const statusMessage = document.getElementById('status-message');

        if (confirm('Êtes-vous sûr de vouloir refuser cette invitation ?')) {
            fetch('{{ route("project.invitation.decline") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    invitation_id: invitationId
                })
            })
                .then(response => response.json())
                .then(data => {
                    statusMessage.classList.remove('hidden');

                    if (data.success) {
                        statusMessage.className = 'mt-6 p-4 rounded bg-yellow-50 border border-yellow-200';
                        statusMessage.innerHTML = `
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-yellow-700">${data.message}</p>
                            </div>
                        </div>
                    `;

                        // Rediriger après 2 secondes
                        setTimeout(() => {
                            window.location.href = '{{ route("dashboard") }}';
                        }, 2000);
                    } else {
                        statusMessage.className = 'mt-6 p-4 rounded bg-red-50 border border-red-200';
                        statusMessage.innerHTML = `
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-red-700">${data.message}</p>
                            </div>
                        </div>
                    `;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    statusMessage.classList.remove('hidden');
                    statusMessage.className = 'mt-6 p-4 rounded bg-red-50 border border-red-200';
                    statusMessage.innerHTML = `
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-red-700">Une erreur est survenue. Veuillez réessayer.</p>
                        </div>
                    </div>
                `;
                });
        }
    }
</script>