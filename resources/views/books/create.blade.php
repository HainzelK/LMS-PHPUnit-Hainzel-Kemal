<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit a New Book</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <div class="container">
        <h1>Submit a New Book</h1>

        <form action="{{ route('books.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" name="title" class="form-control" id="title" required>
            </div>

            <div class="form-group">
                <label for="type">Type</label>
                <input type="text" name="type" class="form-control" id="type" required>
            </div>

            <div class="form-group">
                <label for="author">Author</label>
                <input type="text" name="author" class="form-control" id="author">
            </div>

            <div class="form-group">
                <label for="publisher">Publisher</label>
                <input type="text" name="publisher" class="form-control" id="publisher">
            </div>

            <div class="form-group">
                <label for="access_level">Access Level</label>
                <select name="access_level" class="form-control" id="access_level" required>
                    <option value="public">Public</option>
                    <option value="restricted">Restricted</option>
                </select>
            </div>

            <div class="form-group">
                <label for="file">Upload File (Optional)</label>
                <input type="file" name="file" class="form-control" id="file" accept=".pdf,.epub">
            </div>

            <div class="form-group">
                <label for="publication_date">Publication Date</label>
                <input type="date" name="publication_date" class="form-control" id="publication_date">
            </div>

            <div class="form-group">
                <label for="is_physical">Is Physical?</label>
                <input type="checkbox" name="is_physical" id="is_physical">
            </div>

            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
</body>
</html>
