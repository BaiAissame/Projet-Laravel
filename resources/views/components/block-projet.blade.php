@if (isset($data))
    <section class="w-full">
        <h2 class="mt-8 text-lg font-semibold text-[#262981]"><i class="{{ $icon }} mr-2"></i> {{ $title }}
        </h2>
        <ul class="mt-12 grid grid-cols-4 gap-4 max-w-[968px]">

            @foreach ($data as $projet)
                <li class="col-span-1">
                    <a href="{{ route('projet.show', $projet) }}">
                        <article class="bg-cover bg-center h-28 w-full bg-[#262981] rounded-lg px-4 py-2 text-white">
                            <h1 class="text-2xl font-bold">
                                {{ $projet->name }}
                            </h1>
                            <i class="fas fa-user mt-2"></i>
                        </article>
                    </a>
                </li>
            @endforeach

        </ul>
    </section>
@endif
