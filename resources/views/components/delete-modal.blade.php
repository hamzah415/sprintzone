<div id="deleteModal"
    class="fixed inset-0 bg-black/60 hidden items-center justify-center z-[999] p-4">

    <div class="bg-white w-full max-w-md rounded-2xl overflow-hidden shadow-2xl">

        {{-- HEADER --}}
        <div class="bg-red-500 px-6 py-4">
            <h3 class="text-white font-black uppercase tracking-widest">
                Delete Confirmation
            </h3>
        </div>

        {{-- BODY --}}
        <div class="p-6">

            <div
                class="w-16 h-16 rounded-full bg-red-100 flex items-center justify-center mx-auto mb-5">

                <svg class="w-8 h-8 text-red-500"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24">

                    <path stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M12 9v2m0 4h.01m-.01-12a9 9 0 110 18 9 9 0 010-18z" />

                </svg>

            </div>

            <h2 class="text-xl font-black text-center uppercase mb-2">
                Are you sure?
            </h2>

            <p class="text-sm text-gray-500 text-center leading-relaxed">
                <span id="deleteMessage"></span>
            </p>

            <form id="deleteForm"
                method="POST"
                class="mt-8">

                @csrf
                @method('DELETE')

                <div class="flex justify-end gap-3">

                    <button type="button"
                        onclick="closeDeleteModal()"
                        class="px-5 py-2 text-xs font-black uppercase text-gray-500">

                        Cancel

                    </button>

                    <button type="submit"
                        class="px-6 py-2 bg-red-500 hover:bg-red-600 text-white rounded-xl text-xs font-black uppercase tracking-widest shadow-lg shadow-red-200">

                        Delete

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

<script>

    function openDeleteModal(url, message)
    {
        document
            .getElementById('deleteModal')
            .classList.remove('hidden');

        document
            .getElementById('deleteModal')
            .classList.add('flex');

        document
            .getElementById('deleteForm')
            .action = url;

        document
            .getElementById('deleteMessage')
            .innerHTML = message;
    }

    function closeDeleteModal()
    {
        document
            .getElementById('deleteModal')
            .classList.remove('flex');

        document
            .getElementById('deleteModal')
            .classList.add('hidden');
    }

</script>