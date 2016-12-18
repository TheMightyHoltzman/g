/**
 * Created by heiko on 17/12/16.
 */

// Board
var cells = [
    [  4,    7,  null,    9, null,    5,    6, null,  null],
    [null,   3,  null,    1,    6, null, null,    4,  null],
    [   8,    1, null,   4, null, null,    9,    3,     5],
    [null, null, null,   2,    8,    6, null,    7,     4],
    [   7,    2,    4,  null, null,    null, 8, null, 3],
    [   6, null,    1, 7,    4, null, 5, null,    null],
    [null,  null,    7, null, 2, 1,    3,    null, 6],
    [null, null,    8, null, 9, 4,    null, 5, 2],
    [   2, 9, 3,    null, null,    7, null, 1,  null    ]
];


    // Board
cells = [
        [  2,  null, null,    8, null,    4, null, null,    6],
        [null, null,    6, null, null, null,    5, null, null],
        [null,    7,    4, null, null, null,    9,    2, null],
        [   3, null, null, null,    4, null, null, null,    7],
        [null, null, null,    3, null,    5, null, null, null],
        [   4, null, null, null,    6, null, null, null,    9],
        [null,    1,    9, null, null, null,    7,    4, null],
        [null, null,    8, null, null, null,    2, null, null],
        [   5, null, null,    6, null,    8, null, null,    1]
    ];

//var board = new Board(cells);
var scl  = 60;
var sz = 9;
var board = new Board(cells, sz);
board.setup(board.cells);

function setup() {
    frameRate(100);
    createCanvas(scl*sz + 1, scl*sz + 1);
}

function draw() {
    if (! board.isSolved()) {
        background(51);
        board.show();
        board.solve();
    }

}
