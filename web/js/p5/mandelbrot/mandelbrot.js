var sizeX = 500;
var sizeY = 500;

var iteration = 30;
var constA    = 0;
var constB    = 0;
var threshold = 1000;
var scl       = 0.01;

function isIn(a, b) {
    var oldA = (a - sizeX/2)*scl;
    var oldB = (b - sizeY/2)*scl;

    var newA = 0;
    var newB = 0;
    for (var i = 0; i < this.iteration; i++) {
        newA = oldA*oldA - oldB*oldB + this.constA;
        newB = 2*oldA*oldB + this.constB;
        oldA = newA;
        oldB = newB;
    }
    var amount = Math.abs(newA) + Math.abs(newB);
    return amount < threshold;
}

function setup() {
    frameRate(1);
    createCanvas(sizeX, sizeY);
}

function draw() {
    background(51);
    var counter = 0;
    for (var i = 0; i < sizeX; i++) {
        for(var j = 0; j < sizeY; j++) {
            if (isIn(i,j)) {
                counter++;
                stroke('white');
                fill('white');
                point(i,j);
            }
        }
    }
    console.log(counter);
}
