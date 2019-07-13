@if (session('message'))
    <script>
    	Swal.fire({
		  position: 'center',
		  type: 'success',
		  title: '{{session('message')}}',
		  showConfirmButton: false,
		  timer: 2000
		})
    </script>
@endif
