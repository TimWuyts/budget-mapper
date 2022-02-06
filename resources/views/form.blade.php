<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Budget Mapper</title>
        <link rel="stylesheet" href="{{asset('css/app.css')}}">
    </head>
    <body>
        <main>
            <h1>Budget Mapper</h1>
            <p>A simple CSV tool to map BNP Paribas Fortis banking transactions to Spendee online budget &amp; money manager, including the following features:</p>
            <ul>
                <li>Column mapping/renaming, only required columns are exported.</li>
                <li>Uncluttered notes, by stripping unneeded information.</li>
                <li>Automatic categorisation, based upon a pre-defined set of terms.</li>
            </ul>

            <form action="{{ route('import') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="form-input">
                    <input class="custom-file-input" type="file" name="file" id="file">
                    <label class="custom-file-label btn btn-primary" for="file">Choose file</label>
                </div>

                <button class="btn btn-primary">Import</button>
                <a class="btn btn-secondary" href="{{ route('export') }}">Export</a>
            </form>
        </main>
    </body>
</html>
