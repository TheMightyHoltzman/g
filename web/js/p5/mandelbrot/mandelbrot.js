var iteration = 100;
var constA    = 1;
var constB    = 1;
var threshold = 100;

function isIn(a, b) {
    var aa = a;
    var bb = b;
    for (var i = 0; i < this.iteration; i++) {
        aa = a*a - b*b + this.constA;
        bb = 2*a*b + this.constB;
    }
    return Math.abs(aa) + Math.abs(bb) < 100;
}

function setup() {
    frameRate(1);
    createCanvas(500, 500);
}

function draw() {
    background(51);
    for (var i = 500; i < 500; i++) {
        for(var j = 0; j < 500; j++) {
            if (isIn(i,j)) {
                fill('white');
                point(i,j);
            }
        }
    }
}
