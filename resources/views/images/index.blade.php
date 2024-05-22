
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Captured Images</title>
    <style>
        .image-container {
            display: flex;
            flex-wrap: wrap;
        }
        .image-item {
            margin: 10px;
            border: 1px solid #ccc;
            padding: 10px;
        }
        .image-item img {
            max-width: 100%;
            height: auto;
        }
    </style>
</head>
<body>
    <h1>Captured Images</h1>
    <div class="image-container">
        @foreach ($images as $image)
            <div class="image-item">
                {{ asset('storage/' . $image->image_path) }} <br>
                <img src="{{ asset('storage/' . $image->image_path) }}" alt="Captured Image">
                <p>Latitude: {{ $image->latitude }}</p>
                <p>Longitude: {{ $image->longitude }}</p>
                <p>Captured at: {{ $image->created_at }}</p>
            </div>
        @endforeach
    </div>
</body>
</html>
