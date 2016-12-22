var width  = 600;
var height = 600;
var balls  = [];

function setup() {
    frameRate(50);
    createCanvas(width, height);
    for(var i=0;i<2;i++) {
        balls[i] = new Ball(random(0, width), random(height/2, height));
    }
}

function draw() {
    for(var i=0;i<balls.length;i++) {
        balls[i].update();
        balls[i].show();
    }

    for(var i = 0; i<width; i++) {
        for(var j = 0; j<height; j++) {

            point(i, j);
        }
    }
}

function Ball(x,y) {
    this.velX = random(-20, 20);
    this.velY = random(-20, 20);
    this.x   = x;
    this.y   = y;
    this.r   = random(20, 60);

    this.update = function() {
        this.x = this.x+this.velX;
        this.y = this.y+this.velY;

        if (this.x < 0 || width < this.x) {
            this.velX = -1*this.velX;
        }
        if (this.y < 0 || width < this.y) {
            this.velY = -1*this.velY;
        }
    };

    this.show = function() {
        stroke('black');
        ellipse(this.x, this.y, this.r);
    };
}