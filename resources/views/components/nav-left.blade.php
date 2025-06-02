<section class="bg-white h-[100vh] shadow-lg text-[#262981] w-[8rem] md:w-[10rem] lg:w-[12rem] lg:min-w-[12rem]">
    <ul class="list-none mt-[5rem] text-xs md:text-sm lg:text-lg font-medium">
        <li class="py-4 pl-2 sm:pl-4 lg:pl-6 cursor-pointer hover:bg-[#C8C9FF]">
            <a href="#" class="flex items-center"><i class="fas fa-table mr-2 lg:text-2xl"></i>
                <p>Tableaux</p>
            </a>
        </li>
        <li class="py-4 pl-2 sm:pl-4 lg:pl-6 cursor-pointer hover:bg-[#C8C9FF]">
            <a href="#" class="flex items-center"><i class="fas fa-user mr-2 lg:text-2xl"></i>
                <p>Membres</p>
            </a>
        </li>
        <li class="py-4 pl-2 sm:pl-4 lg:pl-6 cursor-pointer hover:bg-[#C8C9FF]">
            <a href="#" class="flex items-center"><i class="fas fa-gear mr-2 lg:text-2xl"></i>
                <p>Paramètres</p>
            </a>
        </li>
    </ul>
    <div class="mt-12 lg:mt-16">
        <p class="font-bold text-sm lg:text-lg ml-2 sm:ml-4 lg:ml-6">Vos tableaux</p>
        <ul class="list-none mt-6 text-sm lg:text-lg">
            @if (isset($data))
                @foreach ($data as $projet)
                    <li class="my-4 pl-2 sm:pl-4 lg:pl-6">
                        <a href="{{ route('projet.show', $projet) }}" class="flex items-center">
                            <p>{{ $projet->name }}</p>
                        </a>
                    </li>
                @endforeach
            @endif
        </ul>
    </div>
</section>
