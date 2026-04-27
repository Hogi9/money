<div id="modal-delete" class="overlay modal overlay-open:opacity-100 overlay-open:duration-300 modal-middle hidden"
	role="dialog" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h3 class="modal-title">Confirm Deletion</h3>
				<button type="button" class="btn btn-text btn-circle btn-sm absolute end-3 top-3" aria-label="Close"
					data-overlay="#modal-delete">
					<span class="icon-[tabler--x] size-4"></span>
				</button>
			</div>
			<div class="modal-body">
				Are you sure you want to delete this data?
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-soft btn-secondary" data-overlay="#modal-delete">Close</button>
				<form id="delete-form" method="POST" class="inline">
					@csrf
					@method('DELETE')
					<button type="submit" class="btn btn-error">Delete</button>
				</form>
			</div>
		</div>
	</div>
</div>
