<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List of Librarians</title>
</head>
<body>
    <h1>List of Librarians</h1>
    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($librarians as $librarian)
                <tr>
                    <td>{{ $librarian->id }}</td>
                    <td>{{ $librarian->user->name }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
