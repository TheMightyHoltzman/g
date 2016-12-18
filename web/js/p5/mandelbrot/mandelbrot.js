var sz = 500;

var iteration = 10;
var constA    = 0;
var constB    = 0;
var threshold = 10000;
var scl       = 0.025;

function isIn(a, b) {
    // center a and b
    var oldA = transScale(a);
    var oldB = transScale(b);

    var newA = 0;
    var newB = 0;

    var amount = 0;

    for (var i = 0; i < this.iteration; i++) {

        newA = oldA*oldA - oldB*oldB + transScale(this.constA);
        newB = 2*oldA*oldB           + transScale(this.constB);

        oldA = newA;
        oldB = newB;

        amount = Math.abs(newA + newB);

        if (amount > threshold) {
            console.log(amount);
            return false;
        }
    }

    return true;
}

function transScale(val) {
    return (val - sz/2)*scl;
}

function setup() {
    xnoLoop();
    frameRate(1);
    createCanvas(sz, sz);
}

function draw() {
    console.log('from:' + transScale(0), 'to:' + transScale(sz));
    background(51);
    var counter = 0;
    for (var i = 0; i < sz; i++) {
        for(var j = 0; j < sz; j++) {
            if (isIn(i,j)) {
                counter++;
                stroke('green');
                fill('green');
                point(i,j);
                console.log('in');
            }
        }
    }
}
