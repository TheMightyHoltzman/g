/**
 * Created by heiko on 17/12/16.
 */

function setup() {
    console.log('Here');
    frameRate(1);
    createCanvas(60*9, 60*9);
}

function draw() {
    background(51);
    for (var i = 0; i < 9; i++) {
        for(var j = 0; j < 9; j++) {
            stroke('white');
            textSize(20);
            text('(' + i + ',' + j + ')', i*60 +10, j*60+30);
        }
    }
}
