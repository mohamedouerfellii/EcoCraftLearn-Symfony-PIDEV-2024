const canvas = document.querySelector("canvas"),
ctx = canvas.getContext("2d");

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
    ctx.lineWidth = data['brushWidth']; // passing brushSize as line width
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
        ctx.strokeStyle =  data['selectedColor'];
        ctx.lineTo(data['ofssetX'], data['ofssetY']); // creating line according to the mouse pointer
        ctx.stroke(); // drawing/filling line with color
        console.log(data);
    }
}



function clear(){
    ctx.clearRect(0, 0, canvas.width, canvas.height); // clearing whole canvas
    setCanvasBackground();
}
