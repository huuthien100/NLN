document.addEventListener('DOMContentLoaded', async function () {
    const video = document.getElementById('videoElm');
    let faceMatcher = null;
    let canvas = null;

    async function loadModels() {
        try {
            console.log('Loading face detection and recognition models...');
            await faceapi.nets.tinyFaceDetector.loadFromUri('models');
            await faceapi.nets.faceRecognitionNet.loadFromUri('models');
            await faceapi.nets.faceLandmark68Net.loadFromUri('models');
            await faceapi.nets.ssdMobilenetv1.loadFromUri('models');
            console.log('Face detection and recognition models loaded successfully.');
        } catch (error) {
            console.error('Error loading face detection and recognition models:', error);
            throw error;
        }
    }

    async function fetchData() {
        try {
            const response = await fetch('../config/fetch_data.php');
            if (!response.ok) {
                throw new Error('Failed to fetch data');
            }
            const data = await response.json();
            return data;
        } catch (error) {
            console.error('Error fetching data:', error);
            throw error;
        }
    }

    async function loadLabeledDescriptors(data) {
        const labeledDescriptors = [];

        for (const dataItem of data) {
            const imagePath = dataItem.image_path;
            const img = await faceapi.fetchImage(imagePath);
            const detections = await faceapi.detectSingleFace(img).withFaceLandmarks().withFaceDescriptor();

            if (detections) {
                const descriptor = detections.descriptor;
                const labeledDescriptor = new faceapi.LabeledFaceDescriptors(extractStudentName(dataItem.student_name), [descriptor]);
                labeledDescriptors.push(labeledDescriptor);
            }
        }

        return labeledDescriptors;
    }

    async function loadFaceMatcher(data) {
        try {
            console.log('Initializing face matcher...');
            const labeledDescriptors = await loadLabeledDescriptors(data);
            faceMatcher = new faceapi.FaceMatcher(labeledDescriptors);
            console.log('Face matcher initialized successfully.');
        } catch (error) {
            console.error('Error initializing face matcher:', error);
            throw error;
        }
    }

    async function markAttendance(studentname, date, status) {
        try {
            const response = await fetch('../config/mark_attendance.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    studentname: studentname,
                    date: date,
                    status: status
                })
            });
            if (!response.ok) {
                throw new Error('Failed to mark attendance');
            }
            console.log('Attendance marked successfully for student:', studentname);
        } catch (error) {
            console.error('Error marking attendance:', error);
        }
    }

    function extractStudentName(label) {
        const regex = /^(.*?)\s\(\d+\.\d+\)$/;
        const match = label.match(regex);
        return match ? match[1] : label;
    }

    function getCameraStream() {
        if (navigator.mediaDevices.getUserMedia) {
            navigator.mediaDevices.getUserMedia({ video: {} })
                .then(stream => {
                    video.srcObject = stream;
                    video.onloadedmetadata = () => {
                        video.play();
                    };
                })
                .catch(error => {
                    console.error('Error accessing camera stream:', error);
                });
        } else {
            console.error('getUserMedia is not supported in this browser.');
        }
    }

    video.addEventListener('playing', async () => {
        canvas = faceapi.createCanvasFromMedia(video);
        document.body.appendChild(canvas);
        const displaySize = {
            width: video.videoWidth,
            height: video.videoHeight
        }
        setInterval(async () => {
            const detections = await faceapi.detectAllFaces(video, new faceapi.TinyFaceDetectorOptions()).withFaceLandmarks().withFaceDescriptors();
            const resizedDetections = faceapi.resizeResults(detections, displaySize);

            canvas.getContext('2d').clearRect(0, 0, displaySize.width, displaySize.height);

            for (const detection of resizedDetections) {
                const adjustedBox = {
                    x: video.videoWidth - (detection.detection.box.x + detection.detection.box.width),
                    y: detection.detection.box.y,
                    width: detection.detection.box.width,
                    height: detection.detection.box.height
                };
                const bestMatch = faceMatcher.findBestMatch(detection.descriptor);

                const drawBox = new faceapi.draw.DrawBox(adjustedBox, { label: extractStudentName(bestMatch.toString()) });
                drawBox.draw(canvas);

                if (bestMatch.label !== 'unknown') {
                    await markAttendance(extractStudentName(bestMatch.toString()), new Date().toISOString(), 'Checked');
                }
            }
        }, 1000);

        video.style.transform = 'scaleX(-1)';
    });

    

    async function initialize() {
        try {
            const data = await fetchData();
            await loadModels();
            await loadFaceMatcher(data);
            getCameraStream();
        } catch (error) {
            console.error('Error initializing:', error);
        }
    }

    initialize();
});
