var shapes = [
  // L
  [
    [{'x': 0, 'y':0}, {'x': 0, 'y':1}, {'x': 0, 'y':2}, {'x': 1, 'y':2}],
    [{'x': 0, 'y':1}, {'x': 0, 'y':2}, {'x': 1, 'y':1}, {'x': 2, 'y':1}],
    [{'x': 0, 'y':0}, {'x': 1, 'y':0}, {'x': 1, 'y':1}, {'x': 1, 'y':2}],
    [{'x': 0, 'y':0}, {'x': 0, 'y':1}, {'x': 1, 'y':1}, {'x': 2, 'y':1}]
  ],
  // I
  [
    [{'x': 1, 'y':0}, {'x': 1, 'y':1}, {'x': 1, 'y':2}],
    [{'x': 0, 'y':1}, {'x': 1, 'y':1}, {'x': 2, 'y':1}]
  ],
  // O
  [
    [{'x': 0, 'y': 0}, {'x': 1, 'y': 0}, {'x': 0, 'y': 1}, {'x': 1, 'y': 1}]
  ],
  // N
  [
    [{'x': 0, 'y': 0}, {'x': 0, 'y': 1}, {'x': 1, 'y': 1}, {'x': 2, 'y': 1}],
    [{'x': 1, 'y': 0}, {'x': 1, 'y': 1}, {'x': 0, 'y': 1}, {'x': 0, 'y': 2}],
  ]
];

// render-variables
var scl        = 30;
var gridWidth  = 10;
var gridHeight = 20;

// game variables
var boulder    = null;
var board      = null;
var grid       = [];
var score      = 0;
var difficulty = 3;

function setup() {
    frameRate(10);
    createCanvas(gridWidth*scl, gridHeight*scl);
    boulder = new Boulder();
    board    = new Board();
    boulder.setup(difficulty);
    board.setup();
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
  background(51);

  board.cancelRows();
  boulder.drop();
  boulder.render();

  if(hasCollision()) {
    boulder.setup(difficulty);
  }

  board.render();
}

function hasCollision() {
    for (var j = 0; j < boulder.shape[boulder.rotation].length; j++) {
      var brickX = boulder.x + boulder.shape[boulder.rotation][j].x;
      var brickY = boulder.y + boulder.shape[boulder.rotation][j].y + 1;
      if (brickY === gridHeight || grid[brickX][brickY]) {
        boulder.iterator(function(x,y) {
          grid[x][y] = true;
        });
        return true;
      }
    }
    return false;
}

function Board() {

  this.setup = function() {
    for (var i = 0; i < gridHeight; i++) {
      grid[i] = [];
      for (var j = 0; j < gridWidth; j++) {
        grid[i][j] = false;
      }
    }
  };

  this.render = function() {
    fill('yellow');
    for (var i = 0; i < gridHeight; i++) {
      for (var j = 0; j < gridWidth; j++) {
        if (grid[i][j]) {
          paint(i, j);
        }
      }
    }
  };

  this.cancelRows = function() {
    var rows = [];
    for (var row = 0; row < gridWidth; row++) {
      var cancel = true;
      for (var col = 0; col < gridWidth; col++) {
        if (!grid[col][row]) {
          cancel = false;
        }
      }
      rows[row] = cancel;
      if (cancel) {
        score += gridWidth;
        console.log('Canceling row ' + row);
      }
    }

    for (var col = 0; col < gridHeight; col++) {
        for (var row = 0; row < gridWidth; row++) {
          if (rows[row]) {
            console.log('sliced it');
            grid[col].splice(row, 1);
            grid[col].unshift(false);
          }
        }
    }
  }
}

function Boulder() {
  this.x        = null;
  this.y        = null;
  this.shape    = null;
  this.rotation = null;
  this.fall     = null;
  this.dir      = null;

  this.setup = function(difficulty) {
      var index  = Math.floor(random(0, shapes.length));

      this.x        = toGrid(gridWidth*scl/2);
      this.y        = 0;
      this.shape    = null;
      this.rotation = null;
      this.fall     = difficulty;
      this.dir      = 0;
      this.shape    = shapes[index];
      this.rotation = 0;
  };

  this.move = function(dir) {
    var collides = false;
    this.iterator(function(x, y, dir) {
      if (x + dir < 0 || x + dir >= gridWidth || grid[x + dir][y]) {
        collides = true;
        console.log('collides');
      }
    });
    if (!collides) {
        this.x += this.dir;
    }
  };

  this.drop = function() {
    if (this.fall === 0) {
      this.y += 1;
      this.fall = difficulty;
    }
    else {
      this.fall--;
    }
  };

  this.rotate  = function(dir) {
    this.rotation = (this.rotation+1)%this.shape.length;
    var collides = false;

    this.iterator(function(x, y, dir) {
      if (x < 0 || x >= gridWidth || grid[x][y]) {
        collides = true;
        console.log('collides');
      }
    });

    if (collides) {
        this.rotation = (this.rotation-1)%this.shape.length;
    }
  };

  this.iterator = function(onEach) {
    for (var j = 0; j < this.shape[this.rotation].length; j++) {
        onEach(this.x + this.shape[this.rotation][j].x, this.y + this.shape[this.rotation][j].y, this.dir);
    }
  };

  this.render = function() {
    fill('white');
    var renderSingle = function(x, y) {
      rect(toPixel(x), toPixel(y), scl, scl);
    }
    this.iterator(renderSingle);
  };
}


function keyPressed() {
  if(keyCode === RIGHT_ARROW) {
      boulder.dir = 1;
      boulder.move();
      console.log('Moved 1');
  } else if(keyCode === LEFT_ARROW) {
      boulder.dir = -1,
      boulder.move();
      console.log('Moved -1');
  } else if(keyCode === UP_ARROW) {
      boulder.rotate();
  }
}

function keyReleased() {
  if(keyCode === RIGHT_ARROW) {
      boulder.dir = 0;
  } else if(keyCode === LEFT_ARROW) {
      boulder.dir = 0;
  }
}
