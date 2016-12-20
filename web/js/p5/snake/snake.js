var scl      = 40;
var gameOver = false;

var gridWidth  = 20;
var gridHeight = 25;

var snake    = {
    x: null,
    y: null,
    dir: {
        x: 1,
        y: 0
    },
    tail: []
};

var food     = {x: null, y:null};

function setup()
{
    frameRate(10);
    createCanvas(gridWidth*scl, gridHeight*scl);

    // always use grid-values
    snake.x = toGrid(width/2);
    snake.y = toGrid(height/2);
    positionFood();
}

function toGrid(pixel)
{
    return Math.floor(pixel/scl);
}

function toPixel(grid)
{
    return scl*grid;
}

function paint(gridX, gridY)
{
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

    // if it can eat
    if (snake.x === food.x && snake.y === food.y) {
        snake.tail.unshift({'x': snake.x, 'y': snake.y});
        positionFood(); // reposition
    }
    else {
        snake.tail.unshift({'x': snake.x, 'y': snake.y});
        snake.tail.pop();
    }

    // update position
    snake.x = calcX(snake.dir.x);
    snake.y = calcY(snake.dir.y);

    if (hasCollision()) {
        console.log('dead');
        gameOver = true;
    }

    // draw actual snake
    fill('white');
    paint(snake.x, snake.y);
    for (var tailIndex = 0; tailIndex < snake.tail.length; tailIndex++) {
        paint(snake.tail[tailIndex].x, snake.tail[tailIndex].y);
    }

    // draw food
    fill('red');
    paint(food.x, food.y);
}

function calcX(xSpeed) {
    return snake.x + xSpeed;
}

function calcY(ySpeed) {
    return snake.y + ySpeed;
}

function collision(aX, aY, bX, bY) {
    if (aX === bX && aY === bY) {
        return true;
    }
    return false;
}

function hasCollision() {
    for (var tailIndex = 0; tailIndex < snake.tail.length; tailIndex++) {
        // self-collision
        if (collision(snake.x, snake.y, snake.tail[tailIndex].x, snake.tail[tailIndex].y)) {
            return true;
        }
        // edge-collision
        if (snake.x < 0 || snake.y < 0 || snake.x > gridWidth || snake.y > gridHeight) {
            return true;
        }
    }
}

function snakeCollision(x,y) {
    if (collision(x, y, snake.x, snake.y)) {
       return true;
    }
    for (var i = 0; i < snake.tail.length; i++) {
        if (collision(x, y, snake.tail[i].x, snake.tail[i].y)) {
            return true;
        }
    }
    return false;
}

function positionFood() {
    var foodX = null;
    var foodY = null;
    while (foodX == null || snakeCollision(foodX, foodY)) {
        foodX = Math.floor(random(0, gridWidth-1));
        foodY = Math.floor(random(0, gridHeight-1));
    }
    food.x = foodX;
    food.y = foodY;
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

    if (snake.tail.length > 0 && calcX(attemptedDir.x) === snake.tail[0].x && calcY(attemptedDir.y) === snake.tail[0].y) {
        console.log('invalid change');
    }
    else {
        snake.dir.x = attemptedDir.x;
        snake.dir.y = attemptedDir.y;
    }
}