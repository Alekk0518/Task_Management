<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="modal">
    <div class="modal-backdrop" onclick="closeDeleteModal()"></div>
    
    <div class="modal-content">
        <div class="warning-icon">
            <span>!</span>
        </div>

        <h2>Delete Task?</h2>
        
        <p>
            Are you sure you want to delete this task? This action cannot be undone.
        </p>

        <div class="modal-buttons">
            <button
                type="button"
                class="cancel-modal"
                onclick="closeDeleteModal()">
                No
            </button>

            <button
                type="button"
                class="confirm-delete"
                onclick="confirmDelete()">
                Yes, Delete
            </button>
        </div>
    </div>
</div>