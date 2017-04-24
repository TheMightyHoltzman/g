// render-variables
var scl        = 30;
var gridWidth  = 20;
var gridHeight = 25;

// game-variables
var gameOver = false;
var snake    = new Snake();
var food     = new Food();

function setup() {
    frameRate(10);
    createCanvas(gridWidth*scl, gridHeight*scl);

    snake.x = toGrid(width/2);
    snake.y = toGrid(height/2);
    food.position(snake);
}

function toGrid(pixel) {
    return Math.floor(pixel/scl);
}

function toPixel(grid) {
    return scl*grid;
}

function paint(gridX, gridY) {
    rect(toPixel(gridX), toPixel(gridY), scl, scl);
}

function draw() {
    if (gameOver) {
        background(255);
        fill(0);
        textFont();
        textSize(30);
        text("Score:"+snake.tail.length, Math.floor(width/2), Math.floor(height/2));
        return;
    }

    // reset the whole thing again
    background(51);

   if(snake.eat(food.x, food.y)) {
       food.position(snake);
   }

    // update position
    snake.move();

    gameOver = snake.hasCollision();

    snake.render('white');
    food.render('red');
}

function Snake() {
    this.x    = null;
    this.y    = null;
    this.dir  = {x: 1,y: 0};
    this.tail = [];

    this.eat = function(foodX, foodY) {
        // if it can eat
        if (this.x === foodX && this.y === foodY) {
            this.tail.unshift({'x': this.x, 'y': this.y});
            return true;
        }
        else {
            this.tail.unshift({'x': this.x, 'y': this.y});
            this.tail.pop();
            return false;
        }
    };

    this.move = function() {
        this.x += this.dir.x;
        this.y += this.dir.y;
    };

    this.collision = function(aX, aY, bX, bY) {
        return aX === bX && aY === bY;
    };

    this.hasCollision = function() {
        for (var tailIndex = 0; tailIndex < this.tail.length; tailIndex++) {
            // self-collision
            if (this.collision(this.x, this.y, this.tail[tailIndex].x, this.tail[tailIndex].y)) {
                return true;
            }
            // edge-collision
            if (this.x < 0 || this.y < 0 || this.x >= gridWidth || this.y >= gridHeight) {
                return true;
            }
        }
    };

    this.render = function() {
        fill('white');
        paint(this.x, this.y);
        for (var tailIndex = 0; tailIndex < this.tail.length; tailIndex++) {
            paint(this.tail[tailIndex].x, this.tail[tailIndex].y);
        }
    };
}

function Food() {
    this.x = null;
    this.y = null;

    this.position = function(snake) {
      this.x = null;
        while (this.x == null || this.collides(snake)) {
            this.x = this.random(gridWidth);
            this.y = this.random(gridHeight);
        }
    };

    this.random = function(max) {
        return Math.floor(random(0, max - 1));
    };

    this.collides = function(snake) {
        if (this.collision(snake.x, snake.y)) {
            return true;
        }
        else {
            for(var i = 0; i < snake.tail.length; i++) {
                if (this.collision(snake.tail[i].x, this.x, snake.tail[i].y, this.y)) {
                    return true;
                }
            }
            return false;
        }
    };

    this.collision = function(aX, aY, bX, bY) {
        return aX === bX && aY === bY;
    };

    this.render = function() {
        fill('red');
        paint(this.x, this.y);
    }
}

function keyPressed() {
    var attemptedDir = {x: null, y: null}
    if(keyCode === UP_ARROW) {
        attemptedDir.x = 0;
        attemptedDir.y = -1;
    } else if(keyCode === DOWN_ARROW) {
        attemptedDir.x = 0;
        attemptedDir.y = 1;
    } else if(keyCode === RIGHT_ARROW) {
        attemptedDir.x = 1;
        attemptedDir.y = 0;
    } else if(keyCode === LEFT_ARROW) {
        attemptedDir.x = -1;
        attemptedDir.y = 0;
    }

    if (snake.tail.length > 0 && snake.x + attemptedDir.x === snake.tail[0].x && snake.y + attemptedDir.y === snake.tail[0].y) {
        console.log('invalid change');
    }
    else {
        snake.dir.x = attemptedDir.x;
        snake.dir.y = attemptedDir.y;
    }
}
