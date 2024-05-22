<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Road Surveillance</title>
    <style>
        #camera {
            display: block;
            margin: 0 auto;
        }
    </style>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tracking.js/1.1.3/tracking-min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tracking.js/1.1.3/data/face-min.js"></script>
</head>
<body>
    <h1>Road Surveillance</h1>
    <video id="camera" width="640" height="480" autoplay></video>

    <script>
        const video = document.getElementById('camera');
        let previousPosition = null;
        let lastSignificantMovementTime = 0;
        const movementThreshold = 30; // Threshold in pixels for significant movement
        const shakeDurationThreshold = 500; // Time threshold for shake detection in ms

        // Access the camera
        navigator.mediaDevices.getUserMedia({ video: true })
            .then(stream => {
                video.srcObject = stream;
            })
            .catch(err => {
                console.error('Error accessing the camera: ', err);
            });

        // Capture the image
        function captureImage() {
            const canvas = document.createElement('canvas');
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            canvas.getContext('2d').drawImage(video, 0, 0);

            const dataUrl = canvas.toDataURL('image/png');

            // Get location
            navigator.geolocation.getCurrentPosition(position => {
                const latitude = position.coords.latitude;
                const longitude = position.coords.longitude;

                const data = new FormData();
                data.append('image', dataUrl);
                data.append('latitude', latitude);
                data.append('longitude', longitude);

                fetch('{{ route('capture') }}', {
                    method: 'POST',
                    body: data,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    alert(data.message);
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            });
        }

        // Initialize tracking.js for face detection
        const tracker = new tracking.ObjectTracker('face');

        tracker.setInitialScale(4);
        tracker.setStepSize(2);
        tracker.setEdgesDensity(0.1);

        tracking.track('#camera', tracker);

        tracker.on('track', event => {
            if (event.data.length > 0) {
                const currentFace = event.data[0];
                const currentPosition = {
                    x: currentFace.x + currentFace.width / 2,
                    y: currentFace.y + currentFace.height / 2
                };

                const currentTime = new Date().getTime();

                if (previousPosition) {
                    const distance = Math.sqrt(
                        Math.pow(currentPosition.x - previousPosition.x, 2) +
                        Math.pow(currentPosition.y - previousPosition.y, 2)
                    );

                    if (distance > movementThreshold && (currentTime - lastSignificantMovementTime < shakeDurationThreshold)) {
                        captureImage();
                        lastSignificantMovementTime = currentTime; // Update the last capture time
                    } else if (distance > movementThreshold) {
                        lastSignificantMovementTime = currentTime; // Update time on significant movement
                    }
                } else {
                    previousPosition = currentPosition; // Set the initial position
                }

                previousPosition = currentPosition; // Update previous position for next comparison
            }
        });
    </script>
</body>
</html>
