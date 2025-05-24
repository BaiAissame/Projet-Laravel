<article class="bg-[#EEEEEE] w-[322px] min-h-[146px] rounded-[16px] px-4 py-4 text-[#262981]">
    <div class="flex justify-between">
        <h1 class="text-lg font-semibold ml-4">{{ $title }}</h1>
        <i class="fas fa-ellipsis-h text-xl"></i>
    </div>
    <div class="mt-6 content-card">
        <x-block-carte />
        <button class="flex items-center hover:bg-[#DADCF2] rounded-lg px-4 py-2 button-create-card mt-2 w-full">
            <i class="fas fa-plus text-3xl"></i>
            <p class="ml-6 text-base">Ajouter une carte</p>
        </button>
    </div>
</article>