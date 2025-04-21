@extends('admin.categories.index')

@section('js-addon')
    <script>
        $('#form-category').submit(function(e) {
            e.preventDefault();

            var formData = new FormData(this);

            $.ajax({
                url: '{{ route("admin.categories.save") }}',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function(response) {
                    alert(response.message);
                    $('#formCategory')[0].reset();
                },
                error: function(xhr) {
                    let errors = xhr.responseJSON?.errors;
                    if (errors) {
                        // tampilkan error pertama (atau looping semua)
                        alert(Object.values(errors)[0][0]);
                    } else {
                        alert("Terjadi kesalahan.");
                    }
                }
            });
        });
    </script>
@endsection