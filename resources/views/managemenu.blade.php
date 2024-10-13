<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Menus</title>
    <link rel="stylesheet" href="{{ asset('path/to/bootstrap.css') }}">
</head>
<body>
<div class="page-wrapper">
<div class="container-fluid">
    <div class="container">
        <h1>Manage Menus</h1>
        @if (session('message'))
            <div class="alert alert-success">
                {{ session('message') }}
            </div>
        @endif
        <form action="{{ url('home/post_updateMenuVisibility') }}" method="post">
            @csrf <!-- CSRF protection for security -->
   
            <input type="hidden" name="id_menu" value="3">
            <table class="table">
                <thead>
                    <tr>
                        <th>Menu</th>
                        <th>Show for Level 3</th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($columns as $column)
    <tr>
        <td>{{ ucfirst(str_replace('_', ' ', $column)) }}</td>
        <td>
            <!-- Hidden input to send a value of 0 when checkbox is unchecked -->
            <input type="hidden" name="{{ $column }}" value="0">
            <input type="checkbox" name="{{ $column }}" value="1" {{ $menu->$column == 1 ? 'checked' : '' }}>
        </td>
    </tr>
@endforeach


</tbody>

            </table>
            <button type="submit" class="btn btn-primary">Save Changes</button>
        </form>
    </div>
</div>
</div>
<script src="{{ asset('path/to/bootstrap.js') }}"></script>
</body>
</html>
