const canvas = document.querySelector("canvas"),
ctx = canvas.getContext("2d");
const disconnectBtn = document.getElementById("disconnect");

const idCourse = document.getElementById('container').getAttribute('data-id-course');
const mercureUrl = "https://localhost:3000/.well-known/mercure";

let snapshot,selectedTool="#000";

const setCanvasBackground = () => {
    // setting whole canvas background to white, so the downloaded img background will be white
    ctx.fillStyle = "#fff";
    ctx.fillRect(0, 0, canvas.width, canvas.height);
    ctx.fillStyle = "#fff"; // setting fillstyle back to the selectedColor, it'll be the brush color
}

window.addEventListener("load", () => {
    // setting canvas width/height.. offsetwidth/height returns viewable width/height of an element
    canvas.width = canvas.offsetWidth;
    canvas.height = canvas.offsetHeight;
    setCanvasBackground();
});

const startDraw = (data) => {
    ctx.beginPath(); // creating new path to draw
    ctx.lineWidth = data['lineWidth']; // passing brushSize as line width
    ctx.strokeStyle = data['selectedColor']; // passing selectedColor as stroke style
    ctx.fillStyle = data['selectedColor']; // passing selectedColor as fill style
    selectedTool = data['selectedTool'];
    // copying canvas data & passing as snapshot value.. this avoids dragging the image
    snapshot = ctx.getImageData(0, 0, canvas.width, canvas.height);
    console.log(data);
}

const drawing = (data) => { // if isDrawing is false return from here
    ctx.putImageData(snapshot, 0, 0); // adding copied canvas data on to this canvas

    if(selectedTool === "brush" || selectedTool === "eraser") {
        // if selected tool is eraser then set strokeStyle to white 
        // to paint white color on to the existing canvas content else set the stroke color to selected color
        ctx.strokeStyle = selectedTool === "eraser" ? "#fff" : data['selectedColor'];
        ctx.lineTo(data['ofssetX'], data['ofssetY']); // creating line according to the mouse pointer
        ctx.stroke(); // drawing/filling line with color
        console.log(data);
    }
}

function clear(){
    ctx.clearRect(0, 0, canvas.width, canvas.height); // clearing whole canvas
    setCanvasBackground();
}

disconnectBtn.addEventListener("click", () => {
    let data = {
        isConnected: false,
        idStudent: 14,
        studentDetail: ""
    };
    console.log(data);
    fetch(mercureUrl, {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded",
            "Authorization": "Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJtZXJjdXJlIjp7InB1Ymxpc2giOlsiKiJdfX0.a8cjcSRUAcHdnGNMKifA4BK5epRXxQI0UBp2XpNrBdw",
        },
        body: new URLSearchParams({
            topic: "https://ecocraftlearning/wbmeeting/" + idCourse,
            data: JSON.stringify(data),
        }),
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Erreur de publication sur Mercure');
        }
    })
    .catch(error => {
        console.error('Erreur lors de la publication sur Mercure :', error);
    });
    window.location.href = "http://127.0.0.1:8000/";
});

