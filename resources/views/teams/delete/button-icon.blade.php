@can('delete', $team)
	<button type="button" class="delete-button btn btn-circle btn-text btn-sm hover:text-error" aria-haspopup="dialog"
		aria-expanded="false" aria-controls="modal-delete" data-overlay="#modal-delete"
		data-action="{{ route('teams.destroy', $team->id) }}">
		<span class="icon-[tabler--trash] size-5"></span>
	</button>
@endcan
