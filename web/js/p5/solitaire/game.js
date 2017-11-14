function setup() {
    frameRate(100);
    createCanvas(800, 800);
}

function draw() {
    if(!board.isSolved()) {
        background(51);
        board.solve();
    }
    board.show();
}

function Game() {
    this.board = [
        [null, null, 1, 1, 1, null, null],
        [null, null, 1, 1, 1, null, null],
        [   1,    1, 1, 1, 1,    1,    1],
        [   1,    1, 1, 0, 1,    1,    1],
        [   1,    1, 1, 1, 1,    1,    1],
        [null, null, 1, 1, 1, null, null],
        [null, null, 1, 1, 1, null, null],
    ];
    this.solved = false;
    this.possibleMoves = [];

    this.init = 
    this.solve = function() {

    };

}

function Hole() {
    this.hasPeg     = false;
    this.neighbours = [];
};
