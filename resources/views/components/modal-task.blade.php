  <div
      class="container-modal-task-option hidden bg-[rgba(0,0,0,0.6)] w-[100vw] h-[100vh] fixed z-40 top-0 left-0 cursor-default ">
     
      <form
          class="modal-task-option fixed rounded-xl w-[768px] top-[40%] left-[50%] px-8 pt-4 pb-6 bg-[#F0F0F0] translate-x-[-50%] translate-y-[-50%] z-50">
          @csrf
          <div class="flex">
              <i class="fas fa-x cursor-pointer ml-auto" id="close-modal-task"></i>
          </div>
          <input type="text" name="task-title" class="border-none w-[100%] text-xl font-semibold bg-transparent"
              id="task-title-{{ $task->id }}" placeholder="Saississez un titre" value="{{ $task->title }}"
              required />
          <div class="flex mt-3">
              <section class="w-[70%]">
                  <label for="task-description-{{ $task->id }}" class="ml-3  mb-1 block text-base font-semibold">
                      Description
                  </label>
                  <textarea id="task-description-{{ $task->id }}" name="task-description"
                      class="border-none w-[100%] min-h-[100px] rounded-md shadow-lg" placeholder="Saississez une description">{{ $task->description }}</textarea>

              </section>
              <section class="w-[30%]">
                  <ul class="flex flex-col gap-2 text-left">
                      <li>
                          @if ($task->taskUsers->contains('user_id', Auth::id()))
                              <button type="button" value="{{ $task->id }}" data-state="leave"
                                  class="button-join-task text-left pl-4 ml-auto mr-auto block w-[80%] bg-[#262981] text-white rounded-md  py-2 hover:bg-[#1f1f6b] transition-colors duration-200">
                                  <i class='fas fa-door-open mr-1'></i>
                                  Quitter
                              </button>
                          @else
                              <button type="button" value="{{ $task->id }}" data-state="join"
                                  class="button-join-task text-left pl-4 ml-auto mr-auto block w-[80%] bg-[#262981] text-white rounded-md  py-2 hover:bg-[#1f1f6b] transition-colors duration-200">
                                  <i class="fas fa-user-plus mr-1"></i>
                                  Rejoindre
                              </button>
                          @endif
                      </li>
                      <li>
                          <button type="button"
                              class="text-left pl-4 ml-auto mr-auto block w-[80%] bg-[#262981] text-white rounded-md  py-2 hover:bg-[#1f1f6b] transition-colors duration-200">
                              <i class="fas fa-users mr-1"></i>
                              Membres
                          </button>
                      </li>
                      <li>
                          <select name="task-category" data-task-id="{{ $task->id }}"
                              class="block w-[80%] ml-auto mr-auto rounded-md py-2 px-4 bg-[#262981] text-white">
                              <option value="" selected disabled hidden>Catégorie</option>
                              <option value="marketing" @if ($task->category === 'marketing') selected @endif>Marketing
                              </option>
                              <option value="développement" @if ($task->category === 'développement') selected @endif>
                                  Développement</option>
                              <option value="communication" @if ($task->category === 'communication') selected @endif>
                                  Communication</option>
                          </select>
                      </li>
                      <li>
                          <select name="task-priority" data-task-id="{{ $task->id }}"
                              class="block w-[80%] ml-auto mr-auto rounded-md py-2 px-4 bg-[#262981] text-white">
                              <option value="" selected disabled hidden>Priorité</option>
                              <option value="basse" @if ($task->priority === 'basse') selected @endif>Basse</option>
                              <option value="moyenne" @if ($task->priority === 'moyenne') selected @endif>Moyenne</option>
                              <option value="élevée" @if ($task->priority === 'élevée') selected @endif>Élevée</option>
                          </select>
                      </li>
                      <li>
                          <button type="button" value="{{ $task->id }}"
                              class="button-delete-task text-left pl-4 ml-auto mr-auto block w-[80%] bg-[#262981] text-white rounded-md  py-2 hover:bg-[#1f1f6b] transition-colors duration-200">
                              <i class="fas fa-trash-alt mr-1"></i>
                              Supprimer
                          </button>
                      </li>
                  </ul>
              </section>
          </div>
      </form>
  </div>
