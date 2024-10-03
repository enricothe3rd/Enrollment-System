<!-- modal.php -->
<div id="messageModal" class="fixed inset-0 z-50 flex items-center justify-center hidden">
    <div class="bg-white rounded-lg shadow-lg p-6 max-w-sm mx-auto">
        <div id="modalContent">
            <!-- Dynamic content will be inserted here -->
        </div>
        <div id="modalFooter" class="flex justify-end mt-4">
            <button onclick="closeModal()" class="mr-2 bg-gray-300 text-black rounded py-2 px-4 hover:bg-gray-400">Cancel</button>
            <button id="confirmButton" class="bg-red-500 text-white rounded py-2 px-4 hover:bg-red-600">Confirm</button>
        </div>
    </div>
</div>

<script>
    let confirmCallback;

    function showModal(content, onConfirm) {
        document.getElementById('modalContent').innerHTML = content;
        document.getElementById('messageModal').classList.remove('hidden');
        confirmCallback = onConfirm;
    }

    function closeModal() {
        document.getElementById('messageModal').classList.add('hidden');
    }

    document.getElementById('confirmButton').addEventListener('click', function() {
        if (confirmCallback) {
            confirmCallback();
        }
        closeModal();
    });
</script>
