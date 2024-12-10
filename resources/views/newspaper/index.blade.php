<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Newspaper Collection</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body style="background: lightgray">

    <div class="container mt-5">
        <div class="row">
            <div class="col-md-12">
                <h3 class="text-center my-4">Newspaper Collection</h3>
                <hr>
                <div class="card border-0 shadow-sm rounded">
                    <div class="card-body">
                        <a href="{{ route('newspapers.create') }}" class="btn btn-md btn-success mb-3">ADD NEWSPAPER</a>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th scope="col">TITLE</th>
                                    <th scope="col">PUBLISHER</th>
                                    <th scope="col">PUBLICATION DATE</th>
                                    <th scope="col">EDITION</th>
                                    <th scope="col">COPIES</th>
                                    <th scope="col" style="width: 20%">ACTIONS</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($newspapers as $newspaper)
                                    <tr>
                                        <td>{{ $newspaper->title }}</td>
                                        <td>{{ $newspaper->publisher }}</td>
                                        <td>{{ $newspaper->publication_date->format('Y-m-d') }}</td>
                                        <td>{{ $newspaper->edition ?? 'N/A' }}</td>
                                        <td>{{ $newspaper->copies }}</td>
                                        <td class="text-center">
                                            <form onsubmit="return confirm('Are you sure?');" action="{{ route('newspapers.destroy', $newspaper->id) }}" method="POST">
                                                <a href="{{ route('newspapers.show', $newspaper->id) }}" class="btn btn-sm btn-dark">SHOW</a>
                                                <a href="{{ route('newspapers.edit', $newspaper->id) }}" class="btn btn-sm btn-primary">EDIT</a>
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">DELETE</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">No newspapers available.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        {{ $newspapers->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        @if(session('success'))
            Swal.fire({
                icon: "success",
                title: "SUCCESS",
                text: "{{ session('success') }}",
                showConfirmButton: false,
                timer: 2000
            });
        @elseif(session('error'))
            Swal.fire({
                icon: "error",
                title: "FAILED",
                text: "{{ session('error') }}",
                showConfirmButton: false,
                timer: 2000
            });
        @endif
    </script>

</body>
</html>
