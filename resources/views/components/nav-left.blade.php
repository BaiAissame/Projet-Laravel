<section class="bg-white col-span-2 h-[100%] shadow-lg text-[#262981]">
    <ul class="list-none mt-10 text-lg font-medium">
        <li class="py-4 pl-6 cursor-pointer hover:bg-[#C8C9FF]">
            <a href="#" class="flex items-center"><i class="fas fa-table mr-2 text-2xl"></i>
                <p>Tableaux</p>
            </a>
        </li>
        <li class="py-4 pl-6 cursor-pointer hover:bg-[#C8C9FF]">
            <a href="#" class="flex items-center"><i class="fas fa-user mr-2 text-2xl"></i>
                <p>Membres</p>
            </a>
        </li>
        <li class="py-4 pl-6 cursor-pointer hover:bg-[#C8C9FF]">
            <a href="#" class="flex items-center"><i class="fas fa-gear mr-2 text-2xl"></i>
                <p>Paramètres</p>
            </a>
        </li>
    </ul>
    <div class="mt-16">
        <p class="font-bold text-lg ml-6">Vos tableaux</p>
        <ul class="list-none mt-6 ml-6">
            @if (isset($data))
                @foreach ($data as $projet)
                    <li class="my-6">
                        <a href="{{ route('projet.show', $projet) }}" class="flex items-center">
                            <p class="ml-2">{{ $projet->name }}</p>
                        </a>
                    </li>
                @endforeach
            @endif
        </ul>
    </div>
</section>
