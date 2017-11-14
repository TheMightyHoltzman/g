var scl = 500;
var height = 400;
var width = 720;
var eventQueue = [];

function setup() {
    createCanvas(720, 400);
    background(200);

    // Set colors
    fill(204, 101, 192, 127);
    stroke(127, 63, 120);

    // A rectangle
    rect(40, 120, 120, 40);
    // An ellipse
    ellipse(240, 240, 80, 80);
    // A triangle
    triangle(300, 100, 320, 100, 310, 80);

    noLoop();
}

function FillAction(x, y)
{
    this.x = x;
    this.y = y;
    this.color = null;
    this.queue = [];
    this.visited = {};
    this.max = 0;

    this.exec = function() {
        // init with point
        this.queue.push(new Point(x,y));
        this.color = get(x, y);

        while (this.queue.length != 0) {
            var pixel = this.queue.pop();
            this.max = this.queue.length > this.max ? this.queue.length : this.max;

            // paint it
            stroke(255);
            point(pixel.x, pixel.y);
            console.log('Visited ' + this.visited.length);

            var neighbours = this.getNeighbours(pixel.x, pixel.y);
            for (var i = 0; i < neighbours.length; i++) {
                if (neighbours[i].getColor().toString() == this.color.toString()) {
                    this.queue.push(neighbours[i]);
                }
            }
        }
        console.log('Max queue size: ' + this.max);
    };


    this.getNeighbours = function(x, y) {
        var points = [
            new Point(x-1, y-1),
            new Point(x, y-1),
            new Point(x+1, y-1),
            new Point(x-1, y),
            new Point(x+1, y),
            new Point(x-1, y+1),
            new Point(x, y+1),
            new Point(x+1, y+1)
        ];

        for (var i = 0; i < points.length; i++) {
            if (points[i].x < 0 || points[i].x >= width || points[i].y < 0 || points[i].y > height) {
                points[i] = null;
            }
        }

        return points.filter(function(item) {return item != null});
    }
}

function Point(x, y) {
    this.x = x;
    this.y = y;

    this.toString = function() {
        return this.x + "," + this.y;
    };

    this.getColor = function() {
        console.log(this.toString());
        return get(x, y);
    };
}

function mousePressed() {
    var fill = new FillAction(Math.floor(mouseX), Math.floor(mouseY));
    fill.exec();
}

