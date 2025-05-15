<form action="{{ route('tableau.create') }}" method="POST" id="modal-create-table"
    class="hidden top-[45%] left-[50%] bg-white text-[#262981] block font-semibold rounded-lg p-4 w-[400px] h-[350px] translate-x-[-50%] translate-y-[-50%]">
    @csrf
    <div class="flex justify-end">
        <i class="fas fa-x cursor-pointer" id="close-modal-create-table"></i>
    </div>
    <h2 class="text-center text-[1.5rem]">Crée un tableau</h2>
    <label for="table-name" class="mt-8 block">Nom du tableau</label>
    <input type="text" id="table-name" name="name" required
        class="w-full h-[40px] rounded-lg bg-white px-2 mt-2 mb-4 font-medium" placeholder="Nom du tableau">
    <label for="table-description">Description</label>
    <input type="text" id="table-description" name="description"
        class="w-full h-[40px] rounded-lg bg-white px-2 mt-2 mb-4 font-medium" placeholder="Description du tableau">
    <div class="text-center">
        <button class="mt-2 py-2 px-6 text-xl rounded-lg bg-[#262981] text-white">
            Crée
        </button>
    </div>
</form>
