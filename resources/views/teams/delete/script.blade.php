@push('scripts')
	<script>
		document.addEventListener('DOMContentLoaded', function() {
			const deleteButtons = document.querySelectorAll('.delete-button');
			const deleteForm = document.getElementById('delete-form');

			deleteButtons.forEach(button => {
				button.addEventListener('click', function() {
					const action = this.getAttribute('data-action');
					if (deleteForm) {
						deleteForm.setAttribute('action', action);
					}
				});
			});
		});
	</script>
@endpush
